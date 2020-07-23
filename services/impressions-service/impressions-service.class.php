<?php

namespace KDWC\Services\ImpressionsService;

class ImpressionsService {
	private static $instance;

	private function __construct() {
		$web_service_domain = 'http://www.kd-wc.com/api/';
		$web_service_test_domain = 'http://kdwc2.bbold.co.il/api/';
			
		$this->web_service_url = $web_service_domain.IFSO_API_VERSION.'/impressions-service/impressions-api.php';
		$this->impressions_update_interval = 60 * 60 * 4; // 4 hours (in seconds)
		$this->transient_name = 'kdwc_transient_impressions_update';
	}

	public static function get_instance() {
		if ( NULL == self::$instance )
			self::$instance = new ImpressionsService();

		return self::$instance;
	}

	private function check_transient() {
		return get_transient( $this->transient_name ); 
	}

	private function set_new_transient() {
		set_transient( 
				$this->transient_name, 
				true,
		   		$this->impressions_update_interval );
	}

	private function update_impressions_to_kdwc($license, $impressions) {
		$response = wp_remote_post( $this->web_service_url, 
			array(	'method' => 'POST',
				  	'timeout' => 15,
				  	'body' => 
						array('license' => $license,
							  'impressions' => $impressions)
			));

		if( is_array($response) ) {
			$data = json_decode( $response['body'], true );

			return $data;
		} else {
			return json_encode(array('error' => true));
		}
	}

	private function get_kdwc_data() {
		$kdwcData = get_option('kdwc');

		if ( !$kdwcData ) {
			// create new one
			$kdwcData = array(
					'impressions' => 0
				);

			update_option('kdwc', $kdwcData);
		} else if ( !isset($kdwcData['impressions']) &&
			  		 isset($kdwcData['monthly_sesssions_count']) ) {
			// handle deprecated key
			$kdwcData['impressions'] = $kdwcData['monthly_sesssions_count'];

			update_option('kdwc', $kdwcData);
		}

		return $kdwcData;
	}

	private function update_impressions($license) {
		$kdwcData = $this->get_kdwc_data();
		$impressions = $kdwcData['impressions'];
		$this->update_impressions_to_kdwc($license, $impressions);
	}

	public function increment() {
		$kdwcData = $this->get_kdwc_data();
		$kdwcData['impressions'] += 1;
		update_option('kdwc', $kdwcData);
	}

	public function handle($license) {
		if ( !$this->check_transient() ) {
			$this->set_new_transient();
			$this->update_impressions($license);
		}
	}
}