<?php

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$args = array(
  'post_type' => 'news',
  'tax_query' => array(),
  'date_query' => array(),
  'posts_per_page' => 10,
  'paged' => $paged,
  's' => $search
);

$news_year = $_GET['news_year'];
if($news_year != '') {
  array_push($args['date_query'],  array(
    'year' => $news_year
  ));
}

if($news_category != '') {
  array_push($args['tax_query'],  array(
      'taxonomy' => 'news_category',
      'field' => 'slug',
      'terms' => $news_category
  ));
}

$resources = new WP_Query($args);

$oldest = date('Y');

$oldestPost = new WP_Query(
  array(
    'post_type' => 'news',
    'orderby' => 'date',
    'order' => 'ASC',
    'posts_per_page'=> 1
  ));

if ($oldestPost->posts[0]) {
  $oldest = date('Y', strtotime($oldestPost->posts[0]->post_date));
}

global $wp;
$request_url = $wp->request;
$page_pos = strpos( $wp->request, '/page' );
if ( false !== $page_pos ) {
	$request_url = substr( $wp->request, 0, $page_pos );
}

$current_url = home_url( add_query_arg( array(),$request_url ) );
?>
<div id="news-search-form" class="page-anchor"></div>
<h2>
  <?php _e('Browse the News', 'hpjmaster'); ?>
</h2>
<form action="<?php echo $current_url; ?>#news-search-form" method="GET" class="js-form">
  <div class="form-wrapper">
    <div class="form-field form-field-news">
      <label for="product"><?php _e('Category', 'hpjmaster'); ?></label>
      <?php
      $news_terms = array();
	  if ( 'en' == pll_current_language() ) {
		  $news_terms = get_terms( 'news_category', array(
			  'hide_empty' => true,
			  'include' => array( 172, 174, 176, 126 )
		  ));
	  } elseif ( 'fr' == pll_current_language() ) {
		  $news_terms = get_terms( 'news_category', array(
			  'hide_empty' => true,
			  'include' => array( 184, 182, 178, 180 )
		  ));	  
	  }
      ?>
      <div class="select-wrapper">
        <select name="news_category" class="js-submitting-select">
          <option value=""><?php _e('All', 'hpjmaster'); ?></option>
          <?php foreach( $news_terms as $term ) : ?>
            <option value="<?php echo $term->slug ?>" <?php selected( $news_category == $term->slug ); ?>>
              <?php echo $term->name ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-field form-field-news">
      <label for="type"><?php _e('Year', 'hpjmaster'); ?></label>
      <?php
      ?>
      <div class="select-wrapper">
        <select name="news_year" class="js-submitting-select">
          <option value=""><?php _e('All', 'hpjmaster'); ?></option>
          <?php for($i = date('Y'); $i >= $oldest; $i--) : ?>
            <option value="<?php echo $i ?>" <?php selected( $news_year == $i ); ?>>
              <?php echo $i ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>
    </div>
    <div class="form-field form-field-news">
      <label for="search">&nbsp;</label>
      <div class="search-wrapper">
        <input type="text" placeholder="<?php _e('Search', 'hpjmaster'); ?>" name="search" value="<?php echo $search; ?>" />
        <button class="search-button icon-search"></button>
      </div>
    </div>
  </div>
</form>
<?php if ( $resources->have_posts() ) : ?>
	<?php while ( $resources->have_posts() ) : $resources->the_post(); ?>
    <?php
      $document = '';
      $link = get_permalink();
      $ressourse_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'resource-thumb' );
      $thumb = ( has_post_thumbnail()) ? $ressourse_thumb[0] : get_bloginfo( 'stylesheet_directory' ). '/assets/images/img-ressource-cmlabs.jpg';
      
	  $document_terms = get_the_terms(get_the_ID(), 'news_category');
		  if ( 'en' == pll_current_language() ) {
		foreach ( $document_terms as $document_term ) {
			if ( false === strpos ( $document_term->slug, '-fr' ) ) {
				$type = $document_term->name;
			}
		  }
	  } elseif ( 'fr' == pll_current_language() ) {
		foreach ( $document_terms as $document_term ) {
			if ( strpos ( $document_term->slug, '-fr' ) ) {
				$type = $document_term->name;
			}
		  }
	  }
	  
	  
    ?>
		<div class="resource-item">
        <div class="resource-item__image post-image-wrapper ">
          <?php if($link != ''): ?>
            <a href="<?php echo $link ?>" class="other">
              <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
            </a>
          <?php else: ?>
            <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
          <?php endif; ?>
        </div>
        <div class="resource-item__info">
          <?php if($link != ''): ?>
            <a href="<?php echo $link ?>">
              <h3><?php echo get_the_title() ?></h3>
            </a>
          <?php else: ?>
            <h3><?php echo get_the_title() ?></h3>
          <?php endif; ?>
          <strong class="resource-item__type"><?php echo $type ?></strong>
          <?php the_excerpt(); ?>
        </div>
    </div>
	<?php endwhile; ?>
  <nav class="pagination">
    <?php wp_pagenavi( array( 'query' => $resources ) ); ?>
  </nav>
<?php else: ?>
  <p class="no-result"><?php _e('No news corresponding to your search', 'hpjmaster') ?></p>
<?php endif; ?>
<?php wp_reset_postdata(); ?>