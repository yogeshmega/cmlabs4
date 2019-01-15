<?php

/**
 * Shortcode handler
 */

$class = 'item ' . $class;
$style = 'background-image: url("'. $image .'"); ' . $style;
?>
<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
	<div class="slider-caption">
		<h1><?php echo $heading; ?></h1>
		<?php if ( $content ) : ?>
		<div class="slider-text">
			<?php echo $content; ?>
		</div>
		<?php endif; ?>
		<?php if ( $link_label ) : ?>
			<p class="slider-link"><a href="<?php echo $link; ?>" class="btn btn-default"><?php echo $link_label; ?></a></p>
		<?php endif; ?>
	</div>
</div>
