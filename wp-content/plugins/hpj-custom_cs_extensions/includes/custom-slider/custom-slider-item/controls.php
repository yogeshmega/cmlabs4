<?php

/**
 * Element Controls
 */

return array(

	'heading' => array(
		'type'    => 'text',
		'ui' => array(
			'title'   => __( 'Heading', 'cs-extension' ),
		),
	),

	'content' => array(
		'ui' => array(
			'title'   => __( 'Content', 'cs-extension' ),
		),
		'type'    => 'editor',
		'suggest' => __( 'Click to inspect, then edit as needed.', 'cs-extension' ),
	),

	'image' => array(
		'type' => 'image',
		'ui' => array(
			'title' => __( 'Image', 'cs-extension' ),
			'tooltip' => __( 'Choose an image to display.', 'cs-extension' ),
		)
	),

);
