<?php

/**
 * 
 * Defines the base persist strategy
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('KdWc_PersistStrategy')) {
	abstract class KdWc_PersistStrategy {
		abstract public function get_items();
		abstract public function persist( $items );
	}
}