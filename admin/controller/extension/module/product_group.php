<?php
class ControllerExtensionModuleProductGroup extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules

        $language_id = $this->config->get('config_language_id');
        
        $modulename  = 'product_group';
        $data = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'group');
        $groups = array();
        if(gettype($data) == "array"){
            if(count($data) > 0){
                foreach($data as $group){
                    $groups[] = array("label" => $group['group_name'], "value" => $group['group_name']);
                }
            }
        }else{
            $groups[] = array();
        }
        
        
        $this->load->model('catalog/product');
        $results = $this->model_catalog_product->getProducts();
        $product = array();
        $i = 1;
        $product[0] = array('value' => 0, 'label' => "All Products");
        foreach($results as $result){
            $product[$i] = array('value' => $result['product_id'], 'label' => str_replace("'","",$result['name']));
            $i++;
        }
        

        $array = array(
            'oc' => $this,
            'heading_title' => 'Product Group',
            'modulename' => 'product_group',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Product Group', 'name' => 'group',
                    'fields' => array(
                        array('type' => 'text', 'label' => 'Group Name', 'name' => 'group_name')
                    )
                ),
                array('type' => 'repeater', 'label' => 'Product Group', 'name' => 'set_group',
                    'fields' => array(
                        array('type' => 'dropdown', 'label' => 'Group', 'name' => 'group', 'choices' => $groups),
                        array('type' => 'dropdown', 'label' => 'Product', 'name' => 'type', 'choices' => $product)
                    )
                ),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
