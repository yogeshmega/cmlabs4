<?php

/**
 * Shortcode handler
 */

$class = ' custom-ressources-box text-center' . $class;

?>
<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
	<a href="<?php echo $link; ?>">
		<span class="img-container">
			<?php if(!empty($image)) : ?><img alt="<?php echo $heading; ?>" src="<?php echo $image; ?>" class="img-application" /><?php endif; ?>
			<h4><?php echo $heading; ?></h4>
		</span>
		<p><?php echo $content; ?></p>
	</a>
</div>
