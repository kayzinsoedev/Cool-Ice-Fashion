<?php
	class ControllerCommonHeader extends Controller {
		public function index() {
		
			$data['actual_link'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

			$google_map = $this->cache->get('google_map');
			
			// Load Facebook
			$this->facebookPixel($data);

			// Analytics
			$this->load->model('extension/extension');
			
			$data['analytics'] = array();
			
			$analytics = $this->model_extension_extension->getExtensions('analytics');
			
			foreach ($analytics as $analytic) {
				if ($this->config->get($analytic['code'] . '_status')) {
					$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get($analytic['code'] . '_status'));
				}
			}
			
			$server = $this->config->get('config_url');
			
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			}
	
			if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
				$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
			}
			
			$data['meta_store'] = $this->config->get('config_store');
			$data['schema_json_code'] = $this->document->getSchema();

			if(!$data['schema_json_code']){
				$data['schema_json_code'] = $this->config->get('config_schema');
			}

			$data['title'] = $this->document->getTitle();
			
			$data['base'] = $this->url->fix_url($server);
			$data['description'] = $this->document->getDescription();
			$data['keywords'] = $this->document->getKeywords();
			$data['links'] = $this->document->getLinks();
			$data['styles'] = $this->document->getStyles();
			$data['scripts'] = $this->document->getScripts();
			$data['lang'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			
			$data['name'] = $this->config->get('config_name');

			$data['seo_enabled'] = $this->config->get('config_seo_url');
			
			$data['logo'] = '';

			if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
				$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
			}
			
			$this->load->language('common/header');
			
			$data['text_home'] = $this->language->get('text_home');
			
			// Wishlist
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
			
			if ($this->customer->isLogged()) {
				$this->load->model('account/wishlist');
				
				$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
			}
			
			$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
			$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
			
			$data['text_login_register'] = $this->language->get('text_login_register');
			$data['text_account'] = $this->language->get('text_account');
			$data['text_register'] = $this->language->get('text_register');
			$data['text_login'] = $this->language->get('text_login');
			$data['text_order'] = $this->language->get('text_order');
			$data['text_transaction'] = $this->language->get('text_transaction');
			$data['text_download'] = $this->language->get('text_download');
			$data['text_logout'] = $this->language->get('text_logout');
			$data['text_checkout'] = $this->language->get('text_checkout');
			$data['text_category'] = $this->language->get('text_category');
			$data['text_all'] = $this->language->get('text_all');
			
			$data['home'] = $this->url->link('common/home');
			$data['wishlist'] = $this->url->link('account/wishlist', '', true);
			$data['logged'] = $this->customer->isLogged();
			$data['account'] = $this->url->link('account/account', '', true);
			$data['register'] = $this->url->link('account/register', '', true);
			$data['login'] = $this->url->link('account/login', '', true);
			$data['order'] = $this->url->link('account/order', '', true);
			$data['transaction'] = $this->url->link('account/transaction', '', true);
			$data['download'] = $this->url->link('account/download', '', true);
			$data['logout'] = $this->url->link('account/logout', '', true);
			$data['shopping_cart'] = $this->url->link('checkout/cart');
			$data['checkout'] = $this->url->link('checkout/checkout', '', true);
			$data['contact'] = $this->url->link('information/contact');
			$data['flavours'] = $this->url->link('information/information','&information_id=8');
			$data['telephone'] = $this->config->get('config_telephone');
			
			// For page specific css
			
			$data['class'] = 'common-home';
			
			if (isset($this->request->get['route'])) {
				$class = '';
				
				if (isset($this->request->get['product_id'])) {
					$class = ' pid-' . $this->request->get['product_id'];
				}
				elseif (isset($this->request->get['path'])) {
					$class = ' cid-' . $this->request->get['path'];
				}
				elseif (isset($this->request->get['manufacturer_id'])) {
					$class = ' mid-' . $this->request->get['manufacturer_id'];
				}
				elseif (isset($this->request->get['information_id'])) {
					$class = ' iid-' . $this->request->get['information_id'];
				}
				elseif (isset($this->request->get['ncat'])) {
					$class = ' ncat-' . $this->request->get['ncat'];
				}
				elseif (isset($this->request->get['news_id'])) {
					$class = ' nid-' . $this->request->get['news_id'];
				}
				
				$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
			}
			
			// Social Media Sharing

			$this->loadSocialTags($data, $server);

			$theme = $this->config->get('config_theme');
			$menu_id = $this->config->get($theme . "_header");
			
			$menu = $this->load->controller('common/menu', $menu_id);
                        
			$this->fill_sub_categories($menu);

			$data['menu'] = $this->craftHtml($menu);

			$data['mobile_menu'] = $this->craftMobileHtml($menu);

			// Load Controller
			$controllers = array(
				'language'			=>	'common/language',
				'currency'			=>	'common/currency',
				'search'			=>	'common/search',
				'cart'				=>	'common/cart',
				
				'enquiry'			=>	'common/enquiry',
			);
			foreach($controllers as $var => $controller) 
				$data[$var]	=$this->load->controller($controller);

			// Load Parts
			$parts = array(
				'fb_pixel'			=>	'common/header/fb_pixel',
				'fb_messanger'		=>	'', //'common/header/fb_messanger',

				'head_tags'			=>	'common/header/_head_meta',
				'login_part'		=>	'common/header/login',
				'wishlist'			=>	'', //'common/header/wishlist',
				'pop_up_search'		=>	'common/header/search_pop_up',	//	Note: echo $search for non popup search box
			); 

			foreach($parts as $var => $part) 
				$data[$var]	=$this->load->view($part, $data);

			// Load Page Banner
			$data['page_banner'] = $this->load->controller('component/page_banner');

			$data['isMobile'] = $this->mobile_detect->isMobile()?'mobile':'desktop';
			

			// Moz in mac handler
			$os = getenv("HTTP_USER_AGENT"); //debug($os);
			$os = strtolower($os);

			if(strpos('_' . $os, 'macintosh') || strpos('_' . $os, 'mac')){
				$data['isMobile'] .= ' mac-browser';
			}
			
	        $this->load->library('modulehelper');
            $Modulehelper = Modulehelper::get_instance($this->registry);

            $language_id = $this->config->get('config_language_id');
        
            $modulename  = 'header_text';
            $data['header_icon'] = $this->modulehelper->get_field ( $this, $modulename, $language_id, "image");
            $data['header_title'] = $this->modulehelper->get_field ( $this, $modulename, $language_id, "title");

			return $this->load->view('common/header/header', $data);
		}

		private function craftMobileHtml($menu = array(), $level = 0, $menu_part = 0 ){
			 
			if(!is_array($menu)){
				return '';
			}

			$menus = '';

			$index = 1;

			foreach($menu as $link){

				$href = $link['href'];
				$name = $link['name'];

				$sub_menu = '';
				if($link['child']){
					$sub_menu = $this->craftMobileHtml($link['child'], $level+1);
				}

				$inner_text = $name;
				if(!$level){
					$inner_text = '<span>' . $name . '</span>';
				}

				$carat = '';

				$id = generateSlug($name) . '-' . $menu_part . '-' . $level . '-' . $index;

				if($sub_menu){
					$menus .= '<li class="has-children '.($link['active']?'active':'').'">';
					$menus .= '	<input type="checkbox" name ="sub-group-'.$id.'" id="sub-group-'.$id.'" class="hidden">';

					$menus .= '	<a href="' . $href . '" alt="' . $name . '" >'.$inner_text.'</a>';
					$menus .= '	<label for="sub-group-'.$id.'"><i class="fa fa-caret-down" aria-hidden="true"></i></label>';
					$menus .= $sub_menu;
				} 
				else{ 
					$menus .= '<li class="'.($link['active']?'active':'').'">';
					$menus .= '	<a href="' . $href . '" alt="' . $name . '" >'.$inner_text.'</a>';
				}

				$menus .= '</li>';

				$index++;
			}
			
			if($level == 0){
			    $menus .= '<li class="">';
    			    $menus .= '	<a href="' . $this->url->link('information/information','information_id=8') . '" alt="Flavours In Store" >Flavours In Store</a>';
    		    $menus .= '</li>';	
            }
			if( trim($menus) != '' ){
				if($level){
					$menus = '<ul>' . $menus . '</ul>';
				}
				else{
					$menus = 
					'<ul class="cd-accordion-menu animated">'.
						$menus.
					'</ul>';
				}
			}
			

			return $menus;
		}
		private function craftHtml1($menu = array(), $level = 0, $menu_part = 0 ){
			
			if(!is_array($menu)){
				return '';
			}

			$menus = '';

			$index = 1;

			foreach($menu as $link){

				$tab_option = $link['new_tab']?'target="_blank"':'';

				$href = $link['href'];
				$name = $link['name'];

				$sub_menu = '';
				if($link['child']){
					$sub_menu = $this->craftHtml($link['child'], $level+1);
				}

				$inner_text = $name;
				if(!$level){
					$inner_text = '<span>' . $name . '</span>';
				}
	
				// $carat = '<div class="caret"></div>';
				$carat = '';

				if($sub_menu) {
					$menus .= '<li class="'.($link['active']?'active':'').'">';
					if($level){
						$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $name . ' ' . $carat . '</a>' . $sub_menu;
					}else{
						$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>' . $sub_menu;
					}
				} 
				else{ 
					$menus .= '<li class="'.($link['active']?'active':'').'">';
					$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>';
				}

				$menus .= '</li>';

				$index++;
			}

			if( trim($menus) != '' ){
				if($level){
					$menus = '<ul>' . $menus . '</ul>';
				}
				else{
					$menus = 
					'<ul id="main-menu" class="sm sm-blue">'.
						$menus.
					'</ul>';
				}
			}
			

			return $menus;
		}
		private function craftHtml($menu = array(), $level = 0, $menu_part = 0 ){
			if(!is_array($menu)){
				return '';
			}

			$menus = '';

			$index = 1;
                        $count = 1;
                        $home = $this->url->link('common/home');
                        $logo = $this->config->get('config_logo');
                        $name = $this->config->get('config_name');
                        if($level == 0){
                        $menus .= '<li class="hidden_logo">
                                        <a class="header-logo-image" href="'.$home.'">
                                            <img src="image/'.$logo.'" title="'.$name.'" alt="'.$name.'" class="img-responsive" />
					</a>
                                   </li>';
                        }
			foreach($menu as $link){

				$tab_option = $link['new_tab']?'target="_blank"':'';

				$href = $link['href'];
				$name = $link['name'];

				$sub_menu = '';
//				if($link['child']){
////					$sub_menu = $this->craftHtml($link['child'], $level+1);
//                                    $path = 0;
//
//                                    $query_break = explode('&path=', $link['query']);
//
//                                    if(count($query_break) > 1 && isset($query_break[1]) ){
//                                            $path	=	(int)$query_break[1];
//                                    }
//                                    
//                                    $sub_menu = '';
//                                        $sub_menu.= '<div class="dropdown-menu online-shop-menu menu_'.$count.'">';
//                                            $sub_menu.= '<div id="category_accordion" class="row menu_box_hover">';
////                                                $sub_menu.= '<div class="col-md-8 col-sm-8 custom-category-container" style="display: flex;flex-wrap: wrap;">';
//                                                $sub_menu.= '<div class="row">';
//                                                $sub_menu.= '<div class="col-md-9 col-sm-8 custom-category-container">';
//                                                        $sub_menu.= $this->subCraftHtml($link['child'], $level+1);
//                                                $sub_menu.= '</div>';
//					
//                                        
//                                                $sub_menu.= '<div class="col-md-3 col-sm-4 custom-side-menu">';
//                                                    $sub_menu.= '<img src="image/cssbackground/default.png" alt="Online Shop" style="width:100%">';
//                                                        $url = $this->url->link("product/category","path=".$path);
//                                                    $sub_menu.= '<a class="menu_image" href="'.$url.'">View All Products</a>';
//                                                $sub_menu.= '</div>';
//                                                $sub_menu.= '</div>';
//                                            $sub_menu.= '</div>';
//                                        $sub_menu.= '</div>';
//				}
                                if($link['child']){
					$sub_menu = $this->craftHtml($link['child'], $level+1);
				}

				$inner_text = $name;
				if(!$level){
					$inner_text = '<span>' . $name . '</span>';
				}
	
				// $carat = '<div class="caret"></div>';
				$carat = '';

				if($sub_menu) {
					$menus .= '<li class="menu_tab '.($link['active']?'active':'').'" pid="'.$count.'">';
					if($level){
						$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $name . ' ' . $carat . '</a>' . $sub_menu;
					}else{
						$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>' . $sub_menu;
					}
				}
				else{ 
					$menus .= '<li class="menu_tab '.($link['active']?'active':'').'" pid="'.$count.'">';
					$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>';
				}

				$menus .= '</li>';
                                $count++;
				$index++;
			}
                        
            $search = $this->url->link("product/search");
            if($level == 0){
                $menus .= "<li><a href='".$search."'><img src='image/cssbackground/search.png'/></a></li>";
            }
            //$menus .= "<li class='hidden_flavours'><button class='flavours_button'>Flavours In Store</button></li>";
                        
			if( trim($menus) != '' ){
				if($level){
					$menus = '<ul>' . $menus . '</ul>';
				}
				else{
					$menus = 
					'<ul id="main-menu" class="sm sm-blue">'.
						$menus.
					'</ul>';
				}
			}
			

			return $menus;
		}

		private function loadSocialTags(&$data, $server){

			$general_image = false;
			if ($this->config->get('config_image')) {
				$general_image = $this->config->get('config_image');
			}
			
			$data["content_type"] = "website";
			
			$data["store_name"] = $this->config->get("config_name");
			
			$sharing_image	= "";
			
			$data["fb_img"] = "";
			$data["tw_img"] = "";
			$data["gp_img"] = "";
			
			$data['current_page'] = $server;
			
			if (isset($this->request->get['route'])) {
			
				if (isset($this->request->get['product_id'])) {
				
					$data["content_type"] = "article";
					
					$this->load->model("catalog/product");
					
					$product_info = $this->model_catalog_product->getProduct((int)$this->request->get['product_id']);
					
					if($product_info){
						$sharing_image = $product_info["image"];
						
						$data['current_page'] = $this->url->link("catalog/product", "product_id=" . $product_info["product_id"], true);
					}
				}
				
			}

			$fb_width = 600;
			$fb_height = 315;

			$tw_width = 512;
			$tw_height = 299;

			$gp_width = 612;
			$gp_height = 299;
			
			$this->load->model("tool/image");

			if($sharing_image){ 	
				$data["fb_img"] = $this->model_tool_image->resize($sharing_image, $fb_width, $fb_height, 'w');
				$data["tw_img"] = $this->model_tool_image->resize($sharing_image, $tw_width, $tw_height, 'w');
				$data["gp_img"] = $this->model_tool_image->resize($sharing_image, $gp_width, $gp_height, 'w');
			}
			elseif($general_image){
				$data["fb_img"] = $this->model_tool_image->resize($general_image, $fb_width, $fb_height, 'w');
				$data["tw_img"] = $this->model_tool_image->resize($general_image, $tw_width, $tw_height, 'w');
				$data["gp_img"] = $this->model_tool_image->resize($general_image, $gp_width, $gp_height, 'w');
			}
			elseif($this->config->get('config_logo')){
				$sharing_image = $this->config->get('config_logo');

				$data["fb_img"] = $this->model_tool_image->resize($sharing_image, $fb_width, $fb_height, 'w');
				$data["tw_img"] = $this->model_tool_image->resize($sharing_image, $tw_width, $tw_height, 'w');
				$data["gp_img"] = $this->model_tool_image->resize($sharing_image, $gp_width, $gp_height, 'w');
			}
		}

		private function facebookPixel(&$data){
				
			$this->facebookcommonutils = new FacebookCommonUtils();
			$data['facebook_pixel_id_FAE'] =
			$this->config->get('facebook_pixel_id');
			$source = 'exopencart';
			$opencart_version = VERSION;
			$plugin_version = $this->facebookcommonutils->getPluginVersion();
			$agent_string = sprintf(
			'%s-%s-%s',
			$source,
			$opencart_version,
			$plugin_version);
			$facebook_pixel_pii_FAE = array();
			if ($this->config->get('facebook_pixel_use_pii') === 'true'
			&& $this->customer->isLogged()) {
			$facebook_pixel_pii_FAE['em'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getEmail());
			$facebook_pixel_pii_FAE['fn'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getFirstName());
			$facebook_pixel_pii_FAE['ln'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getLastName());
			$facebook_pixel_pii_FAE['ph'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getTelephone());
			}
			$data['facebook_pixel_pii_FAE'] = json_encode(
			$facebook_pixel_pii_FAE,
			JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
			$facebook_pixel_params_FAE = array('agent' => $agent_string);
			$data['facebook_pixel_params_FAE'] = json_encode(
			$facebook_pixel_params_FAE,
			JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
			$data['facebook_pixel_event_params_FAE'] =
			(isset($this->request->post['facebook_pixel_event_params_FAE'])
				&& $this->request->post['facebook_pixel_event_params_FAE'])
			? $this->request->post['facebook_pixel_event_params_FAE']
			: '';
			// flushing away the facebook_pixel_event_params_FAE
			// in the controller to ensure that subsequent fires
			// for the same param is not performed

			$this->request->post['facebook_pixel_event_params_FAE'] = '';
		}

		private function fill_sub_categories(&$menus){
			$this->load->model('catalog/category');

			$current_active_paths = array();
			if(isset($this->request->get['path'])){
				$current_active_paths = explode('_', $this->request->get['path']);
			}

			foreach($menus as &$menu){

				$menu['columns'] = 4;

				// Skip those that have child or not category page
				if( $menu['child'] || !strpos($menu['query'], '/category') ) continue;
				
				$path = 0;

				$query_break = explode('&path=', $menu['query']);
				
				if(count($query_break) > 1 && isset($query_break[1]) ){
					$path	=	(int)$query_break[1];
				}

				$menu['columns'] = 5;

				$subs = array();
                                
				$categories = $this->model_catalog_category->getCategories($path);
		
				foreach($categories as $category){
					$subs_childs = array();
					$active = '';
					
					$sub_categories = $this->model_catalog_category->getCategories($category['category_id']);
					
					foreach($sub_categories as $sub_category){
						$sub_active = '';
						
						if( in_array($sub_category['category_id'], $current_active_paths) ){
								$sub_active = 'active';
						}
						
						$subs_childs[] = array(
							'level'		=>	2,
							'label'		=>	$sub_category['name'],
							'name'	=>	$sub_category['name'],
							'query'	=>	'',
							'new_tab'	=>	0,
							'child'		=>	array(),
							'active'	=>	$sub_active,
							'href'		=>	$this->url->link('product/category', 'path=' . $path . '_' . $category['category_id'] . '_' . $sub_category['category_id'])
						);
					}
					
					if( in_array($category['category_id'], $current_active_paths) ){
							$active = 'active';
					}
					
					$subs[] = array(
						'level'		=>	1,
						'label'		=>	$category['name'],
						'name'	=>	$category['name'],
						'query'	=>	'',
						'new_tab'	=>	0,
						'child'		=>	$subs_childs,
						'active'	=>	$active,
						'href'		=>	$this->url->link('product/category', 'path=' . $path . '_' .$category['category_id'])
					);
				}
				
				$menu['child'] = $subs;
			}
		}
    		private function subCraftHtml($menu = array(), $level = 0, $menu_part = 0 ){

			if(!is_array($menu)){
				return '';
			}

			$menus = '';

			$index = 1;
			foreach($menu as $link){
				$tab_option = $link['new_tab']?'target="_blank"':'';

				$href = $link['href'];
				$name = $link['name'];

				$sub_menu = '';
				if($link['child']){
                                    $sub_menu = '';
                                    
                                    if($level == 2 || $level == 3){
                                            $sub_menu.= '<div id="category_collapse_' . $link['id'] . '" class="collapse in" aria-expanded="true" style="display: content;visibility: visible;">';
                                    }
                                            //$sub_menu.= $this->subCraftHtml($link['child'], $level+1);
                                    if($level == 2 || $level == 3){
                                            $sub_menu.= '</div>';
                                    }
				}

				$inner_text = $name;
				if(!$level){
					$inner_text = '<span>' . $name . '</span>';
				}
	
				$carat = '<div class="caret"></div>';

                                if($level == 1){
                                    if($sub_menu) {
                                            $menus .= '<div class="col-md-3 col-sm-6 custom-category-box">';
                                            $menus .= '<h4 onclick="window.location.href=\''.$href.'\'" style="cursor:pointer">' . $name . '</h4>'.$sub_menu;
                                    } 
                                    else{ 
                                            $menus .= '<div class="col-md-3 col-sm-6 custom-category-box">';
                                            $menus .= '<h4 onclick="window.location.href=\''.$href.'\'" style="cursor:pointer">' . $name . '</h4>';
                                    }
                                    $menus .= '</div>';
                                }else if($level == 2){
                                    $menus.= '<div class="col-sm-12">';
                                    if($sub_menu) {
                                            $menus .= '	<a class="ca" href="javascript:void(0)" data-toggle="collapse" data-target="#category_collapse_'. $link['id']. '">' . $name . '</a>' . $sub_menu;
                                    }else{ 
                                            $menus .= '	<a class="ca no_child" href="'.$href.'">' . $name . '</a>';
                                    }
                                    $menus .= '</div>';
                                }else if($level == 3){
                                    $menus.= '<div class="col-sm-12">';
                                    if($sub_menu) {
                                            $menus .= '	<a class="ca l4" href="javascript:void(0)" data-toggle="collapse" data-target="#category_collapse_'. $link['id']. '">' . $name . '</a>' . $sub_menu;
                                    }else{ 
                                            $menus .= '	<a class="ca l4 no_child" href="'.$href.'">' . $name . '</a>';
                                    }
                                    $menus .= '</div>';
                                }else{
                                            $menus .= '	<div><a href="'. $href .'" class="child_category">'  . $name . '</a></div>';
                                }
                                

				$index++;
			}

//			if( trim($menus) != '' ){
//				if($level){
//					$menus = '<ul>' . $menus . '</ul>';
//				}
//				else{
//					$menus = 
//					'<ul id="main-menu" class="sm sm-blue header_text">'.
//						$menus.
//					'</ul>';
//				}
//			}

			return $menus;
		}        
	} 