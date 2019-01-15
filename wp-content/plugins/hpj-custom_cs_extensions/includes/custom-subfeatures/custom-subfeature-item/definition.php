<?php

/**
 * Element Definition
 */

class CustomSubFeatureItem {

	public function ui() {
		return array(
		'title'       => __( 'HPJ Sub Feature Item', 'cs-extension' ),
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
