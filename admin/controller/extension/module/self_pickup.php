<?php
class ControllerExtensionModuleSelfPickup extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules

        $language_id = $this->config->get('config_language_id');
        $modulename  = 'self_pickup';
        $data = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'default_slot');
        $timeslot = array();
        if(count($data) > 0){
            foreach($data as $time){
                $timeslot[] = array("label" => $time['delivery_times'],"value" => $time['delivery_times']);
            }
        }
        
        $type[0] = array('value' => "1", 'label' => "Add Slot");
        $type[1] = array('value' => "2", 'label' => "Add Delivery Time");
        $type[2] = array('value' => "0", 'label' => "Block Date");
        $type[3] = array('value' => "4", 'label' => "Block Delivery Time");
        $type[4] = array('value' => "3", 'label' => "Charge");
        
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

        $array = array(
            'oc' => $this,
            'heading_title' => 'Self Pickup Setting',
            'modulename' => 'self_pickup',
            'fields' => array(
                array('type' => 'text', 'label' => 'Sunday cut off time', 'name' => 'sunday'),
                array('type' => 'text', 'label' => 'Monday cut off time', 'name' => 'monday'),
                array('type' => 'text', 'label' => 'Tuesday cut off time', 'name' => 'tuesday'),
                array('type' => 'text', 'label' => 'Wednesday cut off time', 'name' => 'wednesday'),
                array('type' => 'text', 'label' => 'Thurday cut off time', 'name' => 'thursday'),
                array('type' => 'text', 'label' => 'Friday cut off time', 'name' => 'friday'),
                array('type' => 'text', 'label' => 'Saturday cut off time', 'name' => 'saturday'),
                array('type' => 'repeater', 'label' => 'Default Setting', 'name' => 'default_slot',
                    'fields' => array(
                        array('type' => 'text', 'label' => 'Delivery Times', 'name' => 'delivery_times'),
                        array('type' => 'text', 'label' => 'Surcharge', 'name' => 'surcharge'),
                        array('type' => 'text', 'label' => 'Block Day', 'name' => 'block_day'),
                        array('type' => 'text', 'label' => 'Slot', 'name' => 'slot'),
                    )
                ),
                array('type' => 'repeater', 'label' => 'Special Setting', 'name' => 'special_setting',
                    'fields' => array(
                        array('type' => 'dropdown', 'label' => 'Product Group', 'name' => 'product_group', 'choices' => $groups),
                        array('type' => 'date', 'label' => 'Delivery Date', 'name' => 'delivery_date'),
                        array('type' => 'dropdown', 'label' => 'Type', 'name' => 'type', 'choices' => $type),
                        array('type' => 'dropdown', 'label' => 'Delivery Time', 'name' => 'delivery_time', 'choices' => $timeslot),
                        array('type' => 'text', 'label' => 'Add Time', 'name' => 'add_time'),
                        array('type' => 'text', 'label' => 'Surcharge', 'name' => 'surcharge2'),
                        array('type' => 'text', 'label' => 'Slot', 'name' => 'slot')
                    )
                ),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
