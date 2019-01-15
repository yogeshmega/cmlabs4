<?php

// =============================================================================
// CORNERSTONE/INCLUDES/ELEMENTS/MIXINS_SETUP/_CONTENT-AREA.PHP
// -----------------------------------------------------------------------------
// V2 element mixins.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Shared
//   02. Setup
//   03. Groups
//   04. Individual Controls
//   05. Control Lists
//   06. Control Groups
// =============================================================================

// Shared
// =============================================================================

include( '_.php' );



// Setup
// =============================================================================
// 01. Available types:
//     -- 'standard'
//     -- 'dropdown'
//     -- 'modal'
//     -- 'off-canvas'

$t_pre       = ( isset( $settings['t_pre'] )       ) ? $settings['t_pre'] . ' ' : '';
$k_pre       = ( isset( $settings['k_pre'] )       ) ? $settings['k_pre'] . '_' : '';
$group       = ( isset( $settings['group'] )       ) ? $settings['group']       : 'content_area';
$group_title = ( isset( $settings['group_title'] ) ) ? $settings['group_title'] : __( 'Content Area', '__x__' );
$condition   = ( isset( $settings['condition'] )   ) ? $settings['condition']   : array();
$type        = ( isset( $settings['type'] )        ) ? $settings['type']        : 'standard'; // 01



// Groups
// =============================================================================

$group_content_area_setup = $group . ':setup';



// Individual Controls
// =============================================================================

$control_content_area_content = array(
  'key'     => $k_pre . 'content',
  'type'    => 'text-editor',
  'title'   => __( 'Content', '__x__' ),
  'group'   => $is_adv ? $group_content_area_setup : $group_std_content,
  'options' => array(
    'mode'   => 'html',
    'height' => $type != 'standard' ? 4 : 5,
  ),
);

$control_content_area_dynamic_rendering = array(
  'keys' => array(
    'dynamic_rendering' => $k_pre . 'content_dynamic_rendering',
  ),
  'type'    => 'checkbox-list',
  'label'   => __( 'Dynamic Rendering', '__x__' ),
  'group'   => $is_adv ? $group_content_area_setup : $group_std_content,
  'options' => array(
    'list' => array(
      array( 'key' => 'dynamic_rendering', 'label' => __( 'Load / reset on element toggle', '__x__' ) ),
    ),
  ),
);



// Control Lists
// =============================================================================

$control_list_content_area_setup_with_dynamic_rendering = array(
  $control_content_area_content,
  $control_content_area_dynamic_rendering,
);



// Control Groups
// =============================================================================

$control_group_content_area_content = array();

if ( $type != 'standard' ) {

  $control_group_content_area_content[] = array(
    'type'     => 'group',
    'title'    => __( $t_pre . 'Content', '__x__' ),
    'group'    => $is_adv ? $group_content_area_setup : $group_std_content,
    'controls' => $control_list_content_area_setup_with_dynamic_rendering,
  );

} else {

  $control_group_content_area_content[] = $control_content_area_content;

}
