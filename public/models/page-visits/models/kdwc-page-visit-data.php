<?php

/**
 * 
 *
 * @author     Matan Green <matangrn@gmail.com>
 */

if (!class_exists('KdWc_Page_Visit_Data')) {
	class KdWc_Page_Visit_Data {

		public function __construct($page, $save_time) {
			$this->page = $page;
			$this->save_time = $save_time;
		}

	}
}
?>