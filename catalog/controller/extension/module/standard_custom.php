<?php
class ControllerExtensionModuleStandardCustom extends Controller {
    public function index($setting) {
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        
        $modulename  = 'standard_custom';
        $data['title1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title1');
        $data['description1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'main_description1');
        $data['image1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image1');
        $data['link1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'link1');
        
        $data['title2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title2');
        $data['description2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'main_description2');
        $data['image2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image2');
        $data['link2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'link2');

        return $this->load->view('extension/module/standard_custom', $data);
    }
}