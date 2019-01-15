<?php

/**
 * Element Controls
 */

return array(
	'heading' => array(
		'type'    => 'textarea',
		'ui' => array(
			'title'   => __( 'Heading', 'cs-extension' ),
		),
	),
	'bg_color' => array( 'mixin' => 'background_color' ),

	'elements' => array(
		'type' => 'sortable',
		'ui' => array(
			'title' => __( 'Sub Feature Item', 'cs-extension' ),
			'tooltip' =>__( 'Add a new item to your Features.', 'cs-extension' ),
		),
		'options' => array(
			'element' => 'custom-subfeature-item',
			'newTitle' => __('Sub Feature Item %s', 'cs-extension'),
			'capacity' => 12,
			'title_field' => 'content'
		),
		'context' => 'content'
	)
);
