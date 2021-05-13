<?php 
class ControllerQuickCheckoutShippingMethod extends Controller {
  	public function index() {
		$data = $this->load->language('checkout/checkout');
		$data = array_merge($data, $this->load->language('quickcheckout/checkout'));
		
		$this->load->model('account/address');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		
		$shipping_address = array();
		
		if ($this->customer->isLogged() && isset($this->request->get['address_id'])) {
			// Selected stored address
			$shipping_address = $this->model_account_address->getAddress($this->request->get['address_id']);

			if (isset($this->session->data['guest'])) {
				unset($this->session->data['guest']);
			}
		} elseif (isset($this->request->post['country_id'])) {
			// Selected new address OR is a guest
			if (isset($this->request->post['country_id'])) {
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			} else {
				$country_info = '';
			}
			
			if (isset($this->request->post['zone_id'])) {
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			} else {
				$zone_info = '';
			}
			
			if ($country_info) {
				$shipping_address['country'] = $country_info['name'];
				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
				$shipping_address['address_format'] = $country_info['address_format'];
			} else {
				$shipping_address['country'] = '';
				$shipping_address['iso_code_2'] = '';
				$shipping_address['iso_code_3'] = '';
				$shipping_address['address_format'] = '';
			}
			
			if ($zone_info) {
				$shipping_address['zone'] = $zone_info['name'];
				$shipping_address['zone_code'] = $zone_info['code'];
			} else {
				$shipping_address['zone'] = '';
				$shipping_address['zone_code'] = '';
			}
		
			$shipping_address['firstname'] = $this->request->post['firstname'];
			$shipping_address['lastname'] = $this->request->post['lastname'];
			$shipping_address['company'] = $this->request->post['company'];
			$shipping_address['address_1'] = $this->request->post['address_1'];
			$shipping_address['address_2'] = $this->request->post['address_2'];
			$shipping_address['postcode'] = $this->request->post['postcode'];
			$shipping_address['city'] = $this->request->post['city'];
			$shipping_address['country_id'] = $this->request->post['country_id'];
			$shipping_address['zone_id'] = $this->request->post['zone_id'];
		}
		
		if (!empty($shipping_address)) {
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/shipping/' . $result['code']);

					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);
					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}
		
		if ($this->config->get('quickcheckout_delivery_time') == '2') {
			$min = $this->config->get('quickcheckout_delivery_min');
			$max = $this->config->get('quickcheckout_delivery_max');
			$today = date('d M Y');
			
			$min_date = date('d M Y', strtotime($today . ' + ' . $min . ' day'));
			$max_date = date('d M Y', strtotime($today . ' + ' . $max . ' day'));
			
			$min = 0;
			$max = 0;
			
			if ($this->config->get('quickcheckout_delivery_unavailable')) {
				$dates = str_replace('"', '', html($this->config->get('quickcheckout_delivery_unavailable')));
			} else {
				$dates = array();
			}
			
			foreach (explode(',', $dates) as $unavailable) {
				$unavailable = strtotime($unavailable);
				
				if ($unavailable >= strtotime($min_date) && $unavailable <= strtotime($max_date)) {
					$max++;
				}
				
				if ($unavailable == strtotime($min_date)) {
					$min++;
				}
			}
			
			$min_date = date('d M Y', strtotime($min_date . ' + ' . $min . ' day'));
			$max_date = date('d M Y', strtotime($max_date . ' + ' . $max . ' day'));
			
			$data['estimated_delivery'] = $min_date . ' - ' . $max_date;
			$data['estimated_delivery_time'] = str_pad($this->config->get('quickcheckout_delivery_min_hour'), 2, '0', STR_PAD_LEFT) . ' 00 - ' . str_pad($this->config->get('quickcheckout_delivery_max_hour'), 2, '0', STR_PAD_LEFT) . ' 00';
		}

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}	
					
		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$data['shipping_methods'] = array();
		}
		
		if (isset($this->request->post['shipping_method'])) {
			$data['code'] = $this->request->post['shipping_method'];
		} elseif (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = $this->config->get('quickcheckout_shipping_default');
		}
		
		$exists = false;
		$stored_code = false;
		
		foreach ($data['shipping_methods'] as $key => $shipping_method) {
			if ($key == $data['code']) {
				foreach ($shipping_method['quote'] as $quote) {
					$exists = true;
					
					$data['code'] = $quote['code'];
					
					break;
				}
				
				break;
			} else {
				foreach ($shipping_method['quote'] as $quote) {
					if (!$stored_code) {
						$stored_code = $quote['code'];
					}
					
					if ($quote['code'] == $data['code']) {
						$exists = true;
						
						break;
					}
				}
			}
		}

		if (!$exists) {
			$data['code'] = $stored_code;
		}
		
		if (isset($this->request->post['delivery_date'])) {
			$data['delivery_date'] = $this->request->post['delivery_date'];
		} elseif (isset($this->session->data['delivery_date'])) {
			$data['delivery_date'] = $this->session->data['delivery_date'];
		} else {
			$data['delivery_date'] = '';
		}
		
		if (isset($this->request->post['delivery_time'])) {
			$data['delivery_time'] = $this->request->post['delivery_time'];
		} elseif (isset($this->session->data['delivery_time'])) {
			$data['delivery_time'] = $this->session->data['delivery_time'];
		} else {
			$data['delivery_time'] = '';
		}
		
		// All variables
		$data['logged'] = $this->customer->isLogged();
		$data['debug'] = $this->config->get('quickcheckout_debug');
		$data['shipping'] = $this->config->get('quickcheckout_shipping');
		$data['shipping_logo'] = $this->config->get('quickcheckout_shipping_logo');

		if($data['shipping_logo']){
			foreach($data['shipping_logo'] as &$image){
				$image = 'image/' . $image;
			}
		}

		// General Shipping Notice
		$quickcheckout_shipping_general_notice = $this->config->get('quickcheckout_shipping_general_notice');
		if($quickcheckout_shipping_general_notice){
			foreach($quickcheckout_shipping_general_notice as $language_id => &$general_description){
				$general_description = html($general_description);
				if(trim(strip_tags($general_description)) == ""){
					$general_description = '';
				}
			}
		}

		$data['general_description'] = '';
		if(isset($quickcheckout_shipping_general_notice[$this->config->get('config_language_id')])){
			$data['general_description'] = $quickcheckout_shipping_general_notice[$this->config->get('config_language_id')];
		}
		// End General Shipping Notice
		
		// Individual Shipping Notice

		$quickcheckout_shipping_individual_shipping_notice = $this->config->get('quickcheckout_shipping_individual_shipping_notice');
		if($quickcheckout_shipping_individual_shipping_notice){
			foreach($quickcheckout_shipping_individual_shipping_notice as $code => $individual_notice){
				foreach($individual_notice as $language_id => $each_notice){
					if(trim(strip_tags($each_notice)) == ""){
						$quickcheckout_shipping_individual_shipping_notice[$code][$language_id] = '';
					}
				}
			}
		}
		else{
			$quickcheckout_shipping_individual_shipping_notice = array();
		}
		//debug($quickcheckout_shipping_individual_shipping_notice);
		foreach ($data['shipping_methods'] as $code => &$value) {
			$value['notice']	=	'';
			if(isset($quickcheckout_shipping_individual_shipping_notice[$code][$this->config->get('config_language_id')])){
				$value['notice']	=	$quickcheckout_shipping_individual_shipping_notice[$code][$this->config->get('config_language_id')];
			}
		}
		//debug($data['shipping_methods']);
		
		// Individual Shipping Notice
		
		
		//debug($data['quickcheckout_shipping_general_notice']);
		//debug($data['quickcheckout_shipping_individual_shipping_notice']);

		$data['delivery'] = $this->config->get('quickcheckout_delivery');
		$data['delivery_delivery_date'] = $this->config->get('quickcheckout_delivery_date');
		$data['delivery_delivery_time'] = $this->config->get('quickcheckout_delivery_time');	//debug($data['delivery_delivery_time']);
		$data['delivery_required'] = $this->config->get('quickcheckout_delivery_required');
		$data['delivery_times'] = $this->config->get('quickcheckout_delivery_times');
		$data['delivery_unavailable'] = html($this->config->get('quickcheckout_delivery_unavailable'));
		$data['delivery_days_of_week'] = html($this->config->get('quickcheckout_delivery_days_of_week'));
		$data['cart'] = $this->config->get('quickcheckout_cart');
		$data['shipping_reload'] = $this->config->get('quickcheckout_shipping_reload');
		$data['language_id'] = $this->config->get('config_language_id');
		
		if ($this->config->get('quickcheckout_delivery_min')) {
			$data['delivery_min'] = date('Y-m-d', strtotime('+' . $this->config->get('quickcheckout_delivery_min') . ' days'));
		} else {
			$data['delivery_min'] = date('Y-m-d');
		}
		
		if ($this->config->get('quickcheckout_delivery_max')) {
			$data['delivery_max'] = date('Y-m-d', strtotime('+' . $this->config->get('quickcheckout_delivery_max') . ' days'));
		} else {
			$data['delivery_max'] = date('Y-m-d');
		}
		
		$hours = range($this->config->get('quickcheckout_delivery_min_hour'), $this->config->get('quickcheckout_delivery_max_hour'));

		$data['hours'] = implode(',', $hours);

        unset($this->session->data['delivery_date']);
        unset($this->session->data['delivery_time']);
                
		$this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        
        $modulename  = 'location';
        $data['outlets'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'locations');        
                
		$this->response->setOutput($this->load->view('quickcheckout/shipping_method', $data));
  	}
	
	public function set() {
		$this->load->model('account/address');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		
		if ($this->customer->isLogged() && isset($this->request->get['address_id'])) {
			// Selected stored address
			$this->session->data['shipping_address_id'] = $this->request->get['address_id'];
			
			$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

			if (isset($this->session->data['guest'])) {
				unset($this->session->data['guest']);
			}
		} elseif (isset($this->request->post['country_id'])) {
			// Selected new address OR is a guest
			if (isset($this->request->post['country_id'])) {
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			} else {
				$country_info = '';
			}
			
			if (isset($this->request->post['zone_id'])) {
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			} else {
				$zone_info = '';
			}
			
			if ($country_info) {
				$shipping_address['country'] = $country_info['name'];
				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
				$shipping_address['address_format'] = $country_info['address_format'];
			} else {
				$shipping_address['country'] = '';
				$shipping_address['iso_code_2'] = '';
				$shipping_address['iso_code_3'] = '';
				$shipping_address['address_format'] = '';
			}
			
			if ($zone_info) {
				$shipping_address['zone'] = $zone_info['name'];
				$shipping_address['zone_code'] = $zone_info['code'];
			} else {
				$shipping_address['zone'] = '';
				$shipping_address['zone_code'] = '';
			}
		
			$shipping_address['firstname'] = $this->request->post['firstname'];
			$shipping_address['lastname'] = $this->request->post['lastname'];
			$shipping_address['company'] = $this->request->post['company'];
			$shipping_address['address_1'] = $this->request->post['address_1'];
			$shipping_address['address_2'] = $this->request->post['address_2'];
			$shipping_address['postcode'] = $this->request->post['postcode'];
			$shipping_address['city'] = $this->request->post['city'];
			$shipping_address['country_id'] = $this->request->post['country_id'];
			$shipping_address['zone_id'] = $this->request->post['zone_id'];
			
			$this->session->data['shipping_address'] = $shipping_address;
		}
		
		if (isset($this->request->post['delivery_date'])) {
			$this->session->data['delivery_date'] = strip_tags($this->request->post['delivery_date']);
		}
		
		if (isset($this->request->post['delivery_time'])) {
			$this->session->data['delivery_time'] = strip_tags($this->request->post['delivery_time']);
		}
		
		if (isset($this->request->post['shipping_method']) && isset($this->session->data['shipping_methods'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);
			
			if (isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			}
		}
	}
	
	public function validate() {
		$this->load->language('checkout/checkout');
		$this->load->language('quickcheckout/checkout');
		
		$json = array();
        
        // Set address
        $shipping_address = array();

		if (isset($this->session->data['shipping_address'])) {
			$shipping_address = $this->session->data['shipping_address'];
		}
		
		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', true);
		}

		// Validate if shipping address has been set.
		if (empty($shipping_address)) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', true);
		}
		
		if (!empty($shipping_address)) {
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/shipping/' . $result['code']);

					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}
		
		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			}
		}
		
		if (empty($this->request->post['delivery_time'])) {
			$json['error']['warning'] = 'Warning: Delivery time is required!';
		}
		
		if ($this->config->get('quickcheckout_delivery_required') && $this->config->get('quickcheckout_delivery_date') ) {
			if (empty($this->request->post['delivery_date'])) {
				$json['error']['warning'] = $this->language->get('error_delivery');
			}
		}
		
		if (!$json) {	
			$shipping = explode('.', $this->request->post['shipping_method']);
				
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			
			$this->session->data['delivery_date'] = strip_tags($this->request->post['delivery_date']);
			
			if (isset($this->request->post['delivery_time'])) {
				$this->session->data['delivery_time'] = strip_tags($this->request->post['delivery_time']);
			} else {
				$this->session->data['delivery_time'] = '';
			}				
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}
    public function block_date(){
        $data = $this->load->language('checkout/checkout');
		$data = array_merge($data, $this->load->language('quickcheckout/checkout'));
		
		$this->load->model('account/address');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		
		$shipping_address = array();
		
		if ($this->customer->isLogged() && isset($this->request->get['address_id'])) {
			// Selected stored address
			$shipping_address = $this->model_account_address->getAddress($this->request->get['address_id']);

			if (isset($this->session->data['guest'])) {
				unset($this->session->data['guest']);
			}
		} elseif (isset($this->request->post['country_id'])) {
			// Selected new address OR is a guest
			if (isset($this->request->post['country_id'])) {
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			} else {
				$country_info = '';
			}
			
			if (isset($this->request->post['zone_id'])) {
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			} else {
				$zone_info = '';
			}
			
			if ($country_info) {
				$shipping_address['country'] = $country_info['name'];
				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
				$shipping_address['address_format'] = $country_info['address_format'];
			} else {
				$shipping_address['country'] = '';
				$shipping_address['iso_code_2'] = '';
				$shipping_address['iso_code_3'] = '';
				$shipping_address['address_format'] = '';
			}
			
			if ($zone_info) {
				$shipping_address['zone'] = $zone_info['name'];
				$shipping_address['zone_code'] = $zone_info['code'];
			} else {
				$shipping_address['zone'] = '';
				$shipping_address['zone_code'] = '';
			}
		
			$shipping_address['firstname'] = $this->request->post['firstname'];
			$shipping_address['lastname'] = $this->request->post['lastname'];
			$shipping_address['company'] = $this->request->post['company'];
			$shipping_address['address_1'] = $this->request->post['address_1'];
			$shipping_address['address_2'] = $this->request->post['address_2'];
			$shipping_address['postcode'] = $this->request->post['postcode'];
			$shipping_address['city'] = $this->request->post['city'];
			$shipping_address['country_id'] = $this->request->post['country_id'];
			$shipping_address['zone_id'] = $this->request->post['zone_id'];
		}
		
		if (!empty($shipping_address)) {
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/shipping/' . $result['code']);

					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}
		
		if ($this->config->get('quickcheckout_delivery_time') == '2') {
			$min = $this->config->get('quickcheckout_delivery_min');
			$max = $this->config->get('quickcheckout_delivery_max');
			$today = date('d M Y');
			
			$min_date = date('d M Y', strtotime($today . ' + ' . $min . ' day'));
			$max_date = date('d M Y', strtotime($today . ' + ' . $max . ' day'));
			
			$min = 0;
			$max = 0;
			
			if ($this->config->get('quickcheckout_delivery_unavailable')) {
				$dates = str_replace('"', '', html($this->config->get('quickcheckout_delivery_unavailable')));
			} else {
				$dates = array();
			}
			
			foreach (explode(',', $dates) as $unavailable) {
				$unavailable = strtotime($unavailable);
				
				if ($unavailable >= strtotime($min_date) && $unavailable <= strtotime($max_date)) {
					$max++;
				}
				
				if ($unavailable == strtotime($min_date)) {
					$min++;
				}
			}
			
			$min_date = date('d M Y', strtotime($min_date . ' + ' . $min . ' day'));
			$max_date = date('d M Y', strtotime($max_date . ' + ' . $max . ' day'));
			
			$data['estimated_delivery'] = $min_date . ' - ' . $max_date;
			$data['estimated_delivery_time'] = str_pad($this->config->get('quickcheckout_delivery_min_hour'), 2, '0', STR_PAD_LEFT) . ' 00 - ' . str_pad($this->config->get('quickcheckout_delivery_max_hour'), 2, '0', STR_PAD_LEFT) . ' 00';
		}

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}	
					
		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$data['shipping_methods'] = array();
		}
		
		if (isset($this->request->post['shipping_method'])) {
			$data['code'] = $this->request->post['shipping_method'];
		} elseif (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = $this->config->get('quickcheckout_shipping_default');
		}
		
		$exists = false;
		$stored_code = false;
		
		foreach ($data['shipping_methods'] as $key => $shipping_method) {
			if ($key == $data['code']) {
				foreach ($shipping_method['quote'] as $quote) {
					$exists = true;
					
					$data['code'] = $quote['code'];
					
					break;
				}
				
				break;
			} else {
				foreach ($shipping_method['quote'] as $quote) {
					if (!$stored_code) {
						$stored_code = $quote['code'];
					}
					
					if ($quote['code'] == $data['code']) {
						$exists = true;
						
						break;
					}
				}
			}
		}

		if (!$exists) {
			$data['code'] = $stored_code;
		}
		
		if (isset($this->request->post['delivery_date'])) {
			$data['delivery_date'] = $this->request->post['delivery_date'];
		} elseif (isset($this->session->data['delivery_date'])) {
			$data['delivery_date'] = $this->session->data['delivery_date'];
		} else {
			$data['delivery_date'] = '';
		}
		
		if (isset($this->request->post['delivery_time'])) {
			$data['delivery_time'] = $this->request->post['delivery_time'];
		} elseif (isset($this->session->data['delivery_time'])) {
			$data['delivery_time'] = $this->session->data['delivery_time'];
		} else {
			$data['delivery_time'] = '';
		}
		
		// All variables
		$data['logged'] = $this->customer->isLogged();
		$data['debug'] = $this->config->get('quickcheckout_debug');
		$data['shipping'] = $this->config->get('quickcheckout_shipping');
		$data['shipping_logo'] = $this->config->get('quickcheckout_shipping_logo');

		if($data['shipping_logo']){
			foreach($data['shipping_logo'] as &$image){
				$image = 'image/' . $image;
			}
		}

		// General Shipping Notice
		$quickcheckout_shipping_general_notice = $this->config->get('quickcheckout_shipping_general_notice');
		if($quickcheckout_shipping_general_notice){
			foreach($quickcheckout_shipping_general_notice as $language_id => &$general_description){
				$general_description = html($general_description);
				if(trim(strip_tags($general_description)) == ""){
					$general_description = '';
				}
			}
		}

		$data['general_description'] = '';
		if(isset($quickcheckout_shipping_general_notice[$this->config->get('config_language_id')])){
			$data['general_description'] = $quickcheckout_shipping_general_notice[$this->config->get('config_language_id')];
		}
		// End General Shipping Notice
		
		// Individual Shipping Notice

		$quickcheckout_shipping_individual_shipping_notice = $this->config->get('quickcheckout_shipping_individual_shipping_notice');
		if($quickcheckout_shipping_individual_shipping_notice){
			foreach($quickcheckout_shipping_individual_shipping_notice as $code => $individual_notice){
				foreach($individual_notice as $language_id => $each_notice){
					if(trim(strip_tags($each_notice)) == ""){
						$quickcheckout_shipping_individual_shipping_notice[$code][$language_id] = '';
					}
				}
			}
		}
		else{
			$quickcheckout_shipping_individual_shipping_notice = array();
		}
		//debug($quickcheckout_shipping_individual_shipping_notice);
		foreach ($data['shipping_methods'] as $code => &$value) {
			$value['notice']	=	'';
			if(isset($quickcheckout_shipping_individual_shipping_notice[$code][$this->config->get('config_language_id')])){
				$value['notice']	=	$quickcheckout_shipping_individual_shipping_notice[$code][$this->config->get('config_language_id')];
			}
		}
		//debug($data['shipping_methods']);
		
		// Individual Shipping Notice
		
		
		//debug($data['quickcheckout_shipping_general_notice']);
		//debug($data['quickcheckout_shipping_individual_shipping_notice']);

		$data['delivery'] = $this->config->get('quickcheckout_delivery');
		$data['delivery_delivery_date'] = $this->config->get('quickcheckout_delivery_date');
		$data['delivery_delivery_time'] = $this->config->get('quickcheckout_delivery_time');	//debug($data['delivery_delivery_time']);
		$data['delivery_required'] = $this->config->get('quickcheckout_delivery_required');
		$data['delivery_times'] = $this->config->get('quickcheckout_delivery_times');
		$data['delivery_unavailable'] = html($this->config->get('quickcheckout_delivery_unavailable'));
		$data['delivery_days_of_week'] = html($this->config->get('quickcheckout_delivery_days_of_week'));
		$data['cart'] = $this->config->get('quickcheckout_cart');
		$data['shipping_reload'] = $this->config->get('quickcheckout_shipping_reload');
		$data['language_id'] = $this->config->get('config_language_id');
                
                $cart = $this->cart->getProducts();
                $booking_day = 0;
                $have_special_month = 0;
                $this->load->model('catalog/product');
                $this->load->model('catalog/category');
                foreach($cart as $product){
                    $product_category = $this->model_catalog_product->getCategories($product['product_id']);
                    foreach($product_category as $category){
                        $cid = $this->model_catalog_category->getCategory($category['category_id']);
                        if($cid){
                            if(isset($this->session->data['shipping_method']['title'])){
                                if($this->session->data['shipping_method']['title'] == "Self Pickup"){
                                    if($booking_day < $cid['selfpick_day']){
                                        $booking_day = $cid['selfpick_day'];
                                    }
                                    $modulename  = 'self_pickup';
                                }else{
                                    if($booking_day < $cid['delivery_day']){
                                        $booking_day = $cid['delivery_day'];
                                    }
                                    $modulename  = 'delivery_times';
                                }
                            }else{
                                if($booking_day < $cid['selfpick_day']){
                                    $booking_day = $cid['selfpick_day'];
                                }
                                $modulename  = 'self_pickup';
                            }
                        }
                    }
                   if($product['date_special_start'] != "0000-00-00"){
                        if(strtotime($product['date_special_start']) <= strtotime(date("Y-m-d")) && strtotime($product['date_special_end']) >= strtotime(date("Y-m-d"))){
                            $have_special_month = 1;
                        }
                    }
                }
                
                if($have_special_month == 1){
                    $now = date("Y-m-d");
                    $last = $product['date_special_end'];
                    $date1 = date_create($now);
                    $date2 = date_create($last);
                    $diff = date_diff($date1,$date2);
                    $between = $diff->format("%a");
                    $max_booking = $between;
                }else{
                    $max_booking = $this->config->get('quickcheckout_delivery_max');
                }
                
                $today_date = date("Y-m-d h:i:sa");
                $day = date("l", strtotime($today_date));
                $day = strtolower($day);
                
                $language_id = $this->config->get('config_language_id');
                $cut_off = $this->modulehelper->get_field ( $this, $modulename, $language_id, $day);

                $cut_off_time = date("Y-m-d h:i:sa", strtotime(date("Y-m-d")." ".$cut_off));
                
                if(strtotime($today_date) >= strtotime($cut_off_time)){
                    $booking_day++;
                }
                
        		if ($this->config->get('quickcheckout_delivery_min')) {
        			$data['delivery_min'] = date('Y-m-d', strtotime('+' . $booking_day . ' days'));
        		} else {
        			$data['delivery_min'] = date('Y-m-d');
        		}
        		
        		if ($this->config->get('quickcheckout_delivery_max')) {
        			$data['delivery_max'] = date('Y-m-d', strtotime('+' . $max_booking . ' days'));
        		} else {
        			$data['delivery_max'] = date('Y-m-d');
        		}
        		
        		$hours = range($this->config->get('quickcheckout_delivery_min_hour'), $this->config->get('quickcheckout_delivery_max_hour'));
        
        		$data['hours'] = implode(',', $hours);

                $language_id = $this->config->get('config_language_id');
                
                if($this->request->get['shipping_method'] == "Self Pickup"){
                    $modulename  = 'self_pickup';
                }else{
                    $modulename  = 'delivery_times';
                }
                    
                
                $special_setting = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'special_setting');
                
                $block_date = "";
                if(gettype($special_setting) == "array"){
                    foreach($special_setting as $arr => $setting){
                        if($setting['type'] == 0){
                            if($setting['product_group'] == ""){
                                $block_date .= "'".$special_setting[$arr]['delivery_date']."',";
                            }else{
                                $group_array = array();
                                $modulename  = 'product_group';
                                $product_groups = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'set_group');
                                if(gettype($product_groups) == "array"){
                                    foreach($product_groups as $group){
                                        if($setting['product_group'] == $group['group']){
                                            $group_array[] = $group['type'];
                                        }
                                    }
                                }
                                foreach($this->cart->getProducts() as $product){
                                    if(in_array($product['product_id'], $group_array)){
                                        $block_date .= "'".$special_setting[$arr]['delivery_date']."',";
                                    }
                                }
                            }
                        }
                    }
                }
                $block_date = trim($block_date,",");
                $data['delivery_unavailable'] = $block_date;
                $data['cart_link'] = $this->url->link('checkout/cart');
		    
		        echo $this->load->view('quickcheckout/delivery_date', $data);
        }
        public function getdeliverytimes(){
                $delivery_date = $this->request->get['delivery_date'];
                $day = date("N",strtotime($delivery_date));
                $message = $this->config->get('config_important_note');
                $shipping_method = $this->request->get['shipping_method'];
                
                $day_num = date("N", strtotime($delivery_date));
                
                $language_id = $this->config->get('config_language_id');
                if($shipping_method == "Self Pickup"){
                    $modulename  = 'self_pickup';
                }else{
                    $modulename  = 'delivery_times';
                }
                
                $default_setting = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'default_slot');
                
                $delivery_time = array();
                $i = 0;
                if(gettype($default_setting) == "array"){
                    foreach($default_setting as $setting){
                        foreach($setting as $key1 => $value1){
                            if($key1 == "delivery_times"){
                                $delivery_time[$i]['time'] = $value1;
                            }
    
                            if($key1 == "slot"){
                                $delivery_time[$i]['slot'] = (int)$value1;
                            }
                            
                            if($key1 == "block_day"){
                                $delivery_time[$i]['block'] = $value1;
                            }
                        }
                        $i++;
                    }
                }
                
                debug($delivery_time);
                $special_setting = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'special_setting'); 
                
                if(gettype($special_setting) == "array"){
                    foreach($special_setting as $arr => $setting){
                        if($setting["type"] == 1){
                            if($setting['product_group'] == ""){
                                if(strtotime($delivery_date) == strtotime($setting["delivery_date"])){
                                    foreach($delivery_time as $key1 => $time){
                                        if($time['time'] == $setting['delivery_time']){
                                            $delivery_time[$key1]['slot'] += (int)$setting['slot'];
                                        }
                                    }
                                }
                            }else{
                                $group_array = array();
                                $modulename  = 'product_group';
                                $product_groups = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'set_group');
                                if(gettype($product_groups) == "array"){
                                    foreach($product_groups as $group){
                                        if($setting['product_group'] == $group['group']){
                                            $group_array[] = $group['type'];
                                        }
                                    }
                                }
                                $block = 0;
                                foreach($this->cart->getProducts() as $product){
                                    if(!in_array($product['product_id'], $group_array)){
                                        $block = 1;
                                    }
                                }
                                if($block == 0){
                                    if(strtotime($delivery_date) == strtotime($setting["delivery_date"])){
                                        foreach($delivery_time as $key1 => $time){
                                            if($time['time'] == $setting['delivery_time']){
                                                $delivery_time[$key1]['slot'] += (int)$setting['slot'];
                                            }
                                        }
                                    }
                                }
                            }
                        }else if($setting["type"] == 2){
                            if($setting['product_group'] == ""){
                                if(strtotime($delivery_date) == strtotime($setting["delivery_date"])){
                                    $delivery_time[$i]['time'] = $setting['add_time'];
                                    $delivery_time[$i]['slot'] = (int)$setting['slot'];
                                    $delivery_time[$i]['block'] = "";
                                }
                            }else{
                                $group_array = array();
                                $modulename  = 'product_group';
                                $product_groups = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'set_group');
                                if(gettype($product_groups) == "array"){
                                    foreach($product_groups as $group){
                                        if($setting['product_group'] == $group['group']){
                                            $group_array[] = $group['type'];
                                        }
                                    }
                                }
                                foreach($this->cart->getProducts() as $product){
                                    $block = 0;
                                    if(!in_array($product['product_id'], $group_array)){
                                        $block = 1;
                                    }
                                }
                                if($block == 0){
                                    if(strtotime($delivery_date) == strtotime($setting["delivery_date"])){
                                        $delivery_time[$i]['time'] = $setting['add_time'];
                                        $delivery_time[$i]['slot'] = (int)$setting['slot'];
                                        $delivery_time[$i]['block'] = "";
                                    }
                                }
                            }
                        }else if($setting["type"] == 4){
                            foreach($delivery_time as $key1 => $time){
                                if(strtotime($delivery_date) == strtotime($setting["delivery_date"])){
                                    if($time['time'] == $setting['delivery_time']){
                                        $delivery_time[$key1]['slot'] = 0;
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(gettype($special_setting) == "array"){
                    $block_date = "";
                    foreach($special_setting as $arr => $setting){
                        if($setting['type'] == 0){
                            if($setting['product_group'] == ""){
                                $block_date .= "'".$special_setting[$arr]['delivery_date']."',";
                            }else{
                                $group_array = array();
                                $modulename  = 'product_group';
                                $product_groups = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'set_group');
                                if(gettype($product_groups) == "array"){
                                    foreach($product_groups as $group){
                                        if($setting['product_group'] == $group['group']){
                                            $group_array[] = $group['type'];
                                        }
                                    }
                                }
                                foreach($this->cart->getProducts() as $product){
                                    if(in_array($product['product_id'], $group_array)){
                                        $block_date .= "'".$special_setting[$arr]['delivery_date']."',";
                                    }
                                }
                            }
                        }
                    }
                }
                
                
                $this->load->model('checkout/order');
                foreach($delivery_time as $key => $time){
                    $used_slot = $this->model_checkout_order->getTotalOrderSlot($shipping_method, $delivery_date, $time['time']);
                    $delivery_time[$key]['slot'] -= (int)$used_slot;
                }
                
                $data = array();
                foreach($delivery_time as $time){
                    if($time['slot'] > 0 ){
                        $arr_block = explode(",",$time['block']);
                        if(!in_array($day_num, $arr_block)){
                            $data[] = array('value' => $time['time'], 'label' => $time['time'], 'day' => $day, 'message' => $message);
                        }
                    }
                }
                
		    $this->response->addHeader('Content-Type: application/json');
		    $this->response->setOutput(json_encode($data));
        }
        
        public function getAddress(){
            
            $this->load->library('modulehelper');
            $Modulehelper = Modulehelper::get_instance($this->registry);
        
            $oc = $this;
            $language_id = $this->config->get('config_language_id');
            
            $modulename  = 'location';
            $outlets = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'locations');    
            
            $data = array();
            foreach($outlets as $outlet){
                if($outlet['postal_code'] == $this->request->post['outlet']){
                    $data['postal_code']    = $outlet['postal_code'];
                    $data['unit_no']        = $outlet['unit_no'];
                    $data['address1']       = $outlet['address1'];
                    $data['address2']       = $outlet['address2'];
                }
            }
            
            $this->session->data['outlet_code'] = $this->request->post['outlet'];
            
            $this->response->addHeader('Content-Type: application/json');
		    $this->response->setOutput(json_encode($data));
        }
        
        public function setSlotSurchage(){
            if (isset($this->request->get['delivery_date'])) {
                    $this->session->data['delivery_date'] = strip_tags($this->request->get['delivery_date']);
            }

            if (isset($this->request->get['delivery_time'])) {
                    $this->session->data['delivery_time'] = strip_tags($this->request->get['delivery_time']);
            }
            
            $json['success'] = 1;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));	
        }
        
        public function isfreealert() {
    		$data = $this->load->language('checkout/checkout');
    		$data = array_merge($data, $this->load->language('quickcheckout/checkout'));
    		
    		$this->load->model('account/address');
    		$this->load->model('localisation/country');
    		$this->load->model('localisation/zone');
    		
    		$shipping_address = array();
    		
    		if ($this->customer->isLogged() && isset($this->request->get['address_id'])) {
    			// Selected stored address
    			$shipping_address = $this->model_account_address->getAddress($this->request->get['address_id']);
    
    			if (isset($this->session->data['guest'])) {
    				unset($this->session->data['guest']);
    			}
    		} elseif (isset($this->request->post['country_id'])) {
    			// Selected new address OR is a guest
    			if (isset($this->request->post['country_id'])) {
    				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
    			} else {
    				$country_info = '';
    			}
    			
    			if (isset($this->request->post['zone_id'])) {
    				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
    			} else {
    				$zone_info = '';
    			}
    			
    			if ($country_info) {
    				$shipping_address['country'] = $country_info['name'];
    				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
    				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
    				$shipping_address['address_format'] = $country_info['address_format'];
    			} else {
    				$shipping_address['country'] = '';
    				$shipping_address['iso_code_2'] = '';
    				$shipping_address['iso_code_3'] = '';
    				$shipping_address['address_format'] = '';
    			}
    			
    			if ($zone_info) {
    				$shipping_address['zone'] = $zone_info['name'];
    				$shipping_address['zone_code'] = $zone_info['code'];
    			} else {
    				$shipping_address['zone'] = '';
    				$shipping_address['zone_code'] = '';
    			}
    		
    			$shipping_address['firstname'] = $this->request->post['firstname'];
    			$shipping_address['lastname'] = $this->request->post['lastname'];
    			$shipping_address['company'] = $this->request->post['company'];
    			$shipping_address['address_1'] = $this->request->post['address_1'];
    			$shipping_address['address_2'] = $this->request->post['address_2'];
    			$shipping_address['postcode'] = $this->request->post['postcode'];
    			$shipping_address['city'] = $this->request->post['city'];
    			$shipping_address['country_id'] = $this->request->post['country_id'];
    			$shipping_address['zone_id'] = $this->request->post['zone_id'];
    		}
    		
    		if (!empty($shipping_address)) {
    			// Shipping Methods
    			$method_data = array();
    
    			$this->load->model('extension/extension');
    
    			$results = $this->model_extension_extension->getExtensions('shipping');
    
    			foreach ($results as $result) {
    				if ($this->config->get($result['code'] . '_status')) {
    					$this->load->model('extension/shipping/' . $result['code']);
    
    					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);
    
    					if ($quote) {
    						$method_data[$result['code']] = array(
    							'title'      => $quote['title'],
    							'quote'      => $quote['quote'],
    							'sort_order' => $quote['sort_order'],
    							'error'      => $quote['error']
    						);
    					}
    				}
    			}
    			
    		
    
    			$sort_order = array();
    
    			foreach ($method_data as $key => $value) {
    				$sort_order[$key] = $value['sort_order'];
    			}
    
    			array_multisort($sort_order, SORT_ASC, $method_data);
    
    			$this->session->data['shipping_methods'] = $method_data;
    		}
    		
    		if ($this->config->get('quickcheckout_delivery_time') == '2') {
    			$min = $this->config->get('quickcheckout_delivery_min');
    			$max = $this->config->get('quickcheckout_delivery_max');
    			$today = date('d M Y');
    			
    			$min_date = date('d M Y', strtotime($today . ' + ' . $min . ' day'));
    			$max_date = date('d M Y', strtotime($today . ' + ' . $max . ' day'));
    			
    			$min = 0;
    			$max = 0;
    			
    			if ($this->config->get('quickcheckout_delivery_unavailable')) {
    				$dates = str_replace('"', '', html($this->config->get('quickcheckout_delivery_unavailable')));
    			} else {
    				$dates = array();
    			}
    			
    			foreach (explode(',', $dates) as $unavailable) {
    				$unavailable = strtotime($unavailable);
    				
    				if ($unavailable >= strtotime($min_date) && $unavailable <= strtotime($max_date)) {
    					$max++;
    				}
    				
    				if ($unavailable == strtotime($min_date)) {
    					$min++;
    				}
    			}
    			
    			$min_date = date('d M Y', strtotime($min_date . ' + ' . $min . ' day'));
    			$max_date = date('d M Y', strtotime($max_date . ' + ' . $max . ' day'));
    			
    			$data['estimated_delivery'] = $min_date . ' - ' . $max_date;
    			$data['estimated_delivery_time'] = str_pad($this->config->get('quickcheckout_delivery_min_hour'), 2, '0', STR_PAD_LEFT) . ' 00 - ' . str_pad($this->config->get('quickcheckout_delivery_max_hour'), 2, '0', STR_PAD_LEFT) . ' 00';
    		}
    
    		if (empty($this->session->data['shipping_methods'])) {
    			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
    		} else {
    			$data['error_warning'] = '';
    		}	
    					
    		if (isset($this->session->data['shipping_methods'])) {
    			$data['shipping_methods'] = $this->session->data['shipping_methods']; 
    		} else {
    			$data['shipping_methods'] = array();
    		}
    		
    		if (isset($this->request->post['shipping_method'])) {
    			$data['code'] = $this->request->post['shipping_method'];
    		} elseif (isset($this->session->data['shipping_method']['code'])) {
    			$data['code'] = $this->session->data['shipping_method']['code'];
    		} else {
    			$data['code'] = $this->config->get('quickcheckout_shipping_default');
    		}
    		
    		$exists = false;
    		$stored_code = false;
    		
    		foreach ($data['shipping_methods'] as $key => $shipping_method) {
    			if ($key == $data['code']) {
    				foreach ($shipping_method['quote'] as $quote) {
    					$exists = true;
    					
    					$data['code'] = $quote['code'];
    					
    					break;
    				}
    				
    				break;
    			} else {
    				foreach ($shipping_method['quote'] as $quote) {
    					if (!$stored_code) {
    						$stored_code = $quote['code'];
    					}
    					
    					if ($quote['code'] == $data['code']) {
    						$exists = true;
    						
    						break;
    					}
    				}
    			}
    		}
    
    		if (!$exists) {
    			$data['code'] = $stored_code;
    		}
    		
    		if (isset($this->request->post['delivery_date'])) {
    			$data['delivery_date'] = $this->request->post['delivery_date'];
    		} elseif (isset($this->session->data['delivery_date'])) {
    			$data['delivery_date'] = $this->session->data['delivery_date'];
    		} else {
    			$data['delivery_date'] = '';
    		}
    		
    		if (isset($this->request->post['delivery_time'])) {
    			$data['delivery_time'] = $this->request->post['delivery_time'];
    		} elseif (isset($this->session->data['delivery_time'])) {
    			$data['delivery_time'] = $this->session->data['delivery_time'];
    		} else {
    			$data['delivery_time'] = '';
    		}
    		
    		// All variables
    		$data['logged'] = $this->customer->isLogged();
    		$data['debug'] = $this->config->get('quickcheckout_debug');
    		$data['shipping'] = $this->config->get('quickcheckout_shipping');
    		$data['shipping_logo'] = $this->config->get('quickcheckout_shipping_logo');
    
    		if($data['shipping_logo']){
    			foreach($data['shipping_logo'] as &$image){
    				$image = 'image/' . $image;
    			}
    		}
    
    		// General Shipping Notice
    		$quickcheckout_shipping_general_notice = $this->config->get('quickcheckout_shipping_general_notice');
    		if($quickcheckout_shipping_general_notice){
    			foreach($quickcheckout_shipping_general_notice as $language_id => &$general_description){
    				$general_description = html($general_description);
    				if(trim(strip_tags($general_description)) == ""){
    					$general_description = '';
    				}
    			}
    		}
    
    		$data['general_description'] = '';
    		if(isset($quickcheckout_shipping_general_notice[$this->config->get('config_language_id')])){
    			$data['general_description'] = $quickcheckout_shipping_general_notice[$this->config->get('config_language_id')];
    		}
    		// End General Shipping Notice
    		
    		// Individual Shipping Notice
    
    		$quickcheckout_shipping_individual_shipping_notice = $this->config->get('quickcheckout_shipping_individual_shipping_notice');
    		if($quickcheckout_shipping_individual_shipping_notice){
    			foreach($quickcheckout_shipping_individual_shipping_notice as $code => $individual_notice){
    				foreach($individual_notice as $language_id => $each_notice){
    					if(trim(strip_tags($each_notice)) == ""){
    						$quickcheckout_shipping_individual_shipping_notice[$code][$language_id] = '';
    					}
    				}
    			}
    		}
    		else{
    			$quickcheckout_shipping_individual_shipping_notice = array();
    		}
    		//debug($quickcheckout_shipping_individual_shipping_notice);
    		foreach ($data['shipping_methods'] as $code => &$value) {
    			$value['notice']	=	'';
    			if(isset($quickcheckout_shipping_individual_shipping_notice[$code][$this->config->get('config_language_id')])){
    				$value['notice']	=	$quickcheckout_shipping_individual_shipping_notice[$code][$this->config->get('config_language_id')];
    			}
    		}
    		//debug($data['shipping_methods']);
    		
    		// Individual Shipping Notice
    		
    		
    		//debug($data['quickcheckout_shipping_general_notice']);
    		//debug($data['quickcheckout_shipping_individual_shipping_notice']);
    
    		$data['delivery'] = $this->config->get('quickcheckout_delivery');
    		$data['delivery_delivery_date'] = $this->config->get('quickcheckout_delivery_date');
    		$data['delivery_delivery_time'] = $this->config->get('quickcheckout_delivery_time');	//debug($data['delivery_delivery_time']);
    		$data['delivery_required'] = $this->config->get('quickcheckout_delivery_required');
    		$data['delivery_times'] = $this->config->get('quickcheckout_delivery_times');
    		$data['delivery_unavailable'] = html($this->config->get('quickcheckout_delivery_unavailable'));
    		$data['delivery_days_of_week'] = html($this->config->get('quickcheckout_delivery_days_of_week'));
    		$data['cart'] = $this->config->get('quickcheckout_cart');
    		$data['shipping_reload'] = $this->config->get('quickcheckout_shipping_reload');
    		$data['language_id'] = $this->config->get('config_language_id');
    		
    		if ($this->config->get('quickcheckout_delivery_min')) {
    			$data['delivery_min'] = date('Y-m-d', strtotime('+' . $this->config->get('quickcheckout_delivery_min') . ' days'));
    		} else {
    			$data['delivery_min'] = date('Y-m-d');
    		}
    		
    		if ($this->config->get('quickcheckout_delivery_max')) {
    			$data['delivery_max'] = date('Y-m-d', strtotime('+' . $this->config->get('quickcheckout_delivery_max') . ' days'));
    		} else {
    			$data['delivery_max'] = date('Y-m-d');
    		}
    		
    		$hours = range($this->config->get('quickcheckout_delivery_min_hour'), $this->config->get('quickcheckout_delivery_max_hour'));
    
    		$data['hours'] = implode(',', $hours);
    
    		$is_free = false;
    		
    		if(!isset($data['shipping_methods']['category_product_based']['quote']['category_product_based_1'])) {
    		    $is_free = true;
    		}
    		
    		echo json_encode(['is_free' => $is_free]);
    	
      	}
}