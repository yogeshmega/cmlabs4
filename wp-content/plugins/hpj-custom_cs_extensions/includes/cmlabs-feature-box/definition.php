<?php

/**
 * Element Definition
 */

class CMLabsFeatureBox {

	public function ui() {
		return array(
      'title'       => __( 'CM Labs Feature Box', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h2.cmlabs-feature-box',
    		'content' => '.cmlabs-feature-box'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
