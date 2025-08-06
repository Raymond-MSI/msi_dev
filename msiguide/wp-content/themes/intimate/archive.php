<?php
/**
 * The template for displaying archive pages
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
				<div class="archive-heading">
					<?php
					the_archive_title( '<h1 class="archive-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</div>

				<div class="breadcrumbs-wrap">
					<?php do_action('intimate_breadcrumb_options_hook'); ?> <!-- Breadcrumb hook -->
				</div>
			</div>
			<div id="primary" class="col-lg-9 col-md-7 col-sm-12 content-area">
				<main id="main" class="site-main">
					<?php if ( have_posts() ) : ?>

						<?php

						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );
					endwhile;
					/**
		             * intimate_action_navigation hook
		             * @since Intimate 1.0.0
		             *
		             * @hooked intimate_action_navigation -  10
		             */
					do_action( 'intimate_action_navigation');
					else :
					get_template_part( 'template-parts/content', 'none' );
					endif;
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
