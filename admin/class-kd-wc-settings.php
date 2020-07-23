<?php

/**
 * The settings of the plugin.
 *
 * @link       http://kd-wc.com
 * @since      1.0.0
 * @package    KDWC
 * @subpackage KDWC/admin
 * @author     Matan Green
 * @author     Nick Martianov
 */

require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

class Kd_Wc_Admin_Settings {

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
	
	public $triggers_obj;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings_service = \KDWC\Services\PluginSettingsService\PluginSettingsService::get_instance();
	}
	
	public function plugin_settings_page(){
		return false; // main display will be overriden by a custom post type
	}
	
	public function kdwc_trigger_settings_metabox( $post ){
		require_once('partials/kdwc_trigger_settings_metabox.php');
		return false;
	}
	
	public function kdwc_shortcode_display_metabox( $post ){
		require_once('partials/kdwc_shortcode_display_metabox.php');
		return false;
	}

	public function kdwc_helper_metabox( $post ) {
		require_once('partials/kdwc_helper_metabox.php');
		return false;
	}

	public function kdwc_statistics_metabox( $post ) {
		//OLD statistics metabox
		require_once('partials/kdwc_statistics_metabox.php');
		return false;
	}

	public function kdwc_analytics_display_metabox( $post ) {
		//NEW statistics(analytics) metabox
		require_once('partials/kdwc_analytics_display_metabox.php');
		return false;
	}

	public function display_admin_menu_geo_page( $post ){
		require_once('partials/kdwc_geo_page_display.php');
		return false;
	}

	public function display_admin_menu_license_page( $post ){
		require_once('partials/kdwc_license_page_display.php');
		return false;
	}

	public function display_admin_menu_settings_page( $post ){
		require_once('partials/kdwc_settings_page_display.php');
		return false;
	}

	public function display_admin_menu_groups_page($post){
		require_once('partials/kdwc_groups_page_display.php');
	}

	private function edd_kdwc_is_in_activations_process() {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
			return true;
		} else {
			return false;
		}
	}

	private function edd_kdwc_get_error_message() {
		if ( !$this->edd_kdwc_is_in_activations_process() )
			return false;

		switch( $_GET['sl_activation'] ) {

				case 'false':
					$message = stripslashes(urldecode( $_GET['message'] ));
                    $message = filter_var($message,FILTER_SANITIZE_FULL_SPECIAL_CHARS);     //REMOVE XSS
					if(isset($_GET['wrongLicenseGoto']) && !empty($_GET['wrongLicenseGoto']) && $_GET['wrongLicenseGoto']!='false'){
						$geo_page_link = (admin_url('admin.php?page=' . EDD_IFSO_PLUGIN_GEO_PAGE));
						$license_page_link = admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_LICENSE_PAGE);
						if ($_GET['wrongLicenseGoto']=='geo') $message = "You have entered a Geolocation License. To activate a Geolocation License please go to <a href='{$geo_page_link}'> Kd-Wc > Geolocation</a>.";
						if ($_GET['wrongLicenseGoto']=='pro') $message = "The license you entered is not a Geolocation license. Click <a href='https://www.kd-wc.com/plans/geolocation-plans/?utm_source=Plugin&utm_medium=message&utm_campaign=geolocation&utm_term=Prolicense&utm_content=a' target='_blank'>here</a> to purchase a Geolocation license or go to <a href='{$license_page_link}'> Kd-Wc > License</a> if you want to activate a Pro license.";
					}

					$match = '/\!\!\!LINKSTART\!\!\!(.+)\!\!\!LINKEND\!\!\!\!\!\!LINKTEXT\!\!\!(.+)\!\!\!LINKTEXTEND\!\!\!/';
					$replace = '<a href="${1}" target="_blank">${2}</a>';	//Look for a specifically encoded link and turn it into an <a> tag in the dashboard
					$message = preg_replace($match,$replace,$message);

					return $message;
					
					break;
				
				case 'true':
				default:
				
					break;

		}

		return true;
	}

	/**
	 * This is a means of catching errors from the activation method above and displaying it to the customer
	 */
	public function edd_kdwc_admin_notices() {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

			switch( $_GET['sl_activation'] ) {

				case 'false':
					$message = stripslashes(urldecode($_GET['message']));
                    $message = filter_var($message,FILTER_SANITIZE_FULL_SPECIAL_CHARS);     //REMOVE XSS
					if(isset($_GET['wrongLicenseGoto']) && !empty($_GET['wrongLicenseGoto']) && $_GET['wrongLicenseGoto']!='false'){
						$geo_page_link = admin_url('admin.php?page=' . EDD_IFSO_PLUGIN_GEO_PAGE);
						$license_page_link = admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_LICENSE_PAGE);
						if ($_GET['wrongLicenseGoto']=='geo') $message = "You have entered a Geolocation License. To activate a Geolocation License please go to <a href='{$geo_page_link}'> Kd-Wc > Geolocation</a>.";
						if ($_GET['wrongLicenseGoto']=='pro') $message = "The license you entered is not a Geolocation license. Click <a href='https://www.kd-wc.com/plans/geolocation-plans/?utm_source=Plugin&utm_medium=message&utm_campaign=geolocation&utm_term=Prolicense&utm_content=a' target='_blank'>here</a> to purchase a Geolocation license or go to <a href='{$license_page_link}'> Kd-Wc > License</a> if you want to activate a Pro license.";
					}
					$match = '/\!\!\!LINKSTART\!\!\!(.+)\!\!\!LINKEND\!\!\!\!\!\!LINKTEXT\!\!\!(.+)\!\!\!LINKTEXTEND\!\!\!/';
					$replace = '<a href="${1}" target="_blank">${2}</a>';	//Look for a specifically encoded link and turn it into an <a> tag in the dashboard
					$message = preg_replace($match,$replace,$message);
					?>
					<div class="error">
						<p><?php echo $message; ?></p>
					</div>
					<?php
					break;

				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;

			}
		}
	}

	/*
	 *	add plugin menu items
	 */
	public function add_plugin_menu_items() {
		
		add_menu_page(
			__( 'If So', 'kd-wc' ), // The title to be displayed on this menu's corresponding page
			__( 'Kd-Wc', 'kd-wc' ), // The text to be displayed for this actual menu item
			'publish_posts', // Which type of users can see this menu
			'kd-wc', // The unique ID - that is, the slug - for this menu item
			array($this, 'plugin_settings_page'), // The name of the function to call when rendering this menu's page
			plugin_dir_url( __FILE__ ) . 'images/logo-256x256.png', // icon url
			90 // position
		);

		if(current_user_can('publish_posts')){
			global $submenu;
			$permalink = admin_url( 'post-new.php' ).'?post_type=kdwc_triggers';


			$submenu['kd-wc'][] = array( __('Add New Trigger', 'kd-wc'), 'publish_posts', $permalink );
		}

		
		// $saveAsideAllTriggers = $submenu['kd-wc'][0];
		// $submenu['kd-wc'][0] = array( __('Add New Trigger', 'kd-wc'), 'manage_options', $permalink );
		// $submenu['kd-wc'][] = $saveAsideAllTriggers;

		add_submenu_page(
			'kd-wc',
			__('Audiences', 'kd-wc'),
			__('Audiences', 'kd-wc'),
			'manage_options',
			'wpcdd_admin_menu_groups_list',
			array($this, 'display_admin_menu_groups_page')
		);

		do_action('kdwc_extra_sumbenu_items');

		add_submenu_page(
			'kd-wc',
			__('Geolocation', 'kd-wc'),
			__('Geolocation', 'kd-wc'),
			'manage_options',
			'wpcdd_admin_geo_license',
			array( $this, 'display_admin_menu_geo_page' )
		);

		add_submenu_page(
			'kd-wc',
			__('Settings', 'kd-wc'),
			__('Settings', 'kd-wc'),
			'manage_options',
			'wpcdd_admin_menu_settings',
			array( $this, 'display_admin_menu_settings_page' )
		);

		add_submenu_page(
			'kd-wc',
			__('License', 'kd-wc'),
			__('License', 'kd-wc'),
			'manage_options',
			'wpcdd_admin_menu_license',
			array( $this, 'display_admin_menu_license_page' )
		);


		/*add_submenu_page(
			'kd-wc',
			'Add New',
			'Add New',
			'manage_options',
			'edit.php?post_type=kdwc_triggers',
			array($this, 'plugin_settings_page')
		);*/
		
		/*add_submenu_page(
			'kd-wc',
			'Instructions',
			'Instructions',
			'manage_options',
			'wpcdd_admin_menu_instruction',
			array( $this, 'display_admin_menu_instruction' )
		);*/
	}
	
	// Create custom column to display shortcode
	public function kdwc_add_custom_column_title( $prev_columns ){
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'title'    => __( 'Title', 'kd-wc' ),
			'trigger' => __( 'Triggers', 'kd-wc' ),
			'shortcode' => __( 'Shortcode', 'kd-wc' )
		);
		
		// add custom columns except yoast seo to the end of the table
		foreach($prev_columns as $col_index => $col_title) {
			if(strpos($col_index, 'wpseo') !== false) continue;
			if(array_key_exists($col_index, $columns)) continue;
			$columns[$col_index] = $col_title;
		}
		
		// set date column at the end of the table
		$columns['date'] = __( 'Date', 'kd-wc' );
		
		return $columns;
	}
	
	public function kdwc_add_custom_column_data( $column, $post_id ){ //MAYBE runs on every publish/update - I think. God given. - Muli
		switch( $column ){
			case 'trigger' :
				$data = array();
				$triggers = '';
				
				$data_json = get_post_meta( $post_id, 'kdwc_trigger_rules', true );
				if(!empty($data_json)) $data = json_decode($data_json, true);
				if(empty($data)) return false;
				$triggers_array = array();
				$query_strings_used = array();
				foreach($data as $rule) {
					if($rule['trigger_type'] == 'url' && !empty($rule['compare'])) $query_strings_used[] = "{$rule['compare']}";
					else
					{
						if ($rule['trigger_type'] == [])
						// incase no trigger got chosen
							$trigger_type = "Blank";
						else
							$trigger_type = $rule['trigger_type'];

						if (!in_array($trigger_type, $triggers_array))
							$triggers_array[] = $trigger_type;
					}
				}
				
				// add all query strings selected to the triggers array
				if(!empty($query_strings_used)) {
					$triggers_array[] = 'Custom URL (?kdwc='.implode(', ', $query_strings_used).')';
				}
				
				if(!empty($triggers_array)) $triggers = implode('<br/>', $triggers_array);
				echo $triggers;
				break;
			case 'shortcode' :
				$shortcode = sprintf( '[kdwc id="%1$d"]', $post_id);
				echo "<span class='shortcode'><input type='text' onfocus='this.select();' readonly='readonly' value='". $shortcode ."' class='large-text code'></span>";
				break;
		}
	}

	public function custom_triggers_template ($content) {
    	global $wp_query, $post;
    	return "HI";
	    /* Checks for single template by post type */
	    if ($post->post_type == "kdwc_triggers"){
	    	die(PLUGIN_PATH);
	        if(file_exists(PLUGIN_PATH . '/Custom_File.php'))
	            return PLUGIN_PATH . '/Custom_File.php';
	    }

		return $single;
	}

	public function include_kdwc_custom_triggers_template ($template_path) {
		if ( get_post_type() == 'kdwc_triggers' ) {
	        if ( is_single() ) {
	            // checks if the file exists in the theme first,
	            // otherwise serve the file from the plugin
	            if ( $theme_file = locate_template( array ( 'single-kdwc_triggers.php' ) ) ) {
	                $template_path = $theme_file;
	            } else {
	                $template_path = plugin_dir_path( __FILE__ ) . '/templates/single-kdwc_triggers.php';
	            }
	    	}
	    }

	    return $template_path;
	}
	
	public function kdwc_add_meta_boxes( $post ){
		global $wp_meta_boxes;

		if($post->post_type=='kdwc_triggers'){		//Remove pre-existing meta boxes from other plugins(maybe just delete the array?)
			$wp_meta_boxes[$post->post_type] = [
                'advanced' => [],
                'side' => [],
                'normal' => []
            ];

			add_meta_box('submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'kdwc_triggers', 'side', 'high' );  //re-add the "publish" metabox
		}

		add_filter( 'wpseo_metabox_prio', function() { return 'low'; } );
		
		add_meta_box(
			'kdwc_triggers_metabox', 
			__('Trigger settings', 'kd-wc'), 
			array($this, 'kdwc_trigger_settings_metabox'),
			'kdwc_triggers',
			'normal',
			'high'
		);

		add_meta_box(
			'kdwc_shortcode_display',
			__('Shortcode', 'kd-wc'),
			array( $this, 'kdwc_shortcode_display_metabox' ),
			'kdwc_triggers',
			'side',
			'default'
		);

		//NEW analytics meta box
		if(!$this->settings_service->disableAnalytics->get()){
			add_meta_box(
				'kdwc_analytics_metabox',
				__('Analytics', 'kd-wc') .'<a id="refreshTriggerAnalytics" style="margin-left:5px;" href="javascript:refreshAnalyticsDisplay();""><i class="fa fa-refresh" aria-hidden="true"></i></a>',
				array( $this, 'kdwc_analytics_display_metabox' ),
				'kdwc_triggers',
				'side',
				'default'
			);
		}

		add_meta_box(
			'kdwc_helper_metabox',
			__('Need Help?', 'kd-wc'),
			array( $this, 'kdwc_helper_metabox' ),
			'kdwc_triggers',
			'side',
			'low'
		);


		/*
		// in case that priority manipulation doesnt work
		function do_something_after_title() {
			$scr = get_current_screen();
			if ( ( $scr->base !== 'post' && $scr->base !== 'page' ) || $scr->action === 'add' )
				return;
			echo '<h2>After title only for post or page edit screen</h2>';
		}

		add_action( 'edit_form_after_title', 'do_something_after_title' );
		*/
	}
	
	public function move_yoast_metabox_down( $priority ){
		return 'low';
	}
	
	public function load_tinymce() {
		check_ajax_referer( 'my-nonce-string', 'nonce' );
		$editor_id = intval( $_POST['editor_id'] );
		
		wp_editor( '', 'repeatable_editor_content'.$editor_id, array(
			'wpautop'       => true,
			'textarea_name' => 'repeater['.$editor_id.'][repeatable_editor_content]',
			'textarea_class' => 'cloned-textarea',
			'textarea_rows' => 20,
		));
		wp_die();
	}


	// Helper method.
	// Loads given's $post_id default version metadata from DB.
	// TODO move it to dedicated service.
	private function load_default_version_metadata($post_id)
	{                           
		// first load default's metadata from the DB
		$data_default_metadata_json = 
		                get_post_meta( $post_id,
		                              'kdwc_trigger_default_metadata',
		                               true );
		// second we check if it exists
		if ( !empty($data_default_metadata_json) ) {
			$default_version_metadata = json_decode($data_default_metadata_json, true);
		} else {
			$default_version_metadata = array(
				'statistics_count' => 0
			);
		}

		return $default_version_metadata;
	}


	// Helper method.
	// save to the DB in $post_id's default version metadata
	// the given $default_version_metadata obj.
	// TODO move this function to dedicated service.
	private function save_default_version_metadata($post_id, 
												   $default_version_metadata)
	{
		// save the new default's metadata to the DB
		// by first serializing it to JSON format
		$default_version_metadata_json = 
				json_encode($default_version_metadata, JSON_UNESCAPED_UNICODE );

		// save default's metadata to DB using WP's update_post_meta func
		update_post_meta( $post_id, 
						  'kdwc_trigger_default_metadata', 
						   $default_version_metadata_json );
	}

	private function extract_autocomplete_selection_data($data) {
		if (!empty($data)) {
			$data = explode("^^", $data);
			$splitted_data = array();

			foreach ($data as $key => $value) {
				if (!empty($value) && $value != "1") {
					array_push($splitted_data, $value);
				}
			}

			$data = utf8_encode(implode('^^', $splitted_data));
			$data = str_replace('\\', '\\\\', $data);
		}

		return $data;
	}

	public function send_test_mail(){
		//Ajax function to send a test email to check whether those go to spam - maybe better to move this to another class
		global $wpdb;
		if(wp_doing_ajax()){
		    $table_name = $wpdb->prefix . 'kdwc_local_user';
		    $db_addr = $wpdb->get_var("SELECT user_email FROM {$table_name}");
			$addr = (!empty($db_addr)) ? $db_addr : get_option('admin_email');
            $domain = $_SERVER['SERVER_NAME'];
			$email = [
				'text'=>"This is a testing email from {$domain}. If this email got to your spam folder please mark it as \"NOT SPAM\".",
				'to'=>$addr,
				'subject'=>'Ifso test email for ' . $domain,
				'headers'=>'From: ' .  'kdwc-email-checker@' . $domain
			];
			var_dump($email);
            if(wp_mail($email['to'],$email['subject'],$email['text'],$email['headers'])){
                http_response_code(200);
                echo 'success';
            }
            else{
                http_response_code(502);
                echo 'fail';
            }
		}
		wp_die();
	}

	function fix_email_return_addr( $phpmailer ) {
		$phpmailer->Sender = $phpmailer->From;
	}

	// TODO cleanup + refactor REQUIRED!
	public function kdwc_save_post_type ( $post_id ){ //MAYBE runs on every publish/update - I think. God given. - Muli
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			die(__( 'You do not have sufficient previlliege to edit the post', 'kd-wc' ).'.');
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		
		// Prevent quick edit from clearing custom fields
		if (defined('DOING_AJAX') && DOING_AJAX) {
			return $post_id;
		}
		
		$trigger_data = array();
		//if(empty($_POST['default'])) return $post_id;
		$trigger_data['default'] = (!empty($_POST['kdwc_default'])) ? $_POST['kdwc_default'] : '';
		//echo "<pre>".print_r($_POST['repeater'], true)."</pre>";
		if(empty($_POST['repeater'])) return $post_id;
		
		// Counting the number of repeaters!
		$repeaters_counter = 1;

		foreach($_POST['repeater'] as $index => $group_item) {
			if(empty($group_item['trigger_type'])) continue;

			$repeaters_counter += 1;
		}

		// die("<h1>".strval($repeaters_counter)."</h1>");

		$testing_mode = (is_numeric($_POST['testing-mode']) &&
						  intval($_POST['testing-mode']) <= $repeaters_counter) ? $_POST['testing-mode'] : "";

		// $default_version_statistics_count = 0;
		// if ( !empty($_POST['kdwc_default_version_statistics_count']) )
		// 	$default_version_statistics_count = 
		// 		$_POST['kdwc_default_version_statistics_count'];

		// Load default's version metadata
		$default_version_metadata = $this->load_default_version_metadata($post_id);

		require_once(IFSO_PLUGIN_BASE_DIR. 'public/services/analytics-service/analytics-service.class.php'); //including the analytics service to later pull the relevant fields out of it;
		$analytics_service = KDWC\PublicFace\Services\AnalyticsService\AnalyticsService::get_instance();

		require_once(IFSO_PLUGIN_BASE_DIR. 'public/models/data-rules/kdwc-data-rules-model.class.php'); //including the analytics service to later pull the relevant fields out of it;
		$data_rules_model  = new KDWC\PublicFace\Models\DataRulesModel\DataRulesModel;

		foreach($_POST['repeater'] as $index => $group_item) {
			
			if($index === 'index_placeholder') continue;
			// if(empty($group_item['trigger_type']) || empty($group_item['repeatable_editor_content'])) continue; // was removed in order to allow saving of an empty content

			// Removed to omit validation
			// if(empty($group_item['trigger_type']))
			// 	continue;
			
			$compare = '';
			if(!empty($group_item['compare_referrer'])) $compare = $group_item['compare_referrer'];
			if(!empty($group_item['compare_url'])) $compare = $group_item['compare_url'];
			
			// Page Url Begin
			$page_url_compare = '';
			if(!empty($group_item['page-url-compare']))
				$page_url_compare = $group_item['page-url-compare'];
			$page_url_operator = '';
			if(!empty($group_item['page-url-operator']))
				$page_url_operator = $group_item['page-url-operator'];
						

			/* Trigger Type Begin */

			if (empty($group_item['trigger_type'])) {
				$trigger_type = array();
			} else {
				$trigger_type = $group_item['trigger_type'];
			}
			/* Trigger Type End */

			/* Begin Sessions */

			$ab_testing_no_sessions = '';
			if(!empty($group_item['ab-testing-sessions']))
				$ab_testing_no_sessions = $group_item['ab-testing-sessions'];

			$ab_testing_custom_sessions = '';
			if (!empty($group_item['ab-testing-custom-no-sessions'])) 
				$ab_testing_custom_sessions = $group_item['ab-testing-custom-no-sessions'];

			/* End Sessions */

			/* Begin User Behavior */

			$user_behavior_loggedinout = '';
			if (!empty($group_item['user-behavior-loggedinout'])) 
				$user_behavior_loggedinout = $group_item['user-behavior-loggedinout'];

			$user_behavior_returning = '';
			if (!empty($group_item['user-behavior-returning'])) 
				$user_behavior_returning = $group_item['user-behavior-returning'];

			$user_behavior_retn_custom = '';
			if (!empty($group_item['user-behavior-retn-custom'])) 
				$user_behavior_retn_custom = $group_item['user-behavior-retn-custom'];

			$user_behavior_browser_language = '';
			if (!empty($group_item['user-behavior-browser-language'])) 
				$user_behavior_browser_language = $group_item['user-behavior-browser-language'];

			$user_behavior_browser_language_primary_lang = '';
			if (!empty($group_item['user-behavior-browser-language-primary-lang'])) 
				$user_behavior_browser_language_primary_lang = $group_item['user-behavior-browser-language-primary-lang'];

			/* End User Behavior */

			$numberOfViews = 0;
			if (!empty($group_item['saved_number_of_views']))
				$numberOfViews = $group_item['saved_number_of_views'];

			$user_behavior_device_mobile = false;
			$user_behavior_device_tablet = false;
			$user_behavior_device_desktop = false;

			if (isset($group_item['user-behavior-device-mobile']) && $group_item['user-behavior-device-mobile'] == "on")
				$user_behavior_device_mobile = true;

			if (isset($group_item['user-behavior-device-tablet']) && $group_item['user-behavior-device-tablet'] == "on")
				$user_behavior_device_tablet = true;

			if (isset($group_item['user-behavior-device-desktop']) && $group_item['user-behavior-device-desktop'] == "on")
				$user_behavior_device_desktop = true;

			// Geolocation Begin
			$geolocation_data = 
				$this->extract_autocomplete_selection_data($group_item['geolocation_data']);
			// Geolocation End

			// Pages visited Begin
			$page_visit_data = 
				$this->extract_autocomplete_selection_data($group_item['page_visit_data']);
			// Pages Visited End

			/* Recurrence Begin */

			$recurrenceOption = "";
			$recurrenceCustomUnits = "";
			$recurrenceCustomValue = "";
			$recurrenceOverride = false;

			/* Check if the selected trigger is one of the allowed triggers
			 * for the Recurrence feature */

			$allowed_triggers_for_recurrence = apply_filters('kdwc_allow_triggers_for_recurrence_filter',  //To allow adding recurrence to custom conditions
                                                array("AB-Testing",
                                                    "advertising-platforms",
                                                    "User-Behavior", // Has sub-option called "Logged"
                                                    "url",
                                                    "referrer",
                                                    "PageUrl",
                                                    "PageVisit",
                                                    "Utm",
                                                )); //"Cookie"

			if (in_array($trigger_type, $allowed_triggers_for_recurrence)) {

				// Check also the sub-option called Logged (if the trigger type is User-Behavior)
				if ($trigger_type != "User-Behavior" ||
					($trigger_type == "User-Behavior" &&
					in_array($group_item['User-Behavior'], array('LoggedIn', 'LoggedOut', 'Logged')))) {

					$rawRecurrenceOption = $group_item['recurrence-option'];
					
					$recurrenceOption = trim($rawRecurrenceOption);

					// none is the default option
					$recurrenceOption = ($recurrenceOption) ? $recurrenceOption : "none";

					/* Custom Handling */
					if ($recurrenceOption == "custom") {
						$recurrenceCustomUnits = $group_item['recurrence-custom-units'];
						$recurrenceCustomValue = $group_item['recurrence-custom-value'];
					}
				}
				$recurrenceOverride = isset($group_item['recurrence-override']) ? $group_item['recurrence-override'] : $recurrenceOverride;
			}
			
			/* Recurrenc End */



			/* Statistics Begin */

			$statistics_counter = 0;
			if ( !empty($group_item['statistics_counter']) )
				$statistics_counter = $group_item['statistics_counter'];

			/* Statistics End */

			// die($recurrenceOption);
			$new_version_rules = array(
				'trigger_type' => $trigger_type,
				'AB-Testing' => isset($group_item['AB-Testing']) ? $group_item['AB-Testing'] : null ,
				'User-Behavior' => isset($group_item['User-Behavior']) ? $group_item['User-Behavior'] : null,
				'user-behavior-loggedinout' => $user_behavior_loggedinout,
				'user-behavior-returning' => $user_behavior_returning,
				'user-behavior-retn-custom' => $user_behavior_retn_custom,
				'user-behavior-loggedinout' => $user_behavior_loggedinout,
				'user-behavior-browser-language' => $user_behavior_browser_language,
				'user-behavior-browser-language-primary-lang' => $user_behavior_browser_language_primary_lang,
				'user-behavior-device-mobile' => $user_behavior_device_mobile,
				'user-behavior-device-tablet' => $user_behavior_device_tablet,
				'user-behavior-device-desktop' => $user_behavior_device_desktop,
				'user-behavior-logged' => isset($group_item['user-behavior-logged']) ? $group_item['user-behavior-logged'] : null ,
				'ab-testing-custom-no-sessions' => $ab_testing_custom_sessions,
				'time-date-start-date' => isset($group_item['time-date-start-date']) ? $group_item['time-date-start-date'] : null,
				'time-date-end-date' => isset($group_item['time-date-end-date']) ? $group_item['time-date-end-date'] : null,
				'Time-Date-Start' => isset($group_item['Time-Date-Start']) ? $group_item['Time-Date-Start'] : null,
				'Time-Date-End' => isset($group_item['Time-Date-End']) ? $group_item['Time-Date-End'] : null,
				'Time-Date-Schedule-Selection' => isset($group_item['Time-Date-Schedule-Selection']) ? $group_item['Time-Date-Schedule-Selection'] : null,
				'Date-Time-Schedule' => isset($group_item['Date-Time-Schedule']) ? $group_item['Date-Time-Schedule']  : null,
				'testing-mode' => $testing_mode,
				'freeze-mode' => isset($group_item['freeze-mode']) ? $group_item['freeze-mode'] : null,
				'ab-testing-sessions' => $ab_testing_no_sessions,
				'number_of_views' => $numberOfViews,
				'trigger' => isset($group_item['trigger']) ? $group_item['trigger'] : null,
				'chosen-common-referrers' => isset($group_item['chosen-common-referrers']) ? $group_item['chosen-common-referrers'] : null,
				'custom' => isset($group_item['custom']) ? $group_item['custom'] : null,
				'page' => isset($group_item['page']) ? $group_item['page'] : null,
				'operator' => isset($group_item['operator']) ? $group_item['operator'] : null,
				'compare' => $compare,
				'page-url-compare' => $page_url_compare,
				'page-url-operator' => $page_url_operator,
				'advertising_platforms' => isset($group_item['advertising_platforms']) ? $group_item['advertising_platforms'] : null,
				'advertising_platforms_option' => isset($group_item['advertising_platforms_option']) ? $group_item['advertising_platforms_option'] : null,
				'geolocation_data' => $geolocation_data,
				'geolocation_behaviour'=> isset($group_item['geolocation-behaviour']) ? $group_item['geolocation-behaviour'] : null,
				'recurrence_option' => $recurrenceOption,
				'recurrence_custom_units' => $recurrenceCustomUnits,
				'recurrence_custom_value' => $recurrenceCustomValue,
				'recurrence-override' => $recurrenceOverride,
				'statistics_counter' => $statistics_counter,
				'page_visit_data' => $page_visit_data,
				'cookie-input' => isset($group_item['CookieVal']) ? $group_item['CookieVal'] : null,
				'cookie-value-input' => isset($group_item['CookieValueVal']) ? $group_item['CookieValueVal'] : null,
				'ip-values' => isset($group_item['UserIp']) ? $group_item['UserIp'] : null,
				'ip-input' => isset($group_item['IpVal']) ? $group_item['IpVal'] : null,
				'utm-type' => isset($group_item['utm-type']) ? $group_item['utm-type'] : null,
				'utm-relation' => isset($group_item['utm-relation']) ? $group_item['utm-relation'] : null,
				'utm-value' => isset($group_item['utm-value']) ? $group_item['utm-value'] : null,
				'add_to_group' => isset($group_item['add_to_group']) ? $group_item['add_to_group'] : null ,
				'remove_from_group' => isset($group_item['remove_from_group']) ? $group_item['remove_from_group'] : null,
				'group-name' => isset($group_item['group-name']) ? $group_item['group-name'] : null,
				'user-group-relation' => isset($group_item['user-group-relation']) ? $group_item['user-group-relation'] : null,
				'user-role-relationship' => isset($group_item['user-role-relationship']) ? $group_item['user-role-relationship'] : null ,
				'user-role' => isset($group_item['user-role']) ? $group_item['user-role'] : null
			);

            $new_version_rules = apply_filters('kdwc_custom_conditions_new_rule_data_extension',$new_version_rules,$group_item);    //For custom triggers extension

			foreach($analytics_service::$analytics_fields as $field){
				$new_version_rules[$field] = $analytics_service->get_analytics_field($post_id,$index,$field);
			}
			//Remove all the fields that are irrelevant to this version
			$new_version_rules = $data_rules_model->trim_version_data_rules($new_version_rules);

			$trigger_data['rules'][] = $new_version_rules;

			// echo "<pre>".print_r($group_item, true)."</pre>";
			$trigger_data['vesrions'][] = $group_item['repeatable_editor_content'];
		}
		
		/*echo "<pre>".print_r($trigger_data, true)."</pre>";
		echo "<pre>".print_r($_POST['repeater'], true)."</pre>";
		die('died in save');*/
		
		/* DB Updates */

		// update default content
		update_post_meta( $post_id,
						  'kdwc_trigger_default',
						  $trigger_data['default'] );

		// update default metadata
		// $default_version_metadata = array(
		// 	'statistics_count' => $default_version_statistics_count
		// );

		$this->save_default_version_metadata($post_id, $default_version_metadata);

		// print_r(json_encode($trigger_data['rules']));
		// die(json_encode($trigger_data['rules'], JSON_UNESCAPED_UNICODE));
		// update rules
		update_post_meta( $post_id, 'kdwc_trigger_rules', json_encode($trigger_data['rules'], JSON_UNESCAPED_UNICODE ));

		// delete all previous versions
		delete_post_meta($post_id, 'kdwc_trigger_version');
		
		if(!empty($trigger_data['vesrions'])) {
			foreach($trigger_data['vesrions'] as $version_content) {
				// add saved versions
				add_post_meta( $post_id, 'kdwc_trigger_version', $version_content );
			}
		}


		// print_r(json_encode($trigger_data['rules']));
		// die();

		// update_post_meta( $post_id, 'kdwc_trigger_rules', json_encode($trigger_data['rules']));

		// echo $post_id;
		// $data_rules_json = get_post_meta( $post_id, 'kdwc_trigger_rules', true );
		// print_r($data_rules_json);
		// die();
		
		
		//die('died in save');
		//update_post_meta( $post_id, 'kdwc_trigger_rules', htmlspecialchars(json_encode($trigger_data), ENT_QUOTES, 'UTF-8') );
	}
}