<?php
class ControllerExtensionModuleNewsletterModule extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
        $choices = $choices2 = array();

        $choices[] = array(
            'label' => 'Test',
            'value' => 'test',
        );
        $choices[] = array(
            'label' => 'Live',
            'value' => 'live',
        );

        // call mailchimp and get lists
        $the_mailchimp = new Newsletter_Module($this->config, $this->db, $this->log, $this->session, $this->url, $this->modulehelper);
        $mailchimp = $the_mailchimp->initMailchimp();
		$mailchimpKey = $the_mailchimp->getMailchimpKey();
		$chimp_list1 = array();
		if($mailchimpKey != '') {
			$chimp_list1 = getLists($mailchimp);
			// debug($chimp_list1);
		}

        if(isset($chimp_list1['lists']) && !empty($chimp_list1['lists'])) {
            foreach ($chimp_list1['lists'] as $list) {
                $choices2[] = array(
                    'label' => $list['name'],
                    'value' =>$list['id'] ,
                );
            }
        }


		$array = array(
            'oc' => $this,
            'heading_title' => 'Newsletter Module',
            'modulename' => 'newsletter_module',
            'fields' => array(
                // mailchimp part
                array('type' => 'dropdown', 'label' => 'Mode', 'name' => 'mode', 'choices' => $choices),
                array('type' => 'text', 'label' => 'Mailchimp API Key (Test)', 'name' => 'mailchimp_api_key_test'),
                array('type' => 'text', 'label' => 'Mailchimp API Key (Live)', 'name' => 'mailchimp_api_key_live'),
                array('type' => 'dropdown', 'label' => 'Mailchimp List<br>(List will appear after saving API key)', 'name' => 'mailchimp_list', 'choices' => $choices2),

                // fontend part
                array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>false),
                array('type' => 'text', 'label' => 'Email Field Placeholder Text', 'name' => 'email_field_placeholder_text'),
                array('type' => 'text', 'label' => 'Submit Button Field Text', 'name' => 'submit_button_field_text'),
            ),
        );

        $this->modulehelper->init($array);    
    }
}
