<?php

/**
 * Shortcode handler
 */

$class = ' ' . $class;

?>

<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
	<div class="container-feature">
		<div class="container-feature-mask"></div>
		<?php if ( $heading ) : ?>
			<h3>
				<span><?php echo $number; ?></span>
				<strong><?php echo $heading; ?></strong>
			</h3>
		<?php endif; ?>
		<div class="container-feature-content" style="background-image: url(<?php echo $image;?>)">
			<div class="container-feature-content-txt">
				<p><?php echo $content; ?></p>
				<?php if ( $link_label ) : ?>
					<a href="<?php echo $link; ?>" class="btn btn-default"><?php echo $link_label; ?></a>
				<?php endif; ?>
			</div>
			<a href="#" class="container-feature-content-close-btn"></a>
		</div>
	</div>
</div>
