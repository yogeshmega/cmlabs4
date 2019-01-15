<?php

/**
 * Element Definition
 */

class CustomModuleItem {

	public function ui() {
		return array(
		'title'       => __( 'HPJ Modules Item', 'cs-extension' ),
		'render'      => false,
			'delegate'    => true
		);
	}
	public function flags() {
		return array(
			'child' => true,
		);
	}


}
