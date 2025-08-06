<?php
/**
 * Front page hook for all WordPress Conditions
 *
 * @param null
 * @return null
 *
 * @since Intimate 1.1.0
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!function_exists('intimate_front_page')) :

    function intimate_front_page()
    {

        if (is_active_sidebar('intimate-home-widget-area')) {
            dynamic_sidebar('intimate-home-widget-area');
        }
        global $intimate_theme_options;
        $intimate_front_page_content = $intimate_theme_options['intimate-front-page-content'];

        if ( 1 == $intimate_front_page_content) {
            if ('posts' == get_option('show_on_front')) {
                if (have_posts()) :
                    /* Start the Loop */
                    echo "<div class='intimate_front_loop_block'>";
                    while (have_posts()) : the_post();

                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part('template-parts/content', get_post_format());
                    endwhile;
                    echo '</div>';
                    /**
                     * intimate_action_navigation hook
                     * @since Intimate 1.0.0
                     *
                     * @hooked intimate_action_navigation -  10
                     */

                    do_action( 'intimate_action_navigation');

                else :
                    get_template_part('template-parts/content', 'none');
                endif;
            } else {
                while (have_posts()) : the_post();
                    get_template_part('template-parts/content', 'page');

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                endwhile; // End of the loop.
            }
        }
    }

endif;
add_action('intimate_action_front_page', 'intimate_front_page', 10);

//hooks for the front page grid slider
if (!function_exists('intimate_front_page_grid_slider')) :

    function intimate_front_page_grid_slider()
    {
        global $intimate_theme_options;
        $intimate_grid_cat = absint($intimate_theme_options['intimate-grid-slider-select-category']);
        $intimate_grid_title = esc_html($intimate_theme_options['intimate_title_grid_post_front']);
          $query_args = array(
              'post_type' => 'post',
              'cat' => absint($intimate_grid_cat),
              'posts_per_page' => 5,
              'ignore_sticky_posts' => true
          );
          $query = new WP_Query($query_args); ?>
          <div class="container-fluid">
            <div class="widget mb-0">
              <h2 class="widget-title"><?php echo esc_html($intimate_grid_title); ?></h2>
            </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="grid__slider__carousel mb-4">
                    <?php if ($query->have_posts()) :
                    while ($query->have_posts()) :
                    $query->the_post();
                    ?>
                      <div class="post__grid pr-3 pl-3">
                          <!-- Post Article -->
                          <div class="card__post">
                              <div class="card__post__body">
                                  <?php if(has_post_thumbnail()){ ?>
                                  <a href="<?php the_permalink(); ?>">
                                      <?php the_post_thumbnail('full'); ?>
                                      </a>
                                  <?php }else{ ?>
                                      <div class="no-image-grid"></div>
                                  <?php } ?>
                                  <div class="card__post__content bg__post-cover">
                                      <div class="card__post__category">
                                          <?php
                                             $categories = get_the_category();
                                             if ( ! empty( $categories ) ) {
                                                echo '<a class="s-cat" href="'.esc_url( get_category_link( $categories[0]->term_id ) ).'">'.esc_html( $categories[0]->name ).'</a>';
                                            }                                 
                                          ?>
                                      </div>
                                      <div class="card__post__title">
                                          <h5 class="mb-1">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?></a>
                                          </h5>
                                      </div>
                                      <div class="card__post__author-info mb-2">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">
                                                <?php intimate_posted_by(); ?>
                                            </li>
                                            <li class="list-inline-item">
                                                <span>
                                                    <?php intimate_posted_on(); ?>
                                                </span>
                                            </li>
                                        </ul>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    endif;
                    ?>
               </div>
              </div>
            </div>
          </div>
<?php    }

endif;
add_action('intimate_front_page_grid_slider_hook', 'intimate_front_page_grid_slider', 10);


//hooks for the front page you may have missed
if (!function_exists('intimate_front_page_you_missed')) :

    function intimate_front_page_you_missed()
    {
        global $intimate_theme_options;
        $intimate_missed_cat = absint($intimate_theme_options['intimate-you-missed-select-category']);
        $intimate_missed_title = esc_html($intimate_theme_options['intimate_title_you_missed_post_front']);


            $query_args = array(
                'post_type' => 'post',
                'cat' => absint($intimate_missed_cat),
                'posts_per_page' => 4,
                'ignore_sticky_posts' => true
            );

              $query = new WP_Query($query_args); ?>
           <div class="container-fluid">   
           <div class="widget mb-3">
            <h3 class="widget-title"><?php echo esc_html($intimate_missed_title); ?></h3>
          </div>
          <div class="row">
          <?php if ($query->have_posts()) :
            while ($query->have_posts()) :
            $query->the_post();
          ?>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="post__grid mb-4">
                    <!-- Post Article -->
                    <div class="card__post">
                        <div class="card__post__body">
                            <?php if(has_post_thumbnail()){  ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('full'); ?>
                                </a>
                            <?php }else{ ?>
                              <div class="no-image-missed"></div>
                            <?php } ?>
                            <div class="card__post__content bg__post-cover">
                                <div class="card__post__category">
                                    <?php
                                       $categories = get_the_category();
                                       if ( ! empty( $categories ) ) {
                                          echo '<a class="s-cat" href="'.esc_url( get_category_link( $categories[0]->term_id ) ).'">'.esc_html( $categories[0]->name ).'</a>';
                                      }                                 
                                    ?>
                                </div>
                                
                                <div class="card__post__author-info mb-2">
                                  <ul class="list-inline">
                                      <li class="list-inline-item">
                                          <?php intimate_posted_by(); ?>
                                      </li>
                                      <li class="list-inline-item">
                                          <span>
                                              <?php intimate_posted_on(); ?>
                                          </span>
                                      </li>
                                  </ul>
                                </div>
                                <div class="card__post__title">
                                    <h5 class="mb-1">
                                      <a href="<?php the_permalink(); ?>">
                                          <?php the_title(); ?></a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
              endif;
              ?>
        </div>
        </div> 
<?php    }

endif;
add_action('intimate_front_page_you_missed_hook', 'intimate_front_page_you_missed', 10);