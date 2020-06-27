<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">
	<div class="container-fluid bg-white sticky-top">
		<div class="container pt-4">
			<div class="row pt-2">
				<div class="col-md-2 col-12 text-center text-md-left">
					<!-- Your site title as branding in the menu -->
					<?php if ( ! has_custom_logo() ) { ?>

						<?php if ( is_front_page() && is_home() ) : ?>

							<h1 class="navbar-brand mb-0"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

						<?php else : ?>

							<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a>

						<?php endif; ?>


						<?php } else {
						the_custom_logo();
					} ?><!-- end custom logo -->
				</div>
				<div class="col-md-10 col-12 align-self-center">
					<div class="row">
						<div class="col-md-10 col-6 text-left d-flex justify-content-start">
							<nav class="navbar navbar-expand-md">
								<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>">
									<span class="navbar-toggler-icon"></span>
								</button>

								<!-- The WordPress Menu goes here -->
								<?php wp_nav_menu(
									array(
										'theme_location'  => 'primary',
										'container_class' => 'collapse navbar-collapse',
										'container_id'    => 'navbarNavDropdown',
										'menu_class'      => 'navbar-nav ml-auto',
										'fallback_cb'     => '',
										'menu_id'         => 'main-menu',
										'depth'           => 2,
										'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
									)
								); ?>
							</nav><!-- .site-navigation -->
						</div>
						<div class="col-md-2 col-6 text-right">
							<div class="header__right d-flex justify-content-end">
                                <div class="log__in">
									<?php if ( is_user_logged_in() ) { ?>
										<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','woothemes'); ?>"><i class="fa fa-user-o"></i></a>
									<?php } 
									else { ?>
										<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Register','woothemes'); ?>"><i class="fa fa-user-o"></i></a>
									<?php } ?>
                                </div>
                                <div class="shopping__cart">
									<?php 
									if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
										$count = WC()->cart->cart_contents_count;
										if ( $count >= 0 ) {
											?>
											<a href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><i class="fa fa-shopping-basket"></i></a>
											<div class="shop__qun">
												<span><?php echo esc_html( $count ); ?></span>
											</div>
									<?php }} ?>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
