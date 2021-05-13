<?php
class ModelExtensionPaymentOmisePaynow extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/omise_paynow');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('omise_paynow_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('paynow_total') > 0 && $this->config->get('paynow_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('omise_paynow_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

        $language_id = $this->config->get('config_language_id');

        if ($this->config->get('omise_paynow_payment_title' . $language_id) != "")
            $payment_title = $this->config->get('omise_paynow_payment_title' . $language_id);
        else
            $payment_title = $this->language->get('text_title');

		if ($status) {
			$method_data = array(
				'code'       => 'omise_paynow',
				'title'      => $payment_title,
				'terms'      => '',
				'sort_order' => $this->config->get('omise_paynow_sort_order')
			);
		}

		return $method_data;
	}
}