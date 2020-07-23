<?php

require_once( __DIR__. '/services/triggers-service/triggers-service.class.php' );
require_once( __DIR__. '/services/page-visits-service/page-visits-service.class.php' );
require_once( __DIR__. '/services/analytics-service/analytics-service.class.php' );
require_once( __DIR__. '/services/ajax-triggers-service/ajax-triggers-service.class.php' );
require_once(KDWC_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

use KDWC\PublicFace\Services\TriggersService;
use KDWC\PublicFace\Services\PageVisitsService;
use KDWC\PublicFace\Services\AjaxTriggersService;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://kd-wc.com
 * @since      1.0.0
 * @package    KDWC
 * @subpackage KDWC/public
 * @author     Matan Green
 * @author     Nick Martianov
 */
class Kd_Wc_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/*
	 *	Create shortcode
	 */
	public function add_kd_wc_shortcode( $atts ) {
	    $render_via_ajax_option_value = \KDWC\Services\PluginSettingsService\PluginSettingsService::get_instance()->renderTriggersViaAjax->get();
	    $load_later_param = isset($atts['ajax']) ? $atts['ajax'] : '';
	    if(($render_via_ajax_option_value || $load_later_param === 'yes') && $load_later_param !== 'no')
	        return AjaxTriggersService\AjaxTriggersService::get_instance()->handle($atts);
        else
		    return TriggersService\TriggersService::get_instance()->handle($atts);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kd-wc-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        /**
         * This method is hooked into wordpress in the main kd-wc class(class-kd-wc.php) via kd-wc loader
         * Enqueues public js files as well as providing the required global JS variables
         */

		$ajax_nonce = wp_create_nonce( "kdwc-nonce" );
		echo "<script>var nonce = '".$ajax_nonce."';</script>";
		$ajax_url = admin_url('admin-ajax.php');
		echo "<script>var ajaxurl = '".$ajax_url."';</script>";
		$page_url = $this->get_current_page_url();
		echo "<script>var kdwc_page_url = '".$page_url."';</script>";
        $isAnalyticsOn = (KDWC\PublicFace\Services\AnalyticsService\AnalyticsService::get_instance()->isOn) ? 'true' : 'false';
        echo "<script> var isAnalyticsOn = {$isAnalyticsOn};</script>";
        $isPagesVisitedOn = (\KDWC\Services\PluginSettingsService\PluginSettingsService::get_instance()->removePageVisitsCookie->get()) ? 'false' : 'true';
        echo "<script> var isPageVisitedOn = {$isPagesVisitedOn};</script>";
        $referrerAtPageload = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        echo "<script> var referrer_for_pageload = '{$referrerAtPageload}';</script>";

        //wp_deregister_script( 'jquery');

        //wp_enqueue_script( 'jquery', plugin_dir_url( __FILE__ ) . 'js/jquery-3.4.1.min.js', array( ),'3.4.1' , false );     //Enqueue a newer version of jquery

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kd-wc-public.js', array( 'jquery' ), $this->version, false );

	}

	private function get_current_page_url() {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $url = "https://";
        else
            $url = "http://";

        $url.= $_SERVER['HTTP_HOST'];
        $url.= $_SERVER['REQUEST_URI'];

        return $url;
    }

	public function wp_ajax_kdwc_add_page_visit_handler() {
		check_ajax_referer( 'kdwc-nonce', 'nonce' );

		$page_url = $_POST['page_url'];
		PageVisitsService\PageVisitsService::get_instance()->save_page($page_url);

		wp_die(); // indicate end of stream
	}

	public function start_sesh(){
	    if(!is_admin() && !isset($_SESSION)){    //Prevent using session_start on admin pages to fix theme/plugin editor
            session_start();
        }
    }

    public function set_kdwc_group_cookie(){
        if(isset($_REQUEST['kdwcGroup']) && !empty($_REQUEST['kdwcGroup'])){
            $grp = $_REQUEST['kdwcGroup'];
            setcookie('kdwcGroup',$grp,time()+60*60*24*365*3,'/');  //Set a cookie to identify a member of a group(3 years)
            $_COOKIE['kdwcGroup'] = $grp;
        }
    }


}
