<?php
/**
 * Template Name: News
 */

$args = array(
  'post_type' => 'news',
  'posts_per_page' => 1,
  'order' => 'DESC',
  'orderby' => 'post_date',
  'tax_query' => array()
);

if($news_category != '') {
  array_push($args['tax_query'],  array(
      'taxonomy' => 'news_category',
      'field' => 'slug',
      'terms' => $news_category
  ));
}

$featured_resource = new WP_Query($args);
$sidebar = true;

// if($search != '' || $solution != '' || $product != '' || $resource_type != '') {
//   $sidebar = false;
// }

?>
<div class="container-fluid">
  <div class="row">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('templates/resources-header'); ?>
    <?php endwhile; ?>
  </div>
  </div>
<div class="container">
  <div class="row featured-resource">
    <?php if ( $featured_resource->have_posts() ) : ?>
      <?php while ( $featured_resource->have_posts() ) : $featured_resource->the_post(); ?>
        <?php
          $document = '';
          $link = get_permalink();
          $ressourse_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'resource-large' );
          $thumb = ( has_post_thumbnail()) ? $ressourse_thumb[0] : get_bloginfo( 'stylesheet_directory' ). '/assets/images/img-ressource-large-cmlabs.jpg';
          $document = get_post_meta(get_the_ID(), 'wpcf-document')[0];
		  
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
        <div class="col-md-6">
          <div class="post-image-wrapper">
            <?php if($link != ''): ?>
              <a href="<?php echo $link ?>">
                <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
              </a>
            <?php else: ?>
              <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-6 resource-item__info">
          <?php if($link != ''): ?>
            <a href="<?php echo $link ?>">
              <h2><?php echo get_the_title() ?></h2>
            </a>
          <?php else: ?>
            <h2><?php echo get_the_title() ?></h2>
          <?php endif; ?>
          
          <strong class="resource-item__type"><?php echo $type; ?></strong>
          <?php the_excerpt(); ?>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div></div>
<div class="container-fluid resources-wrapper ">
  <div class="container">
    <div class="row">
      <div class="resources-list <?php if($sidebar) { echo 'with-sidebar'; } ?>">
        <?php if($sidebar): ?>
          <div class="events-list from-tablet">
            <?php get_template_part('templates/events-list'); ?>
          </div>
        <?php endif; ?>
        <?php get_template_part('templates/news-list'); ?>
        <?php if($sidebar): ?>
          <div class="events-list until-tablet">
            <?php get_template_part('templates/events-list'); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
