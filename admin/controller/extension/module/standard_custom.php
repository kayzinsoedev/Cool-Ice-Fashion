<?php
class ControllerExtensionModuleStandardCustom extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
		$array = array(
            'oc' => $this,
            'heading_title' => 'Standard and Custome Bakes',
            'modulename' => 'standard_custom',
            'fields' => array(
                array('type' => 'text', 'label' => 'Title 1', 'name' => 'title1'),
                array('type' => 'image', 'label' => 'Background Image 1', 'name' => 'image1'),
                array('type' => 'textarea', 'label' => 'Main Description 1', 'name' => 'main_description1','ckeditor'=>true),
                array('type' => 'text', 'label' => 'Link 1', 'name' => 'link1'),
                array('type' => 'text', 'label' => 'Title 2', 'name' => 'title2'),
                array('type' => 'image', 'label' => 'Background Image 2', 'name' => 'image2'),
                array('type' => 'textarea', 'label' => 'Main Description 2', 'name' => 'main_description2','ckeditor'=>true),
                array('type' => 'text', 'label' => 'Link 2', 'name' => 'link2')
            ),
        );

        $this->modulehelper->init($array);    
	}
}
