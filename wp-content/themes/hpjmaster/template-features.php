<?php
/**
 * Template Name: Features
 */
?>

<?php while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; ?>
<?php //get_template_part('includes/_product', 'subpage'); ?>
