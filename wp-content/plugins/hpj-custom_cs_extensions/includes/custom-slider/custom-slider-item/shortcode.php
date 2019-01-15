<?php

/**
 * Shortcode handler
 */
$class = 'item ' . $class;
?>
<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
	<div class="row display-table">
		<div class="col-md-6 display-table-cell">
			<img class="img-responsive" src="<?php echo $image; ?>" alt="<?php echo $heading; ?>">
		</div>
		<div class="col-md-6 display-table-cell vertical-align-middle">
			<?php echo $title; ?>
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>
</div>
