<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Intimate
 */
global $intimate_theme_options;
$social_share = absint($intimate_theme_options['intimate-single-social-share']);
$image = absint($intimate_theme_options['intimate-single-page-featured-image']);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-wrap">
        <div class="post-cats">
                <?php intimate_entry_meta(); ?>
            </div>
            <?php
            if (is_singular()) :
                the_title('<h1 class="post-title entry-title">', '</h1>');
            else :
                the_title('<h2 class="post-title entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                ?>
            <?php endif; ?>
            <div class="post-date mb-4">
                <?php
                if ('post' === get_post_type()) :
                    ?>
                    <div class="entry-meta">
                        <?php
                        intimate_posted_by();
                        intimate_posted_on();
                        ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>
            </div>
        <?php if($image == 1 ){ ?>
            <div class="post-media">
                <?php intimate_post_thumbnail(); ?>
            </div>
        <?php } ?>
        <div class="post-content">
            

            <div class="content post-excerpt entry-content clearfix">
                <?php
                the_content(sprintf(
                    wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'intimate'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                
                ));
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'intimate'),
                    'after' => '</div>',
                
                ));
                ?>
            </div><!-- .entry-content -->
            <footer class="post-footer entry-footer">
                <?php 
                if( 1 == $social_share ){
                    do_action( 'intimate_social_sharing' ,get_the_ID() );
                }
                ?>
            </footer><!-- .entry-footer -->
            <?php the_post_navigation(); ?>
            <div class="col-lg-12">
                <?php 
                /**
                 * intimate_related_posts hook
                 * @since Intimate 1.0.0
                 *
                 * @hooked intimate_related_posts -  10
                 */
                do_action( 'intimate_related_posts' ,get_the_ID() );
                ?>
            </div>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->