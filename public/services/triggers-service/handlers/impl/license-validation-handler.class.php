<?php

namespace KDWC\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once(KDWC_PLUGIN_BASE_DIR . 'services/license-service/license-service.class.php');

use KDWC\Services\LicenseService;

class LicenseValidationHandler extends ChainHandlerBase {
	public function handle($context) {
		LicenseService\LicenseService::get_instance()->edd_kdwc_is_license_valid();

		return $this->handle_next($context);
	}
}