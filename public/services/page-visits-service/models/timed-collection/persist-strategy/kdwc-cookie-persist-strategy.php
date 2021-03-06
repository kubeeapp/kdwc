<?php

/**
 * 
 * 
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('KdWc_CookiePersistStrategy')) {

	require_once('kdwc-persist-strategy.php');

	class KdWc_CookiePersistStrategy extends KdWc_PersistStrategy {

		protected $cookie_name;

		public function __construct( $cookie_name ) {
			$this->cookie_name = $cookie_name;
		}

		public function get_items() {
			$items = null;

			if ( isset( $_COOKIE[$this->cookie_name] ) )
				$items = json_decode( 
					stripslashes( $_COOKIE[$this->cookie_name] ),
					true );
			else {
				$items = array();
			}

			return $items;
		}

		public function persist( $items ) {
			$encoded_items = json_encode( $items, JSON_UNESCAPED_UNICODE );
			setcookie( $this->cookie_name,
					   $encoded_items,
					   2147483647,
					   '/' );
		}
	}

}