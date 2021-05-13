<?php
class ControllerExtensionModuleTestimonials extends Controller {
    public function index($setting) {
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        
        $modulename  = 'testimonials';
        $data['title'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title');

        $this->load->model('extension/module/testimonial');
        $results = $this->model_extension_module_testimonial->getModuleReviews(0, 5, 0);
        
        if ($results) {
            foreach ($results as $result) {
                $data['reviews'][] = array(
                    'review_id' => $result['review_id'],
                    'text' => utf8_substr(strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8')), 0, 300),
                    'rating' => (int)$result['rating'],
                    'author' => $result['author'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                );
            }
        }
        $data['view_more'] = $this->url->link('testimonial/testimonial');
        
        return $this->load->view('extension/module/testimonials', $data);
    }
}