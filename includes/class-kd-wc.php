<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://kd-wc.com
 * @since      1.0.0
 *
 * @package    KDWC
 * @subpackage KDWC/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    KDWC
 * @subpackage KDWC/includes
 * @author     Matan Green
 * @contributor Nick Martianov
 */

class Kd_Wc {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */


	public function __construct() {
		$this->plugin_name = 'kd-wc';

		$this->define_global_constants();

        $this->version = IFSO_WP_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Kd_Wc_Loader. Orchestrates the hooks of the plugin.
	 * - Kd_Wc_i18n. Defines internationalization functionality.
	 * - Kd_Wc_Admin. Defines all hooks for the admin area.
	 * - Kd_Wc_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kd-wc-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kd-wc-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kd-wc-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-kd-wc-public.php';

		if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			// load our custom updater
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/EDD_SL_Plugin_Updater/EDD_SL_Plugin_Updater.php';
		}

		/**
		 * The class responsible for defining all code necessary to activate /
		 deactivate / etc of KDWC's License.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/license-service/license-service.class.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/license-service/geo-license-service.class.php';

		/**
		 * Plugin settings service.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/plugin-settings-service/plugin-settings-service.class.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/plugin-settings-service/plugin-settings-service.class.php';

		/**
		 * For Extended Shortcodes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/kdwc-extended-shortcodes/extended-shortcodes.php';

        /**
         * For various AJAX actions relating to license checks
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/license-ajax-service/license-ajax-service.class.php';

		 /**
		 * For Privacy Policy.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/privacy-policy/privacy-policy.php';
		add_action('admin_init', 'privacy_on_admin_init');
        /**
         * For modifying the admin(dashboard) interface
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/services/interface-modifier-service/interface-mod.class.php';

        /**
         * For checking whether the plugin was updated and preforming relevant actions if it was
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/after-upgrade-service/after-upgrade-service.class.php';

        /**
         * For checking whether the plugin was updated and preforming relevant actions if it was
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/after-upgrade-service/after-upgrade-service.class.php';

        /**
         * For registering an Ajax API for the analytics system
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/services/analytics-service/analytics-ajax-handler.class.php';

        /**
         * For importing and exporting triggers
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/trigger-port-service/trigger-port-handler.class.php';

        /**
         * For Gutenbeg Block
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/kdwc-guntenberg-block/kdwc-gutenberg-block.class.php';

        /**
         * For Elementor element
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/kdwc-elementor-element/kdwc-elementor-support.php';

        /**
         * For Kd-Wc Groups functionality's handler
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/services/groups-service/groups-handler.class.php';

        /**
         * For loading kd-wc triggers via AJAX
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/services/ajax-triggers-service/ajax-triggers-service.class.php';


        $this->loader = new Kd_Wc_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Kd_Wc_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Kd_Wc_Admin( 
				$this->get_plugin_name(),
				$this->get_version() );

		$plugin_settings = new Kd_Wc_Admin_Settings( 
				$this->get_plugin_name(),
				$this->get_version() );

		$license_service = KDWC\Services\LicenseService\LicenseService::get_instance();
		$geo_license_service = KDWC\Services\GeoLicenseService\GeoLicenseService::get_instance();
        $plugin_settings_service = KDWC\Services\PluginSettingsService\PluginSettingsService::get_instance();
        $license_ajax_service = KDWC\Services\LicenseAjaxService\LicenseAjaxService::get_instance();
        $interface_mod = KDWC\Admin\Services\InterfaceModService\InterfaceModService::get_instance();
        $after_upgrade = KDWC\Services\AfterUpgradeService\AfterUpgradeService::get_instance();
        $trigger_port_handler = \KDWC\Services\TriggerPortService\TriggerPortHandler::get_instance();
        $analytics_ajax_handler = KDWC\PublicFace\Services\AnalyticsService\AnalyticsAjaxHandler::get_instance();
        $gutenberg_block_includer = KDWC\Extensions\IfSoGutenbergBlock\IfSoGutenberBlock\IfSoGutenberBlock::get_instance();
        $groups_handler = KDWC\PublicFace\Services\GroupsService\GroupsHandler::get_instance();
        $elementor_support = Ifso\Extensions\Elementor\IFSO_Elementor_Widgets::get_instance();


        /**
         * For checking whether the plugin was upgraded and preform relevant actions if it was
         */

        $this->loader->add_action('admin_init', $after_upgrade,'handle');

		/**
		 * For Title ShortCodes.
		 */
		$shortcodes_in_title_checkbox = $plugin_settings_service->allowShortcodesInTitle->get();
		$shortcodes_in_title_checkbox ? add_filter( 'document_title_parts', function($atts){$atts['title'] = do_shortcode($atts['title']) ;return $atts; } ) : '';  //Apply to meta title
		$shortcodes_in_title_checkbox ? add_filter( 'the_title', 'do_shortcode' ) : '';     //Apply to title


		/**
		 * For Extended Shortcodes.
		 */
		$ext_shortcodes = KDWC\Extensions\IFSOExtendedShortcodes\ExtendedShortcodes\ExtendedShortcodes::get_instance();
		$this->loader->add_action('init', $ext_shortcodes, 'add_extended_shortcodes', 10);

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' , 99 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_post_types', 1 );
		
		$this->loader->add_action( 'admin_menu', $plugin_settings, 'add_plugin_menu_items' );
		//$this->loader->add_action( 'network_admin_menu', $plugin_settings, 'add_plugin_menu_items' );
		$this->loader->add_filter( 'manage_kdwc_triggers_posts_columns', $plugin_settings, 'kdwc_add_custom_column_title', 100, 1 );
		$this->loader->add_action( 'manage_kdwc_triggers_posts_custom_column', $plugin_settings, 'kdwc_add_custom_column_data', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes_kdwc_triggers', $plugin_settings, 'kdwc_add_meta_boxes', 1 );
		$this->loader->add_action( 'save_post_kdwc_triggers', $plugin_settings, 'kdwc_save_post_type' );
		$this->loader->add_filter( 'wpseo_metabox_prio', $plugin_settings, 'move_yoast_metabox_down', 10 );
		
		$this->loader->add_filter( 'template_include', $plugin_settings, 'include_kdwc_custom_triggers_template', 1 );

		/* Ajax Actions */		
		$this->loader->add_action( 'wp_ajax_load_tinymce_repeater', $plugin_settings, 'load_tinymce' );
        $this->loader->add_action( 'wp_ajax_kdwc_analytics_req', $analytics_ajax_handler, 'handle' );
        $this->loader->add_action( 'wp_ajax_trigger_export_req', $trigger_port_handler, 'handle' );     //Import/Export/Duplicate actions handler
        $this->loader->add_action('wp_ajax_kdwc_groups_req',$groups_handler,'handle');  //Kd-Wc groups actions handler

		$this->loader->add_action( 'wp_ajax_send_test_mail', $plugin_settings, 'send_test_mail' );
		// $this->loader->add_action('admin_init', $plugin_settings,'edd_kdwc_register_option');




		/* License Hooks */
		$this->loader->add_action('admin_init', $license_service,'edd_kdwc_activate_license');
		$this->loader->add_action('admin_init', $license_service,'edd_kdwc_deactivate_license');
		$this->loader->add_action('admin_init', $license_service,'edd_sl_kdwc_plugin_updater',0);
		$this->loader->add_action('admin_init', $license_service,'edd_kdwc_is_license_valid',0);
        $this->loader->add_action( 'wp_ajax_get_license_message', $license_ajax_service, 'licenseAjaxController' );

		/* Geo License Hooks */
		$this->loader->add_action('admin_init', $geo_license_service,'edd_kdwc_activate_geo_license');
		$this->loader->add_action('admin_init', $geo_license_service,'edd_kdwc_deactivate_geo_license');
		$this->loader->add_action('admin_init', $geo_license_service,'edd_sl_kdwc_plugin_updater_geo',0);
		$this->loader->add_action('admin_init', $geo_license_service,'edd_kdwc_is_geo_license_valid',0);

		/* Settings Page Hook(s) */
		$this->loader->add_action(
				'admin_init',
				$plugin_settings_service,
				'settings_page_update',
				0);

		$this->loader->add_action('admin_notices', $plugin_settings,'edd_kdwc_admin_notices');

        /*Interface modification hook(s)*/
        $this->loader->add_filter('enter_title_here',$interface_mod,'replace_newtrigger_title_placeholder',10,2);
        $this->loader->add_filter( 'post_row_actions', $interface_mod, 'add_export_button', 10, 2 );
        $this->loader->add_action( 'views_edit-kdwc_triggers', $interface_mod, 'add_import_button');
        $this->loader->add_action('admin_notices', $interface_mod,'trigger_imported_notice');
        $this->loader->add_action('media_buttons', $interface_mod,'add_editor_modal_button');
        //$this->loader->add_action('wp_insert_post_data', $ext_shortcodes,'modify_kdwc_shorcode_add_edit',99,1);  //--remove for now--Add "edit" button to kd-wc shortcodes on save
        $this->loader->add_filter( 'post_row_actions', $interface_mod, 'add_duplicate_button', 10, 2 );  //Add duplicate button to trigger action bar
        $this->loader->add_filter('the_content',$interface_mod,'do_shortcode',999);  //Prevent external themes/plugins from striping the_content filter out before internal shorcodes can be rendered
        $this->loader->add_action('admin_notices', $interface_mod,'groups_page_notices');
        $this->loader->add_action('show_pagebuilders_noticebox', $interface_mod,'show_pagebuilders_noticebox');

        /*Elementor widget hooks*/
        $this->loader->add_action('init',$elementor_support,'init_elementor_widget');
        $this->loader->add_action('elementor/init',$elementor_support,'register_elementor_category');


        /*Prevent the emails from going to spam by setting the value of the sender header to equal to "FROM"*/
        $this->loader->add_action( 'phpmailer_init', $plugin_settings, 'fix_email_return_addr' );

        /*Enqueue kdwc block assets*/
        $this->loader->add_action( 'init', $gutenberg_block_includer, 'enqueue_block_assets' );
        $this->loader->add_action( 'wp_ajax_get_kdwc_triggers', $gutenberg_block_includer, 'get_trigger_list' ); //Get a list of kd-wc trigers for the gutenberg block

		// $this->loader->add_filter('the_content', $plugin_settings, 'custom_triggers_template', 99);

		// no need for the following action - used for if you want it to fire on the front-end for both visitors and logged-in users
		//add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );
	}

	private function define_global_constants() {
        require(plugin_dir_path( __FILE__ ) . 'kdwc-constants.php');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Kd_Wc_Public( $this->get_plugin_name(), $this->get_version());
        $analytics_ajax_handler = KDWC\PublicFace\Services\AnalyticsService\AnalyticsAjaxHandler::get_instance();
        $ajax_triggers_service = KDWC\PublicFace\Services\AjaxTriggersService\AjaxTriggersService::get_instance();

        $this->loader->add_action('wp_loaded', $plugin_public, 'start_sesh' ); //session_start

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_kdwc_add_page_visit', $plugin_public, 'wp_ajax_kdwc_add_page_visit_handler' );
		$this->loader->add_action( 'wp_ajax_nopriv_kdwc_add_page_visit', $plugin_public, 'wp_ajax_kdwc_add_page_visit_handler' );
        $this->loader->add_action( 'wp_ajax_nopriv_kdwc_analytics_req', $analytics_ajax_handler, 'public_handle' );

        $this->loader->add_action( 'wp_ajax_render_kdwc_shortcodes', $ajax_triggers_service, 'handle_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_render_kdwc_shortcodes', $ajax_triggers_service, 'handle_ajax' );

        $this->loader->add_action( 'init', $plugin_public, 'set_kdwc_group_cookie' );   //Set kd-wc group cookie if the relevant get/post variable is set


		// create shortcode
		$this->loader->add_shortcode( 'kdwc', $plugin_public, 'add_kd_wc_shortcode' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
 
