<?php
class ControllerProductCategory extends Controller {

	public function index() {

		$data = array();

		// Load Languages
		$data = array_merge($data, $this->load->language('product/category'));

		$this->document->setTitle($data['heading_title']);

		$data['text_compare'] = sprintf($data['text_compare'], (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$theme = $this->config->get('config_theme');

		$sort_default = 'p.sort_order';

		if($this->config->get('product_category_sort_order_status') && isset($this->request->get['path'])){
			$sort_default = 'p2co.sort_order, p.sort_order, LCASE(pd.name)';
		}

		$listing_conditions = array(
			// Listing
			'sort'				=>	$sort_default,
			'order'				=>	'ASC',
			'page'				=>	1,
			'limit'				=>	(int)$this->config->get( $theme . '_product_limit'),
			// Filtering
			'path'				=>	0,
			'price_min' 		=>	0,
			'price_max' 		=>	0,
			'manufacturer_id'	=>	'',
			'filter'			=>	'',
		);

		// Filter Url to apply for Pagination / Breadcrumbs
		$url_filter = array(
			'pagination_filter'		=>	'page,path',
			'breadcrumbs_filter'	=>	'page,path',
		);
                
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		
        foreach ($listing_conditions as $var => &$default){
			if(isset($this->request->get[$var])){
				$default	=	$this->request->get[$var];
			}

			if($var=='sort'){
				$sort_n_order = explode('-', $default);
				
				$order = $listing_conditions[$var];
				if(count($sort_n_order) > 1){
					$order	=	$sort_n_order[1];
				}
				
				${$var}	=	$sort_n_order[0];
			}
			elseif($var != 'order'){
				${$var}	=	$default;
			}
			
		}

		foreach($url_filter as $url => $skip){
			${$url}	= '';
			foreach ($listing_conditions as $var => $default_1){
				if( !strpos( '_' . $skip, $var) && $default_1){ 
					${$url} .= '&' . $var . '=' . ${$var};
				}
			}
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			//'href' => $this->url->link('product/category') . $breadcrumbs_filter
			'href' => $this->url->link('product/category')
		);

		// Load Category Portion

		$category_info = array();

		$categories = array();

		if($path){
			$this->load->model('catalog/category');

			$categories = explode('_', $path);

			$category_path = array();
			foreach($categories as $category_id){
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if($category_info){
					$category_path[] = $category_id;

					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . implode('_', $category_path) . $breadcrumbs_filter)
					);
				}

				if($category_info){
					
					$data['heading_title'] = $category_info['name'];

					$this->document->setTitle($category_info['meta_title']);

					$this->document->setDescription($category_info['meta_description']);

					$this->document->setKeywords($category_info['meta_keyword']);

				}
			}
			
		}
		// End Load Category

		$data['compare'] = $this->url->link('product/compare');

		$data['products'] = array();
		
		$filter_data = array(
			'filter_category_id' => $categories?end($categories):0,
			'filter_manufacturer'=> $manufacturer_id,
			'filter_sub_category'=> true,
			'filter_special'	 => false,
			'price_min'			 => $price_min,
			'price_max'			 => $price_max,
			'filter_filter'      => $filter,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit,				
		); // debug($filter_data);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
		
		$results = $this->model_catalog_product->getProducts($filter_data);
		//debug($results);
		if($category_info){
			$this->facebookcommonutils = new FacebookCommonUtils();
			$params = new DAPixelConfigParams(array(
			'eventName' => 'ViewCategory',
			'products' => $results,
			'currency' => $this->currency,
			'currencyCode' => $this->session->data['currency'],
			'hasQuantity' => false,
			'isCustomEvent' => false,
			'paramNameUsedInProductListing' => 'content_category',
			'paramValueUsedInProductListing' => $category_info['name']));
			$facebook_pixel_event_params_FAE =
			$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
			// stores the pixel params in the session
			$this->request->post['facebook_pixel_event_params_FAE'] =
			addslashes(json_encode($facebook_pixel_event_params_FAE));
		}
		else{

			// Default category
			$this->facebookcommonutils = new FacebookCommonUtils();
			$params = new DAPixelConfigParams(array(
			'eventName' => 'ViewCategory',
			'products' => $results,
			'currency' => $this->currency,
			'currencyCode' => $this->session->data['currency'],
			'hasQuantity' => false,
			'isCustomEvent' => false,
			'paramNameUsedInProductListing' => 'content_category',
			'paramValueUsedInProductListing' => $this->language->get('text_products')));
			$facebook_pixel_event_params_FAE =
			$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
			// stores the pixel params in the session
			$this->request->post['facebook_pixel_event_params_FAE'] =
			addslashes(json_encode($facebook_pixel_event_params_FAE));
			
		}

		$after_clean = '';
		if(isset($this->request->get['path']) && !is_array($this->request->get['path']) ){
			$after_clean = '&';
			$before_clean = $this->request->get['path'];
			$before_clean = explode('_', $before_clean);
			foreach($before_clean as $key => $category_id){
				if((int)$category_id < 1){
					unset($before_clean[$key]);
				}
			}

			if($before_clean){
				$after_clean = 'path=' .implode('_', $before_clean);
			}
		}
        $data['vote_product'] = $results;
        $today = date("Y-m-d");
        
		foreach ($results as $result) {
		    if($result['date_special_start'] != "0000-00-00"){
		        $this->load->model('catalog/category');
		        $product_category = $this->model_catalog_product->getCategories($result['product_id']);
		        $day = 0;
                foreach($product_category as $category){
                    $cid = $this->model_catalog_category->getCategory($category['category_id']);
                    if($day < $cid['delivery_day']){
                        $day = $cid['delivery_day'];
                    }
                    if($day < $cid['selfpick_day']){
                        $day = $cid['selfpick_day'];
                    }
                }
                $end = $result['date_special_end'];
                if($day > 0){
                    $end = date("Y-m-d",strtotime($result['date_special_end']." -".$day." day"));
                }
		        if(strtotime($result['date_special_start']) <= strtotime($today) && strtotime($end) >= strtotime($today)){
			        $data['products'][] = $this->load->controller('component/product_info', array( 'product_id' => $result['product_id'], 'url' => $after_clean));
		        }
		    }else{
		        $data['products'][] = $this->load->controller('component/product_info', array( 'product_id' => $result['product_id'], 'url' => $after_clean));
		    }
		}

		// Sort
			
		$type_of_sort = array(
			'name'	=>	'pd.name',
			'price'	=>	'p.price',
			'price'	=>	'p.price',
			'model'	=>	'p.model',
		);

		if ($this->config->get('config_review_status')) {
			$type_of_sort['rating'] = 'rating';
		}

		$data['sorts'] = array();

		// Default
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default_asc'),
			'value' => 'sort_order-ASC',
		);

		// The rest of the ordering from $type_of_sort
		foreach($type_of_sort as $type => $column){
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_' . $type . '_asc'),
				'value' => $column.'-ASC',
			);
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_' . $type . '_desc'),
				'value' => $column.'-DESC',
			);
		}
		// End Sort

		// Limit
		$data['limits'] = array();
		
		$config_limit = $this->config->get($theme . '_product_limit');

		$limits = range($config_limit, $config_limit*5, $config_limit);
		
		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
			);
		}
		
		// End Limit

		$path = '';
		if(isset($this->request->get['path'])){
			$path = 'path=' . $this->request->get['path'] . '&';
		}
		$page_data = array(
			'total'	=>	$product_total,
			'page'	=>	$page,
			'limit'	=>	$limit,
			'url'	=>	$this->url->link('product/category', $path . 'page={page}'. $pagination_filter),
		); 

		//debug($page_data);
		$data = array_merge($this->load->controller('component/pagination', $page_data), $data);
		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
		    $this->document->addLink($this->url->link('product/category', '', true), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('product/category', '', true), 'prev');
		} else {
		    $this->document->addLink($this->url->link('product/category', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($product_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('product/category', 'page='. ($page + 1), true), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data = $this->load->controller('component/common', $data);

        if(isset($this->request->get['product_id'])){
		    $product_id = (int)$this->request->get['product_id'];
		    $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($product_id);
            $data['product_detail'] = $product_info;
        }

                if(isset($this->request->get['path'])){
                    $cid = explode("_",$this->request->get['path']);
                    $c_data = $this->model_catalog_category->getCategory(end($cid));
                    if($c_data['custom'] == 1){
                        $data['custom'] = 1;
                    }else{
                        $data['custom'] = 0;
                    }
                    $data['description'] = html_entity_decode($c_data['description']);
                    $fc_data = $this->model_catalog_category->getCategory($cid[0]);
                    $data['cat_title'] = $data['heading_title'];
                    $data['heading_title'] = $fc_data['name'];
                }else{
                    $data['custom'] = 0;
                }
                
                // Captcha
                $data['captcha'] = '';
                if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
                    $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
                }
                if(isset($category_info['vote'])){
                    if($category_info['vote'] == 1){
                        $this->response->setOutput($this->load->view('product/vote', $data));
                    }else{
                        $this->response->setOutput($this->load->view('product/category', $data));
                    }
                }else{
                    $this->response->setOutput($this->load->view('product/category', $data));
                }
	}
        
        public function enquiry(){
            //ADMIN EMAIL
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->request->post['email']);

            $mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode(sprintf($this->request->post['subject'], $this->request->post['name']), ENT_QUOTES, 'UTF-8'));

            $message = "";

//            $attachment = DIR_UPLOAD_ENQUIRY.$this->request->post['upload_file_field'];

            $mail->setText($message);
            // Pro email Template Mod

            if($this->config->get('pro_email_template_status')){
                $this->load->model('tool/pro_email');

                $email_params = array(
                        'type' => 'admin.product.enquiry',
                        'mail' => $mail,
                        'reply_to' => $this->request->post['email'],
//                        'enquiry_attacment' => $attachment,
                        'data' => array(
                                'enquiry_subject' => html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'),
                                'enquiry_name' => html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'),
                                'enquiry_email' => $this->request->post['email'],
                                'enquiry_sample_link' => $this->request->post['sample_link'],
                                'enquiry_no_pax' => html_entity_decode($this->request->post['no_pax'], ENT_QUOTES, 'UTF-8'),
                                'enquiry_date_required' => date("d-M-Y", strtotime($this->request->post['date_required'])),
                                'enquiry_message' => html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8')
                        ),
                );
            }else{
                $mail->send();
            }

            $this->model_tool_pro_email->generate($email_params);
            
            $json['success'] = 'Enquiry Submitted';
            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode($json));
        }
        
}

// Original Line: 422
// After reduced: 268