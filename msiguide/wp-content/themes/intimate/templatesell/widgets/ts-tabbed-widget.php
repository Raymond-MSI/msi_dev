<?php
/**
 * Intimate post Slider Widget
 *
 * @since 1.0.0
 */
if (!class_exists('Intimate_Tabbed')) :

    /**
     * Highlight Post widget class.
     *
     * @since 1.0.0
     */
    class Intimate_Tabbed extends WP_Widget
    {
        private function defaults()
        {
            $defaults = array(
                'title' => esc_html__('Recent Post', 'intimate' ),
                'popular_title'=> esc_html__('Popular', 'intimate' ),
                'recent_title'=> esc_html__('Recent', 'intimate' ),
                'random'=> esc_html__('Random', 'intimate' ),
                'post-number' => 5,
           );
            return $defaults;
        }

        function __construct()
        {
            $opts = array(
                'classname' => 'intimate-tabbed',
                'description' => esc_html__('It will help to display the popular and Recent Post Via Tabbed. Suitable on Sidebars.', 'intimate'),
            );

            parent::__construct('intimate-tabbed', esc_html__('Intimate Tabbed', 'intimate'), $opts);
        }


        function widget($args, $instance)
        {

            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
            
            echo $args['before_widget'];


            if (!empty($title)) {
                echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
            }
            $popular_title = !empty($instance['popular_title']) ? $instance['popular_title'] : '';
            $recent_title = !empty($instance['recent_title']) ? $instance['recent_title'] : '';
            $random = !empty($instance['random']) ? $instance['random'] : '';
            $post_number = !empty($instance['post-number']) ? $instance['post-number'] : '';

            ?>
            
            <ul id="tab_second" class="tabs-nav">
                <?php if(!empty($popular_title)){ ?>
                <li class="current" data-tab="TAB1">
                    <button><i class="fa fa-fire"></i><?php echo esc_html($popular_title); ?></button>
                </li>
                <?php } ?>
                <?php if(!empty($recent_title)){ ?>
                <li  data-tab="TAB2">
                    <button><i class="fa fa-clock-o"></i><?php echo esc_html($recent_title); ?></button>
                </li>
                <?php } ?>
                <?php if(!empty($random)){ ?>
                <li data-tab="TAB3">
                    <button><i class="fa fa-random"></i><?php echo esc_html($random); ?></button>
                </li>
                <?php } ?>
            </ul>

            <div class="tab-content">
                <?php if(!empty($popular_title)) { ?>
                    <div id="TAB1" class="tab-block current">
                        <section class="tab-posts-block">
                            <?php
                            $p_query_args = array(
                                'post_type' => 'post',
                                'posts_per_page' => absint($post_number),
                                'ignore_sticky_posts' => true,
                                'orderby' => 'comment_count'
                            );
                            ?> 
                            <ul class="list-unstyled">
                            <?php
                            $i = 1;
                            $p_query = new WP_Query($p_query_args);
                            if ($p_query->have_posts()) :
                                while ($p_query->have_posts()) :
                                    ?>
                                    <li>
                                        <?php
                                        $p_query->the_post();
                                        if (has_post_thumbnail()) {
                                            ?>
                                            
                                            <figure class="widget_featured_thumbnail">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </a>
                                            </figure>
                                            <?php
                                        }
                                        ?>
                                        <div class="widget_featured_content">
                                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <div class="post-date">
                                                <?php echo get_the_date(); ?>
                                            </div><!-- .entry-meta -->
                                        </div>
                                    </li>
                                    <?php
                                    $i++;
                                endwhile;
                                wp_reset_postdata();
                            endif;?></ul><?php
                            ?>
                        </section>
                    </div>
                <?php } ?>
                <?php if(!empty($recent_title)) { ?>
                    <div id="TAB2" class=" tab-block">
                        <section class="tab-posts-block">
                            <?php
                            $query_args = array(
                                'post_type' => 'post',
                                'posts_per_page' => absint($post_number),
                                'ignore_sticky_posts' => true
                            );
                            ?> 
                            <ul class="list-unstyled">
                            <?php
                            $query = new WP_Query($query_args);
                            $query = new WP_Query($query_args);
                            if ($query->have_posts()) :
                                while ($query->have_posts()) :
                                     ?>
                                    <li>
                                        <?php
                                        $query->the_post();
                                        if (has_post_thumbnail()) {
                                            ?>
                                                <figure class="widget_featured_thumbnail">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('thumbnail'); ?>
                                                    </a>
                                                </figure>
                                            <?php
                                        }
                                        ?>
                                        <div class="widget_featured_content">
                                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <div class="post-date">
                                                <?php echo get_the_date(); ?>
                                            </div><!-- .entry-meta -->
                                        </div>
                                    </li>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                            endif; ?></ul><?php
                            ?>
                        </section>                    
                    </div>
                <?php } ?>
                <?php if(!empty($random)) { ?>
                    <div id="TAB3" class="tab-block">
                        <section class="tab-posts-block">
                            <?php
                            $c_query_args = array(
                                'post_type' => 'post',
                                'posts_per_page' => absint($post_number),
                                'ignore_sticky_posts' => true,
                                'orderby' => 'rand',
                            );
                            ?> 
                            <ul class="list-unstyled">
                            <?php
                            $c_query = new WP_Query($c_query_args);
                            if ($c_query->have_posts()) :
                                while ($c_query->have_posts()) :
                                     ?>
                                    <li>
                                        <?php
                                        $c_query->the_post();
                                        if (has_post_thumbnail()) {
                                            ?>
                                             <figure class="widget_featured_thumbnail">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </a>
                                            </figure>
                                            <?php
                                        }
                                        ?>
                                        <div class="widget_featured_content">
                                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <div class="post-date">
                                                <?php echo get_the_date(); ?>
                                            </div><!-- .entry-meta -->
                                        </div>
                                    </li>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                            endif; ?></ul><?php
                            ?>
                        </section>
                    </div>
                <?php } ?>
            </div>

            <?php     
            echo $args['after_widget'];            
        }

        function update($new_instance, $old_instance)
        {
            $instance = $old_instance;

            $instance['title'] = sanitize_text_field($new_instance['title']);
            $instance['popular_title'] = sanitize_text_field($new_instance['popular_title']);
            $instance['recent_title'] = sanitize_text_field($new_instance['recent_title']);
            $instance['random'] = sanitize_text_field($new_instance['random']);
            $instance['post-number'] = absint($new_instance['post-number']);

            return $instance;
        }

        function form($instance)
        {
            
            $instance  = wp_parse_args( (array )$instance, $this->defaults() );
            ?>
            <p>
                <label
                for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget Title:', 'intimate'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                value="<?php echo esc_attr($instance['title']); ?>"/>
            </p>
            <p>
                <label
                for="<?php echo esc_attr($this->get_field_id('popular_title')); ?>"><?php esc_html_e('Popular Title:', 'intimate'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('popular_title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('popular_title')); ?>" type="text"
                value="<?php echo esc_attr($instance['popular_title']); ?>"/>
            </p>
            <p>
                <label
                for="<?php echo esc_attr($this->get_field_id('recent_title')); ?>"><?php esc_html_e('Recent Title:', 'intimate'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('recent_title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('recent_title')); ?>" type="text"
                value="<?php echo esc_attr($instance['recent_title']); ?>"/>
            </p>
            <p>
                <label
                for="<?php echo esc_attr($this->get_field_id('random')); ?>"><?php esc_html_e('Commented Title:', 'intimate'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('random')); ?>"
                name="<?php echo esc_attr($this->get_field_name('random')); ?>" type="text"
                value="<?php echo esc_attr($instance['random']); ?>"/>
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