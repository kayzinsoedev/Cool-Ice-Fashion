<?php
class ControllerExtensionModuleMilestones extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
            $array = array(
            'oc' => $this,
            'heading_title' => 'Milestones',
            'modulename' => 'milestones',
                'fields' => array(
                    array('type' => 'repeater', 'label' => 'milestone', 'name' => 'milestone', 'fields' => array(
                        array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                        array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                        array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>true)
                        ),
                    ), // end of repeater
                ),
            );

            $this->modulehelper->init($array);
	}
}