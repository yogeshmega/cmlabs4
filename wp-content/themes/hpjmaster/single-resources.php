<div class="container-fluid">
  <?php while (have_posts()) : the_post(); ?>
    <div class="row">
	<?php 
		get_template_part('templates/video-header');
	?>
    </div>
    <div class="container container-overlapped container-video">
      <?php 
	the_content();
      ?>
      <div class="back-wrapper">

        <?php
          $terms = get_terms( 'resource_type', array(
            'hide_empty' => false,
          ));

          foreach($terms as $term) {
            if (strpos($term->slug, 'video') !== false) {
              $resource_type = $term->slug;
            }
          }
		  
		  $parts = parse_url($_SERVER['HTTP_REFERER']);
		  parse_str($parts['query'], $query);

		  $gated_content = get_post_meta( get_the_ID(), 'content_gate', true );
		  
		  if ( !$gated_content ) {
		      if ( 'www.cm-labs.com' == $parts['host'] ) {
			$button_text = 'Back';  
			if ( !empty( $parts['query'] ) ) {
			    $back_url = add_query_arg( $query, $parts['scheme'] . '://' . $parts['host'] . $parts['path'] );
			} else {
			    $back_url = add_query_arg( array(), $parts['scheme'] . '://' . $parts['host'] . $parts['path'] );
			}
		      } else {
			    $button_text = 'See more resources';
			    $back_url = get_post_type_archive_link( 'resources' );
		      }
		  }
	if ( !$gated_content ) {
        ?>
	  
        <a href="<?php echo $back_url; ?>" class="btn btn-default"><?php _e( $button_text, 'hpjmaster')?></a>
	<?php } ?>
      </div>
    </div>
  <?php endwhile; ?>
</div>