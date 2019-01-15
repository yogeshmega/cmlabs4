<?php
/**
 * Template Name: Product form subscription
 */
?>
<?php while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
  <div class="container container-form-container">
    <div class="row">
      <div class="col-md-10 col-md-push-1">
        <?php if (!empty(get_post_meta(get_the_id(), 'wpcf-product-subscription-confirmation-content')[0])) : ?>
          <div class="form-container-confirmation-message" style="display: none;">
            <div class="row">
              <div class="col-sm-1"><span class="icon-check"></span></div>
              <div class="col-sm-11">
                <?php echo apply_filters('meta_content', get_post_meta(get_the_id(), 'wpcf-product-subscription-confirmation-content')[0]); ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
