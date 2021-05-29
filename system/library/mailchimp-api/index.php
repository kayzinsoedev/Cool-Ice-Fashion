<?php

include('MailChimp.php');
include('Batch.php'); 
include('Webhook.php'); 

use \DrewM\MailChimp\MailChimp;

function initilizeMailchimp($key) {
    $MailChimp = new MailChimp($key);
    return $MailChimp;
}

function getLists($MailChimp) {
    $result = $MailChimp->get('lists');
    return($result);
}

function createList($MailChimp, $data) {
    $result = $MailChimp->post('lists', $data);
    return($result);
}

function addStore($MailChimp, $data) {
    $result = $MailChimp->post('ecommerce/stores', $data);
    return($result);
}
function addProduct($MailChimp, $data, $store_id) { echo $store_id;die;
    $result = $MailChimp->post('ecommerce/stores/'.$store_id.'/products', $data);
    return($result);
}
function addCustomer($MailChimp, $data, $store_id) {
    $result = $MailChimp->post('ecommerce/stores/'.$store_id.'/customers', $data);
    return($result);
}

function addSubscriber($MailChimp, $data, $list_id) {
    $result = $MailChimp->post('lists/'.$list_id.'/members', $data);
    return($result);
}
