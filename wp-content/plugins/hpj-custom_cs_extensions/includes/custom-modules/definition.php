<?php

/**
 * Element Definition
 */

class CustomModules {

	public function ui() {
		return array(
      'title'       => __( 'HPJ modules list', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h4.custom-modules-heading',
    		'content' => '.custom-modules'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
