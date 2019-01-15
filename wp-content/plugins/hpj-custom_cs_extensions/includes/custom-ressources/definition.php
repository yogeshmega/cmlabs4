<?php

/**
 * Element Definition
 */

class CustomRessoursesBox {

	public function ui() {
		return array(
      'title'       => __( 'HPJ ressources box', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h4.custom-solution-heading',
    		'content' => '.custom-solution'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
