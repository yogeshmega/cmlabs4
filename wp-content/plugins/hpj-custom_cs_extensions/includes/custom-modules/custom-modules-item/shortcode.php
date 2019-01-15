<?php

/**
 * Shortcode handler
 */

?>


<li <?php echo cs_atts( array( 'id' => $id, 'class' => $class, 'style' => $style ) ); ?>>
    <?php if($href) : ?><a href="<?php echo $href;?>"><?php endif;?>
        <?php if($image) : ?>
            <img src="<?php echo $image;?>" alt="<?php echo do_shortcode( $content ); ?>">
        <?php endif;?>
        <?php if($title) : ?>
            <div class="module-title">
                    <?php echo $title;?>
            </div>
        <?php endif;?>
    <?php if($href) : ?></a><?php endif;?>
</li>