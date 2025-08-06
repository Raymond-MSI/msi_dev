<?php
/**
 * Dynamic css
 *
 * @since Intimate 1.0.0
 *
 * @param null
 * @return null
 *
 */
if (!function_exists('intimate_dynamic_css')) :

    function intimate_dynamic_css()
    {
        global $intimate_theme_options;

        /* Color Options Options */
        $intimate_primary_color              = esc_attr($intimate_theme_options['intimate_primary_color']);
        $intimate_logo_width              = absint($intimate_theme_options['intimate_logo_width_option']);
        
        $intimate_header_overlay  = esc_attr($intimate_theme_options['intimate_slider_overlay_color']);
        $intimate_header_transparent  = esc_attr($intimate_theme_options['intimate_slider_overlay_transparent']);
        $intimate_header_min_height              = absint($intimate_theme_options['intimate_header_image_height']);

        $intimate_side_width             = absint($intimate_theme_options['intimate_container_width_options']);

        $custom_css = '';

        //Primary  Background 
        if (!empty($intimate_primary_color)) {
            $custom_css .= "
            #toTop,
            .trending-news .trending-news-inner .title,
            .tab__wrapper .tabs-nav li,
            .title-highlight:before,
            .card__post__category a,
            .slide-wrap .caption .post-category,
            .intimate-home-icon a,
            span.menu-description,
            a.effect:before,
            .widget .widget-title:before, 
            .widget .widgettitle:before,
            .show-more,
            a.link-format,
            .tabs-nav li.current,
            .post-slider-section .s-cat,
            .sidebar-3 .widget-title:after,
            .bottom-caption .slick-current .slider-items span,
            aarticle.format-status .post-content .post-format::after,
            article.format-chat .post-content .post-format::after, 
            article.format-link .post-content .post-format::after,
            article.format-standard .post-content .post-format::after, 
            article.format-image .post-content .post-format::after, 
            article.hentry.sticky .post-content .post-format::after, 
            article.format-video .post-content .post-format::after, 
            article.format-gallery .post-content .post-format::after, 
            article.format-audio .post-content .post-format::after, 
            article.format-quote .post-content .post-format::after{ 
                background-color: ". $intimate_primary_color."; 
                border-color: ".$intimate_primary_color.";
            }";

        }

        //Primary Color
        if (!empty($intimate_primary_color)) {
            $custom_css .= "
            a:hover,
            .post__grid .cat-links a,
            .card__post__author-info .cat-links a,
            .post-cats > span i, 
            .post-cats > span a,
            .top-menu > ul > li > a:hover,
            .main-menu ul li.current-menu-item > a, 
            .header-2 .main-menu > ul > li.current-menu-item > a,
            .main-menu ul li:hover > a,
            .post-navigation .nav-links a:hover, 
            .post-navigation .nav-links a:focus,
            ul.trail-items li a:hover span,
            .author-socials a:hover,
            .post-date a:focus, 
            .post-date a:hover,
            .post-excerpt a:hover, 
            .post-excerpt a:focus, 
            .content a:hover, 
            .content a:focus,
            .post-footer > span a:hover, 
            .post-footer > span a:focus,
            .widget a:hover, 
            .widget a:focus,
            .footer-menu li a:hover, 
            .footer-menu li a:focus,
            .footer-social-links a:hover,
            .footer-social-links a:focus,
            .site-footer a:hover, 
            .site-footer a:focus, .content-area p a{ 
                color : ". $intimate_primary_color."; 
            }";
        }
        // Border Color
        if (!empty($intimate_primary_color)) {
            $custom_css .= "
            span.menu-description:before{ 
                border-color: transparent  ".$intimate_primary_color."; 
            }";
        }

        //Logo Width
        if (!empty($intimate_logo_width)) {
            $custom_css .= "
            .header-1 .head_one .logo{ 
                max-width : ". $intimate_logo_width."px; 
            }";
        }

        //Header Overlay
        if (!empty($intimate_header_overlay)) {
            $custom_css .= "
            .header-image:before { 
                background-color : ". $intimate_header_overlay."; 
            }";
        }

        //Header Tranparent
        if (!empty($intimate_header_transparent)) {
            $custom_css .= "
            .header-image:before { 
                opacity : ". $intimate_header_transparent."; 
            }";
        }

        //Header Min Height
        if (!empty($intimate_header_min_height)) {
            $custom_css .= "
            .header-1 .header-image .head_one { 
                min-height : ". $intimate_header_min_height."px; 
            }";
        }

        //Header Min Height
        if (!empty($intimate_side_width)) {
            $custom_css .= "
            .container-fluid { 
                width : ". $intimate_side_width."%; 
            }";
        }

        wp_add_inline_style('intimate-style', $custom_css);
    }
endif;
add_action('wp_enqueue_scripts', 'intimate_dynamic_css', 99);