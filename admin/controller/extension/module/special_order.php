<?php
class ControllerExtensionModuleSpecialOrder extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
            
            $array = array(
            'oc' => $this,
            'heading_title' => 'Special Order',
            'modulename' => 'special_order',
                'fields' => array(
                    array('type' => 'text', 'label' => 'content', 'name' => 'title'),
                    array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>true)
                ),
            );

        $this->modulehelper->init($array);    
	}
}