<?php
class ControllerExtensionPaymentOmisePaynow extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/omise_paynow');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('omise_paynow', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_notice'] = $this->language->get('text_notice');

		$data['entry_bank'] = $this->language->get('entry_bank');
		$data['entry_extra_note'] = $this->language->get('entry_extra_note');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_payment_title'] = $this->language->get('entry_payment_title');
		$data['entry_payment_description'] = $this->language->get('entry_payment_description');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank' . $language['language_id']])) {
				$data['error_bank' . $language['language_id']] = $this->error['bank' . $language['language_id']];
			} else {
				$data['error_bank' . $language['language_id']] = '';
			}
		}
                
                if(isset($this->error['image'])) {
                    $data['error_image'] = $this->error['image'];
                }else{
                    $data['error_image'] = '';
                }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/omise_paynow', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/omise_paynow', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		$this->load->model('localisation/language');

		foreach ($languages as $language) {
			if (isset($this->request->post['omise_paynow_bank' . $language['language_id']])) {
				$data['omise_paynow_bank' . $language['language_id']] = $this->request->post['omise_paynow_bank' . $language['language_id']];
			} else {
				$data['omise_paynow_bank' . $language['language_id']] = $this->config->get('omise_paynow_bank' . $language['language_id']);
			}
			
			if (isset($this->request->post['omise_paynow_extra_note' . $language['language_id']])) {
				$data['omise_paynow_extra_note' . $language['language_id']] = $this->request->post['omise_paynow_extra_note' . $language['language_id']];
			} else {
				$data['omise_paynow_extra_note' . $language['language_id']] = $this->config->get('omise_paynow_extra_note' . $language['language_id']);
			}

			if (isset($this->request->post['omise_paynow_payment_description' . $language['language_id']])) {
				$data['omise_paynow_payment_description' . $language['language_id']] = $this->request->post['omise_paynow_payment_description' . $language['language_id']];
			} else {
				$data['omise_paynow_payment_description' . $language['language_id']] = $this->config->get('omise_paynow_payment_description' . $language['language_id']);
			}

	      	if (isset($this->request->post['omise_paynow_payment_title' . $language['language_id']])) {
	        	$data['omise_paynow_payment_title' . $language['language_id']] = $this->request->post['omise_paynow_payment_title' . $language['language_id']];
	      	} else {
	        	$data['omise_paynow_payment_title' . $language['language_id']] = $this->config->get('omise_paynow_payment_title' . $language['language_id']);
	      	}
		}

		$data['languages'] = $languages;


		if (isset($this->request->post['omise_paynow_order_status_id'])) {
			$data['omise_paynow_order_status_id'] = $this->request->post['omise_paynow_order_status_id'];
		} else {
			$data['omise_paynow_order_status_id'] = $this->config->get('omise_paynow_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['omise_paynow_geo_zone_id'])) {
			$data['omise_paynow_geo_zone_id'] = $this->request->post['omise_paynow_geo_zone_id'];
		} else {
			$data['omise_paynow_geo_zone_id'] = $this->config->get('omise_paynow_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['omise_paynow_status'])) {
			$data['omise_paynow_status'] = $this->request->post['omise_paynow_status'];
		} else {
			$data['omise_paynow_status'] = $this->config->get('omise_paynow_status');
		}

		if (isset($this->request->post['omise_paynow_sort_order'])) {
			$data['omise_paynow_sort_order'] = $this->request->post['omise_paynow_sort_order'];
		} else {
			$data['omise_paynow_sort_order'] = $this->config->get('omise_paynow_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/omise_paynow', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/omise_paynow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['omise_paynow_bank' . $language['language_id']])) {
				$this->error['bank' .  $language['language_id']] = $this->language->get('error_bank');
			}
		}
//                if (empty($this->request->post['paynow_image'])) {
//                        $this->error['image'] = $this->language->get('error_image');
//                }
                
		return !$this->error;
	}
}