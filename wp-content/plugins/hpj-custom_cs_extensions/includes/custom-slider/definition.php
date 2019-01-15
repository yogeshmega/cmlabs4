<?php

/**
 * Element Definition
 */

class CustomSlider {

	public function ui() {
		return array(
		'title'       => __( 'HPJ Slider', 'cs-extension' ),
		'autofocus' => array(
				'heading' => 'h4.custom-slider-heading',
				'content' => '.custom-slider'
			),
			'icon_group' => 'cs-extension'
		);
	}
	public function flags() {
		return array(
			'dynamic_child' => true,
		);
	}

}
