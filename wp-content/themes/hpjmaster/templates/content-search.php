<?php
  $document = '';
  $link = get_permalink();
  $ressourse_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'resource-thumb' );
  $thumb = ( has_post_thumbnail()) ? $ressourse_thumb[0] : get_bloginfo( 'stylesheet_directory' ). '/assets/images/img-ressource-cmlabs.jpg';
  $type = get_the_terms(get_the_ID(), 'resource_type')[0]->name;

  $links_terms = array('Video', 'Vidéo');

  if(!in_array($type, $links_terms) && $post->post_type == 'resource') {
    $link = '';
  }
?>
<article class="resource-item">
  <div class="resource-item__image post-image-wrapper ">
    <?php if($link != ''): ?>
        <a href="<?php echo $link ?>" class="<?php if($type == 'Video' || $type == 'Vidéo') { echo 'icon-play'; } ?>">
          <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
        </a>
      <?php else: ?>
        <img src="<?php echo $thumb ?>" alt="<?php echo get_the_title() ?>">
      <?php endif; ?>
  </div>
  <div class="resource-item__info">
    <div class="search-entry__summary">
      <?php if($link != ''): ?>
            <a href="<?php echo $link ?>" >
              <h3><?php echo get_the_title() ?></h3>
            </a>
          <?php else: ?>
            <h3><?php echo get_the_title() ?></h3>
          <?php endif; ?>
          <strong class="resource-item__type">
          <?php echo $type ?>
          <?php $categories_list = get_the_category_list( __( ', ', 'hpjmaster' ) ); printf($categories_list);?>
          </strong>
          <?php the_excerpt(); ?>
    </div>
  </div>
</article>
