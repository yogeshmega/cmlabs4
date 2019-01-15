<?php
/**
 * Template Name: User login pages
 */
?>
<div class="profile-page user-login">
  <div class="container">
    <div class="row">
    <div class="login-form-container col-sm-12 col-sm-offset-0 col-md-6 col-md-offset-3">
      <img class="size-full wp-image-1123 aligncenter" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/vortex_studio_logo.png" alt="vortex studio logo" width="130" height="55" />
      <?php while ( have_posts() ) : the_post(); ?>
        <div class="login-form-wrapper"><?php the_content(); ?></div>
      <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>