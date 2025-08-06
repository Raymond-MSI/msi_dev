<?php
/**
 * Intimate post Slider Widget
 *
 * @since 1.0.0
 */
if (!class_exists('Intimate_Post_Grid')) :

    /**
     * Highlight Post widget class.
     *
     * @since 1.0.0
     */
    class Intimate_Post_Grid extends WP_Widget
    {

         private function defaults()
        {
            $defaults = array(
            'title' => esc_html__('Grid Posts', 'intimate' ),
            'cat' => 0,
            'post-number' => 6,
            'excerpt-length'=> 15,
           );
        return $defaults;
        }

        function __construct()
        {
            $opts = array(
                'classname' => 'intimate-post-grid',
                'description' => esc_html__('Display post in Grid Form. Suitable on Sidebars.', 'intimate'),
            );

            parent::__construct('intimate-post-grid', esc_html__('Intimate Post Grid', 'intimate'), $opts);
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
          <?php if ($query->have_posts()) :
                      while ($query->have_posts()) :
                          $query->the_post();
                          ?>
            
            <div class="col-lg-4">
                <div class="post__grid mb-3">
                    <!-- Post Article -->
                    <div class="card__post">
                        <div class="card__post__body">
                            <?php if(has_post_thumbnail()){ ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('full'); ?>
                            </a>
                            <?php } else{ ?>
                              <div class="no-image"></div>
                            <?php } ?>
                            <div class="card__post__content ">
                                <div class="card__post__author-info mb-2">
                                    <?php intimate_list_category(get_the_ID()); ?>
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
                                <div class="card__post__text">
                                     <?php echo wp_trim_words(get_the_content(),$excerpt_length); ?>
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