<?php

namespace KDWC\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');

class CookiesHandler extends ChainHandlerBase {
	public function handle($context) {
		$cookie_name = 'kdwc_visit_counts';

		// TODO move to another service
		$is_new_user = isset( $_COOKIE[$cookie_name] ) && $_COOKIE[$cookie_name] == '';

		$num_of_visits = 0;
		if ( !$is_new_user ) {
			if ( isset( $_COOKIE[$cookie_name] ) )
				$num_of_visits = $_COOKIE[$cookie_name]; // TODO move to another service

			$num_of_visits = $num_of_visits + 1;
		}

		setcookie($cookie_name, $num_of_visits, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day

		return $this->handle_next($context);
	}
}