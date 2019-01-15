<?php

/**
 * Element Definition
 */

class CustomSolutionBox {

	public function ui() {
		return array(
      'title'       => __( 'HPJ solution box', 'cs-extension' ),
      'autofocus' => array(
    		'heading' => 'h4.custom-solution-heading',
    		'content' => '.custom-solution'
    	),
    	'icon_group' => 'cs-extension'
    );
	}

}
