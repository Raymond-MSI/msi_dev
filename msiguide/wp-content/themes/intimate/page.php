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
 * @package Intimate
 */
get_header();
?>
<section id="content" class="site-content posts-container">
    <div class="container-fluid">
        <div class="row">
        	<div class="col-lg-12">
				<div class="breadcrumbs-wrap">
					<?php do_action('intimate_breadcrumb_options_hook'); ?> <!-- Breadcrumb hook -->
				</div>
			</div>
			<div id="primary" class="col-lg-9 col-md-7 col-sm-12 content-area">
				<main id="main" class="site-main">
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
			</div><!-- #primary -->
			<aside id="secondary" class="col-lg-3 col-md-5 col-sm-12 widget-area side-right">
				<?php get_sidebar(); ?>
			</aside><!-- #secondary -->
		</div>
	</div>
</section>
<?php get_footer();
