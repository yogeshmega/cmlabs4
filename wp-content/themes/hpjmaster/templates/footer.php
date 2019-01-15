<footer>
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-8">

        <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/cmlabs_logo.svg" class="logo" alt="cmlabs logo" />
        </a>

        <?php
        if (has_nav_menu('footer_navigation')) :
          wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'footer-nav']);
        endif;
        ?>

      </div>
      <div class="col-md-4">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      </div>
    </div>
  </div>
</footer>

<a href="#header" id="btn-scroll-top-page"><span class="icon-right-open-big"></span></a>

<?php wp_footer(); ?>
