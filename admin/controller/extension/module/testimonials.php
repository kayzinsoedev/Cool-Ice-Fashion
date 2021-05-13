<?php
class ControllerExtensionModuleTestimonials extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
            $array = array(
            'oc' => $this,
            'heading_title' => 'Testimonials',
            'modulename' => 'testimonials',
                'fields' => array(
                    array('type' => 'text', 'label' => 'Title', 'name' => 'title'), // end of repeater
                ),
            );

        $this->modulehelper->init($array);    
	}
}