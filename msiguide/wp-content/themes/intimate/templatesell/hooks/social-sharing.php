<?php
/**
 * Social Sharing Hook *
 * @since 1.0.0
 *
 * @param int $post_id
 * @return void
 *
 */
if (!function_exists('intimate_social_sharing')) :
    function intimate_social_sharing($post_id)
    {
        $intimate_url = get_the_permalink($post_id);
        $intimate_title = get_the_title($post_id);
        $intimate_image = get_the_post_thumbnail_url($post_id);
        
        //sharing url
        $intimate_twitter_sharing_url = esc_url('http://twitter.com/share?text=' . $intimate_title . '&url=' . $intimate_url);
        $intimate_facebook_sharing_url = esc_url('https://www.facebook.com/sharer/sharer.php?u=' . $intimate_url);
        $intimate_pinterest_sharing_url = esc_url('http://pinterest.com/pin/create/button/?url=' . $intimate_url . '&media=' . $intimate_image . '&description=' . $intimate_title);
        $intimate_linkedin_sharing_url = esc_url('http://www.linkedin.com/shareArticle?mini=true&title=' . $intimate_title . '&url=' . $intimate_url);
        
        ?>
        <div class="meta_bottom">
            <div class="post-share">
                <a target="_blank" href="<?php echo $intimate_facebook_sharing_url; ?>"><i class="fa fa-facebook"></i></a>
                <a target="_blank" href="<?php echo $intimate_twitter_sharing_url; ?>"><i
                            class="fa fa-twitter"></i></a>
                <a target="_blank" href="<?php echo $intimate_pinterest_sharing_url; ?>"><i
                            class="fa fa-pinterest"></i></a>
                <a target="_blank" href="<?php echo $intimate_linkedin_sharing_url; ?>"><i class="fa fa-linkedin"></i></a>
            </div>
        </div>
        <?php
    }
endif;
add_action('intimate_social_sharing', 'intimate_social_sharing', 10);