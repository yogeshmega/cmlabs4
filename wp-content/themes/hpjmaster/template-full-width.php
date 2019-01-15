<?php
/**
 * Template Name: Page full width, no sidebar
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <div class="container-fluid">
    <div class="row">
      <main class="main-fluid col-sm-12">
        <?php get_template_part('templates/content', 'page'); ?>
      </main><!-- /.main -->
    </div>
  </div>
<?php endwhile; ?>
