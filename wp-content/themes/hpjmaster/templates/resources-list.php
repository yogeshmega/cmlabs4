<?php
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$args = array(
  'post_type' => 'resources',
  'tax_query' => array(),
  'posts_per_page' => 10,
    'post__not_in' => array(9618), 
  'paged' => $paged,
  's' => $search,
  'lang' => 'en,fr'
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
$resources = new WP_Query($args);

global $wp;
$request_url = $wp->request;
$page_pos = strpos( $wp->request, '/page' );
if ( false !== $page_pos ) {
	$request_url = substr( $wp->request, 0, $page_pos );
}

$current_url = home_url( add_query_arg( array(),$request_url ) );
?>
<div id="resources-search-form" class="page-anchor"></div>
<h2>
  <?php _e('Browse <br>Resources', 'hpjmaster'); ?>
</h2>
<form action="<?php echo $current_url; ?>#resources-search-form" method="GET" class="js-form">
  <div class="form-wrapper">
    <div class="form-field">
      <label for="resource_product"><?php _e('Products', 'hpjmaster'); ?></label>
      <?php
	  
      $product_terms = array();
	  if ( 'en' == pll_current_language() ) {
		  $product_terms = get_terms( 'resource_product', array(
			  'hide_empty' => true,
			  'exclude' => array( 199 )
		  ));
	  } elseif ( 'fr' == pll_current_language() ) {
		  $product_terms = get_terms( 'resource_product', array(
			  'hide_empty' => true,
			  'exclude' => array( 116 )
		  ));	  
	  }
	  
      ?>
      <div class="select-wrapper">
        <select name="resource_product" class="js-submitting-select">
          <option value=""><?php _e('All', 'hpjmaster'); ?></option>
          <?php foreach($product_terms as $term) : ?>
            <option value="<?php echo $term->slug ?>" <?php selected( $resource_product == $term->slug ); ?>>
              <?php echo $term->name ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-field">
      <label for="type"><?php _e('Types', 'hpjmaster'); ?></label>
      <?php
	  $type_terms = array();
	  if ( 'en' == pll_current_language() ) {
		  $type_terms = get_terms( 'resource_type', array(
			  'hide_empty' => true,
			  'include' => array( 121, 122, 120, 111, 194, 210 )
		  ));
	  } elseif ( 'fr' == pll_current_language() ) {
		  $type_terms = get_terms( 'resource_type', array(
			  'hide_empty' => true,
			  'include' => array( 192, 143, 145, 196, 147 )
		  ));	  
	  }
	        
      ?>
      <div class="select-wrapper">
        <select name="resource_type" class="js-submitting-select">
          <option value=""><?php _e('All', 'hpjmaster'); ?></option>
          <?php foreach($type_terms as $term) : ?>
            <option value="<?php echo $term->slug ?>" <?php selected( $resource_type == $term->slug ); ?>>
              <?php echo $term->name ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-field">
      <label for="resource_solution"><?php _e('Solutions', 'hpjmaster'); ?></label>
      <?php
	  
	  $solution_terms = array();
	  if ( 'en' == pll_current_language() ) {
		  $solution_terms = get_terms( 'resource_solution', array(
			  'hide_empty' => true,
			  'include' => array( 114, 108, 117, 119, 109, 110, 115 )
		  ));
	  } elseif ( 'fr' == pll_current_language() ) {
		  $solution_terms = get_terms( 'resource_solution', array(
			  'hide_empty' => true,
			  'include' => array( 164, 168, 160, 166, 158, 162, 156 )
		  ));	  
	  }
	  
      ?>
      <div class="select-wrapper">
        <select name="resource_solution" class="js-submitting-select">
          <option value=""><?php _e('All', 'hpjmaster'); ?></option>
          <?php foreach($solution_terms as $term) : ?>
            <option value="<?php echo $term->slug ?>" <?php selected( $resource_solution == $term->slug ); ?>>
              <?php echo $term->name ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-field">
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
      $link = '';
      $ressourse_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'resource-thumb' );
      $thumb = ( has_post_thumbnail()) ? $ressourse_thumb[0] : get_bloginfo( 'stylesheet_directory' ). '/assets/images/img-ressource-cmlabs.jpg';
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
	  
	  
	  if($type == 'Video' || $type == 'Vidéo' || $type == 'Vortex Studio Tutorials' || $type == 'Tutoriaux Vortex Studio' || $type == 'Newsletter' ) {
        $link = get_permalink();
      } elseif($document != '') {
        $link = $document;
      }
    ?>
		<div class="resource-item">
        <div class="resource-item__image post-image-wrapper ">
          <?php if($link != ''): ?>
            <a href="<?php echo $link ?>" <?php if ($document) { echo 'target="_blank"'; } ?> class="<?php if($type == 'Video' || $type == 'Vidéo' || $type == 'Vortex Studio Tutorials' || $type == 'Tutoriaux Vortex Studio') { echo 'icon-play'; } else { echo 'other'; } ?>">
              <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
            </a>
          <?php else: ?>
            <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
          <?php endif; ?>
        </div>
        <div class="resource-item__info">
          <?php if($link != ''): ?>
            <a href="<?php echo $link ?>" <?php if ($document) { echo 'target="_blank"'; } ?>>
              <h3><?php echo get_the_title() ?></h3>
            </a>
          <?php else: ?>
            <h3><?php echo get_the_title() ?></h3>
          <?php endif; ?>
          <strong class="resource-item__type"><?php echo $type ?></strong>
          <?php the_excerpt(); ?>
        </div>
    </div>
    <?php $document = '' ?>
	<?php endwhile; ?>
  <nav class="pagination">
    <?php wp_pagenavi( array( 'query' => $resources ) ); ?>
  </nav>
<?php else: ?>
  <p class="no-result"><?php _e('No resources corresponding to your search', 'hpjmaster') ?></p>
<?php endif; ?>
<?php wp_reset_postdata(); ?>