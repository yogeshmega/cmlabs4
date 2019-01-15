<?php
$image_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post_array->ID), 'blog-thumb');
$thumbnail = $image_arr[0];
$image_alt = get_post_meta( $image->id, '_wp_attachment_image_alt', true);
$alt_text = ($image_alt != '') ? $image_alt : get_the_title() ;
?>
<article class="post col-md-6 <?php echo $counter;?>">
  <div class="post-header">
    <?php if($thumbnail) { ?><img src="<?php echo $thumbnail;?>" alt="<?php echo $alt_text?>" class="img-responsive featured-image" /><?php }?>
    <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php get_template_part('templates/entry-meta'); ?>
  </div>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
    <p><a class="btn btn-default" href="<?php the_permalink();?>"><?php _e('Read more', 'hpjmaster');?></a></p>
  </div>
</article>
