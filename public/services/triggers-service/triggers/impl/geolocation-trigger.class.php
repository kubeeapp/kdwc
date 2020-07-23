<?php
namespace KDWC\PublicFace\Services\TriggersService\Triggers;
require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once( KDWC_PLUGIN_SERVICES_BASE_DIR . 'timezones-service/timezones-service.class.php' );
require_once(KDWC_PLUGIN_BASE_DIR . 'services/license-service/license-service.class.php');
require_once(KDWC_PLUGIN_BASE_DIR . 'services/geolocation-service/geolocation-service.class.php');
use KDWC\PublicFace\Services\TimezonesService;
use KDWC\Services\LicenseService;
use KDWC\Services\GeolocationService;
class GeolocationTrigger extends TriggerBase {
	public function __construct() {
		parent::__construct('Geolocation');
	}
	protected function is_valid($trigger_data) {
		$rule = $trigger_data->get_rule();
		if ( !isset($rule['geolocation_data']) )
			return false;
		else if ( empty ( $rule['geolocation_data'] ) )
			return false;
		$user_geolocation = $this->get_user_geolocation($trigger_data);

        if($user_geolocation != NULL && empty($user_geolocation['stateProv']))   //Sometimes the geo API response doesn't include this field(For example for city states)
            $user_geolocation['stateProv'] = '';

		return ( $user_geolocation != NULL &&
				 isset($user_geolocation['success']) &&
				 isset($user_geolocation['countryCode']) &&
			 	 isset($user_geolocation['city']) &&
	 		 	 isset($user_geolocation['stateProv']) &&
	 		 	 $user_geolocation['success'] &&
	 		 	 $user_geolocation['countryCode'] &&
	 		 	 $user_geolocation['city'] );
	}
	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();
		
		$user_geolocation = $this->get_user_geolocation($trigger_data);
		$countryCode = $user_geolocation['countryCode'];
		$stateProv = $user_geolocation['stateProv'];
		$continentCode = $user_geolocation['continentCode'];
		$city = $this->convert_smart_quotes(trim($user_geolocation['city']));
		$city = str_replace("‘", "'", $city); // Replace that weird character
		$geolocation_data = utf8_decode($rule['geolocation_data']);
		$splitted_geolocation_data = explode("^^", $geolocation_data);

        $is_not_geo = (isset($rule['geolocation_behaviour']) && $rule['geolocation_behaviour'] === 'is-not') ? true : false;    //is it an IS NOT condition?
        $geo_matched = false;

        foreach ($splitted_geolocation_data as $key => $value) {
            $explodedData = explode("!!", $value);
            $symbolType = strtolower($explodedData[0]);
            if ($symbolType == "country") {
            	// COUNTRY HANDLING
            	$selectedCountryCode = $explodedData[2];
            	if (!$selectedCountryCode)
            		continue;
                if ($this->are_they_equal_or_contains($countryCode, $selectedCountryCode)) {
                	$geo_matched = true;
                    if(!$is_not_geo)
                        return $content;
                }
            } else if ($symbolType == 'city') {
            	// CITY HANDLING
            	
            	$selectedCity = $explodedData[2];
                $cleanedSelectedCity = $this->convert_smart_quotes(trim(str_replace('\\', '', $selectedCity)));
				$cleanedSelectedCity = str_replace("‘", "'", $selectedCity);
				if (!$cleanedSelectedCity)
					continue;
				if ($this->are_they_equal_or_contains($city, $cleanedSelectedCity)) {
				    $geo_matched = true;
                    if(!$is_not_geo)
                	    return $content;
				} else if ($this->are_they_equal_or_contains($stateProv, $cleanedSelectedCity)) {
				    $geo_matched = true;
                    if(!$is_not_geo)
                	    return $content;
				}
            } else if ($symbolType == 'continent') {
            	// CONTINENT HANDLING
            	$selectedContinentCode = $explodedData[2];
            	if (!$selectedContinentCode)
            		continue;
                if ($this->are_they_equal_or_contains($continentCode, $selectedContinentCode)) {
                    $geo_matched = true;
                    if(!$is_not_geo)
                	    return $content;
                }
            } else if ($symbolType == 'state') {
            	// STATE HANDLING
            	$selectedStateName = $explodedData[1];
            	if (!$selectedStateName)
            		continue;
                if ($this->are_they_equal_or_contains($stateProv, $selectedStateName)) {
                    $geo_matched = true;
                    if(!$is_not_geo)
                	    return $content;
                }
            } else if ($symbolType == 'timezone') {
            	// TIMEZONE HANDLING
            	if ( !isset($user_timezone) ) {
            		$user_timezone = "";
            		if (isset($user_geolocation['timeZone'])) {
						$user_timezone = $user_geolocation['timeZone'];
						$user_timezone = str_replace("\/", "/", $user_timezone);
					}
            	}
            	$selectedTimezone = $explodedData[1];
            	if (!$selectedTimezone)
            		continue;
                if ($this->is_user_timezone_in_selected_timezone($user_timezone, $selectedTimezone)) {
                	// We got a match!
                    $geo_matched = true;
                    if(!$is_not_geo)
                	    return $content;
                }
            }
        }

        if($is_not_geo && !$geo_matched)
            return $content;

        return false;
	}
	private function get_user_geolocation($trigger_data) {
		$user_geolocation = $trigger_data->get_general_data('user_geolocation');
		if ( !$user_geolocation ) {
			$user_ip = $this->get_user_ip();
			$license = LicenseService\LicenseService::get_instance()->get_license();
			$user_geolocation = GeolocationService\GeolocationService::get_instance()->get_location_by_ip($license, $user_ip);
			$trigger_data->set_general_data('user_geolocation', $user_geolocation);
		}
		return $user_geolocation;		
	}
	private function get_user_ip() {
		$ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
		return $ip;
	}
	private function is_user_timezone_in_selected_timezone($user_timezone, $timezone_name) {
		global $timezones;
		$utcs = TimezonesService\TimezonesService::get_instance()->get_timezone_utcs($timezone_name);
		if ( NULL == $utcs )
			return false;
		foreach ($utcs as $timezone) {
			if ($this->are_they_equal_or_contains($user_timezone, $timezone))
				return true;
		}
		return false;
	}
	private function are_they_equal_or_contains($a, $b) {
		return (strpos($a, $b) !== false ||
				strpos($b, $a) !== false ||
			    $a == $b);
	}
	// convert microsoft word kind of apostrophe -encoded
	private function convert_smart_quotes($string) 
	{ 
	    $search = array(chr(8216),
	    				chr(145), 
	                    chr(146), 
	                    chr(147), 
	                    chr(148), 
	                    chr(151)); 
	    $replace = array("'",
	    				 "'", 
	                     "'", 
	                     '"', 
	                     '"', 
	                     '-'); 
	    return str_replace($search, $replace, $string); 
	} 
}