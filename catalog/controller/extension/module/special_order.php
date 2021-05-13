<?php
class ControllerExtensionModuleSpecialOrder extends Controller {
    public function index($setting) {
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        
        $modulename  = 'special_order';
        $data['title'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title');
        $data['description'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'description');

        return $this->load->view('extension/module/special_order', $data);
    }
}