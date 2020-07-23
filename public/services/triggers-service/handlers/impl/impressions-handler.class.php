<?php

namespace KDWC\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once(KDWC_PLUGIN_BASE_DIR . 'services/license-service/license-service.class.php');
require_once(KDWC_PLUGIN_BASE_DIR . 'services/impressions-service/impressions-service.class.php');

use KDWC\Services\LicenseService;
use KDWC\Services\ImpressionsService;

class ImpressionsHandler extends ChainHandlerBase {
	public function handle($context) {
		// TODO create this service under KdWcServices namespace (Already exists)
		$license = LicenseService\LicenseService::get_instance()->get_license();

		// TODO create this service under KdWcServices namespace
		$impression_service = ImpressionsService\ImpressionsService::get_instance();

		$impression_service->increment();
		$impression_service->handle($license);

		return $this->handle_next($context);
	}
}