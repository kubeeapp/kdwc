<?php
namespace KDWC\Admin\Services\InterfaceModService;

class InterfaceModService{

    private static $instance;

    private function __construct(){
    }

    public static function get_instance(){
        if (NULL == self::$instance)
            self::$instance = new InterfaceModService();

        return self::$instance;
    }

    public function replace_newtrigger_title_placeholder($title,$post){

        if($post->post_type == 'kdwc_triggers'){
            $ret = __('Add title (optional)','kd-wc');
            return $ret;
        }

        return $title;
    }

    public function add_export_button($actions,$post){
        if ($post->post_type=='kdwc_triggers' && current_user_can('edit_posts')) {
            $actions['export'] = '<a href="' . admin_url('admin-ajax.php?action=trigger_export_req&exporttrigger&postid=' . $post->ID, basename(__FILE__)) . '" title="'. __('Export this trigger', 'kd-wc').'" rel="permalink">'. __('Export', 'kd-wc') .'</a>';
        }
        return $actions;
    }

    public function add_import_button($arr){
        if (current_user_can('edit_posts')) {
            $html = '<div class="wrap" style="margin-bottom:0;color: #0073aa;"> <form action="' . admin_url('admin-ajax.php?action=trigger_export_req&importtrigger=true') . '" method="post" enctype="multipart/form-data"><label for="triggerToImport" style="font-weight:normal"><span>+ '. __('Import  trigger', 'kd-wc') .'</span><input style="display:none" type="file" onchange="form.submit()" name="triggerToImport" id="triggerToImport"></label></form></div>';
        }
        echo $html;
        return $arr;
    }

    public function add_duplicate_button($arr,$post){
        if($post->post_type=='kdwc_triggers'){
            if (current_user_can('edit_posts')) {
                $html = '<a href="' . admin_url('admin-ajax.php?action=trigger_export_req&duplicatetrigger=true&postid='.$post->ID) . '">'. __('Duplicate', 'kd-wc') .'</a>';
            }
            $arr[] = $html;
        }
        return $arr;
    }

    public function trigger_imported_notice(){
        if(isset($_REQUEST['kdwcTriggerImported'])){
            if($_REQUEST['kdwcTriggerImported'] =='success'){
            ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Trigger imported successfully', 'kd-wc'); ?></p>
                </div>
                <?php
            }
            if($_REQUEST['kdwcTriggerImported'] =='fail'){
                ?>
                <div class="notice notice-warning is-dismissible">
                    <p><?php _e('Failed at importing trigger', 'kd-wc'); ?></p>
                </div>
                <?php
            }
        }
    }

    public function add_editor_modal_button(){
        global $post;
        if(isset($post) && $post->post_type !=='kdwc_triggers' && !(isset($_GET['action']) && $_GET['action'] === 'elementor')){
            echo '<a href="'. admin_url( 'edit.php' ).'?post_type=kdwc_triggers&TB_iframe=true&width=1024&height=600" id="kdwc-editor-button" class="button thickbox" title="Kd-Wc triggers"><img style="bottom:1px;position:relative;width:11px;" src="'. plugin_dir_url(__FILE__) . '../../images/logo-256x256.png">'. __('Dynamic Content', 'kd-wc') .'</a>';
        }

    }

    public function do_shortcode($content,$param=false){
       return do_shortcode($content,$param);
    }

    public function groups_page_notices(){
        if(!empty($_COOKIE['kdwc-group-action-notice'])){
            $notice = $_COOKIE['kdwc-group-action-notice'];
            $ret = '';

            if($notice === 'no-name-to-add'){
                $ret = '
                <div class="notice error is-dismissible" >
                    <p>'. __( 'You did not enter an audience name.', 'kd-wc' ) . '</p>
                </div>';
            }

            elseif($notice === 'already-exists'){
                $ret = '
                <div class="notice error is-dismissible" >
                    <p>'. __( 'An audience with that name already exists.', 'kd-wc' ) . '</p>
                </div>';
            }

            elseif($notice === 'successfully-added'){
                $ret = '
                <div class="notice updated is-dismissible" >
                    <p>'. __( 'The audience has been successfully created', 'kd-wc' ) . '</p>
                </div>';
            }

            elseif($notice === 'successfully-removed'){
                $ret = '
                <div class="notice error is-dismissible" >
                    <p>'. __( 'The audience has been successfully removed.', 'kd-wc' ) . '</p>
                </div>';
            }

            setcookie('kdwc-group-action-notice','no-name-to-add',time() - 3600*24,'/');

            echo $ret;
        }

    }

    private function get_active_plugins(){
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugins = get_plugins();
        $active = get_option('active_plugins');
        $ret = [];
        foreach($plugins as $key=>$val){
            if (in_array($key,$active)){
                $ret[] = $val['Name'];
            }
        }

        return $ret;
    }

    public function show_pagebuilders_noticebox(){
        $active_plugins = $this->get_active_plugins();
        $page_builder_list = [
            'Elementor',
            'Fusion Builder',
            'Divi Builder',
            'Elementor Pro',
            'Page Builder by SiteOrigin',
            'Brizy',
            'Brizy Pro',

        ];
        $active_page_builders = array_intersect($active_plugins,$page_builder_list);
        if(empty($active_page_builders)) return false;

    ?>
        <div class="pagebuilders-noticebox purple-noticebox">
            <span class="closeX" style="border-color:#c0bc25;">X</span>
            <p>We noticed that you are using <?php echo implode(', ', $active_page_builders); ?>. If you encounter any issues after pasting the shortcode go to <a href="<?php echo admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_SETTINGS_PAGE ); ?>" target="_blank">Kd-Wc > Settings </a> and change the status of the "The_content" filter checkbox.</p>
        </div>
    <?php
    }

}

