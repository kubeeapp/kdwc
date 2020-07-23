<?php

namespace KDWC\PublicFace\Services\TriggersService\Filters\Hooks;

require_once( plugin_dir_path ( __DIR__ ) . 'hook.interface.php');
require_once( KDWC_PLUGIN_SERVICES_BASE_DIR . 'analytics-service/analytics-service.class.php' );

use KDWC\PublicFace\Services\AnalyticsService;

class AnalyticsHook implements IHook {
    public function apply($text, $rule_data) {
        $analytics_service = AnalyticsService\AnalyticsService::get_instance();
        $analytics_service->handle($rule_data);
    }

    public function apply_default($rule_data){
        $analytics_service = AnalyticsService\AnalyticsService::get_instance();
        $analytics_service->handle_default($rule_data);
    }
}