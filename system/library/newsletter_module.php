<?php
class Newsletter_Module {
    private $modulename = 'newsletter_module';

    public function __construct($config, $db, $log, $session, $url, $modulehelper) {
		$this->config = $config;
		$this->db = $db;
		$this->log = $log;
		$this->session = $session;
		$this->url = $url;
		$this->modulehelper = $modulehelper;
    }

    public function initMailchimp() {
        $language_id = $this->config->get('config_language_id');
        $mode = $this->modulehelper->get_field ( $this, $this->modulename, $language_id, 'mode' );
        $mailchimp_api_key_test = $this->modulehelper->get_field ( $this, $this->modulename, $language_id, 'mailchimp_api_key_test' );
        $mailchimp_api_key_live = $this->modulehelper->get_field ( $this, $this->modulename, $language_id, 'mailchimp_api_key_live' );
        $mailchimp_api_key = '';
        $mailchimp = array();

        if($mode == 'live') {
            $mailchimp_api_key = $mailchimp_api_key_live;
        }
        else {
            $mailchimp_api_key = $mailchimp_api_key_test;
        }

        if($mailchimp_api_key != '') {
            $mailchimp = $this->getMailchimpArr($mailchimp_api_key);
        }

        return $mailchimp;
    }
	
	public function getMailchimpKey() {
		$language_id = $this->config->get('config_language_id');
        $mode = $this->modulehelper->get_field ( $this, $this->modulename, $language_id, 'mode' );
        $mailchimp_api_key_test = $this->modulehelper->get_field ( $this, $this->modulename, $language_id, 'mailchimp_api_key_test' );
        $mailchimp_api_key_live = $this->modulehelper->get_field ( $this, $this->modulename, $language_id, 'mailchimp_api_key_live' );
        $mailchimp_api_key = '';
        $mailchimp = array();

        if($mode == 'live') {
            $mailchimp_api_key = $mailchimp_api_key_live;
        }
        else {
            $mailchimp_api_key = $mailchimp_api_key_test;
        }
		
		return $mailchimp_api_key;
	}

    public function getMailchimpListId() {
        // pull Mailchimp list
        $language_id = $this->config->get('config_language_id');
        $mailchimp_list_list_id = $this->modulehelper->get_field ( $this, $this->modulename, $language_id, 'mailchimp_list' );
       
        return $mailchimp_list_list_id;
    }
    
    public function getMailchimpArr($mca_api_key) {
		//echo DIR_SYSTEM . 'library/mailchimp-api/index.php'; die;
        include(DIR_SYSTEM . 'library/mailchimp-api/index.php');
        $mailchimp = initilizeMailchimp($mca_api_key);
        return $mailchimp;
    }

    public function subscribeTheSubscriber($MailChimp, $data) {
        $list_id = $this->getMailchimpListId();
        $result = $MailChimp->post('lists/'.$list_id.'/members', $data);
        return($result);
    }
    
    public function unsubcribeTheSubscriber($MailChimp, $data) {
        $list_id = $this->getMailchimpListId();
        $result = $MailChimp->patch('lists/'.$list_id.'/members/'. md5(strtolower($data['email_address'])), array('status' => 'unsubscribed') );
        return($result);
    }

    public function resubscribeTheSubscriber($MailChimp, $data) {
        $list_id = $this->getMailchimpListId();
        $result = $MailChimp->put('lists/'.$list_id.'/members/'. md5(strtolower($data['email_address'])), array('status' => 'subscribed') );
        return($result);
    }
}