<?php

// =============================================================================
// CORNERSTONE/INCLUDES/ELEMENTS/DEFINITIONS/BREADCRUMBS.PHP
// -----------------------------------------------------------------------------
// V2 element definitions.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Define Element
//   02. Builder Setup
//   03. Register Element
// =============================================================================

// Define Element
// =============================================================================

$data = array(
  'title'  => __( 'Breadcrumbs', '__x__' ),
  'values' => x_values_element_breadcrumbs(),
);



// Builder Setup
// =============================================================================

function x_element_builder_setup_breadcrumbs() {
  return array(
    'controls'           => x_controls_element_breadcrumbs(),
    'controls_adv'       => x_controls_element_breadcrumbs( true ),
    'control_groups'     => x_control_groups_element_breadcrumbs(),
    'control_groups_adv' => x_control_groups_element_breadcrumbs( true ),
  );
}



// Register Module
// =============================================================================

cornerstone_register_element( 'breadcrumbs', x_element_base( $data ) );
