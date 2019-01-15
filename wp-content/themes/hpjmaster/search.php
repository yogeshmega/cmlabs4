<?php
  $banner_bg = get_bloginfo( 'stylesheet_directory' ). '/assets/images/search-header.jpg'; 
?>
<div class="page-header" style="background-image: url(<?php echo $banner_bg; ?>)">
  <div class="container">
    <h1><strong><?php _e( 'Search results for: ', 'hpjmaster' ); ?>"<?php the_search_query(); ?>" </strong></h1>
  </div>
</div>
<div class="container">
  <?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
      <?php _e('Sorry, no results were found.', 'hpjmaster'); ?>
    </div>
    <?php get_search_form(); ?>
  <?php endif; ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('templates/content', 'search'); ?>
  <?php endwhile; ?>

  <nav class="pagination">
    <?php wp_pagenavi(); ?>
  </nav>
</div>
