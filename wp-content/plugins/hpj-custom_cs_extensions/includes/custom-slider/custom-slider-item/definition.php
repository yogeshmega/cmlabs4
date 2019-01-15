<?php

/**
 * Element Definition
 */

class CustomSliderItem {

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
