<?php

// =============================================================================
// CORNERSTONE/INCLUDES/ELEMENTS/MIXINS_ELEMENTS/TAB.PHP
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

function x_controls_element_tab( $adv = false ) {

  include( dirname( __FILE__ ) . '/../mixins_setup/_.php' );

  if ( $adv ) {

    $controls = array_merge(
      x_controls_tab( array( 'adv' => $adv ) ),
      x_controls_omega( $settings_add_toggle_hash )
    );

  } else {

    $controls = array_merge(
      x_controls_tab(),
      x_controls_omega( array_merge( $settings_std_customize, $settings_add_toggle_hash ) )
    );

  }

  return $controls;

}



// Control Groups
// =============================================================================

function x_control_groups_element_tab( $adv = false ) {

  include( dirname( __FILE__ ) . '/../mixins_setup/_.php' );

  if ( $adv ) {

    $control_groups = array_merge(
      x_control_groups_tab(),
      x_control_groups_omega()
    );

  } else {

    $control_groups = x_control_groups_std( array( 'group_title' => __( 'Tab', '__x__' ), 'no_design' => true ) );

  }

  return $control_groups;

}



// Values
// =============================================================================

function x_values_element_tab( $settings = array() ) {

  include( dirname( __FILE__ ) . '/../mixins_setup/_.php' );

  $values = array_merge(
    x_values_tab(),
    x_values_omega( $settings_add_toggle_hash )
  );

  return x_bar_mixin_values( $values, $settings );

}
