<?php
/**
 * Template Name: Applications
 */
?>

<?php
  // add_filter( 'product_subpage_after_subheader', 'get_boxes' );
  // function get_boxes(){
  //   get_template_part('templates/product-boxes');
  // }
?>
<?php while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; ?>
<?php //get_template_part('includes/_product', 'subpage'); ?>
