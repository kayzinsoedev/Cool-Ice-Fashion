<?php
class ControllerAccountOmise extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/omise', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/omise');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/omise');

		$this->getList();
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/omise', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/omise');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/omise');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		    
// 			$this->model_account_address->addAddress($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_add');

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);

				$this->model_account_activity->addActivity('credit_card_add', $activity_data);
			}

			$this->response->redirect($this->url->link('account/omise', '', true));
		}

		$this->getForm();
	}

// 	public function edit() {
// 		if (!$this->customer->isLogged()) {
// 			$this->session->data['redirect'] = $this->url->link('account/address', '', true);

// 			$this->response->redirect($this->url->link('account/login', '', true));
// 		}

// 		$this->load->language('account/address');

// 		$this->document->setTitle($this->language->get('heading_title'));

// 		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
// 		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
// 		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

// 		$this->load->model('account/address');
		
// 		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
// 			$this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);

// 			// Default Shipping Address
// 			if (isset($this->session->data['shipping_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address']['address_id'])) {
// 				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

// 				unset($this->session->data['shipping_method']);
// 				unset($this->session->data['shipping_methods']);
// 			}

// 			// Default Payment Address
// 			if (isset($this->session->data['payment_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address']['address_id'])) {
// 				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

// 				unset($this->session->data['payment_method']);
// 				unset($this->session->data['payment_methods']);
// 			}

// 			$this->session->data['success'] = $this->language->get('text_edit');

// 			// Add to activity log
// 			if ($this->config->get('config_customer_activity')) {
// 				$this->load->model('account/activity');

// 				$activity_data = array(
// 					'customer_id' => $this->customer->getId(),
// 					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
// 				);

// 				$this->model_account_activity->addActivity('address_edit', $activity_data);
// 			}

// 			$this->response->redirect($this->url->link('account/address', '', true));
// 		}

// 		$this->getForm();
// 	}

	public function delete() {
	    
	    $this->load->library('omise');
		$this->load->language('extension/payment/omise');
		$this->load->model('extension/payment/omise');
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/omise', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/omise');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/omise');

		if (isset($this->request->get['id'])) {

            $omise_customer_query = $this->model_extension_payment_omise->getCustomer($this->customer->getId());
		
    		if ($omise_customer_query->num_rows) {
    		
    		    $omise_customer_result = $omise_customer_query->row;
    
    			$omise_cust_id = $omise_customer_result['omise_customer_id'];
    		
    			$omise_keys = $this->model_extension_payment_omise->retrieveOmiseKeys();
    		
        		if ($omise_keys) {
                    
        			$pkey = $omise_keys['pkey'];
        			$skey = $omise_keys['skey'];
        			define('OMISE_PUBLIC_KEY', $pkey);
        			define('OMISE_SECRET_KEY', $skey);
        			
        		    $customer = OmiseCustomer::retrieve($omise_cust_id);
        		    $card = $customer->getCards()->retrieve($this->request->get['id']);
                    
                    $card->destroy();
        		    
        		    if($card->isDestroyed()){
        		        $this->session->data['success'] = $this->language->get('text_delete');
            
            			// Add to activity log
            			if ($this->config->get('config_customer_activity')) {
            				$this->load->model('account/activity');
            
            				$activity_data = array(
            					'customer_id' => $this->customer->getId(),
            					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            				);
            				
            				$this->model_account_activity->addActivity('credit_card_delete', $activity_data);
            			}
            
            			$this->response->redirect($this->url->link('account/omise', '', true));
        		    }
        		}
    		}
		}

		$this->getList();
	}

	protected function getList() {
	    
	    $this->load->library('omise');
		$this->load->language('extension/payment/omise');
		$this->load->model('extension/payment/omise');
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/omise', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_omise_credit_card'] = $this->language->get('text_omise_credit_card');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_new_credit_card'] = $this->language->get('button_new_credit_card');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_back'] = $this->language->get('button_back');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_actions'] = $this->language->get('text_actions');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['credit_cards'] = array();
		
		$omise_customer_query = $this->model_extension_payment_omise->getCustomer($this->customer->getId());
		
		if ($omise_customer_query->num_rows) {
		
		    $omise_customer_result = $omise_customer_query->row;

			$omise_cust_id = $omise_customer_result['omise_customer_id'];
		
			$omise_keys = $this->model_extension_payment_omise->retrieveOmiseKeys();
		
    		if ($omise_keys) {
                
    			$pkey = $omise_keys['pkey'];
    			$skey = $omise_keys['skey'];
    			define('OMISE_PUBLIC_KEY', $pkey);
    			define('OMISE_SECRET_KEY', $skey);
    			
    		    $omise_customer = OmiseCustomer::retrieve($omise_cust_id);
    		    
    		    $omise_customer_all_card = $omise_customer['cards']['data'];
    		    
    		    $results = $omise_customer_all_card;
        
                foreach ($results as $result) {
                    $data['credit_cards'][] = array(
        				'id' => $result['id'],
        				'omise_card_no' => '('. $result['name'] .') Card no. ending with '. $result['last_digits'],
        				'delete'     => $this->url->link('account/omise/delete', 'id=' . $result['id'], true)
        			);
                }
    		}
		}
		
		

		
        
        // debug($data['credit_cards']);

		$data['add'] = $this->url->link('account/omise/add', '', true);
		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/omise_list', $data));
	}

	protected function getForm() {
	    
	   // $this->load->language('account/omise');
	    $this->load->model('extension/payment/omise');
		$this->load->library('omise');
		$this->load->language('extension/payment/omise');
	    
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/omise', '', true)
		);

		if (!isset($this->request->get['id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_add_credit_card'),
				'href' => $this->url->link('account/omise/add', '', true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_add_credit_card'] = $this->language->get('text_add_credit_card');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');


		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_upload'] = $this->language->get('button_upload');
		
		$data['action'] = $this->url->link('account/address/add', '', true);
		
		$data['success_url'] = $this->url->link('account/omise', '', 'SSL');
		$data['loop_months'] = $this->getMonths();
		$data['loop_years'] = $this->getYears();
        $data['button_confirm'] = $this->language->get('button_confirm');
        
        $data['omise'] = $this->model_extension_payment_omise->retrieveOmiseKeys();
        
		$data['back'] = $this->url->link('account/address', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		$this->response->setOutput($this->load->view('account/omise_form', $data));
	}
	
	/**
	 * Retrieve list of months translation
	 *
	 * @return array
	 */
	public function getMonths() {
		$months = array();
		for ($index=1; $index <= 12; $index++) {
			$month = ($index < 10) ? '0'.$index : $index;
			$months[$month] = $month;
		}
		return $months;
	}

	/**
	 * Retrieve array of available years
	 *
	 * @return array
	 */
	public function getYears() {
		$years = array();
		$first = date("Y");

		for ($index=0; $index <= 10; $index++) {
			$year = $first + $index;
			$years[$year] = $year;
		}
		return $years;
	}
	
	public function create_update_customer() {
	   // debug($this->request->post);die;
		if ($this->request->post) {	

			// create or update customer

			// if ticked {}

			$email = $this->customer->getEmail();
			$token = $this->request->post['omise_token'];
			$firstname = $this->customer->getFirstName();
			$lastname = $this->customer->getLastName();

			$this->load->model('extension/payment/omise');
			$this->load->library('omise');

			$omise_keys = $this->model_extension_payment_omise->retrieveOmiseKeys();
			$pkey = $omise_keys['pkey'];
			$skey = $omise_keys['skey'];
			define('OMISE_PUBLIC_KEY', $pkey);
			define('OMISE_SECRET_KEY', $skey);
			
			$omise_customer_query = $this->model_extension_payment_omise->getCustomer($this->customer->getId());
		
    		if ($omise_customer_query->num_rows) {
    		
    		    $omise_customer_result = $omise_customer_query->row;
    
    			$omise_cust_id = $omise_customer_result['omise_customer_id'];
    		}

			if (isset($omise_cust_id)) {
				$customer = OmiseCustomer::retrieve($omise_cust_id);
				$customer->update(array(
				    'card' => $token
				));
			} else {
				// create customer with attach card
				$omise_customer = OmiseCustomer::create(array(
					'email' => $email,
					'description' => 'Card for ' . $firstname . ' ' . $lastname,
					'card' => $token
				));
			}

			if (isset($omise_customer)) {

				$cust_id = $omise_customer['id'];

				$this->db->query("INSERT INTO " . DB_PREFIX . "omise_customer 
				SET 
					customer_id = '".(int)$this->customer->getId()."',
					omise_customer_id = '".$this->db->escape($cust_id)."',
					date_added = NOW()
				;");
			}

            if (isset($omise_cust_id)){
                $json['omise_customer_id'] = $omise_cust_id;
            }else{
                $json['omise_customer_id'] = $cust_id;
            }
			
		}
		
		$json['success'] = 1;
		$json['redirect'] = $this->url->link('account/omise', '', true);
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}