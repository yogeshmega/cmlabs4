<?php
/**
 * Template Name: Profile pages
 */
?>
<div class="profile-page">
  <div class="container">
    <div class="row">
      <div class="col-md-2 profile-menu">
      <?php dynamic_sidebar('sidebar-profile'); ?>
      </div>
      <div class="col-md-10">
        <img class="vortex-logo" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/vortex_studio_logo.png" alt="vortex studio logo" width="130" height="55" />
        <?php while ( have_posts() ) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>