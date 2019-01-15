<?php

/**
 * Element Controls
 */

return array(

	'content' => array(
		'ui' => array(
			'title'   => __( 'Content', 'cs-extension' ),
		),
		'type'    => 'textarea',
	),
	'image' => array(
		'type' => 'image',
		'ui' => array(
			'title' => __( 'Image', 'cs-extension' ),
			'tooltip' => __( 'Choose an image to display.', 'cs-extension' ),
		)
	),
	'href' => array(
		'type' => 'text',
		'ui' => array(
			'title' => __( 'HREF', 'cs-extension' ),
		)
	),

);
