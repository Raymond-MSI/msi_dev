<?php
/**
 * Intimate post Slider Widget
 *
 * @since 1.0.0
 */
if (!class_exists('Intimate_Latest_Post')) :

    /**
     * Highlight Post widget class.
     *
     * @since 1.0.0
     */
    class Intimate_Latest_Post extends WP_Widget
    {

     private function defaults()
     {
      $defaults = array(
        'title' => esc_html__('Latest Posts', 'intimate' ),
        'cat' => 0,
        'post-number' => 5,
      );
      return $defaults;
    }

    function __construct()
    {
      $opts = array(
        'classname' => 'intimate-latest-post',
        'description' => esc_html__('Display lastest post and from category.', 'intimate'),
      );

      parent::__construct('intimate-latest-post', esc_html__('Intimate Latest Posts', 'intimate'), $opts);
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

      $query_args = array(
        'post_type' => 'post',
        'cat' => absint($cat_id),
        'posts_per_page' => absint($post_number),
        'ignore_sticky_posts' => true
      );

      $query = new WP_Query($query_args); ?>
      <?php if ($query->have_posts()) :
        $i = 1;
        ?>
        <div class="row">
          <div class="col-lg-12">
            <div class="latest__post">
              <div class="row">
                <div class="col-lg-7 col-md-12 col-sm-12 mb-3">
                <?php while ($query->have_posts()) :
                  $query->the_post(); ?>
                  <?php if($i <= 1 ){ ?>                    
                      <!-- Post Article -->
                      <div class="card__post">
                        <div class="card__post__body">
                          <?php if(has_post_thumbnail()){ ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large'); ?>
                            </a>
                            <?php } else{ ?>
                              <div class="no-image"></div>
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
                              <h3 class="mb-2">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                              </h3>
                            </div>
                              <div class="card__post__author-info mb-2">
                                <ul class="list-inline">
                                  <li class="list-inline-item">
                                    <a href="#">
                                      <?php intimate_posted_by(); ?>
                                    </a>
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
                    <div class="col-lg-5 col-md-12 col-sm-12">
                      <?php }else{ ?>
                        <!-- Post Article -->
                        <div class="card__post card__post-list mb-3">
                          <?php if(has_post_thumbnail()){ ?>
                          <div class="image-sm my-auto">
                              <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </a>
                          </div><?php } else{ ?>
                            <div class="no-image"></div>
                          <?php } ?>
                          <div class="card__post__body my-auto">
                            <div class="card__post__content">
                              <div class="card__post__author-info mb-1">
                                <?php intimate_list_category(get_the_ID()); ?>
                              </div>
                              <div class="card__post__title">
                              <h6 class="mb-1">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                              </h6>
                              </div>
                              <div class="card__post__author-info">
                                <ul class="list-inline">
                                  <li class="list-inline-item">
                                    <span class="text-primary">
                                     <?php intimate_posted_by(); ?>
                                   </span>
                                 </li>
                                 <li class="list-inline-item">
                                  <span class="text-dark text-capitalize">
                                    <?php intimate_posted_on(); ?>
                                  </span>
                                </li>
                              </ul>
                            </div>
                            </div>
                          </div>
                        </div>
                    <?php } ?>
                    <?php $i++;
                  endwhile;
                  wp_reset_postdata();
                  ?>
                </div>
              </div>
              </div>
            </div>
          </div> 
          <?php 
        endif;
        echo $args['after_widget'];
      }

      function update($new_instance, $old_instance)
      {
       $instance = $old_instance;

       $instance['title'] = sanitize_text_field($new_instance['title']);

       $instance['cat'] = absint($new_instance['cat']);

       $instance['post-number'] = absint($new_instance['post-number']);

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

      <?php
    }
  }

endif;