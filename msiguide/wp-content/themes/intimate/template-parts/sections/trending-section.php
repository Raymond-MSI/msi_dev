<?php 
/**
 * Intimate Trending Slide Function
 * @since Intimate 1.0.0
 *
 * @param null
 * @return void
 *
 */
global $intimate_theme_options;
$trending_id = absint($intimate_theme_options['intimate-select-big-trending-category']);
      $args = array(
			'posts_per_page' => 9,
			'cat' => $trending_id,
      'ignore_sticky_posts' => true,
			'post_type' => 'post'
		);
?>	
    <!-- Tranding news  carousel-->
<section class="trending-news-two">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card__post__slider">
          <?php
          $trending_query = new WP_Query($args);
          if ( $trending_query->have_posts()):
          while($trending_query->have_posts())
          : $trending_query->the_post(); ?>
            <div class="item">
              <!-- Post Article -->
              <div class="card__post card__post-list">
                <?php if(has_post_thumbnail()){ ?>
                <div class="image-sm my-auto">
                  <a href="<?php the_permalink();?>">
                    <?php the_post_thumbnail('thumbnail'); ?>
                  </a>
                </div>
                <?php }else{  ?>
                  <div class="image-sm my-auto no-image-trending">
                  </div>
                <?php } ?>
                
                <div class="card__post__body my-auto">
                  <div class="card__post__content">
                    <div class="card__post__title">
                      <h6 class="mb-1">
                        <a href="<?php the_permalink();?>">
                          <?php the_title();?>
                        </a>
                      </h6>
                    </div>
                    <div class="card__post__author-info">
                      <ul class="list-inline">
                        <li class="list-inline-item">
                          <?php intimate_posted_on(); ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Tranding news carousel -->