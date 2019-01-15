<?php

/**
 * Shortcode handler
 */

$class = 'cta-container display-table' . $class;
?>
<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
	<a href="<?php echo $link; ?>">
		<div class="img-container display-table-cell">
			<img src="<?php echo $image; ?>" />
			<h2 class="title"><?php echo $heading; ?></h2>
		</div>
	</a>
	<p><?php echo $content; ?></p>
	<a href="<?php echo $link; ?>" class="btn btn-default"><?php echo $link_label; ?></a>
</div>
