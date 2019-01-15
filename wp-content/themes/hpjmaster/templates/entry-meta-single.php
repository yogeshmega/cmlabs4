<div class="entry-meta">
    <div class="post-date"><time class="updated" datetime="<?= get_post_time('c', true); ?>"><?= get_the_date(); ?></time></div>
    <div class="author-image">
        <?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
        <?php the_author_meta('first_name');?> <?php the_author_meta('last_name'); ?>
    </div>
    <div class="post-categories">
        <?php $categories_list = get_the_category_list( __( ', ', 'hpjmaster' ) ); printf($categories_list);?>
    </div>
</div>