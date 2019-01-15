<?php

/**
 * Shortcode handler
 */
preg_match_all("/heading=\"(.*?)\"/", $content, $matches);
$slider_items = $matches[1];
?>
<script>
	jQuery(document).ready(function () {
		jQuery('#applicationsCarousel').find('.item').first().addClass('active');
	});
</script>

<div id="applicationsCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
	<div class="x-container max width">
		<ul class="nav">
		<?php
			$counter = 0;
			foreach($slider_items as $slider_item) { ?>
			<li role="presentation" class="<?php if ($counter == 0) { echo "active";} ?>" data-target="#applicationsCarousel" data-slide-to="<?php echo $counter ?>">
				<a href="#"><?php echo $slider_item ?></a>
			</li>
			<?php
				$counter++;
			}
		?>
		</ul>

		<div class="carousel-inner" role="listbox">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>

	<a class="left carousel-control" href="#applicationsCarousel" role="button" data-slide="prev">
		<span class="icon-left-open-big" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#applicationsCarousel" role="button" data-slide="next">
		<span class="icon-right-open-big" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
