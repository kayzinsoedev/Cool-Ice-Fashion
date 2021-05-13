<?php
class ModelAccountOmise extends Model {
    
    public function getCustomer($customer_id) {
        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "omise_customer` WHERE customer_id = '" . (int)$customer_id. "'");
    }
}