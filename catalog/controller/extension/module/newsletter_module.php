<?php
class ControllerExtensionModuleNewsletterModule extends Controller {
	public function index() {
        $this->load->language('extension/module/newsletter_module');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['error_title'] = $this->language->get('error_title');
        $data['success_title'] = $this->language->get('success_title');

        $data['uniqid'] = uniqid();

        $language_id = $this->config->get('config_language_id');
        $modulename  = 'newsletter_module';

        $title = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'title' );
        $description = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'description' );
        $email_field_placeholder_text = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'email_field_placeholder_text' );
        $submit_button_field_text = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'submit_button_field_text' );

        $data['title'] = $this->language->get('text_default_title');
        $data['description'] = '';
        $data['email_field_placeholder_text'] = $this->language->get('text_default_email_text');
        $data['submit_button_field_text'] = $this->language->get('text_default_submit_text');

        if($title) {
            $data['title'] = $title;
        }
        if($description) {
            $data['description'] = $description;
        }
        if($email_field_placeholder_text) {
            $data['email_field_placeholder_text'] = $email_field_placeholder_text;
        }
        if($submit_button_field_text) {
            $data['submit_button_field_text'] = $submit_button_field_text;
        }

        // if customer logged in then populate the email
        if($this->customer->isLogged()) {
            $data['email'] = $this->customer->getEmail();
        }
        else {
            $data['email'] = '';
        }

        // if customer logged in and has subscribed, then show message
        $data['text_has_subscribed'] = $this->language->get('text_has_subscribed');
        if( $this->customer->isLogged() && $this->hasSubscribed($this->customer->getEmail()) ) {
            $data['has_subscribed'] = 1;
        }
        else {
            $data['has_subscribed'] = 0;
        }

        return $this->load->view('extension/module/newsletter_module', $data);
    }

    public function validate() {
		$json = array();
		
		$this->load->language('extension/module/newsletter_module');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$json['error']['email'] = $this->language->get('error_email');
            }
            else {
                $query = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer_newsletter_list WHERE email = '" . $this->db->escape($this->request->post['email']) . "' AND status = '1'");

                if($query->num_rows){
                    $json['error']['email'] = $this->language->get('error_email_exists');
                }
            }
			
			if (!isset($json['error'])) {
                $query = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer_newsletter_list WHERE email = '" . $this->db->escape($this->request->post['email']) . "'");

                if($query->num_rows){
                    // update record
                    $this->db->query("UPDATE " . DB_PREFIX . "customer_newsletter_list SET status = '1' WHERE customer_id = '".$this->customer->isLogged()."'");
                }
                else {
                    // save record to database
                    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_newsletter_list SET customer_id = '".$this->customer->isLogged()."', email = '" . $this->db->escape($this->request->post['email']) . "', date_added = NOW(), status = '1'");
                }

                // mailchimp (newlsetter module)
                $the_mailchimp = new Newsletter_Module($this->config, $this->db, $this->log, $this->session, $this->url, $this->modulehelper);
                $mailchimp = $the_mailchimp->initMailchimp();
                $mailchimp_param = array('email_address' => $this->request->post['email'], 'status' => 'subscribed');
                $chimp = $the_mailchimp->subscribeTheSubscriber($mailchimp, $mailchimp_param); 
                 // mailchimp (newlsetter module)

                // update newsletter in customer
			    $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '1' WHERE email = '".$this->db->escape($this->request->post['email'])."'");

				$json['success'] = $this->language->get('text_success_newsletter');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    private function hasSubscribed($email) {
        $query = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer_newsletter_list WHERE email = '" . $this->db->escape($email) . "' AND status = '1'");

        if($query->num_rows){
            return true;
        }

        return false;
    }
}