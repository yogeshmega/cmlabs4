<?php

return array(

  array(
    'key'         => 'advanced_mode',
		'type'        => 'toggle',
		'title'       => __( 'Advanced Mode', 'cornerstone' ),
		'description' => __( 'Show more design controls when inspecting an element in the builders.', 'cornerstone' ),
    'condition'   => array( 'user_can:preference.advanced_mode.user' => true ),
	),

  array(
    'key'         => 'show_wp_toolbar',
		'type'        => 'toggle',
		'title'       => __( 'WordPress Toolbar', 'cornerstone' ),
		'description' => __( 'Allow WordPress to display the toolbar above the app. Requires a page refresh to take effect.', 'cornerstone' ),
    'condition'   => array( 'user_can:preference.show_wp_toolbar.user' => true ),
	),

  array(
    'key'         => 'help_text',
		'type'        => 'toggle',
		'title'       => __( 'Help Text', 'cornerstone' ),
		'description' => __( 'Show helpful inline messaging throughout the tool to describe various features.', 'cornerstone' ),
    'condition'   => array( 'user_can:preference.help_text.user' => true ),
	),

  array(
    'key'         => 'rich_text_default',
    'type'        => 'toggle',
    'title'       => __( 'Rich Text Editor Default', 'cornerstone' ),
    'description' => __( 'By default, start text editors in rich text mode whenever possible', 'cornerstone' ),
    'condition'   => array( 'user_can:preference.rich_text_default.user' => true ),
  ),

  array(
    'key'         => 'ui_theme',
    'type'        => 'select',
    'title'       => __( 'UI Theme', 'cornerstone' ),
    'description' => __( 'Select how you would like the application UI to appear.', 'cornerstone' ),
    'condition'   => array( 'user_can:preference.ui_theme.user' => true ),
    'options'     => array(
      'choices' => array(
        array( 'value' => 'light', 'label' => __( 'Light', 'cornerstone' ) ),
        array( 'value' => 'dark', 'label' => __( 'Dark', 'cornerstone' ) )
      )
    )
  ),

);
