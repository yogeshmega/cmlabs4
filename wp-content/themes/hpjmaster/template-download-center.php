<?php
/**
 * Template Name: Download Center
 */
?>
<div class="download-center-page">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
		<h2><?php the_title(); ?></h2>
        <?php while ( have_posts() ) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>
