<?php
class ControllerExtensionModuleAboutUs extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
            $position = array();
            $position[0]['value'] = 0;
            $position[0]['label'] = "Left";
            $position[1]['value'] = 1;
            $position[1]['label'] = "Right";
            
            $array = array(
            'oc' => $this,
            'heading_title' => 'About Us',
            'modulename' => 'about_us',
                'fields' => array(
                    array('type' => 'repeater', 'label' => 'content', 'name' => 'content', 'fields' => array(
                        array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                        array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                        array('type' => 'dropdown', 'label' => 'Image Position', 'name' => 'position', 'choices' => $position),
                        array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>true)
                        ),
                    ), // end of repeater
                ),
            );

        $this->modulehelper->init($array);    
	}
}