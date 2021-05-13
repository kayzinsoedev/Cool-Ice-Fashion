<?php
class ControllerExtensionModuleOurTeam extends Controller {
    public function index($setting) {
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        
        $modulename  = 'our_team';
        $data['contents'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'content');

        return $this->load->view('extension/module/our_team', $data);
    }
}