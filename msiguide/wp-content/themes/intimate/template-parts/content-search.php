<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Intimate
 */
global $intimate_theme_options;
$category = absint($intimate_theme_options['intimate-show-hide-category']);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-wrap">
        <?php if(has_post_thumbnail()) { ?>
            <div class="post-media">
                <?php intimate_post_thumbnail(); ?>
            </div>
        <?php } ?>
        <div class="post-content">
            <?php if($category == 1 ){ ?>
                <div class="post-cats">
                    <?php intimate_entry_meta(); ?>
                </div>
            <?php } ?>

            <div class="date_title">
                
                <?php the_title(sprintf('<h5 class="post-title entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h5>'); ?>
            </div>
            <div class="post-date">
                    <?php if ('post' === get_post_type()) : ?>
                        <div class="entry-meta">
                            <?php
                            intimate_posted_on();
                            intimate_posted_by();
                            ?>
                        </div><!-- .entry-meta -->
                    <?php endif; ?>
                </div>
            <div class="post-excerpt entry-summary">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->

            <footer class="post-footer entry-footer">
                <?php do_action( 'intimate_social_sharing' ,get_the_ID() );?>
            </footer><!-- .entry-footer -->
        </div>
    </div>
</article><!-- #post-->

