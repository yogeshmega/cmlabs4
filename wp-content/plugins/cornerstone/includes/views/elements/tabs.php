<?php

// =============================================================================
// VIEWS/BARS/TABS.PHP
// -----------------------------------------------------------------------------
// Tabs element.
// =============================================================================

$mod_id      = ( isset( $mod_id ) ) ? $mod_id : '';
$class       = ( isset( $class )  ) ? $class  : '';
$set_initial = ! did_action( 'cs_element_rendering' );


// Atts: Tabs
// ----------

$atts_tabs = array(
  'class' => x_attr_class( array( $mod_id, 'x-tabs', $class ) ),
);

if ( isset( $id ) && ! empty( $id ) ) {
  $atts_tabs['id'] = $id;
}

if ( $tabs_panels_equal_height === true ) {
  $atts_tabs = array_merge( $atts_tabs, cs_element_js_atts( 'tabs', array( 'equalPanelHeight' => $tabs_panels_equal_height ) ) );
}


// Output
// ------

?>

<div <?php echo x_atts( $atts_tabs ); ?>>

  <div class="x-tabs-list">
    <ul role="tablist">

      <?php foreach ( $_custom_data['_modules'] as $key => $tab ) : ?>

        <?php

        // x_dump($tab);

        $tab_atts = array(
          'class'               => $key === 0 && $set_initial ? 'x-active' : '',
          'role'                => 'tab',
          'aria-selected'       => ( $key === 0 && $set_initial ) ? 'true' : 'false',
          'aria-controls'       => 'panel-' . $tab['_id'],
          'data-x-toggle'       => 'tab',
          'data-x-toggleable'   => 'tab-item-' . $tab['_id'],
          'data-x-toggle-group' => 'tab-group-' . $mod_id,
        );
        
        // if (isset($tab['id'])) {
        //   $tab_atts['id'] = $tab['id'];
        // }

        if ( ! empty( $tab['toggle_hash'] ) ) {
          $tab_atts['data-x-toggle-hash'] = $tab['toggle_hash'];
        }

        if (isset($tab['class'])) {
          $tab_atts['class'] .= ' ' . $tab['class'];
        }

        ?>

        <li role="presentation">
          <button <?php echo x_atts( $tab_atts ); ?>>
            <span><?php echo do_shortcode( $tab['tab_label_content'] ); ?></span>
          </button>
        </li>

      <?php endforeach; ?>

    </ul>
  </div>

  <div class="x-tabs-panels">

    <?php foreach ( $_custom_data['_modules'] as $key => $tab ) : ?>

      <?php

      $panel_atts = array(
        'class'             => 'x-tabs-panel',
        'role'              => 'tabpanel',
        'aria-labelledby'   => 'tab-' . $tab['_id'],
        'aria-hidden'       => ( $key === 0 && $set_initial ) ? 'false' : 'true',
        'data-x-toggleable' => 'tab-item-' . $tab['_id']
      );

      if (isset($tab['id'])) {
        $panel_atts['id'] = 'panel-' . $tab['id'];
      }

      if ( $key === 0 && $set_initial ) {
        $panel_atts['class'] .= ' x-active';
      }

      if (isset($tab['class'])) {
        $panel_atts['class'] .= ' ' . $tab['class'];
      }

      ?>

      <div <?php echo x_atts( $panel_atts ); ?>>
        <?php echo do_shortcode( $tab['tab_content'] ); ?>
      </div>

    <?php endforeach; ?>

  </div>

</div>
