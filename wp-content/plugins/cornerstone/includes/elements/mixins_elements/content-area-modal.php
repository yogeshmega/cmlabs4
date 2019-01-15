<?php

// =============================================================================
// CORNERSTONE/INCLUDES/ELEMENTS/MIXINS_ELEMENTS/CONTENT-AREA-MODAL.PHP
// -----------------------------------------------------------------------------
// V2 element mixins.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Controls
//   02. Control Groups
//   03. Values
// =============================================================================

// Controls
// =============================================================================

function x_controls_element_content_area_modal( $adv = false ) {

  include( dirname( __FILE__ ) . '/../mixins_setup/_.php' );

  $content_area       = array( 'type' => 'modal', 'k_pre' => 'modal', 't_pre' => __( 'Modal', '__x__' ) );

  $cond_adv           = array( 'adv' => $adv );
  $content_area_adv   = array_merge( $content_area, $cond_adv );
  $toggle_adv         = x_bar_module_settings_anchor( 'toggle', $cond_adv );

  $toggle_std_content = x_bar_module_settings_anchor( 'toggle', array( 'inc_t_pre' => true, 'group' => $group_std_content ) );
  $toggle_std_design  = x_bar_module_settings_anchor( 'toggle', array( 'inc_t_pre' => true, 'group' => $group_std_design ) );

  if ( $adv ) {

    $controls = array_merge(
      x_controls_content_area( $content_area_adv ),
      x_controls_anchor_adv( $toggle_adv ),
      x_controls_modal_adv( $cond_adv ),
      x_controls_omega( $settings_add_toggle_hash )
    );

  } else {

    $controls = array_merge(
      x_controls_content_area( $content_area ),
      x_controls_anchor_std_content( $toggle_std_content ),
      x_controls_anchor_std_design_setup( $toggle_std_design ),
      x_controls_modal_std_design_setup(),
      x_controls_anchor_std_design_colors( $toggle_std_design ),
      x_controls_modal_std_design_colors(),
      x_controls_omega( array_merge( $settings_std_customize, $settings_add_toggle_hash ) )
    );

  }

  return $controls;

}



// Control Groups
// =============================================================================

function x_control_groups_element_content_area_modal( $adv = false ) {

  $content_area = array( 'type' => 'modal', 'k_pre' => 'modal', 't_pre' => __( 'Modal', '__x__' ) );
  $toggle       = x_bar_module_settings_anchor( 'toggle' );

  if ( $adv ) {

    $control_groups = array_merge(
      x_control_groups_content_area( $content_area ),
      x_control_groups_anchor( $toggle ),
      x_control_groups_modal(),
      x_control_groups_omega()
    );

  } else {

    $control_groups = x_control_groups_std( array( 'group_title' => __( 'Content Area Modal', '__x__' ) ) );

  }

  return $control_groups;

}



// Values
// =============================================================================

function x_values_element_content_area_modal( $settings = array() ) {

  include( dirname( __FILE__ ) . '/../mixins_setup/_.php' );

  $content_area = array( 'type' => 'modal', 'k_pre' => 'modal', 't_pre' => __( 'Modal', '__x__' ) );
  $toggle       = x_bar_module_settings_anchor( 'toggle' );

  $values = array_merge(
    x_values_content_area( $content_area ),
    x_values_anchor( $toggle ),
    x_values_modal(),
    x_values_omega( $settings_add_toggle_hash )
  );

  return x_bar_mixin_values( $values, $settings );

}
