<?php

/**
 * Element Definition
 */

class CustomHeaderSliderItem {

	public function ui() {
		return array(
		'title'       => __( 'HPJ Slider Item', 'cs-extension' ),
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
