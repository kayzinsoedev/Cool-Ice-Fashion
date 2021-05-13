<?php
class ControllerExtensionModuleLocation extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules

        $language_id = $this->config->get('config_language_id');
        $modulename  = 'location';

        $array = array(
            'oc' => $this,
            'heading_title' => 'Outlet Details',
            'modulename' => 'location',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Outlet Details', 'name' => 'locations',
                    'fields' => array(
                        array('type' => 'text', 'label' => 'Name', 'name' => 'title'),
                        array('type' => 'text', 'label' => 'Postal Code', 'name' => 'postal_code'),
                        array('type' => 'text', 'label' => 'Unit No', 'name' => 'unit_no'),
                        array('type' => 'text', 'label' => 'Address 1', 'name' => 'address1'),
                        array('type' => 'text', 'label' => 'Address 2', 'name' => 'address2')
                    )
                ),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
