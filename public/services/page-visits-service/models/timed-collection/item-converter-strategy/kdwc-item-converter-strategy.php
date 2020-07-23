<?php

/**
 * 
 * 
 *
 * @author Matan Green <matangrn@gmail.com>
 */

if (!class_exists('KdWc_ItemConverterStrategy')) {
	abstract class KdWc_ItemConverterStrategy {
		abstract public function convert_to_model( $item );
		abstract public function convert_to_array( $item );
	}
}