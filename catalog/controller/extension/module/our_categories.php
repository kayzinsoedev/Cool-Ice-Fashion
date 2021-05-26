<?php
class ControllerExtensionModuleOurCategories extends Controller {
    public function index($setting) {
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $oc = $this;
        $language_id = $this->config->get('config_language_id');

        $modulename  = 'our_categories';
        $data['items'] = $Modulehelper->get_field ($oc, $modulename, $language_id, 'items');
        $data['main_title'] = $Modulehelper->get_field ($oc, $modulename, $language_id, 'main_title');
        $data['description'] = $Modulehelper->get_field ($oc, $modulename, $language_id, 'description');


        // $data['title1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title1');
        // $data['text1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text1');
        // $data['image1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image1');
        // $data['link1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'link1');
        //
        // $data['title2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title2');
        // $data['text2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text2');
        // $data['image2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image2');
        // $data['link2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'link2');
        //
        // $data['title3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title3');
        // $data['text3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text3');
        // $data['image3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image3');
        // $data['link3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'link3');
        //
        // $data['title4'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title4');
        // $data['text4'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text4');
        // $data['image4'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image4');
        // $data['link4'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'link4');

        return $this->load->view('extension/module/our_categories', $data);
    }
}
