<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));
                $api_key = $this->config->get('config_google_api');
                $this->document->addScript("https://maps.googleapis.com/maps/api/js?key=$api_key&callback=gmap", "header", true);
                

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

                $data['store'] = $this->config->get('config_name');
                $data['address'] = nl2br($this->config->get('config_address'));
                $data["geomap"] = $this->config->get('config_geocode');
                $data["geocode"] = str_replace(" ", "", $this->config->get('config_geocode'));

                $data['google_map'] = $this->gmap("google_map",$this->config->get('config_address'));
                
                $data['geocode_hl'] = $this->config->get('config_language');
                $data['store_telephone'] = $this->config->get('config_telephone');
                $data['fax'] = $this->config->get('config_fax');
                $data['open'] = nl2br($this->config->get('config_open'));
                $data['comment'] = $this->config->get('config_comment');
                $data['contact_page'] = $this->url->link('information/contact');
                
		$this->response->setOutput($this->load->view('common/home', $data));
	}
        
        private function gmap($map = '', $address = '', $store = ''){ 
			$details = array();
			
			if($map && $address){
				
				$cached_map = $this->cache->get($map);
				
				if(!$cached_map){
				
					$find = array(
						"\n",
						"\r",
						"\r\n",
						"\n\r",
						" ",
					);
					
					$address = str_replace( $find, ' ', $address );
		
					$param = rawurlencode($address) . '&key=' . $this->config->get('config_google_api');
		
					$api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $param; 
					
					$response = dynamic($api_url);
		
					if($response && isset($response['status']) && $response['status'] == 'OK'){
						$cached_map = array(
							'lat'	=> $response['results'][0]['geometry']['location']['lat'],
							'lng'	=> $response['results'][0]['geometry']['location']['lng'],
							'store'	=> $store?$store:$this->config->get('config_name'),
							'address'=> $address,
						);
						
						$this->cache->set($map, $cached_map);
					}
					else{
						$this->log->write( $map . " - Either the url is invalid or curl / fopen is not enabled" );
					}
				}
				
				$details = $cached_map;
			}
			
			return $details;
		}
}
