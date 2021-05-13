<?php
class ControllerModuleInstagram extends Controller {
	public function index() {debug("dsa");die;
		$file = DIR_APPLICATION . 'view/javascript/instagram/css/mycustom.css';
		$filename = 'instagram.log';
		
		$this->load->language('module/instagram');

		$data['heading_title'] = $this->language->get('heading_title');
		$this->document->addStyle('catalog/view/javascript/instagram/slick/slick.css');
		$this->document->addStyle('catalog/view/javascript/instagram/slick/slick-theme.css');
	
		if( file_exists($file) ){
			$this->document->addStyle('catalog/view/javascript/instagram/css/mycustom.css');
		}else{			
			$this->document->addStyle('catalog/view/javascript/instagram/css/custom.css');
		}

		$this->document->addScript('catalog/view/javascript/instagram/slick/slick.min.js');
		
		$json_link="https://api.instagram.com/v1/users/self/media/recent/?";

		if ($this->request->server['HTTPS']) {
			$json_link.="access_token=".str_replace('http', 'https', $this->config->get('instagram_access_token'))."&count={$this->config->get('instagram_photo_amount')}";	
		} else {
			$json_link.="access_token=".$this->config->get('instagram_access_token')."&count={$this->config->get('instagram_photo_amount')}";		
		}

		$data['log'] = '';
		$data['error_warning'] = '';

		$cUrl = curl_init();

		curl_setopt($cUrl, CURLOPT_URL, $json_link);

		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);

		$returnCurl = curl_exec($cUrl);

		if($returnCurl){
			$json = $returnCurl;
			$instagram = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
			
			foreach ($instagram['data'] as $instagram ) {
				$data['instagrams'][] = array(
					'href'  => $instagram['link'],
					'likes' => $instagram['likes']['count'],				
					'img' 	=> str_replace('http://', 'https://', $instagram['images']['standard_resolution']['url']),
					'text'	=> $instagram['caption']['text']
				);
			}			

			$data['entry_instagram'] 	= $this->config->get('instagram_module_name');

			$data['slidesToShow'] 		= $this->config->get('instagram_plugin_slide_show');		
			$data['slidesToScroll'] 	= $this->config->get('instagram_plugin_slide_scroll');
			$data['autoplay'] 			= $this->config->get('instagram_plugin_auto_play');
			$data['autoplaySpeed'] 		= $this->config->get('instagram_plugin_auto_play_speed');
			$data['dots'] 				= $this->config->get('instagram_plugin_dots');
			$data['arrows'] 			= $this->config->get('instagram_plugin_arrows');

			$data['heart_color'] 		= $this->config->get('instagram_heart_color');
			$data['heart_text_color'] 	= $this->config->get('instagram_text_heart_color');

			$data['slidesToShowTablet'] 	= $this->config->get('instagram_plugin_slide_show_tablet');
			$data['slidesToScrollTablet'] 	= $this->config->get('instagram_plugin_slide_scroll_tablet');
			$data['slidesToShowCelphone'] 	= $this->config->get('instagram_plugin_slide_show_celphone');
			$data['slidesToScrollCelphone'] = $this->config->get('instagram_plugin_slide_scroll_celphone');
			$data['center_mode']			= $this->config->get('instagram_center_mode');

			$data['use_plugin']				= $this->config->get('instagram_use_plugin');

			$data['color'] = $this->config->get('instagram_arrow_color');
			$data['text_align'] = $this->config->get('instagram_text_align');
			$data['hover_effect'] = $this->config->get('instagram_hover_heart');

			return $this->load->view('default/template/module/instagram.tpl', $data);			
		}else{
			$this->log = new log($filename);
			$this->log->write(file_get_contents($json_link));
		}
	}
}