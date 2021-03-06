<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="knx_item_wrapper">
        <?php if(has_post_thumbnail()): ?>
            <div class="knx_item_image">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
            </div>
        <?php endif;?>
        <div class="knx_item_content">
            <h4 class="knx_item_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div class="knx_item_meta-data">
                <p><?php _e('Available until: ', 'knx-promotions');  echo gmdate('d/m/Y', get_post_meta( get_the_ID(), '_knx-promo_valido_hasta', true )); ?></p>
            </div>
            <div class='knx_item_excerpt'>
                <?php the_excerpt(); ?>
            </div>
            <a href="<?php the_permalink();?>" class="vc_btn vc_general vc_gitem-link knx_btn"><?php _e('View promotion', 'knx-promotions'); ?></a>
            <?php // Si el VC rompe las clases del child theme quitar las clases vc_* del <a> y definirlas en styles.css ?>
        </div>
        <div class="knx_clearfix"></div>
    </div><!-- close .post-container-pro -->
</article><!-- #post-## --> 