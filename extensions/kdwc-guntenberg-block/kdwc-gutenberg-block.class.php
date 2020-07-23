<?php
/**
 * This extension provides the functionality for the kd-wc gutenberg block(wp >v5.0)
 *
 * @since      1.4.4
 * @package    KDWC
 * @subpackage KDWC/extensions
 * @author Nick Martianov
 */
namespace KDWC\Extensions\KdWcGutenbergBlock\KdWcGutenberBlock;

class KdWcGutenberBlock{
    private static $instance;

    private $gutenberg_exists = false;

    private function __construct(){
        if(function_exists('has_blocks') || function_exists('is_gutenberg_page'))
            $this->gutenberg_exists = true;
    }

    public static function get_instance(){
        if(self::$instance==NULL)
            self::$instance = new KdWcGutenberBlock();
        return self::$instance;
    }

    public function enqueue_block_assets(){

        if($this->gutenberg_exists){
            wp_register_script(
                'kdwc-gutenberg-block',
                plugin_dir_url( __FILE__ ) . './kdwc-gutenberg-block.js',
                array( 'wp-blocks', 'wp-element', 'wp-data')
            );

            wp_register_style(
                'kdwc-gutenberg-block',
                plugin_dir_url( __FILE__ ) . './kdwc-gutenberg-block.css',
                array()
            );

            register_block_type('kdwc/kdwc-block',array(
                'editor_script'=>'kdwc-gutenberg-block',
                'editor_style'=>'kdwc-gutenberg-block',
                'render_callback'=>[$this,'render_kdwc_block']
            ));
        }


    }

    public function enqueue_block_styles(){
        if($this->gutenberg_exists){
            wp_enqueue_style(
                'kdwc-gutenberg-block',
                plugin_dir_url( __FILE__ ) . './kdwc-gutenberg-block.css',
                array()
            );
        }
    }

    public function render_kdwc_block($atts,$content){
        if (isset($atts['selected']) && $atts['selected'] > 0){
            return do_shortcode(sprintf( '[kdwc id="%1$d"]', $atts['selected']));
        }
    }

    public function get_trigger_list(){
        $ret = [];
        $args = [
            'post_type'=>'kdwc_triggers',
            'posts_per_page' => -1,
        ];
        $query = new \WP_Query($args);
        if($query->have_posts()){
            while($query->have_posts()) {
                $query->the_post();
                // Loop in here
                $ret[] = [get_the_ID()=>(null != the_title('','',false) ?  the_title('','',false) : '')];
            }
        }
        wp_reset_postdata();
        echo json_encode($ret);
        wp_die();
    }

}