<?php
class ControllerExtensionPaymentOmisePaynow extends Controller {

	/**
	 * @return string
	 */
	public function checkStatus() {
		// debug($this->session->data['order_id']);exit;
		$json = array();

		if (isset($this->session->data['order_id']) && !empty($this->session->data['order_id'])) {
			$this->load->model('checkout/order');
			$this->load->language('extension/payment/omise');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if ($order_info) {
				if ( in_array($order_info['order_status_id'], $this->config->get('config_complete_status') ) ) {
					$json['redirect'] = $this->url->link('checkout/success', '', true);
				}
				if ( in_array($order_info['order_status_id'], $this->config->get('config_cancel_status') ) ){
					$json['redirect'] = $this->url->link('checkout/failure', '', true);
				}
			}

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	private function searchErrorTranslation($clue) {
	    $this->load->language('extension/payment/omise');

	    $translate_code = 'error_' . str_replace(' ', '_', strtolower($clue));
	    $translate_msg  = $this->language->get($translate_code);

	    if ($translate_code !== $translate_msg)
	        return $translate_msg;

	    return $clue;
	}

	public function checkoutCallback() {
		if (isset($this->request->get['order_id'])) {
			$this->load->library('omise');
			
			$this->load->model('extension/payment/omise');
			$this->load->model('checkout/order');

			$order_id    = $this->request->get['order_id'];
			$omise_keys  = $this->model_extension_payment_omise->retrieveOmiseKeys();
			$transaction = $this->model_extension_payment_omise->getChargeTransaction($this->request->get['order_id']);

			$charge = OmiseCharge::retrieve($transaction->row['omise_charge_id'], $omise_keys['pkey'], $omise_keys['skey']);

			if ($charge && $charge['status'] == 'successful' ) {
				// Status: processed.
				$success_status = 15;
				if ($this->config->get('omise_paynow_order_status_id')) {
					$success_status = $this->config->get('omise_paynow_order_status_id');
				}
				$this->model_checkout_order->addOrderHistory($order_id, $success_status);
				$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
			} elseif ($charge && $charge['status'] == 'pending') {
				$this->renderWaitingPage();
			} else {
				// Status: failed.
			//$this->model_checkout_order->addOrderHistory($order_id, 10, $charge['failure_message']);
				$this->model_checkout_order->addOrderHistory($order_id, 7, $charge['failure_message']); // 7 failed status
				$this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
			}
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}

	public function index() {
		$this->load->language('extension/payment/omise_paynow');

		$this->load->model('extension/payment/omise');
		$this->load->model('checkout/order');

		$this->load->library('omise');

		// Get Omise configuration.
		$omise = array();
		if ($this->config->get('omise_test_mode')) {
			$omise['pkey'] = $this->config->get('omise_pkey_test');
			$omise['skey'] = $this->config->get('omise_skey_test');
		} else {
			$omise['pkey'] = $this->config->get('omise_pkey');
			$omise['skey'] = $this->config->get('omise_skey');
		}

		$data['error'] = false;

		// Create a order history with `Processing` status
		$order_id    = $this->session->data['order_id'];
		$order_info  = $this->model_checkout_order->getOrder($order_id);
		$order_total = $this->currency->format($order_info['total'], $order_info['currency_code'], '', false);
		if ($order_info) {

			try{

				// Try to create a charge and capture it.
				$omise_charge = OmiseCharge::create(
					array(
						"amount"      => OmisePluginHelperCharge::amount($order_info['currency_code'], $order_total),
						"currency"    =>$this->config->get('config_currency'),
						"description" => $this->config->get('omise_paynow_payment_description' . $this->config->get('config_language_id')) . 'Order Id is ' . $order_id,
						"return_uri"  => $this->url->link('extension/payment/omise_paynow/checkoutcallback&order_id='.$order_id, '', 'SSL'),
						"source"	  => array('type' => 'paynow')
					),
					$omise['pkey'],
					$omise['skey']
				);

				// Status: failed.
				if ($omise_charge['failure_code'] || $omise_charge['failure_code']) {
					throw new Exception($omise_charge['failure_code'].': '.$omise_charge['failure_code'], 1);
				}

				$this->model_extension_payment_omise->addChargeTransaction($order_id, $omise_charge['id']);

				 if ($omise_charge['capture']) {
					// Status: processing.
					// $this->model_checkout_order->addOrderHistory($order_id, 0);
				 	if (isset($omise_charge['source']['scannable_code']['image']['download_uri']) && $omise_charge['source']['scannable_code']['image']['download_uri']) {
						$data['scannable_code'] = $omise_charge['source']['scannable_code']['image']['download_uri'];
					}

				} else {
					// Status: failed.
					throw new Exception('Your charge was failed - '.$omise_charge['failure_code'].': '.$omise_charge['failure_code'], 1);
				}
			}
			catch (Exception $e) {
				// Status: failed.
				$error_message = $this->searchErrorTranslation('Payment ' . $e->getMessage());

				//$this->model_checkout_order->addOrderHistory($order_id, 10, $error_message);
				// $this->model_checkout_order->addOrderHistory($order_id, 7, $error_message);
				$data['error'] = $error_message;
			}
		}
		else{
			$data['error'] = 'Cannot find your order, please try again.';
		}

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['bank'] = nl2br($this->config->get('omise_paynow_bank' . $this->config->get('config_language_id')));
		$data['text_payment'] = nl2br($this->config->get('omise_paynow_extra_note' . $this->config->get('config_language_id')));
		$data['image'] = $this->config->get('paynow_image');

		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/omise_paynow', $data);
	}

	public function webhook() {
		$event = json_decode(file_get_contents('php://input'), true);
		if (!isset($event['key'])) {
			$this->response->addHeader('HTTP/1.1 400 Bad Request');
			return;
		}

		if ($event['key'] != 'charge.complete') {
			return;
		}

		$this->load->model('extension/payment/omise');

		$transaction = $this->model_extension_payment_omise->getOrderId($event['data']['id']);
		if (empty($transaction->row)) {
			return;
		}

		$this->request->get['order_id'] = $transaction->row['order_id'];
		return $this->refresh();
	}

	public function processing()
    {
        if (! isset($this->request->get['order_id'])) {
            return;
        }

        if (isset($this->session->data['order_id'])) {
            $backup_order_id = $this->session->data['order_id'];
        }

        // Reuse success logic from OpenCart to cleanup current cart.
        // And checkout/success only works with session->data['order_id'].
        $this->session->data['order_id'] = $this->request->get['order_id'];
        $this->load->controller('checkout/success');
        if (isset($backup_order_id)) {
            $this->session->data['order_id'] = $backup_order_id;
        }

        // But display our page.
        $this->load->language('extension/payment/omise_processing');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_processing'),
            'href' => $this->url->link('extension/payment/omise_paynow/processing')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        if ($this->customer->isLogged()) {
            $data['text_message'] = sprintf(
                $this->language->get('text_customer'),
                $this->url->link('account/account', '', 'SSL'),
                $this->url->link('account/order', '', 'SSL'),
                $this->url->link('account/download', '', 'SSL'),
                $this->url->link('information/contact')
            );
        } else {
            $data['text_message'] = sprintf(
                $this->language->get('text_guest'),
                $this->url->link('information/contact')
            );
        }

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->response->setOutput(
                $this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data)
            );
        } else {
            $this->response->setOutput($this->load->view('common/success.tpl', $data));
        }
    }

     private function renderWaitingPage()
    {
        $omise_waiting = 'omise_waiting_' . $this->request->get['order_id'];

        if (! isset($this->session->data[$omise_waiting])) {
            $this->session->data[$omise_waiting] = 1;
        } else {
            $this->session->data[$omise_waiting]++;
            if ($this->session->data[$omise_waiting] > 5) {
                $this->response->redirect(
                    $this->url->link('extension/payment/omise_paynow/processing', 'order_id=' . $this->request->get['order_id'])
                );
                return;
            }
        }

        $this->load->language('checkout/success');
        $this->load->language('extension/payment/omise_waiting');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_waiting'),
            'href' => $this->url->link('extension/payment/omise_paynow/checkoutcallback', 'order_id=' . $this->request->get['order_id'])
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_message');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/omise_waiting.tpl')) {
            $this->response->setOutput(
                $this->load->view($this->config->get('config_template') . '/template/extension/payment/omise_waiting.tpl', $data)
            );
        } else {
            $this->response->setOutput($this->load->view('extension/payment/omise_waiting.tpl', $data));
        }
    }
}