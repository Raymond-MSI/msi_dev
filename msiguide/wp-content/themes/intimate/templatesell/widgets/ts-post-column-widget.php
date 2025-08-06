<?php
/**
 * Intimate post Slider Widget
 *
 * @since 1.0.0
 */
if (!class_exists('Intimate_Post_Column')) :

    /**
     * Highlight Post widget class.
     *
     * @since 1.0.0
     */
    class Intimate_Post_Column extends WP_Widget
    {

         private function defaults()
        {
            $defaults = array(
            'title' => esc_html__('Post Column', 'intimate' ),
            'cat' => 0,
            'post-number' => 5,
            'excerpt-length'=> 15,
           );
        return $defaults;
        }

        function __construct()
        {
            $opts = array(
                'classname' => 'intimate-post-column',
                'description' => esc_html__('Display post in Column Form. Suitable on home page widget.', 'intimate'),
            );

            parent::__construct('intimate-post-column', esc_html__('Intimate Post Column', 'intimate'), $opts);
        }


        function widget($args, $instance)
        {
          $instance = wp_parse_args( (array) $instance, $this->defaults() );

            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

            echo $args['before_widget'];

            if (!empty($title)) {
                echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
            }
            $cat_id = !empty($instance['cat']) ? $instance['cat'] : '';

            $post_number = !empty($instance['post-number']) ? $instance['post-number'] : '';
            $excerpt_length = !empty($instance['excerpt-length']) ? $instance['excerpt-length'] : '';

            $query_args = array(
                'post_type' => 'post',
                'cat' => absint($cat_id),
                'posts_per_page' => absint($post_number),
                'ignore_sticky_posts' => true
            );

              $query = new WP_Query($query_args); ?>
          <div class="row">
            <div class="col-lg-12">
                <div class="post__column mb-5">
                     <?php if ($query->have_posts()) :
                      while ($query->have_posts()) :
                          $query->the_post();
                          ?>
                    <!-- Post Article -->
                    <div class="card__post mb-3">
                      <div class="row">
                        <?php if(has_post_thumbnail()){ ?>
                          <div class="col-lg-5 col-md-12 col-sm-12 my-auto">
                            <a href="<?php the_permalink(); ?>">
                                  <?php the_post_thumbnail('large'); ?>
                            </a>
                          </div>
                          <div class="col-lg-7 col-md-12 col-sm-12 my-auto">
                            <div class="card__post__body">
                                <div class="card__post__content ">
                                    <div class="card__post__author-info mb-2">
                                      <?php intimate_list_category(get_the_ID()); ?>
                                    </div>
                                    <div class="card__post__title">
                                        <h3 class="mb-2">
                                          <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?></a>
                                        </h3>
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
                                    <div class="card__post__text mb-2">
                                        <?php echo wp_trim_words(get_the_content(),$excerpt_length); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="readmore mt-2"><?php esc_html_e('Read More', 'intimate'); ?></a>
                                </div>
                            </div>
                          </div>
                          <?php } else{ ?>
                          <div class="col-lg-12 col-md-12 col-sm-12 my-auto">
                            <div class="card__post__body">
                                <div class="card__post__content ">
                                    <div class="card__post__author-info mb-2">
                                      <?php intimate_list_category(get_the_ID()); ?>
                                    </div>
                                    <div class="card__post__title">
                                        <h3 class="mb-2">
                                          <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?></a>
                                        </h3>
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
                                    <div class="card__post__text mb-2">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="readmore mt-2"><?php esc_html_e('Read More', 'intimate'); ?></a>
                                </div>
                            </div>
                          </div>    
                          <?php } ?>
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
        <?php 
    }

    function update($new_instance, $old_instance)
    {
         $instance = $old_instance;

        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['cat'] = absint($new_instance['cat']);
        $instance['post-number'] = absint($new_instance['post-number']);
        $instance['excerpt-length'] = absint($new_instance['excerpt-length']);

        return $instance;

    }

    function form($instance)
    {
        
         $instance  = wp_parse_args( (array )$instance, $this->defaults() );
            ?>
        <p>
                <label
                    for="<?php echo esc_attr($this->get_field_id('title')); ?>"><strong><?php esc_html_e('Title:', 'intimate'); ?></strong></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($instance['title']); ?>"/>
            </p>
        <p class="custom-dropdown-posts">
            <label for="<?php echo esc_attr($this->get_field_name('cat')); ?>">
                <strong><?php esc_html_e('Select Category: ', 'intimate'); ?></strong>
            </label>
            <?php
            $cat_args = array(
                'orderby' => 'name',
                'hide_empty' => 0,
                'id' => $this->get_field_id('cat'),
                'name' => $this->get_field_name('cat'),
                'class' => 'widefat',
                'taxonomy' => 'category',
                'selected' => absint($instance['cat']),
                'show_option_all' => esc_html__('Show Recent Posts', 'intimate')
            );
            wp_dropdown_categories($cat_args);
            ?>
        </p>

        <p>
            <label
            for="<?php echo esc_attr($this->get_field_id('post-number')); ?>"><?php esc_html_e('Number of Posts to Display:', 'intimate'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('post-number')); ?>"
            name="<?php echo esc_attr($this->get_field_name('post-number')); ?>" type="number"
            value="<?php echo esc_attr($instance['post-number']); ?>"/>
        </p>
        <p>
        <label
        for="<?php echo esc_attr($this->get_field_id('excerpt-length')); ?>"><?php esc_html_e('Excerpt Length', 'intimate'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('excerpt-length')); ?>"
        name="<?php echo esc_attr($this->get_field_name('excerpt-length')); ?>" type="number"
        value="<?php echo esc_attr($instance['excerpt-length']); ?>"/>
    </p>

        <?php
    }
}

endif;