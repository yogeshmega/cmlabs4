<?php

if (get_option('page_for_posts') ) {
  $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_option('page_for_posts')),'full'); 
  $banner_bg = $img[0];
} else {
  $banner_bg = 'https://www.cm-labs.com/wp-content/uploads/banner_blog.png';
}
if ( is_archive()) {
  $blog_title = get_the_archive_title();

} else {
	$blog_title = get_the_title( get_option('page_for_posts', true) );

	if ( $_SERVER['HTTP_HOST'] == 'www-sales.cm-labs.com' ) {
		$blog_title = 'Sales Portal';
	}   
}
?>
<div
  class="page-header"
  <?php if (!empty($banner_bg)) : ?>style="background-image: url(<?php echo $banner_bg; ?>)"<?php endif; ?>>
  <div class="container">
    <h1><strong><?php echo $blog_title;?></strong></h1>
  </div>
</div>
