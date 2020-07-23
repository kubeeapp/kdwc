<?php
/*
Plugin Name: KDWC Extended Shortcodes
Description: Shortcodes for kd-wc.com
Version: 1.0
Author: If So Plugin
*/
namespace KDWC\Extensions\IFSOExtendedShortcodes\ExtendedShortcodes;

require_once(IFSO_PLUGIN_BASE_DIR . 'services/geolocation-service/geolocation-service.class.php');
require_once (IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'public/services/analytics-service/analytics-service.class.php');

require_once(__DIR__.'/models/user-languages/index.php');

use KDWC\PublicFace\Services\AnalyticsService\AnalyticsService;
use KDWC\Services\GeolocationService;

class ExtendedShortcodes {

	public static $instance;

    private function __construct(){}

	public static function get_instance() {
		if ( NULL == self::$instance )
		self::$instance = new ExtendedShortcodes();
		return self::$instance;
	}

    public function add_extended_shortcodes(){
        $this->doDKIShortcode();
        $this->do_geo_shortcode();
        $this->do_language_shortcode();
        $this->do_referrer_shortcode();
        $this->do_analytics_conversion_shortcode();
        $this->do_user_details_shortcode();
        $this->do_login_link_shortcode();

        do_action('kdwc_extra_extended_shortcodes');
    }

    public function doDKIShortcode(){
        //Super shortcode IfsoDKI combines the functionality of the other extended shortcodes- others still here for compatability.DRY! Refactor?
        add_shortcode('kdwcDKI',function($atts){

            if(!isset($atts['type']))
                return false;
            $type = $atts['type'];
            $show = (isset($atts['show'])) ? $atts['show'] : '';
            $fallback = (isset($atts['fallback'])) ? $atts['fallback'] : 'Unknown';

            if($type=='geo'){
                // Get client's IP
                if (!empty($_SERVER['HTTP_CLIENT_IP']))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else
                    $ip = $_SERVER['REMOTE_ADDR'];

                $geo_data = GeolocationService\GeolocationService::get_instance()->get_location_by_ip("kdwc-lic", $ip);

                if ( !$geo_data )
                    return "Unknown";
                else {
                    switch ( $show ) {

                        case 'country':
                            if ( isset( $geo_data['countryName'] ) ) {
                                return $geo_data['countryName'];
                            }
                            break;

                        case 'state':
                            if ( isset( $geo_data['stateProv'] ) ) {
                                return $geo_data['stateProv'];
                            }
                            else return 'Your state';
                            break;

                        case 'city':
                            if ( isset( $geo_data['city'] ) ) {
                                return $geo_data['city'];
                            }
                            break;
                        case 'continent':
                            if ( isset( $geo_data['continentName'] ) ) {
                                return $geo_data['continentName'];
                            }
                            break;
                        case 'timezone':
                            if ( isset( $geo_data['timeZone'] ) ) {
                                $tzArr = explode('/',$geo_data['timeZone']);
                                if(is_array($tzArr) && count($tzArr)>1) return $tzArr[1];
                                return $geo_data['timeZone'];
                            }
                            break;
                    }
                }
                return $fallback;
            }
            elseif($type=='language') {
                $user_languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                $languages = [];

                preg_match_all("/[a-zA-Z-]{2,10}/",
                    $user_languages,
                    $languages);


                if ($languages && is_array($languages[0]))
                    $languages = $languages[0];
                else
                    return "";

                switch ($show) {
                    case 'primary-only':
                        return get_language_name($languages[0]);
                        break;
                    case 'all':
                        return build_user_languages_visual($languages);
                        break;

                    case 'all-except-primary':
                        array_shift($languages);
                        return build_user_languages_clean_visual($languages);
                        break;

                    case 'count':
                        return count($languages);
                        break;

                    case 'count-without-primary':
                        array_shift($languages);
                        return count($languages);
                        break;
                }

                return '';
            }
            elseif($type=='referrer'){
                $referrer = trim($_SERVER['HTTP_REFERER'], '/');
                $clean_referer = trim($_SERVER['HTTP_REFERER'], '/');
                $referrer = parse_url($referrer, PHP_URL_HOST);
                $referrer = str_replace('https://', '', $referrer);
                $referrer = str_replace('http://', '', $referrer);
                $referrer = str_replace('www.', '', $referrer);

                if ( !empty( $referrer ) ) {
                    switch ( $show ) {
                        case 'domain-only':
                            return $referrer;
                            break;
                    }

                    return $clean_referer;
                }
                else
                    return "No referrer / hidden";
            }
            elseif($type=='viewcount'){
                $pid = (isset($atts['id'])) ? $atts['id'] : false;
                if($pid){
                    $analytics_service = AnalyticsService::get_instance();
                    $fields = $analytics_service->get_analytics_fields($pid);
                    $ret = 0;
                    if($fields){
                        foreach($fields as $version){
                            $ret += (int) $version['views'];
                        }
                        return $ret;
                    }
                }
            }
            elseif($type==='querystring'){
                if(!empty($atts['parameter'])){
                    $param = $atts['parameter'];
                    if(isset($_GET[$param])){
                        return filter_var($_GET[$param],FILTER_SANITIZE_SPECIAL_CHARS);  //avoid XSS
                    }
                }
            }
            elseif($type=='dayOfWeek'){
                return date('l');
            }
            return false;
        });
    }


	public function do_geo_shortcode() {
		add_shortcode('kdwc_display_user_geo', function($atts) {
		if ( !isset( $atts['type'] ) )
			$atts['type'] = 'country';

		$type = $atts['type'];

		// Get client's IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];

		$geo_data = GeolocationService\GeolocationService::get_instance()->get_location_by_ip("kdwc-lic", $ip);

		if ( !$geo_data )
			return "Unknown";
		else {
			switch ( $type ) {

				case 'country':
					if ( isset( $geo_data['countryName'] ) ) {
						return $geo_data['countryName'];
					}
					break;
					
				case 'state':
					if ( isset( $geo_data['stateProv'] ) ) {
						return $geo_data['stateProv'];
					}
					break;

				case 'city':
					if ( isset( $geo_data['city'] ) ) {
						return $geo_data['city'];
					}				
					break;
				case 'continent':
					if ( isset( $geo_data['continentName'] ) ) {
						return $geo_data['continentName'];
					}				
					break;
				case 'timezone':
					if ( isset( $geo_data['timeZone'] ) ) {
                        $tzArr = explode('/',$geo_data['timeZone']);
                        if(is_array($tzArr) && count($tzArr)>1) return $tzArr[1];
						return $geo_data['timeZone'];
					}				
					break;
			}
		}
		return "Unknown";
	});
}

    public function do_language_shortcode(){
        add_shortcode('kdwc_display_user_languages', function($atts) {

            if ( !isset( $atts['type'] ) )
                $atts['type'] = 'all';

            $type = $atts['type'];

            $user_languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $languages = [];

            preg_match_all("/[a-zA-Z-]{2,10}/",
                $user_languages,
                $languages);


            if ( $languages && is_array( $languages[0] ) )
                $languages = $languages[0];
            else
                return "";

            switch ( $type ) {
                case 'only-primary':
                    return get_language_name($languages[0]);
                    break;
                case 'all':
                    return build_user_languages_visual($languages);
                    break;

                case 'all-except-primary':
                    array_shift($languages);
                    return build_user_languages_clean_visual($languages);
                    break;

                case 'count':
                    return count($languages);
                    break;

                case 'count-without-primary':
                    array_shift($languages);
                    return count($languages);
                    break;
            }

            return '';
        });
    }

    public function do_referrer_shortcode(){
        add_shortcode('kdwc_display_referrer', function($atts) {

            if ( !isset( $atts['type'] ) )
                $atts['type'] = 'default';

            $type = $atts['type'];

            $referrer = trim($_SERVER['HTTP_REFERER'], '/');
            $clean_referer = trim($_SERVER['HTTP_REFERER'], '/');
            $referrer = parse_url($referrer, PHP_URL_HOST);
            $referrer = str_replace('https://', '', $referrer);
            $referrer = str_replace('http://', '', $referrer);
            $referrer = str_replace('www.', '', $referrer);

            if ( !empty( $referrer ) ) {
                switch ( $type ) {
                    case 'domain-only':
                        return $referrer;
                        break;
                }

                return $clean_referer;
            }
            else
                return "No referrer / hidden";
        });
    }


    public function do_analytics_conversion_shortcode(){
        if(!is_admin()){
            add_shortcode('kdwc_conversion', function($atts) {
                $analytics_service = AnalyticsService::get_instance();
                $allowed_triggers = (isset($atts['triggers']) && strtolower($atts['triggers'])!='all') ? explode(',',$atts['triggers'])  : false;
                $disallowed_triggers = (isset($atts['exclude'])) ? explode(',',$atts['exclude'])  : [];

                if($analytics_service->isOn && $analytics_service->allow_counting){
                    if($analytics_service->useAjax){

                        $el = '<div class="kdwc-conversion-complete" '. ($allowed_triggers ? 'allowed_triggers="' . implode(',',$allowed_triggers) . '"' : '')  . ($disallowed_triggers ? 'disallowed_triggers="' . implode(',',$disallowed_triggers) . '"' : '') . ' style="display:none;height:0;"></div>';  //public javascript file catches uses this div as trigger for conversion to fire
                        return $el;
                    }

                    else{
                        static $already_had_conversion=[];
                        if(isset($_COOKIE[$analytics_service->last_viewed_version_cookie_name]) && !empty($_COOKIE[$analytics_service->last_viewed_version_cookie_name])){
                            $viewed_arr = json_decode(stripslashes($_COOKIE[$analytics_service->last_viewed_version_cookie_name]),true);
                            foreach($viewed_arr as $pid=>$vid){
                                if(!in_array($pid,$disallowed_triggers) && !isset($already_had_conversion[$pid]) && (!$allowed_triggers || is_array($allowed_triggers) && in_array($pid,$allowed_triggers))){
                                    if($vid!=='default')
                                        $analytics_service->increment_analytics_field($pid,$vid,'conversion');
                                    else
                                        $analytics_service->increment_default_analytics_field($pid,'conversion');
                                    $already_had_conversion[$pid] = $pid;
                                }
                            }
                        }
                    }
                }

            });
        }
    }

    public function do_user_details_shortcode(){
        add_shortcode('kdwc_user_details',function($atts){
            $user = wp_get_current_user();
            if(isset($user->ID) && 0!== $user->ID){     //If user is logged in
                $user_meta = get_user_meta($user->ID);
                $user_data = [
                    'first_name' =>$user_meta['first_name'][0],
                    'last_name' =>$user_meta['last_name'][0],
                    'nickname' =>$user_meta['nickname'][0],
                    'email' =>$user->data->user_email,
                ];
                if(isset($atts['show'])){
                    switch ($atts['show']){
                        case 'firstName':
                            return $user_data['first_name'];
                        case 'lastName':
                            return $user_data['last_name'];
                        case 'fullName':
                            return trim("{$user_data['first_name']} {$user_data['last_name']}");
                        case 'email':
                            return $user_data['email'];
                        case 'username':
                            return $user_data['nickname'];
                    }
                }
                return $user_data['nickname'];
            }
            elseif(!empty($atts['default'])){
                return $atts['default'];
            }
        });
    }

    public function do_login_link_shortcode(){
        add_shortcode('kdwc_login_link',function($atts){
            $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $redirect_to = !empty($atts['login_redirect']) ? $atts['login_redirect'] : $current_url;

            if(!is_user_logged_in()){
                $link_text = !empty($atts['login_text']) ? $atts['login_text'] : 'Log In';
                $link_url = esc_url(wp_login_url($redirect_to));
            }
            else{
                $link_text = !empty($atts['logout_text']) ? $atts['logout_text'] : 'Log Out';
                $link_url = esc_url(wp_logout_url($redirect_to));
            }

            $html = "<a class='kdwc_loginout_link' href='{$link_url}'>{$link_text}</a>";
            return $html;

        });
    }


    public function modify_kdwc_shorcode_add_edit($data){
        if($data['post_type']!='kdwc_triggers'){
            $pattern = '/\[kdwc (id\=)(([\"\']{0,1})(\d+)([\"\']{0,1}))( .+){0,1}\]/';
            $old_content = stripslashes($data['post_content']);
            $data['post_content'] = preg_replace($pattern,'[kdwc ${1}"${4}" <a target="_blank" href="?post=${4}&action=edit">edit</a>]',$old_content);
        }
        return $data;
    }

}

?>