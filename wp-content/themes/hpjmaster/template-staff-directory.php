<?php
/**
 * Template Name: Staff Directory
 */
?>
<div class="staff-directory-page">
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
