<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package happify
 */
if ( defined('FW') ) {
  $happify_post_meta = fw_get_db_customizer_option('happify_post_meta');
  $happify_readmore_text = fw_get_db_customizer_option('happify_readmore_text');
}
$happify_post_meta = isset( $happify_post_meta ) ? $happify_post_meta : '';
$happify_readmore_text = isset( $happify_readmore_text ) ? $happify_readmore_text : esc_html__( 'Read More', 'happify' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-thumbnail">
		<?php happify_post_thumbnail(); ?>
	</div>
	<div class="entry-meta-wrapper">
		<?php
		if ( !$happify_post_meta && 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				happify_posted_on();
				happify_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</div><!-- .entry-header -->

	<?php 
		if ( !is_single() && 'post' == get_post_type() ) { ?>
		<div class="entry-title">
			<h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3>
		</div>
	<?php	}
	?>

	<div class="entry-content">
		<?php
		if ( is_single() && 'post' == get_post_type() ) {
				the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'happify' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
		} else { ?>
			<p><?php echo wp_trim_words( get_the_content(), 50); ?></p>
		<?php }
	
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'happify' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<div class="entry-footer">
		<?php 
		if ( is_single() && 'post' == get_post_type() ) {
			happify_entry_footer(); 
		} ?>
	</div><!-- .entry-footer -->

	<?php 
		if ( !is_single() && 'post' == get_post_type() ) { ?>
	<div class="entry-more">
		 <a href="<?php echo esc_url( get_permalink() ); ?>" class="reammore-btn">
		  	<?php echo esc_html( $happify_readmore_text ); ?>	
		  </a> 
	</div><!-- .entry-more -->
	<?php	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->
