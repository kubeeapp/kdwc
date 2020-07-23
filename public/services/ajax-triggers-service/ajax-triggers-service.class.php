<?php
namespace KDWC\PublicFace\Services\AjaxTriggersService;

require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers-service.class.php';
require_once(IFSO_PLUGIN_BASE_DIR . 'public/helpers/kdwc-request/Kd-Wc-Http-Get-Request.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'public/services/analytics-service/analytics-service.class.php');

use KDWC\PublicFace\Services\TriggersService;
use KDWC\PublicFace\Helpers\IfSoHttpGetRequest as IfsoRequest;

class AjaxTriggersService{
    private static $instance;

    private function __construct(){}

    public static function get_instance(){
        if(NULL === self::$instance)
            self::$instance = new AjaxTriggersService();

        return self::$instance;
    }

    public function create_kdwc_ajax_tag($trigger_id){
        $html = "<IfSoTrigger tid='{$trigger_id}' style='display:none;'></IfSoTrigger>";
        return $html;
    }

    public function handle($atts){
        if(!empty($atts['id'])){
            return $this->create_kdwc_ajax_tag($atts['id']);
        }
        return '';
    }

    public function handle_ajax(){
        if(wp_doing_ajax() && !empty($_REQUEST['triggers'])){
            $triggers = $_REQUEST['triggers'];
            $page_url = $_REQUEST['page_url'];
            $pageload_referrer = !empty($_REQUEST['pageload_referrer']) ? $_REQUEST['pageload_referrer'] : '';
            $triggers_service = TriggersService\TriggersService::get_instance();
            $http_request = IfsoRequest\IfSoHttpGetRequest::create($page_url,$pageload_referrer);
            \KDWC\PublicFace\Services\AnalyticsService\AnalyticsService::get_instance()->useAjax=false;
            if($triggers && is_array($triggers)){
                $res = new \stdClass();

                foreach($triggers as $id){
                    $res->$id = $triggers_service->handle(['id'=>$id],$http_request);
                }

                if(!empty($res)){
                    echo json_encode($res);
                }
            }
        }
        wp_die();
    }
}