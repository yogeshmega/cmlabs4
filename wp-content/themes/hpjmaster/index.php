<?php
use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
$counter = 1; //start counter
$grids = 2; //Grids per row
?>

<?php get_template_part('templates/blog', 'header'); ?>
<div class="container">
  <div class="row">
    <div class="main main-container">
      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'hpjmaster'); ?>
        </div>
      <?php get_search_form();
      endif;?>
      <div class="row posts">
        <?php
          while (have_posts()) : the_post();
          $image_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post_array->ID), 'blog-thumb');
          $thumbnail = $image_arr[0];
          $image_alt = get_post_meta( $image->id, '_wp_attachment_image_alt', true);
          $alt_text = ($image_alt != '') ? $image_alt : get_the_title() ;
          $do_not_duplicate = $post->ID;
          ?>
          <article class="post matchHeight <?php
            if (($counter ==1) and ( !is_paged() ) && '1' == get_current_blog_id() ) {
              echo "col-md-12 latest";
            } else {
                echo "col-md-6";
                is_paged();
            }
          ?>">
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
        <?php
        $counter++;?>
        <?php
        endwhile;?>
        <script type="text/javascript">jQuery('.posts').imagesLoaded().then(function(){jQuery('.matchHeight').matchHeight();});</script>
      </div>
      <div class="post-navigation">
        <?php posts_nav_link( ' | ', '<span class="arrow">‹</span> Previous', 'Next <span class="arrow">›</span>' ); ?>
      </div>
    </div>
    <?php if (Setup\display_sidebar()) : ?>
      <aside class="sidebar">
        <?php include Wrapper\sidebar_path(); ?>
      </aside><!-- /.sidebar -->
    <?php endif; ?>
  </div>
</div>
<?php /* get_template_part('templates/getedition-cta'); */ ?>
