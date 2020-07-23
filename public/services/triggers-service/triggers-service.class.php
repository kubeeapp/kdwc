<?php

namespace KDWC\PublicFace\Services\TriggersService;

require_once(__DIR__  . '/handlers/impl/cookies-handler.class.php');
require_once(__DIR__  . '/handlers/impl/empty-data-rules-handler.class.php');
require_once(__DIR__  . '/handlers/impl/impressions-handler.class.php');
require_once(__DIR__  . '/handlers/impl/license-validation-handler.class.php');
require_once(__DIR__  . '/handlers/impl/recurrence-handler.class.php');
require_once(__DIR__  . '/handlers/impl/skip-handler.class.php');
require_once(__DIR__  . '/handlers/impl/testing-mode-handler.class.php');
require_once(__DIR__  . '/handlers/impl/triggers-handler.class.php');
require_once(__DIR__  . '/triggers/impl/ab-testing-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/advertising-platform-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/dynamic-link-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/device-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/geolocation-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/page-url-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/page-visit-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/referrer-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/schedule-date-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/start-end-time-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/user-behavior-browser-language-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/user-behavior-logged-in-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/user-behavior-logged-out-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/user-behavior-logged-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/user-behavior-new-user-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/user-behavior-returning-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/cookie-is-set.class.php');
require_once(__DIR__  . '/triggers/impl/from-ip.class.php');
require_once(__DIR__  . '/triggers/impl/user-role.class.php');
require_once(__DIR__  . '/triggers/impl/utm-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/groups-trigger.class.php');
require_once(__DIR__  . '/triggers/impl/user-roles-trigger.class.php');




require_once('trigger-context-loader.class.php');

use KDWC\PublicFace\Services\TriggersService\Handlers;
use KDWC\PublicFace\Services\TriggersService\Triggers;

class TriggersService {
	private static $instance;
	
	private $root_handler;
	
	private function __construct() {
		$this->root_handler = $this->build_handlers();
	}
	
	private function build_handlers() {
		$triggers = $this->build_triggers();

		$licenseValidationHandler = new Handlers\LicenseValidationHandler();
		$licenseValidationHandler
			->set_next(new Handlers\SkipHandler())
			->set_next(new Handlers\TestingModeHandler())
			->set_next(new Handlers\EmptyDataRulesHandler())
			->set_next(new Handlers\CookiesHandler())
			->set_next(new Handlers\ImpressionsHandler())
			->set_next(new Handlers\RecurrenceHandler())
			->set_next(new Handlers\TriggersHandler($triggers));

		return $licenseValidationHandler;
	}

	private function build_triggers() {
		$triggers = array();

		$triggers[] = new Triggers\ReferrerTrigger();
		$triggers[] = new Triggers\PageUrlTrigger();
		$triggers[] = new Triggers\AdvertisingPlatformTrigger();
		$triggers[] = new Triggers\DynamicLinkTrigger();
		$triggers[] = new Triggers\ABTestingTrigger();
		$triggers[] = new Triggers\UserBehaviorNewUserTrigger();
		$triggers[] = new Triggers\UserBehaviorReturningTrigger();
		$triggers[] = new Triggers\UserBehaviorLoggedInTrigger();
		$triggers[] = new Triggers\UserBehaviorLoggedOutTrigger();
		$triggers[] = new Triggers\UserBehaviorLoggedTrigger();
		$triggers[] = new Triggers\UserBehaviorBrowserLanguageTrigger();
		$triggers[] = new Triggers\DeviceTrigger();
		$triggers[] = new Triggers\StartEndTimeTrigger();
		$triggers[] = new Triggers\ScheduleDateTrigger();
		$triggers[] = new Triggers\GeolocationTrigger();
		$triggers[] = new Triggers\PageVisitTrigger();
		$triggers[] = new Triggers\CookieIsSet();
		$triggers[] = new Triggers\UserIpAddress();
		$triggers[] = new Triggers\UserRole();
        $triggers[] = new Triggers\UtmTrigger();
        $triggers[] = new Triggers\GroupTrigger();
        $triggers[] = new Triggers\UserRolesTrigger();

        $triggers = apply_filters('kdwc_triggers_list_filter',$triggers);   //For custom triggers extension


		return $triggers;
	}
	
	public static function get_instance() {
		if ( NULL == self::$instance )
			self::$instance = new TriggersService();

		return self::$instance;
	}
	
	public function handle($atts,$http_request=null) {
		if ( empty( $atts['id'] ) )
			return '';
		
		return $this->root_handler->handle(TriggerContextLoader::load_context($atts,$http_request));
	}
}