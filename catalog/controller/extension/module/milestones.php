<?php
class ControllerExtensionModuleMilestones extends Controller {
    public function index($setting) {
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        
        $modulename  = 'milestones';
        $data['milestones'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'milestone');

        return $this->load->view('extension/module/milestones', $data);
    }
}