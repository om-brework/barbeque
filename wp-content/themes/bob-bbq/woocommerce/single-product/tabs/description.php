<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;
?>
<div class="container">
	<div class="row">
		<div class="col-12 col-md-12">
			<?php
			$args = array(
			'numberposts'		=> -1, // -1 is for all
			'post_type'		=> 'sauce', // or 'post', 'page'
			'orderby' 		=> 'ID', // or 'date', 'rand'
			'order' 		=> 'ASC', // or 'DESC'
			);
			// Get the posts
			$myposts = get_posts($args);

			// If there are posts
			if($myposts):
				echo '<div class="row">';
				// Loop the posts
				foreach ($myposts as $mypost): 
					$custom_css = get_post_meta($mypost->ID, 'custom_class',true);
				?>
					<div class="col <?php echo (!empty($custom_css)) ? "$custom_css" : ""; ?>">
						<?php 
						echo "<h3><i class='fa fa-crown'></i>".get_the_title($mypost->ID)."</h3>";
						echo get_post_field('post_content', $mypost->ID);
						?>
					</div>
				<?php endforeach;
				wp_reset_postdata();
				echo '</div>';
			endif;
			?>
		</div>
		<?php the_content(); ?>
	</div>
</div>
<?php

