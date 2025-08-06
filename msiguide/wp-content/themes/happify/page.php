<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package happify
 */

if ( defined('FW') ) {
  $happify_custom_padding = fw_get_db_post_option($post->ID, 'happify_custom_padding', '');
  $sidebar_position = fw_get_db_post_option($post->ID, 'happify_sidebar_style', '');
}

$happify_custom_padding = isset( $happify_custom_padding ) ? $happify_custom_padding : '';
$sidebar_position = isset( $sidebar_position ) ? $sidebar_position : '';

if ( $happify_custom_padding ) {
  $padding_style = ' style="';
  $padding_style .= ( $happify_custom_padding['from'] ) ? 'padding-top: '. esc_attr( $happify_custom_padding['from'] ) .'px;' : '';
  $padding_style .= ( $happify_custom_padding['to'] ) ? 'padding-bottom: '. esc_attr( $happify_custom_padding['to'] ) .'px;' : '';
  $padding_style .= '"';
} else {
  $padding_style = '';
}

// Sidebar Position
if ( $sidebar_position === 'sidebar_full' ) {
	$column_class = 'col col-md-12';
	$sidebar_column = '';
	$hapify_sidebar_wrap = 'fulid-no-sidebar';
} elseif ( $sidebar_position === 'sidebar_left' ) {
	$column_class = 'col col-md-8 col-md-push-4';
	$sidebar_column = 'col col-md-4 col-md-pull-8';
	$hapify_sidebar_wrap = 'left-sidebar';
} elseif( !is_active_sidebar( 'sidebar-2' ) ) {
	$column_class = 'col col-md-12';
	$sidebar_column = '';
	$hapify_sidebar_wrap = 'not-active-page-sidebar';
} else {
	$column_class = 'col col-md-8';
	$sidebar_column = 'col col-md-4';
	$hapify_sidebar_wrap = 'right-sidebar';
}

get_header();
?>
<div class="main-content-wrapper <?php echo esc_attr( $hapify_sidebar_wrap ); ?>" <?php echo $padding_style; ?>>
	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr( $column_class ); ?>">
				<main id="primary" class="site-main">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</main><!-- #main -->
			</div><!-- #col -->
			<?php
				if ( $sidebar_position !== 'sidebar_full' && is_active_sidebar( 'sidebar-2' ) ) {
				 ?>
					<div class="<?php if ( isset( $sidebar_column ) ) { echo esc_attr( $sidebar_column ); } ?>">
						<?php get_sidebar(); ?>
					</div>
				<?php 
				} 
			?>

		</div><!-- #eow -->
	</div><!-- #container -->
</div><!-- #main-content-wrapper -->

<?php
get_footer();
