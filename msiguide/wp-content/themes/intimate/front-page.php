<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Intimate
 */

get_header();
?>
<section id="content" class="site-content">
    <div class="container-fluid">
        <div class="row">
            <div id="primary" class="col-lg-9 col-md-7 col-sm-12 content-area">
                <main id="main" class="site-main">
                <?php
                /**
                 * intimate_action_front_page hook
                 * @package Intimate
                 *
                 * @hooked intimate_featured_section -  10
                 */
                do_action('intimate_action_front_page');
                ?>
            </main><!-- #main -->
            </div><!-- #primary -->

        <aside id="secondary" class="col-lg-3 col-md-5 col-sm-12 widget-area side-right">
            <?php get_sidebar(); ?>
        </aside><!-- #secondary -->
    </div> <!-- .front-page-content-wrapper -->
</div>
</section>
<?php

get_footer();