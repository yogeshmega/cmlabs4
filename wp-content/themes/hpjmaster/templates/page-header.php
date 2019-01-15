<?php

if (get_option('page_for_posts') ) {
  $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_option('page_for_posts')),'full'); 
  $banner_bg = $img[0];
  $blog_title = get_the_title( get_option('page_for_posts', true) );
}
?>
<div
  class="page-header"
  <?php if (!empty($banner_bg)) : ?>style="background-image: url(<?php echo $banner_bg; ?>)"<?php endif; ?>>
  <div class="container">
    <h1><strong><?php echo $blog_title;?></strong></h1>
  </div>
</div>
