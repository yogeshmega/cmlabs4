<?php

/**
 * Element Definition
 */

class CustomFeatures {

	public function ui() {
		return array(
      'title'       => __( 'HPJ Feature list', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h4.custom-slider-heading',
    		'content' => '.custom-features'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
