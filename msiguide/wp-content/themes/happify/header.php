<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package happify
 */
if ( defined('FW') ) {
  $header_style = fw_get_db_post_option(get_the_ID(), 'happify_header_styles', '');
  $show_preloader = fw_get_db_customizer_option('happify_preloader');
}

$header_style = isset( $header_style ) ? $header_style : '';
$show_preloader = isset( $show_preloader ) ? $show_preloader : '';

if ( $header_style == 'hs_2') {
	$site_header = 'site-header-s2';
} else {
	$site_header = 'site-header-s1';
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'happify' ); ?></a>
	<?php if ( $show_preloader ) { ?>
		<div class="preloader">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
	<?php } ?>
	<header id="header" class="site-header <?php echo esc_attr( $site_header ); ?>">
		<nav id="site-navigation" class="navigation navbar navbar-default">
      	<div class="container">
          <div class="navbar-header">
          	<div class="site-branding">
							<?php
								if ( has_custom_logo() ) { ?>
									<div class="navbar-brand site-logo"><?php the_custom_logo(); ?></div>
								<?php } else { ?>
									<h2 class="site-title">
											<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
									</h2>
								<?php }
							?>
						</div><!-- .site-branding -->
              <button type="button" id="hamburger-menu" class="open-nav-btn open-btn" aria-label="<?php echo esc_attr__( 'open navigation','happify' ) ?>" aria-controls="link-list" aria-expanded="false">
                  <span class="sr-only"><?php echo esc_html__( 'Toggle navigation','happify' ) ?></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
          </div>
					<?php if ( has_nav_menu( 'primary' ) ) : ?>
				  <div id="navbar" class="navbar-collapse collapse navbar-right navigation-holder slide-content">
				      <button  type="button" id="close" class="close-btn close-navbar" aria-label="<?php echo esc_attr__( 'close navigation','happify' ) ?>">
				        <i class="fa fa-times"></i>
				      </button>
				      <?php
				        wp_nav_menu(
				          array(
				            'menu'              => 'primary',
				            'theme_location'    => 'primary',
				            'container'         => '',
				            'container_class'   => '',
				            'container_id'      => '',
				            'menu_id'           => 'link-list',
				            'menu_class'        => 'nav navbar-nav menu nav-menu',
				            'fallback_cb'       => '__return_false',
				          )
				        );
				      ?>
				  </div><!-- end of nav-collapse -->
				<?php endif; ?>
			</div>
		</nav>
	</header><!-- #masthead -->

	<!-- end of header -->
	<?php get_template_part( 'template-parts/entry', 'header' ); ?>