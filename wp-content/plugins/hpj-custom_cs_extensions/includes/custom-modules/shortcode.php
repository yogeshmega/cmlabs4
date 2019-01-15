<?php

/**
 * Shortcode handler
 */
$class = 'modules ' . $class;
?>

<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
    <ul class="modules-list list-unstyled">
        <?php echo do_shortcode( $content ); ?>
    </ul>
</div>