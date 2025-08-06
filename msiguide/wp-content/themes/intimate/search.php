<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
					<h1 class="archive-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'intimate' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
				</div>
				<div class="breadcrumbs-wrap">
					<?php 
					// breadcrumb hook
					do_action('intimate_breadcrumb_options_hook'); ?> 
				</div>
			</div>
			<div id="primary" class="col-lg-9 col-md-7 col-sm-12 content-area">
				<main id="main" class="site-main">
					<?php if ( have_posts() ) : ?>
					<?php

						/* Start the Loop */
						while ( have_posts() ) :
							the_post();
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */

							get_template_part( 'template-parts/content', 'search' );

						endwhile;
						
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

