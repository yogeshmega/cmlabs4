<?php
/**
 * Template Name: Product homepage
 */
?>

<?php $productkey = get_producthomepage_key( get_the_id() ); ?>
<div class="producthomepage ">


	<div class="container-fluid">
		<div class="row">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	</div>
	<?php //get_template_part('templates/getedition-cta'); ?>
</div>
