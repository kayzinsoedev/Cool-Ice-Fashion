<?php
class ControllerExtensionModuleOurCategories extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
		$array = array(
            'oc' => $this,
            'heading_title' => 'Our Categories',
            'modulename' => 'our_categories',

						'fields' => array(
													array('type' => 'text', 'label' => 'Main Title', 'name' => 'main_title'),
													array('type' => 'text', 'label' => 'Description', 'name' => 'description'),
													array('type' => 'repeater', 'label' => 'Item', 'name' => 'items',
																 'fields' => array(
																		 array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
																		 array('type' => 'text', 'label' => 'Text', 'name' => 'text'),
																		 array('type' => 'text', 'label' => 'Text Color', 'name' => 'text_color'),
																		 array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
																		 array('type' => 'text', 'label' => 'Link', 'name' => 'link'),
																 )
												 ),

						// array('type' => 'text', 'label' => 'Main Title', 'name' => 'main_title'),
						// array('type' => 'text', 'label' => 'Description', 'name' => 'description'),
						//
						// array('type' => 'text', 'label' => 'Title 1', 'name' => 'title1'),
						// array('type' => 'image', 'label' => 'Background Image 1', 'name' => 'image1'),
						// array('type' => 'text', 'label' => 'Text 1', 'name' => 'text1','ckeditor'=>true),
						// array('type' => 'text', 'label' => 'Link 1', 'name' => 'link1'),
						//
						// array('type' => 'text', 'label' => 'Title 2', 'name' => 'title2'),
						// array('type' => 'image', 'label' => 'Background Image 2', 'name' => 'image2'),
						// array('type' => 'text', 'label' => 'Text 2', 'name' => 'text1','ckeditor'=>true),
						// array('type' => 'text', 'label' => 'Link 2', 'name' => 'link2'),
						//
						//
						// array('type' => 'text', 'label' => 'Title 3', 'name' => 'title3'),
						// array('type' => 'image', 'label' => 'Background Image 3', 'name' => 'image3'),
						// array('type' => 'text', 'label' => 'Text 3', 'name' => 'text2','ckeditor'=>true),
						// array('type' => 'text', 'label' => 'Link 3', 'name' => 'link3'),
						//
						//
						// array('type' => 'text', 'label' => 'Title 4', 'name' => 'title4'),
						// array('type' => 'image', 'label' => 'Background Image 4', 'name' => 'image4'),
						// array('type' => 'text', 'label' => 'Text 4', 'name' => 'text4','ckeditor'=>true),
						// array('type' => 'text', 'label' => 'Link 4', 'name' => 'link4')
		        ),


        );

        $this->modulehelper->init($array);
	}
}
