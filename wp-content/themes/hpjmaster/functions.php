<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php', // Theme customizer
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'hpjmaster'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


// WORDPRESS OVERRIDE FUNCTION
// ---------------------------------------------------------------------

/* @Recreate the default filters on the_content so we can pull formated content with get_post_meta
-------------------------------------------------------------- */
add_filter( 'meta_content', 'wptexturize'        );
add_filter( 'meta_content', 'convert_smilies'    );
add_filter( 'meta_content', 'convert_chars'      );
add_filter( 'meta_content', 'wpautop'            );
add_filter( 'meta_content', 'shortcode_unautop'  );
add_filter( 'meta_content', 'prepend_attachment' );

// Permet l'ajout de fichier svg via le menu Médias
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function custom_wp_trim_excerpt($text) {

$raw_excerpt = $text;
if ( '' == $text ) {
    //Retrieve the post content.
    $text = get_the_content('');

    //Delete all shortcode tags from the content.
    $text = strip_shortcodes( $text );

    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);

    $allowed_tags = '<h1>,<h2>,<h3>,<p>,<a>,<br>'; /*** MODIFY THIS. Add the allowed HTML tags separated by a comma.***/
    $text = strip_tags($text, $allowed_tags);

    $excerpt_word_count = 9999; /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
    $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);

    $excerpt_end = '[...]'; /*** MODIFY THIS. change the excerpt endind to something else.***/
    $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);

    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
        array_pop($words);
        $text = implode(' ', $words);
        $text = $text . $excerpt_more;
    } else {
        $text = implode(' ', $words);
    }
}
return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');




// SHARE PROJECTS FUNCTION
// ---------------------------------------------------------------------
function get_childs_page($post_id, $args = []){

  $args_default = array(
    'post_parent'     => $post_id,
    'post_type'       => 'page',
    'posts_per_page'  => -1,
    'order'           => 'ASC',
    'orderby'         => 'menu_order'
  );

  $args = array_merge($args_default, $args);

  return new WP_Query( $args );

}

function get_templatepage($post_id){
  return str_replace('.php', '', basename( get_page_template() ) );
}

add_filter( 'template_include', 'cmlabs_template_include', 1 );

function cmlabs_template_include( $template_path ) {
	if ( get_post_type() == 'resources' ) {
		if ( is_single() ) {
			$document_terms = get_the_terms(get_the_ID(), 'resource_type');
			$newsletter_template = false;
			$redirect_direct_files = false;
			
			foreach( $document_terms as $document_term ) {
				if ( $document_term->term_id == 210 ) {
					$newsletter_template = true;
				}
			}

			foreach( $document_terms as $document_term ) {
				if ( $document_term->name != 'Video' && $document_term->name != 'Vidéo' && $document_term->name != 'Vortex Studio Tutorials' && $document_term->name != 'Tutoriaux Vortex Studio' && $document_term->name != 'Newsletter' ) {
					$redirect_direct_files = true;
				}
			}
			
			if ( $redirect_direct_files ) {
				$modified_search_string = str_replace( '-', '+', basename(get_permalink()));
				$redirect_url = add_query_arg( array( 'resource_product' => '', 'resource_type' => '', 'resource_solution' => '', 'search' => $modified_search_string ), 'https://www.cm-labs.com/resources/' );
				wp_redirect( $redirect_url );
				exit();
			}
			
			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			if ( $newsletter_template && $theme_file = locate_template( array( 'single-newsletters.php' ) ) ) {
				$template_path = $theme_file;
			}
		}
	}
	return $template_path;
}


// PROJECT FUNCTIONS
// ---------------------------------------------------------------------


// Global Configuration data
add_filter( 'config_data', 'return_config_data' );
function return_config_data( $arg = null ) {

  $data = array(
    'pages_id' => array(
      'vortex' => array(
        'subscription_form_essentials' => 231,
        'subscription_form_academic' => 533,
        'subscription_form_solo' => 529,
        'subscription_form_team' => 531,
        'get_vortex_studio_page' => 538,
        'features_parent' => 118,
        'application_parent' => 109,
        'usecases_parent' => 127,
        'studio_essentials' => 497
      ),
      'global' => array(
        'contact' => 198
      )
    ),
    'default_value' => array(
      'theme_color_header' => '#575756',
      'pageheader' => array(
        'image' => get_stylesheet_directory_uri() . '/assets/images/header_default_img.jpg'
      )
    ),
    'product_keys' => array(
      106 => 'vortex'
    )
  );

  if (empty($arg)){
    return $data;
  }
  else{
    // on loop le param pour trouver dans tableau. Si une clef existe pas on retourne null
    // Pattern = clef_parent[.]clef_enfant[.]clef_sous_enfant
    $params = explode('.', $arg);

    $return = $data;
    foreach ($params as $param){
      if (isset($return[$param])){
        $return = $return[$param];
      }
      else{
        return null;
      }
    }
    return $return;
  }

}


function get_producthomepage_key($post_id){
  $keys = apply_filters( 'config_data', 'product_keys' );
  return $keys[$post_id];
}


function get_headerpage($post_id){
  $infos = [];
  $infos['title'] = get_post_meta($post_id, 'wpcf-banner-title')[0];
  $infos['text'] = get_post_meta($post_id, 'wpcf-banner-text')[0];
  $infos['image'] = get_post_meta($post_id, 'wpcf-banner-image')[0];
  $infos['moreinfo'] = get_post_meta($post_id, 'wpcf-additionnal-info')[0];

  // Default header image
  if (empty($infos['image'])){
    $infos['image'] = apply_filters( 'config_data', 'default_value.pageheader.image' );
  }

  // Video
  if (get_post_meta($post_id, 'wpcf-banner-video-active')[0] == 1){
    $infos['video'] = [];
    $infos['video']['format'] = [];

    if (!empty(get_post_meta($post_id, 'wpcf-banner-video-mp4')[0])){
      $infos['video']['format']['mp4'] = get_post_meta($post_id, 'wpcf-banner-video-mp4')[0];
    }
    if (!empty(get_post_meta($post_id, 'wpcf-banner-video-webm')[0])){
      $infos['video']['format']['webm'] = get_post_meta($post_id, 'wpcf-banner-video-webm')[0];
    }
    if (!empty(get_post_meta($post_id, 'wpcf-banner-video-ogg')[0])){
      $infos['video']['format']['ogg'] = get_post_meta($post_id, 'wpcf-banner-video-ogg')[0];
    }

    $infos['video']['attrs'] = [];
    if (!empty(get_post_meta($post_id, 'wpcf-video-attr-autoplay')[0])){
      $infos['video']['attrs'][] = 'autoplay';
    }
    if (!empty(get_post_meta($post_id, 'wpcf-video-attr-loop')[0])){
      $infos['video']['attrs'][] = 'loop';
    }
    if (!empty(get_post_meta($post_id, 'wpcf-video-attr-muted')[0])){
      $infos['video']['attrs'][] = 'muted';
    }

  }

  return $infos;
}

function get_productboxes($post_id){
  $boxes = [];
  $nb_box = 3;
  for ($i=1; $i<=$nb_box; $i++){
    if (!empty(get_post_meta($post_id, 'wpcf-box-title-' . $i)[0])){
      $boxes[$i] = [];
      $boxes[$i]['title'] = get_post_meta($post_id, 'wpcf-box-title-' . $i)[0];
      $boxes[$i]['text'] = get_post_meta($post_id, 'wpcf-box-text-' . $i)[0];
      $boxes[$i]['image'] = get_post_meta($post_id, 'wpcf-box-image-' . $i)[0];
    }
  }
  return $boxes;
}

function get_productversion($args=[]){

  $args_default = array(
    'post_type' => 'product',
    'posts_per_page' => 3,
    'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
    'order' => 'ASC'
  );

  $args = array_merge($args_default, $args);

  return new WP_Query( $args );

}

function get_blogarticles($args=[]){

  $args_default = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
    'order' => 'ASC'
  );

  $args = array_merge($args_default, $args);

  return new WP_Query( $args );

}

function get_subfeatures($post_id){
  $subfeatures = get_post_meta(get_the_ID(), 'wpcf-sub-feature');
  if (sizeof($subfeatures) == 1 && !strlen($subfeatures[0])){
    return [];
  }
  else{
    return $subfeatures;
  }
}


function get_productversionincludes($post_id){
  $child_posts = types_child_posts('product-jct-include');
  foreach ($child_posts as $child_post) {
    $produit_id = wpcf_pr_post_get_belongs($child_post->ID, 'product-include');
    $produit = get_post($produit_id);
  }
}

function get_featureSubfeaturesheader($post_id){
  return array(
    'title' => get_post_meta($post_id, 'wpcf-features-sub-features-header-title')[0],
    'subtitle' => get_post_meta($post_id, 'wpcf-features-sub-features-header-content')[0]
  );
}

function get_application_extrated($txt){
  $title = extractString($txt, '<h3>', '</h3>');
  $content = str_replace($title, '', $txt);
  $content = preg_replace ("/<h3>(.*?)<\/h3>/i", "", $content);
  return array(
    'title' => $title,
    'content' => $content
  );
}


function extractString($string, $start, $end) {
  $string = " ".$string;
  $ini = strpos($string, $start);
  if ($ini == 0) return "";
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}


function get_includes($args=[]){

  $args_default = array(
    'post_type' => 'product-include',
    'posts_per_page' => -1,
    'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
    'order' => 'ASC'
  );

  $args = array_merge($args_default, $args);

  return new WP_Query( $args );

}


function get_contactinfo(){

  $post_id = apply_filters( 'config_data', 'pages_id.global.contact' );

  $infos = [];
  $infos['adresse'] = get_post_meta($post_id, 'wpcf-contact-adresse')[0];
  $infos['ville'] = get_post_meta($post_id, 'wpcf-contact-ville')[0];
  $infos['province'] = get_post_meta($post_id, 'wpcf-contact-province')[0];
  $infos['pays'] = get_post_meta($post_id, 'wpcf-contact-pays')[0];
  $infos['code-postal'] = get_post_meta($post_id, 'wpcf-contact-code-postal')[0];
  $infos['telephone'] = get_post_meta($post_id, 'wpcf-contact-telephone')[0];
  $infos['courriel'] = get_post_meta($post_id, 'wpcf-contact-courriel')[0];

  $infos['social'] = array(
    'facebook' => get_post_meta($post_id, 'wpcf-sm-facebook')[0],
    'twitter' => get_post_meta($post_id, 'wpcf-sm-twitter')[0],
    'linkedin' => get_post_meta($post_id, 'wpcf-sm-linkedin')[0],
    'youtube' => get_post_meta($post_id, 'wpcf-sm-youtube')[0],
  );

  return $infos;
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
  if (!current_user_can('edit_posts') && !is_admin()) {
    show_admin_bar(false);
  }
}

add_filter( 'wp_nav_menu_items', 'top_loginout_menu_link', 10, 2 );

function top_loginout_menu_link( $items, $args ) {
  if ($args->theme_location == 'top_nav') {
    if (is_user_logged_in()) {
        $profilelink = '<li><a href="'. wp_logout_url( home_url() ) .'">'. __("Logout", 'hpjmaster') .'</a></li><li><a href="' . home_url( 'account' ) .'">'. __("My Account", 'hpjmaster') .'</a></li>';
    } else {
        $profilelink = '<li><a href="'.  home_url( __( 'login', 'hpjmaster' ) ) .'">'. __("Log In", 'hpjmaster') .'</a></li>';
    }
   $items =  $profilelink . $items;
  }
  // if ($args->theme_location == 'product_navigation') {
  //   if (is_user_logged_in()) {
  //     // $profilelink = '<li><a href="'. wp_logout_url() .'">'. __("Logout") .'</a></li>';
  //   }
  // }
  return $items;
}

function custom_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
function wpdocs_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

//add_filter( 'cornerstone_enqueue_styles', '__return_false' );
//add_filter( 'cornerstone_customizer_output', '__return_false' );
//add_filter( 'cornerstone_use_customizer', '__return_false' );

add_filter('embed_oembed_html', 'cmlabs_embed_oembed_html', 99, 4);
function cmlabs_embed_oembed_html($html, $url, $attr, $post_id) {
  return '<div class="embed-responsive embed-responsive-16by9">' . $html  . '</div>';
}
