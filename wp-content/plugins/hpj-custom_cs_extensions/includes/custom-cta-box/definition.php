<?php

/**
 * Element Definition
 */

class CustomCTABox {

	public function ui() {
		return array(
      'title'       => __( 'HPJ CTA Box', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h2.custom-cta-heading',
    		'content' => '.custom-cta'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
