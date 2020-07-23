<?php
namespace KDWC\Extensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class IFSO_Elementor_Widgets {

	protected static $instance = null;

    public $isOn = false;

	public static function get_instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	protected function __construct() {}

	public function init_elementor_widget(){
        if(did_action( 'elementor/loaded' )){
            $this->isOn = true;
        }

        if($this->isOn){
            require_once( 'widgets/kdwc_dynamic_widget.php' );
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'kdwc_register_widgets' ] );

            // scripts and styles
            add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'kdwc_enqueue_scripts' ] );
            add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'kdwc_enqueue_styles' ] );
            add_action( 'elementor/preview/enqueue_styles', [ $this, 'kdwc_enqueue_preview_styles' ] );
        }
    }


	public function kdwc_register_widgets() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\IFSO_Dynamic_Widget() );
	}

	public function kdwc_enqueue_preview_styles(){
		wp_enqueue_style( 'kdwc-preview', plugin_dir_url(__FILE__)  . 'assets/css/kdwc-preview.css' );
	}

	public function kdwc_enqueue_scripts() {
		wp_enqueue_script( 'datetime', plugin_dir_url( dirname( IFSO_PLUGIN_MAIN_FILE_NAME ) ) . 'admin/js/jquery.kdwcdatetimepicker.full.min.js',  [ 'jquery' ]);
		wp_enqueue_script( 'WeeklyScheduleMinJs', plugin_dir_url( dirname( IFSO_PLUGIN_MAIN_FILE_NAME ) ) . 'admin/js/jquery.weekly-schedule-plugin.min.js',  [ 'jquery' ] );
		wp_enqueue_script( 'kdwc-jquery-ui', plugin_dir_url( dirname( IFSO_PLUGIN_MAIN_FILE_NAME ) ) . 'admin/js/jquery-ui.min.js', [ 'jquery' ] );
		wp_enqueue_script( 'kdwc-editor-js', plugin_dir_url(__FILE__)  . 'assets/js/kdwc.js', [
			'jquery',
			'kdwc-jquery-ui',
			'WeeklyScheduleMinJs',
			'datetime'
		] );

	}

	public function kdwc_enqueue_styles() {
		wp_enqueue_style( 'kdwc-font', plugin_dir_url(__FILE__)  . 'assets/css/kdwc-font.css' );
		wp_enqueue_style( 'kdwc-editor-css', plugin_dir_url(__FILE__)  . 'assets/css/kdwc-editor.css' );
	}

    public function register_elementor_category() {
            \Elementor\Plugin::instance()->elements_manager->add_category(
                'IFSO',
                [
                    'title' => 'IF-SO',
                ]
            );
    }

}





