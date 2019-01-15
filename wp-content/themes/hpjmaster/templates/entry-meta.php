<div class="entry-meta">
    <div class="post-date"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author_meta('first_name');?> <?php the_author_meta('last_name'); ?></a>, <time class="updated" datetime="<?= get_post_time('c', true); ?>"><?= get_the_date(); ?></time></div>
    <div class="post-categories">
        <?php $categories_list = get_the_category_list( __( ', ', 'twentyeleven' ) ); printf($categories_list);?>
    </div>
</div>