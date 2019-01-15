<?php

/**
 * Element Controls
 */

return array(

	'title' => array(
		'type'    => 'text',
		'ui' => array(
			'title'   => __( 'Title', 'cs-extension' ),
			'tooltip' => __( 'Tooltip to describe your controls.', 'cs-extension' ),
		),
	),

	'content' => array(
		'ui' => array(
			'title'   => __( 'Content', 'cs-extension' ),
		),
		'type'    => 'text',
		'suggest' => __( 'Click to inspect, then edit as needed.', 'cs-extension' ),
	),
	
	'title_color' => array(
		'ui' => array(
			'title'   => __( 'Title Color', 'cs-extension' ),
		),
		'type'    => 'color',
	),
	
	'text_color' => array(
		'ui' => array(
			'title'   => __( 'Content Color', 'cs-extension' ),
		),
		'type'    => 'color',
	),
	
	'graphic' => array(
	  'type' => 'select',
	  'ui' => array(
		'title' => __( 'Image Type', 'my-extension' ),
		'tooltip' => __( 'Select type of graphic to be shown', 'my-extension' ),
	  ),
	  'options' => array(
		'choices' => array(
		  array( 'value' => 'image',   'label' => __( 'Image', 'my-extension' ) ),
		)
	  )
	),
	
	'graphic_image' => array(
		'type' => 'image',
		'ui' => array(
			'title' => __( 'Image', 'cs-extension' ),
			'tooltip' => __( 'Choose an image to display as content bg.', 'cs-extension' ),
		)
	),
	
	'graphic_size' => array(
		'ui' => array(
			'title'   => __( 'Graphic Size', 'cs-extension' ),
		),
		'type'    => 'text',
	),

	'href' => array(
		'ui' => array(
			'title'   => __( 'HREF', 'cs-extension' ),
		),
		'type'    => 'text',
		'suggest' => __( '#', 'cs-extension' ),
	),

);
