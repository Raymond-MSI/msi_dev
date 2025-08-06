<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package happify
 */

get_header();
?>

<div class="error-404-wrapper">
	<div class="container">
		<div class="row">
			<main id="primary" class="site-main">
				<section class="error-404 not-found">
					<header class="page-header">
						<h2 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'happify' ); ?></h2>
					</header><!-- .page-header -->

					<div class="page-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'happify' ); ?></p>

							<?php
							get_search_form();

							?>
					</div><!-- .page-content -->
				</section><!-- .error-404 -->
			</main><!-- #main -->
		</div>
	</div>
</div>

<?php
get_footer();
