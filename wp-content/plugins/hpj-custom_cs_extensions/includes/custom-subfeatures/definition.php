<?php

/**
 * Element Definition
 */

class CustomSubFeatures {

	public function ui() {
		return array(
      'title'       => __( 'HPJ Sub Feature list', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h4.custom-slider-heading',
    		'content' => '.custom-features'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
