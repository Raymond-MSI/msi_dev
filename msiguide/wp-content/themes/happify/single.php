<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
<div class="single-post-wrapper <?php echo esc_attr( $hapify_blog_sidebar_wrap ); ?>">

	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr( $blog_column_class ); ?>">
				<main id="primary" class="site-main">

					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', get_post_type() );

						the_post_navigation(
							array(
								'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'happify' ) . '</span> <span class="nav-title">%title</span>',
								'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'happify' ) . '</span> <span class="nav-title">%title</span>',
							)
						);

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
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
