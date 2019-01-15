<?php

/**
 * Shortcode handler
 */
$class = 'subfeatures ' . $class;
$style = 'background-color: ' . $bg_color . ';' . $style;
?>

<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
    <p class="subfeatures-title">
        <?php echo $heading; ?>
    </p>
    <ul class="subfeatures-list list-unstyled">
        <?php echo do_shortcode( $content ); ?>
    </ul>
</div>