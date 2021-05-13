<?php
class ControllerExtensionModuleHeaderText extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
            
            $array = array(
            'oc' => $this,
            'heading_title' => 'Header Text',
            'modulename' => 'header_text',
                'fields' => array(
                    array('type' => 'image', 'label' => 'Icon', 'name' => 'image'),
                    array('type' => 'textarea', 'label' => 'Title', 'name' => 'title', 'ckeditor'=>true)
                ),
            );

        $this->modulehelper->init($array);    
	}
}