<?php

namespace KDWC\PublicFace\Services\TriggersService\Filters\Hooks;

require_once( plugin_dir_path ( __DIR__ ) . 'hook.interface.php');
require_once( KDWC_PLUGIN_SERVICES_BASE_DIR . 'groups-service/groups-service.class.php' );

use KDWC\PublicFace\Services\GroupsService;

class GroupHook implements IHook {
    public function apply($text, $rule_data){
        $groups_service = GroupsService\GroupsService::get_instance();
        $groups_service->handle($rule_data);
    }
}