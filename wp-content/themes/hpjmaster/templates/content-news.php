<?php
$image_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post_array->ID), 'blog-large');
$thumbnail = $image_arr[0];
$image_alt = get_post_meta( $image->id, '_wp_attachment_image_alt', true);
$alt_text = ($image_alt != '') ? $image_alt : get_the_title() ;

while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <h1 class="h2"><?php the_title(); ?></h1>
    <?php get_template_part('templates/entry-meta-news'); ?>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'hpjmaster'), 'after' => '</p></nav>']); ?>
    <?php //comments_template('/templates/comments.php'); ?>
  </article>
  <div class="post-navigation">
    <?php $prevPost = get_previous_post(true);
    if($prevPost) {?>
    <span class="nav-link prev">
      <?php previous_post_link('%link','<span class="arrow">‹</span> Previous', TRUE); ?>
    </span>
    <?php
     } $nextPost = get_next_post(true);
     if(($prevPost) && ($nextPost)) {
        echo '<span class="separator icon-th"></span>';
      }
      if($nextPost) { ?>

      <span class="nav-link next">
        <?php next_post_link('%link','Next <span class="arrow">›</span>', TRUE); ?>
      </span>
    <?php } ?>
  </div>
<?php endwhile; ?>