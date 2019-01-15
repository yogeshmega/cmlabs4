<?php

/**
 * Element Controls
 */

return array(

	'elements' => array(
		'type' => 'sortable',
		'ui' => array(
			'title' => __( 'Modules Item', 'cs-extension' ),
			'tooltip' =>__( 'Add a new item to your Features.', 'cs-extension' ),
		),
		'options' => array(
			'element' => 'custom-modules-item',
			'newTitle' => __('Modules Item %s', 'cs-extension'),
			'capacity' => 12,
			'title_field' => 'title'
		),
		'context' => 'content'
	)
);
