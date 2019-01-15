<?php

/**
 * Element Definition
 */

class CustomHeaderSlider {

	public function ui() {
		return array(
		'title'       => __( 'HPJ Header Slider', 'cs-extension' ),
		'autofocus' => array(
				'heading' => 'h4.header-slider-heading',
				'content' => '.header-slider'
			),
			'icon_group' => 'cs-extension'
		);
	}

}
