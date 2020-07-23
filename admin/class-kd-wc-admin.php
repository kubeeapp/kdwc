<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://kd-wc.com
 * @since      1.0.0
 * @package    KDWC
 * @subpackage KDWC/admin
 * @author     Matan Green
 * @author     Nick Martianov
 */
class Kd_Wc_Admin {

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
     * List of admin pages in the plugin.
     *
     * @since    1.4.9
     * @access   private
     * @var      array    $admin_pages    List of admin pages in the plugin.
     */
    private $admin_pages = [
        EDD_KDWC_PLUGIN_GROUPS_PAGE,
        EDD_KDWC_PLUGIN_SETTINGS_PAGE,
        EDD_KDWC_PLUGIN_GEO_PAGE,
        EDD_KDWC_PLUGIN_LICENSE_PAGE,
    ];

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

		$this->load_dependencies();
	}
	
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		 
		//require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-kd-wc-settings-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-kd-wc-settings.php';
	}
	
	public function register_post_types() {
		$labels = array(
			'name'               => _x( 'Triggers', 'kd-wc' ),
			'singular_name'      => _x( 'Trigger', 'kd-wc' ),
			'add_new'            => _x( 'Add New', 'kd-wc' ),
			'add_new_item'       => __( 'Add New Trigger', 'kd-wc' ),
			'edit_item'          => __( 'Edit Trigger', 'kd-wc' ),
			'new_item'           => __( 'New Trigger', 'kd-wc' ),
			'all_items'          => __( 'All Triggers', 'kd-wc' ),
			'view_item'          => __( 'View Trigger', 'kd-wc' ),
			'search_items'       => __( 'Search Triggers', 'kd-wc' ),
			'not_found'          => __( 'No Triggers found', 'kd-wc' ),
			'not_found_in_trash' => __( 'No Triggers found in the Trash', 'kd-wc' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Triggers'
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Holds all the customized content triggers',
			'public'             => true,
			// 'publicly_queryable' => false, // removed at 27/1/2018
			'exclude_from_search' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'kd-wc',
			'menu_position'			=> 90,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'revisions' ),
            'show_in_rest'       => true
		);

		register_post_type( 'kdwc_triggers', $args ); 
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
         * This method is hooked into wordpress in the main kd-wc class(class-kd-wc.php) via kd-wc loader
		 */

		//wp_enqueue_style( $this->plugin_name.'BootstrapGridOnly', plugin_dir_url( __FILE__ ) . 'css/bootstrap-grid-only.css', array(), $this->version, 'all' );
		
		// load botstrap only on current plugin - to prevent collision
       // if (is_page())
		$current_post_type = get_post_type();
        $is_trigger_page = (!empty($current_post_type) && $current_post_type == 'kdwc_triggers');
        $is_kdwc_admin_page = (!empty($_GET['page']) && in_array($_GET['page'], $this->admin_pages));
		if($is_trigger_page) {
			echo "<style>
				/* collusion fix with other plugins */
				#kdwc_triggers_metabox.postbox, #kdwc_shortcode_display.postbox {
					display: block !important;
				}

				#edit-slug-box {
					display:none;
				}
			</style>";
			wp_enqueue_style( $this->plugin_name.'BootstrapCustom', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		}

		if($is_trigger_page || $is_kdwc_admin_page){
            //wp_enqueue_style( $this->plugin_name.'BootstrapCss', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name.'FontAwesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome-4.7.0/css/font-awesome.min.css', array(), $this->version, 'all' );

            /* jquery modal - jquerymodal.com */
            wp_enqueue_style( $this->plugin_name.'KdWcJqueryModalCSS', plugin_dir_url( __FILE__ ) . 'css/jquery.modal.min.css', array(), $this->version, 'all' );

            wp_enqueue_style( $this->plugin_name.'Style', plugin_dir_url( __FILE__ ) . 'css/kd-wc-admin.css', array(), $this->version, 'all' );

            wp_enqueue_style( $this->plugin_name.'JQueryUiMinCss', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );

            wp_enqueue_style( $this->plugin_name.'DateTimePickerCss', plugin_dir_url( __FILE__ ) . 'css/jquery.kdwcdatetimepicker.css', array(), $this->version, 'all' );

            wp_enqueue_style( $this->plugin_name.'EasyAutoCompleteCSS', plugin_dir_url( __FILE__ ) . 'css/easy-autocomplete.min.css', array(), $this->version, 'all' );
        }

		if($is_trigger_page && is_rtl()) {
			wp_enqueue_style( $this->plugin_name.'StyleRtl', plugin_dir_url( __FILE__ ) . 'css/kd-wc-admin-rtl.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
         *
         * This method is hooked into wordpress in the main kd-wc class(class-kd-wc.php) via kd-wc loader
         *
		 */
		

		global $plugin_page;
		if ( is_plugin_active("wp-all-import-pro/wp-all-import-pro.php") &&
		     	( $plugin_page === 'pmxi-admin-import' || 
		     	  $plugin_page === 'pmxi-admin-manage' ) ) {
			// Prevent JS error
			return;
		} else if ( is_plugin_active("wp-all-export/wp-all-export.php") &&
		     		  ( $plugin_page === 'pmxe-admin-export' || 
		     		    $plugin_page === 'pmxe-admin-manage' ) ) {
			// Prevent JS error
			return;
		}

        $current_post_type = get_post_type();
        $is_trigger_page = (!empty($current_post_type) && $current_post_type == 'kdwc_triggers');
        $is_kdwc_admin_page = (!empty($_GET['page']) && in_array($_GET['page'], $this->admin_pages));
        echo "<script>var kdwc_base_url = '".home_url()."';</script>";

        if($is_trigger_page || $is_kdwc_admin_page){
            $ajax_nonce = wp_create_nonce( "my-nonce-string" );

            echo "<script>var nonce = '".$ajax_nonce."';</script>";
            echo "<script>
				var jsTranslations = [];
				jsTranslations['Version'] = '".__('Version')."';
				jsTranslations['translatable_dupplicated_query_string_notification_trigger'] = '".__('This query string is already in use with the current trigger.')."';
				jsTranslations['translatable_dupplicated_query_string_notification_publish'] = '".__('It is not possible to create two query strings with the same name. If you publish now, the second version will be deleted.')."';
		    </script>";
            // wp_enqueue_script( $this->plugin_name.'GoogleAPIService', 'https://maps.googleapis.com/maps/api/js?key='.GOOGLE_API_KEY.'&libraries=places&callback=initAutocomplete', array(  ), $this->version, true );
            wp_enqueue_script( $this->plugin_name.'KdWcHelpers', plugin_dir_url( __FILE__ ) . 'js/helpers.js', array(), $this->version, false );

            wp_enqueue_script( $this->plugin_name.'BootstrapJS', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name.'JQueryMinUI', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array( 'jquery' ), $this->version, false );


            wp_enqueue_script( $this->plugin_name.'BootstrapValidator', plugin_dir_url( __FILE__ ) . 'js/validator.min.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name.'DateTimePickerFullMinJs', plugin_dir_url( __FILE__ ) . 'js/jquery.kdwcdatetimepicker.full.min.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name.'WeeklyScheduleMinJs', plugin_dir_url( __FILE__ ) . 'js/jquery.weekly-schedule-plugin.min.js', array( 'jquery' ), $this->version, false );
            //wp_enqueue_script( $this->plugin_name.'RepeaterJs', plugin_dir_url( __FILE__ ) . 'js/repeater.js', array( 'jquery' ), $this->version, false );

            wp_enqueue_script( $this->plugin_name.'GooglePlacesJS', plugin_dir_url( __FILE__ ) . 'js/kd-wc-google-places.js', array( 'jquery' ), $this->version, true );

            wp_enqueue_script( $this->plugin_name.'EasyAutocompleteJS', plugin_dir_url( __FILE__ ) . 'js/jquery.easy-autocomplete.min.js', array( 'jquery' ), $this->version, false );

            /* jquery modal - http://jquerymodal.com/ */
            wp_enqueue_script( $this->plugin_name.'KdWcJqueryModalJS', plugin_dir_url( __FILE__ ) . 'js/jquery.modal.min.js', array( 'jquery' ), $this->version, false );

            wp_enqueue_script( $this->plugin_name.'VendorsJS', plugin_dir_url( __FILE__ ) . 'js/vendors.js', array( 'jquery' ), $this->version, false );

            wp_enqueue_script( $this->plugin_name.'CustomizedContentJs', plugin_dir_url( __FILE__ ) . 'js/kd-wc-admin.js', array( 'jquery' ), $this->version, false );


            wp_enqueue_script( $this->plugin_name.'GooglePlacesAPI', 'https://maps.googleapis.com/maps/api/js?key='.GOOGLE_API_KEY.'&language=en&libraries=places&callback=initAutocomplete', array(), $this->version, true );
        }


	}

}