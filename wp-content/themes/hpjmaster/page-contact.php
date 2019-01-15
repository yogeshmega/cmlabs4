<?php
/**
 * Template Name: Page Contact
 */
?>
<?php
use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
?>
<?php while (have_posts()) : the_post(); $custom = get_post_custom(); ?>
  <div class="container">
    <div class="row">
      <main class="main">
        <?php get_template_part('templates/content', 'page'); ?>
        <?php
          if(!empty($custom['wpcf-script-google-maps']) && !empty($custom['wpcf-script-google-maps'][0])){ ?>
            <div class="map-content">
            </div>
            <script src="//maps.google.com/maps/api/js" type="text/javascript"></script>
            <script type="text/javascript">
                <?php echo $custom['wpcf-script-google-maps'][0]; ?>
            </script>
            <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/map.js" type="text/javascript" ></script>
        <?php } ?>
      </main><!-- /.main -->
      <?php if (Setup\display_sidebar()) : ?>
        <aside class="sidebar">
          <?php include Wrapper\sidebar_path(); ?>
        </aside><!-- /.sidebar -->
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; ?>
