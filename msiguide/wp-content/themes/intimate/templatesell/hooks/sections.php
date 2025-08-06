<?php
/**
 * Custom theme hooks
 *
 * This file contains hook functions attached to theme hooks.
 *
 * @package Intimate
 */
if (!function_exists('intimate_add_main_header')) :
    
    /**
     * Add main header.
     *
     * @since 1.0.0
     */
    function intimate_add_main_header()
    {
        get_template_part('template-parts/sections/header', 'section');        
    }
endif;
add_action('intimate_action_header', 'intimate_add_main_header', 10);

/**
 * Custom theme hook for slider
 *
 * This file contains hook functions attached to theme hooks.
 *
 * @package Intimate
 */
if (!function_exists('intimate_add_main_slider')) :
    
    /**
     * Add main slider.
     *
     * @since 1.0.0
     */
    function intimate_add_main_slider()
    {
        
        get_template_part('template-parts/sections/slider', 'section');
    }
endif;
add_action('intimate_action_slider', 'intimate_add_main_slider', 10);

/**
 * Custom theme hook for trending news
 *
 * This file contains hook functions attached to theme hooks.
 *
 * @package Intimate
 */
if (!function_exists('intimate_add_main_trending')) :
    
    /**
     * Add main Trending.
     *
     * @since 1.0.0
     */
    function intimate_add_main_trending()
    {
        
        get_template_part('template-parts/sections/trending', 'section');
    }
endif;
add_action('intimate_action_trending', 'intimate_add_main_trending', 10);

/**
 * Custom theme hook for promo section
 *
 * This file contains hook functions attached to theme hooks.
 *
 * @package Intimate
 */
if (!function_exists('intimate_boxes_section')) :
    
    /**
     * Add main slider.
     *
     * @since 1.0.0
     */
    function intimate_boxes_section()
    {       
        
        get_template_part('template-parts/sections/boxes', 'section');
    }
endif;
add_action('intimate_action_boxes', 'intimate_boxes_section', 10);

//only for blog and archive page
if( !function_exists( 'intimate_blog_sidebar_position_array' ) ) :
    /*
     * Function to get blog categories
     */
    function intimate_blog_sidebar_position_array() {

        $sidebar_positions = array(
            'right-sidebar'  => get_template_directory_uri() . '/assets/images/right-sidebar.png',
            'left-sidebar' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
            'no-sidebar'  => get_template_directory_uri() . '/assets/images/no-sidebar.png',
            'middle-column'  => get_template_directory_uri() . '/assets/images/middle-content.png',
        );
        
        return $sidebar_positions;

    }
endif;


//only for single page
if( !function_exists( 'intimate_sidebar_position_array' ) ) :
    /*
     * Function to get blog categories
     */
    function intimate_sidebar_position_array() {

        $sidebar_positions = array(
            'single-right-sidebar'  => get_template_directory_uri() . '/assets/images/right-sidebar.png',
            'single-left-sidebar' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
            'single-no-sidebar'  => get_template_directory_uri() . '/assets/images/no-sidebar.png',
            'single-middle-column'  => get_template_directory_uri() . '/assets/images/middle-content.png',
        );
        
        return $sidebar_positions;

    }
endif;