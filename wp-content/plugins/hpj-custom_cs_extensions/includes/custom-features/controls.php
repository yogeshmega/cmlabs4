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

	'elements' => array(
		'type' => 'sortable',
		'ui' => array(
			'title' => __( 'Feature Item', 'cs-extension' ),
			'tooltip' =>__( 'Add a new item to your Features.', 'cs-extension' ),
		),
		'options' => array(
			'element' => 'custom-feature-item',
			'newTitle' => __('Feature Item %s', 'cs-extension'),
			'capacity' => 10,
			'title_field' => 'content'
		),
		'context' => 'content'
	)
);
