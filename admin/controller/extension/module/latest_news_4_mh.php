<?php
class ControllerExtensionModuleLatestNews4Mh extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules
        $choices = array();

        $this->load->model('catalog/news');

        $sdata = array(
            'filter_status' => 1,
            'start' => 0,
			'limit' => 999
		);
        $results = $this->model_catalog_news->getNewsLimited($sdata);

        foreach ($results as $result) {
            $choices[] = array(
                'label' =>  $result['title'],
                'value' => $result['news_id'],
            );
        }

		$array = array(
            'oc' => $this,
            'heading_title' => 'Latest News - Layout 4',
            'modulename' => 'latest_news_4_mh',
            'fields' => array(
                array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'text', 'label' => 'Description', 'name' => 'desc'),
                array('type' => 'repeater', 'label' => 'News List', 'name' => 'news_list',
                    'fields' => array(
                        array('type' => 'dropdown', 'label' => 'News', 'name' => 'news', 'choices' => $choices),
                    ),
                ),
            ),
        );

        $this->modulehelper->init($array);
	}
}
