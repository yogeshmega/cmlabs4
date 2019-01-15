<?php

/**
 * Shortcode handler
 */

$class = 'container-applications-col display-table-cell ' . $class;

?>
<div <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
		<a href="<?php echo $link; ?>">
		<div class="img-container">
			<span class="masque" style="background-color: <?php echo $mask; ?>"></span>
			<?php if(!empty($image)) : ?><img src="<?php echo $image; ?>" class="img-application" /><?php endif; ?>
			<h3><?php echo $heading; ?></h3>
		</div>
		</a>
	<p><?php echo $content; ?></p>
	<a href="<?php echo $link; ?>" class="btn btn-default"><?php echo $link_label; ?></a>
</div>
