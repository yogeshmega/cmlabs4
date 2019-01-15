<?php
/**
 * Template Name: Resources
 */

$args = array(
  'post_type' => 'resources',
  'posts_per_page' => 1,
  'order' => 'DESC',
  'orderby' => 'post_date',
  'post__not_in' => array(9618), 
  'meta_key'     => 'wpcf-featured',
	'meta_value'   => 1,
	'meta_compare' => '==',
  'tax_query' => array(),
  'lang' => 'en'
);

if($resource_solution != '') {
  array_push($args['tax_query'],  array(
      'taxonomy' => 'resource_solution',
      'field' => 'slug',
      'terms' => $resource_solution
  ));
}

if($resource_product != '') {
  array_push($args['tax_query'],  array(
      'taxonomy' => 'resource_product',
      'field' => 'slug',
      'terms' => $resource_product
  ));
}

if($resource_type != '') {
  array_push($args['tax_query'],  array(
      'taxonomy' => 'resource_type',
      'field' => 'slug',
      'terms' => $resource_type
  ));
}

$featured_resource = new WP_Query($args);

if($featured_resource->post_count == 0) {
  unset($args['meta_key']);
  unset($args['meta_value']);
  unset($args['meta_compare']);
  $featured_resource = new WP_Query($args);
}

$sidebar = true;

?>
<div class="container-fluid">
  <div class="row">
    <?php while ($featured_resource->have_posts()) : $featured_resource->the_post(); ?>
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
          $link = '';
          $ressourse_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'resource-large' );
          $thumb = ( has_post_thumbnail()) ? $ressourse_thumb[0] : get_bloginfo( 'stylesheet_directory' ). '/assets/images/img-ressource-large-cmlabs.jpg';
          $document = get_post_meta(get_the_ID(), 'wpcf-document')[0];
          
		   $document_terms = get_the_terms(get_the_ID(), 'resource_type');
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
	  
          if($type == 'Video' || $type == 'Vidéo' || $type == 'Vortex Studio Tutorials' || $type == 'Tutoriaux Vortex Studio' ) {
            $link = get_permalink();
          } elseif($document != '') {
            $link = $document;
          }
        ?>
        <div class="col-md-6">
          <div class="post-image-wrapper">
            <?php if($link != ''): ?>
              <a href="<?php echo $link ?>" <?php if ($document) { echo 'target="_blank"'; } ?> class="<?php if($type == 'Video' || $type == 'Vidéo' || $type == 'Vortex Studio Tutorials' || $type == 'Tutoriaux Vortex Studio') { echo 'icon-play'; } ?>">
                <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
              </a>
            <?php else: ?>
              <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-6 resource-item__info">
          <?php if($link != ''): ?>
            <a href="<?php echo $link ?>" <?php if ($document) { echo 'target="_blank"'; } ?>>
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
        <?php get_template_part('templates/resources-list'); ?>
        <?php if($sidebar): ?>
          <div class="events-list until-tablet">
            <?php get_template_part('templates/events-list'); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
