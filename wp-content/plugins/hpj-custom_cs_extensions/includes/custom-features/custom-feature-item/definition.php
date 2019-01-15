<?php

/**
 * Element Definition
 */

class CustomFeatureItem {

	public function ui() {
		return array(
		'title'       => __( 'Custom Features Item', 'cs-extension' ),
		'render'      => false,
			'delegate'    => true
		);
	}
	public function flags() {
		return array(
			'child' => true,
		);
	}


}
