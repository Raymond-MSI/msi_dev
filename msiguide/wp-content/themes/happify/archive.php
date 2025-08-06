<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package happify
 */

if ( defined('FW') ) {
  $happify_blog_sidebar_style = fw_get_db_customizer_option('happify_blog_sidebar_style');
}
$blog_sidebar_position = isset( $happify_blog_sidebar_style ) ? $happify_blog_sidebar_style : '';

// Sidebar Position
if ( $blog_sidebar_position === 'sidebar_full' ) {
	$blog_column_class = 'col col-md-12';
	$blog_sidebar_column = '';
	$hapify_blog_sidebar_wrap = 'fulid-no-sidebar';
} elseif ( $blog_sidebar_position === 'sidebar_left' ) {
	$blog_column_class = 'col col-md-8 col-md-push-4';
	$blog_sidebar_column = 'col col-md-4 col-md-pull-8';
	$hapify_blog_sidebar_wrap = 'left-sidebar';
} elseif( !is_active_sidebar( 'sidebar-1' ) ) {
	$blog_column_class = 'col col-md-12';
	$blog_sidebar_column = '';
	$hapify_blog_sidebar_wrap = 'not-active-page-sidebar';
} else {
	$blog_column_class = 'col col-md-8';
	$blog_sidebar_column = 'col col-md-4';
	$hapify_blog_sidebar_wrap = 'right-sidebar';
}

get_header();
?>
<div class="main-content-post-wrapper <?php echo esc_attr( $hapify_blog_sidebar_wrap ); ?>">

	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr( $blog_column_class ); ?>">
				<main id="primary" class="site-main">
					<?php
						if ( have_posts() ) :

							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', get_post_type() );

							endwhile;

							the_posts_navigation();

						else :

							get_template_part( 'template-parts/content', 'none' );

						endif;
						?>
				</main><!-- #main -->
			</div><!-- #col -->
			<?php
				if ( $blog_sidebar_position !== 'sidebar_full' && is_active_sidebar( 'sidebar-1' ) ) {
				 ?>
					<div class="<?php if ( isset( $blog_sidebar_column ) ) { echo esc_attr( $blog_sidebar_column ); } ?>">
						<?php get_sidebar(); ?>
					</div>
				<?php 
				} 
			?>
			</div><!-- #row -->
		</div><!-- #container -->
	</div><!-- #main-content-wrapper -->
<?php
get_footer();
