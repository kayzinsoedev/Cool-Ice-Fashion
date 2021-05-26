<?php
class ControllerExtensionModuleLatestNews4Mh extends Controller {
	public function index() {
		$this->load->language('extension/module/latest_news_4_mh');

		$this->load->model('catalog/news');
		$this->load->model('catalog/ncategory');

		$language_id = $this->config->get('config_language_id');
		$modulename  = 'latest_news_4_mh';

		$data['modulename'] = $modulename;

		$data['article'] = array();

		$title = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'title' );
		$descripton = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'desc');
	
		$data['heading_title'] = $title ? $title : '';
		$data['heading_descripton'] = $descripton ? $descripton : '';
		$data['button_read_more'] = $this->language->get('button_read_more');
		$data['button_view_more'] = $this->language->get('button_view_more');
		$data['view_more_link'] = $this->url->link('news/ncategory', '', true);

		$date_format = $this->config->get('ncategory_bnews_date_format') ? $this->config->get('ncategory_bnews_date_format') : 'd.m.Y';
		$bbwidth = ($this->config->get('ncategory_bnews_image_width')) ? $this->config->get('ncategory_bnews_image_width') : 80;
		$bbheight = ($this->config->get('ncategory_bnews_image_height')) ? $this->config->get('ncategory_bnews_image_height') : 80;

		// $sdata = array(
			// 'start'           => 0,
			// 'limit'           => 3,
		// );

		// $results = $this->model_catalog_news->getNews($sdata);

		$news = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'news_list' );

		foreach ($news as $n) {
			$result = $this->model_catalog_news->getNewsStory($n['news']);

			$name = $result['title'];

			// $da = date($date_format, strtotime($result['date_added']));
			$da = date('d/m/Y', strtotime($result['date_added']));
			// debug($result['image2']);
			if ($result['image'] || $result['image2']) {
				if ($result['image']) {
					$image = 'image/'.$result['image'];
				} else {
					//$image = $this->model_tool_image->resize($result['image'], $bbwidth, $bbheight);
					$image ='image/'.$result['image'];
				}
			} else {
				$image = false;
			}

			if ($result['description'] || $result['description2']) {
				//	$desc_limit = $this->config->get('ncategory_bnews_desc_length') ? $this->config->get('ncategory_bnews_desc_length') : 600;
				$desc_limit = 150;

				if($result['description2'] && (strlen(html_entity_decode($result['description2'], ENT_QUOTES, 'UTF-8')) > 20)) {
					$desc = utf8_substr(strip_tags(html_entity_decode($result['description2'], ENT_QUOTES, 'UTF-8')), 0, $desc_limit) . '..';
				} else {
					$desc = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $desc_limit) . '..';
				}
			} else {
				$desc = '';
			}

			$category = "";
			$cats = $this->model_catalog_news->getNcategoriesbyNewsId($result['news_id']);
			if ($cats) {
				$comma = 0;
				foreach($cats as $catid) {
					$catinfo = $this->model_catalog_ncategory->getncategory($catid['ncategory_id']);
					if ($catinfo) {

						$bkground_class = '';

						if ($comma) {
							$category .= ', <a class="block w100 h100 pd-5 pd-l15 pd-r15 '.$bkground_class.'" href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
						} else {
							$category .= '<a class="block w100 h100 pd-5 pd-l15 pd-r15 '.$bkground_class.'" href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
						}
						$comma++;
					}
				}
			}

			$href  = $this->url->link('news/article','news_id=' . $result['news_id']);

			$ntags = array();
			if($result['ntags']) {
				$tags = explode(',', $result['ntags']);
				foreach($tags as $each) {
					$tag_href = $this->url->link('news/ncategory', 'tags='.trim($each));

					$ntags[] = array(
						'name' => trim($each),
						'href' => $tag_href,
					);
				}
			}

			$data['article'][] = array(
				'article_id'  => $result['news_id'],
				'name'        => $name,
				'thumb'       => $image,
				'date_added'  => $da,
				'description' => $desc,
				'category'    => $category,
				'href'        => $href,
				'tags' => $ntags,
			);
		}

		// debug($data['article']);

		$this->document->addStyle('catalog/view/javascript/slick/slick.css');
		$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');

		return $this->load->view('extension/module/latest_news_4_mh', $data);
	}
}
