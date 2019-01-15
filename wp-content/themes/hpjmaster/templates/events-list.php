<?php
$time = time();
$args = array(
  'post_type' => 'event',
  'posts_per_page' => 3,
  'meta_query' => array(
      'relation' => 'OR',
	  'wpcf-event-start-date' => array( 
		'key' => 'wpcf-event-start-date',
		'value' => time(),
		'compare' => '>='
	   ),
	   'wpcf-event-end-date' => array(
		'key' => 'wpcf-event-end-date',
		'value' => time(),
		'compare' => '>='
	   ),
  ),  
  'orderby' => array( 'wpcf-event-end-date' => 'ASC' ),
);
$events = new WP_Query($args)
?>
<h2>
  <?php _e('Upcoming <br>Events', 'hpjmaster'); ?>
</h2>
<hr>
<?php if ( $events->have_posts() ) : ?>
	<?php while ( $events->have_posts() ) : $events->the_post(); ?>
		<div class="event-item">
      <div class="event-item__info">
        <h3><?php echo get_the_title() ?></h3>
        <strong class="event-item__type"><?php echo get_the_category()[0]->name; ?></strong>
        <div class="event-item__date">
          <?php
          $start_date = get_post_meta(get_the_ID(), 'wpcf-event-start-date')[0];
          $end_date = get_post_meta(get_the_ID(), 'wpcf-event-end-date')[0];
          if(explode('_', get_locale())[0] == 'fr') {
            $date_format = ' j F ';
          } else {
            $date_format = ' F j ';
          }
          ?>
          <?php _e('from', 'hpjmaster'); ?> 
          <?php echo date_i18n($date_format, $start_date); ?>
          <?php _e('to', 'hpjmaster'); ?>
          <?php echo date_i18n($date_format, $end_date); ?>
        </div>
        <?php the_excerpt(); ?>
      </div>
    </div>
	<?php endwhile; ?>

	<?php wp_reset_postdata(); ?>
<?php endif; ?>