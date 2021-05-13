<?php
class ControllerProductVote extends Controller {

        public function vote(){
            $product_name = "";
            if (isset($this->request->post['vote'])) {
                $product_id = (int)$this->request->post['vote'];
                $this->load->model('catalog/product');
                $product_info = $this->model_catalog_product->getProduct($product_id);
                $product_name = $product_info['name'];
            }
            
            //ADMIN EMAIL
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom("edith@website.com");

            $mail->setSender(html_entity_decode("edith@website.com", ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode(sprintf($product_name), ENT_QUOTES, 'UTF-8'));

            $message = "";

            $mail->setText($message);
            // Pro email Template Mod

            if($this->config->get('pro_email_template_status')){
                $this->load->model('tool/pro_email');

                $email_params = array(
                        'type' => 'admin.product.vote',
                        'mail' => $mail,
                        'data' => array(
                                'product_name' => html_entity_decode($product_name, ENT_QUOTES, 'UTF-8'),
                                'message' => html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8')
                        ),
                );
            }else{
                $mail->send();
            }

            $this->model_tool_pro_email->generate($email_params);
            
            $json['success'] = 'Submitted';
            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode($json));
        }
        
}

// Original Line: 422
// After reduced: 268