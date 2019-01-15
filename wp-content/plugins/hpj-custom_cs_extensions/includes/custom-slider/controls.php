<?php

/**
 * Element Controls
 */

return array(
	'elements' => array(
		'type' => 'sortable',
		'ui' => array(
			'title' => __( 'Slider Items', 'cs-extension' ),
			'tooltip' =>__( 'Add a new item to your Slider.', 'cs-extension' ),
		),
		'options' => array(
			'element' => 'custom-slider-item',
			'newTitle' => __('Slider Item %s', 'cs-extension'),
			'floor' => 2,
			'capacity' => 100,
			'title_field' => 'heading'
		),
		'context' => 'content',
		'suggest' => array(
			array( 'heading' => __('Slider Item 1', 'cs-extension') ),
			array( 'heading' => __('Slider Item 2', 'cs-extension') ),
			array( 'heading' => __('Slider Item 3', 'cs-extension') ),
		)
	)
);