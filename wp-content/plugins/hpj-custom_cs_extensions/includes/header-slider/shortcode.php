<?php

/**
 * Shortcode handler
 */
//var_dump($elements);exit;
?>
<script>
	jQuery(document).ready(function ($) {
		$('#headerCarousel').find('.item').first().addClass('active');
	});
</script>

<div id="headerCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">
	<div class="x-container">
		<div class="carousel-inner" role="listbox">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<ol class="carousel-indicators">
			<li data-target="#headerCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#headerCarousel" data-slide-to="1"></li>
			<li data-target="#headerCarousel" data-slide-to="2"></li>
		</ol>
	</div>
</div>
