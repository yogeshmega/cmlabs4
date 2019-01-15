<header class="js-header">
  <div class="logo">
    <a href="<?php
	if ( 3 == get_current_blog_id() ) {
		switch_to_blog( 1 );
		echo esc_url(home_url('/')); 
		restore_current_blog();
	} else {
		echo esc_url(home_url('/')); 
	}
	?>">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/cmlabs_logo.svg" alt="cmlabs  logo" />
    </a>
  </div>

  <nav>

    <?php
	wp_nav_menu(['theme_location' => 'primary_navigation', 'menu' => 'Main menu', 'menu_class' => 'nav navbar-nav']);
    // if (has_nav_menu('product_navigation')) :
    //   wp_nav_menu(['theme_location' => 'product_navigation', 'menu_class' => 'nav navbar-nav']);
    // endif;
    ?>
  </nav>

  <a href="#" class="toggle js-mobile-menu-toggle">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/burger.svg" alt="">
  </a>

  <nav class="mobile-nav js-mobile-menu">

    <?php
	wp_nav_menu(['theme_location' => 'primary_navigation', 'menu' => 'Main menu', 'menu_class' => 'nav navbar-nav']);
    // if (has_nav_menu('product_navigation')) :
    //   wp_nav_menu(['theme_location' => 'product_navigation', 'menu_class' => 'nav navbar-nav']);
    // endif;
    ?>

    <div class="top-nav-mobile">
      <?php
        if (has_nav_menu('top_nav')) :
          wp_nav_menu(['theme_location' => 'top_nav', 'menu_class' => 'top-menu list-unstyled']);
        endif;
		if ( function_exists( 'pll_the_languages' ) ) {
      ?>
      <ul class="lang-mod top-menu list-unstyled list-inline"><?php $args = array('display_names_as' => true); pll_the_languages($args); ?></ul>
	  <?php } ?>
    </div>
  </nav>

  <div class="top-nav">
    <?php
      if (has_nav_menu('top_nav')) :
        wp_nav_menu(['theme_location' => 'top_nav', 'menu_class' => 'top-menu list-unstyled text-right list-inline']);
      endif;
    ?>
    <ul class="lang-mod top-menu list-unstyled text-right list-inline">
      <li class="search-icon">
        <a class="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"><span class="icon icon-search"></span></a>
        <div class="dropdown-menu search-container <?php echo explode('_', get_locale())[0]; ?>" aria-labelledby="dLabel">
          <?php get_search_form()?>
        </div>
      </li>
      <?php
		if ( function_exists( 'pll_the_languages' ) ) {
			$args = array('display_names_as' => true); pll_the_languages($args);
		}
	  ?>
    </ul>
  </div>
  </nav>
</header>