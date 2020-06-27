<?php
add_shortcode( 'bbq_render_product', 'bbq_render_product' );
function bbq_render_product(){
    ?>
            <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 12
                    );
                $loop = new WP_Query( $args );
                if ( $loop->have_posts() ) {
                    while ( $loop->have_posts() ) : $loop->the_post();
                        wc_get_template_part( 'content', 'product' );
                    endwhile;
                } else {
                    echo __( 'No products found' );
                }
                wp_reset_postdata();
            ?>
    <?php
}

