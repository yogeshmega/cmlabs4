<?php

/**
 * Shortcode handler
 */

$class = 'product-container display-table darkbg' . $class;
$style = 'background-image: url("'. $image .'"); ' . $style;
?>
<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
	<a href="<?php echo $link; ?>">
		<span class="img-container display-table-cell">
			<h3 class="title"><?php echo $heading; ?></h3>
			<p><?php echo $content; ?></p>
			<button class="btn btn-default"><?php echo $link_label; ?></button>
		</span>
	</a>
</div>
