<?php 
class ControllerQuickCheckoutCart extends Controller {
	public function index() {
		$data = $this->load->language('checkout/checkout');
		$data = array_merge($data, $this->load->language('quickcheckout/checkout'));
		
		$data['button_view'] = $this->language->get('button_view');

		// Totals
		$this->load->model('extension/extension');
		
		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);
		
		// Display prices
		$data['totals'] = array();
		
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$sort_order = array(); 
			
			$results = $this->model_extension_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
		
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}
			
			$total_data = $totals;
				
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);
			
			foreach ($total_data as $total) {
                                if($total['code'] == "shipping" || $total['code'] == "total"){
                                    if(isset($this->session->data['delivery_date']) && isset($this->session->data['delivery_time'])){
                                        $language_id = $this->config->get('config_language_id');
                                        
                                        if($this->session->data['shipping_method']['title'] == "Self Pickup"){
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
                                                    if($key1 == "delivery_times" && $value1 == $this->session->data['delivery_time']){
                                                        $total['value'] += (int)$setting['surcharge'];
                                                    }
                                                }
                                            }
                                        }
                                        
                                        $special_setting = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'special_setting');
                                        if(gettype($special_setting) == "array"){
                                            foreach($special_setting as $arr => $setting){
                                                if($setting['product_group'] == ""){
                                                    if($setting["type"] == 2 && $setting['add_time'] == $this->session->data['delivery_time'] && $setting['delivery_date'] == $this->session->data['delivery_date']){
                                                        $total['value'] += (int)$setting['surcharge2'];
                                                    }
                                                    if($setting["type"] == 3 && $setting['delivery_time'] == $this->session->data['delivery_time'] && $setting['delivery_date'] == $this->session->data['delivery_date']){
                                                        $total['value'] += (int)$setting['surcharge2'];
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
                                                        if($setting["type"] == 2 && $setting['add_time'] == $this->session->data['delivery_time'] && $setting['delivery_date'] == $this->session->data['delivery_date']){
                                                            $total['value'] += (int)$setting['surcharge2'];
                                                        }
                                                        if($setting["type"] == 3 && $setting['delivery_time'] == $this->session->data['delivery_time'] && $setting['delivery_date'] == $this->session->data['delivery_date']){
                                                            $total['value'] += (int)$setting['surcharge2'];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

				$text = $this->currency->format($total['value'], $this->session->data['currency']);
				
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $text
				);
			}
		}
		
		$this->load->model('tool/image');
		$this->load->model('tool/upload');
		
		$data['products'] = array();
		
		$products = $this->cart->getProducts();
		
		$no_image = $this->model_tool_image->resize('no_image.png', $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
    
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $different = 0;
        $dday = 100;
		//$sday = 100;
		$delivery = 0;

		foreach ($products as $product) {
		    $product_category = $this->model_catalog_product->getCategories($product['product_id']);
            if(count($products) > 1){
                foreach($product_category as $category){
                    $cid = $this->model_catalog_category->getCategory($category['category_id']);
                    if(!isset($cid['delivery_day'])){
                        $cid['delivery_day'] = 3;
                    }
                    if($dday == 100){
                        $dday = $cid['delivery_day'];
                        //$sday = $cid['selfpick_day'];
                        $delivery = $cid['delivery_day'];
                    }else{
                        if($dday != $cid['delivery_day']){
                            $dday = $cid['delivery_day'];
                            $delivery = $cid['delivery_day'];
                            $different = 1;
                        }
                        //if($sday != $cid['selfpick_day']){
                        //    $different = 1;
                        //}
                    }
                }
            }else{
                $delivery = 0;
            }
            
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}
			
			$image = $no_image;
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
			}
			
			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$price = false;
			}

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']);
			} else {
				$total = false;
			}

			$recurring = '';

			if ($product['recurring']) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);
				
				if ($product['recurring']['trial']) {
					$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
				}

				if ($product['recurring']['duration']) {
					$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				} else {
					$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				}
			}

			$data['products'][] = array(
				'key'        => isset($product['key']) ? $product['key'] : $product['cart_id'],
				'thumb'     => $image,
				'day'       => $delivery,
				'name'      => $product['name'],
				'model'     => $product['model'],
				'option'    => $option_data,
				'recurring' => $recurring,
				'quantity'  => $product['quantity'],
				'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
				'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
				'price'     => $price,
				'total'     => $total,
				'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
			);
		}
		
		// Gift Voucher
		$data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$image = $no_image;
				if ($voucher['image']) {
					$image = $this->model_tool_image->resize($voucher['image'], $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
				}

				$data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
					'remove'      => $this->url->link('checkout/cart', 'remove=' . $key),
					'image' => $image,
					'href' => $this->url->link('product/gift_card', 'voucher_theme_id='.$voucher['voucher_theme_id'], true),
				);
			}
		}
		
		// All variables
		$data['edit_cart'] = $this->config->get('quickcheckout_edit_cart');
	    $data['checkout_note'] = $this->config->get('config_checkout_note');
	    $data['different'] = $different;
	
		$this->response->setOutput($this->load->view('quickcheckout/cart', $data));
	}
	
	public function update() {
		$json = array();
		
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}
		}
		
		if (isset($this->request->get['remove'])) {
			$this->cart->remove($this->request->get['remove']);
			
			unset($this->session->data['vouchers'][$this->request->get['remove']]);
		}
		
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		
		if ($this->cart->getTotal() < $this->config->get('quickcheckout_minimum_order')) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}
}