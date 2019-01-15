<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  // add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-relative-urls');
  // add_theme_support('soil-js-to-footer');
  add_theme_support('soil-relative-urls');
  add_theme_support('soil-disable-trackbacks');
  add_theme_support('se-nav-walker');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('hpjmaster', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'hpjmaster')
  ]);
  register_nav_menus([
    'top_nav' => __('Top navigation', 'hpjmaster')
  ]);
  register_nav_menus([
    'footer_navigation' => __('Footer navigation', 'hpjmaster')
  ]);
  register_nav_menus([
    'profile_navigation' => __('Profile Navigation', 'hpjmaster')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');
  add_image_size( 'blog-thumb', 555, 250, true );
  add_image_size( 'blog-large', 750, 330, true );
  add_image_size( 'resource-large', 555, 315, true );
  add_image_size( 'resource-thumb', 260, 148, true );
  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
  // Add edition post type
  register_post_type('cmlabs_edition',
  array(
    'labels' => array(
      'name' => __('Editions'),
    'singular_name' => __('Edition')
    ),
    'public' => true,
    'show_ui' => true,
    'rewrite' => false,
  ));

}

add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'hpjmaster'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Menu', 'hpjmaster'),
    'id'            => 'main-menu',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'hpjmaster'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ]);
  register_sidebar([
    'name'          => __('Profile', 'hpjmaster'),
    'id'            => 'sidebar-profile',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
  ]);

  return apply_filters('masterwp/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('masterwp/css', Assets\asset_path('styles/css/main.css'), false, '2016-12-06-1437');

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
  wp_enqueue_script('boostrap/js', Assets\asset_path('scripts/bootstrap.min.js'), ['jquery'], null, true);
  wp_enqueue_script('mainjs', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);
  wp_enqueue_script('imageloaded/js', Assets\asset_path('scripts/imageloaded.js'), ['jquery'], null, true);
  wp_enqueue_script('matchheight/js', Assets\asset_path('scripts/matchheight.js'), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

@ini_set( 'upload_max_size' , '2M' );
