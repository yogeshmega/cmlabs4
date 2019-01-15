<?php

/**
 * Element Definition
 */

class CustomProductBox {

	public function ui() {
		return array(
      'title'       => __( 'HPJ product', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h4.custom-product-heading',
    		'content' => '.custom-product'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
