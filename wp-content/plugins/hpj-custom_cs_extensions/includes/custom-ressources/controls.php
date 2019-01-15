<?php

/**
 * Element Controls
 */

return array(

	'heading' => array(
		'type'    => 'text',
		'ui' => array(
			'title'   => __( 'Heading', 'cs-extension' ),
			'tooltip' => __( 'Tooltip to describe your controls.', 'cs-extension' ),
		),
	),

	'content' => array(
		'ui' => array(
			'title'   => __( 'Content', 'cs-extension' ),
		),
		'type'    => 'textarea',
		'suggest' => __( 'Click to inspect, then edit as needed.', 'cs-extension' ),
	),

	'link' => array(
		'ui' => array(
			'title'   => __( 'Link URL', 'cs-extension' ),
		),
		'type'    => 'text',
		'suggest' => __( '#', 'cs-extension' ),
	),

	'image' => array(
		'type' => 'image',
		'ui' => array(
			'title' => __( 'Image', 'cs-extension' ),
			'tooltip' => __( 'Choose an image to display as content bg.', 'cs-extension' ),
		)
	),

);
