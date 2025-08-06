<?php
/**
 * Intimate Theme Customizer
 *
 * @package Intimate
 */

if ( !function_exists('intimate_default_theme_options_values') ) :

    function intimate_default_theme_options_values() {

        $default_theme_options = array(

            /*Logo Options*/
            'intimate_logo_width_option' => '600',
            'intimate-logo-position'=>'left-logo',

            /*Top Header*/
            'intimate_enable_top_header'=> 1, 
            'intimate_enable_top_header_social'=> 0,
            'intimate_enable_top_trending'=> 1,
            'intimate_enable_top_date'=> 1,

            /*Header Image*/
            'intimate_enable_header_image_overlay'=> 0,
            'intimate_slider_overlay_color'=> '#000000',
            'intimate_slider_overlay_transparent'=> '0.1',
            'intimate_header_image_height'=> '100',

           /*Header Options*/
            'intimate_enable_offcanvas'  => 1,
            'intimate_enable_search'  => 0,
            'intimate_enable_header_ads'=> 0,
            'intimate-header-ads-image'=>'',
            'intimate-header-ads-image-link'=>'',
            'intimate_enable_trending_news_big'=> 1,
            'intimate-select-big-trending-category'=> 0,


            /*Front Page Options*/
            'intimate_enable_slider'      => 1,
            'intimate-select-category'    => 0,
            'intimate-select-category-slider-right'=> 0,
            'intimate_enable_promo'       => 1,
            'intimate-promo-select-category'=> 0,
            'intimate_highlights_title'=> esc_html__('Today Highlights','intimate'),
            'intimate-select-category-trending'=> 0,
            'intimate_enable_grid_post_front'=> 1,
            'intimate_title_grid_post_front'=> esc_html__('Grid Posts Slider','intimate'),
            'intimate-grid-slider-select-category'=> 0,
            'intimate_enable_missed_post_front'=> 1,
            'intimate_title_you_missed_post_front'=> esc_html__('You May Have Missed','intimate'),
            'intimate-you-missed-select-category'=> 0, 

            /*Colors Options*/
            'intimate_primary_color'              => '#d42929',
 
            /*Blog Page*/
            'intimate-sidebar-blog-page' => 'right-sidebar',
            'intimate-content-show-from' => 'excerpt',
            'intimate-excerpt-length'    => 15,
            'intimate-pagination-options'=> 'numeric',
            'intimate-blog-exclude-category'=> '',
            'intimate-read-more-text'    => '',
            'intimate-show-hide-share'   => 1,
            'intimate-show-hide-category'=> 1,
            'intimate-show-hide-date'=> 1,
            'intimate-show-hide-author'=> 1,

            /*Single Page */
            'intimate-single-page-featured-image' => 1,
            'intimate-single-page-related-posts'  => 0,
            'intimate-single-page-related-posts-title' => esc_html__('Related Posts','intimate'),
            'intimate-sidebar-single-page'=> 'single-right-sidebar',
            'intimate-single-social-share' => 1,

            /*Site Layout Options*/
            'intimate_container_width_options' => 100,


            /*Sticky Sidebar*/
            'intimate-enable-sticky-sidebar' => 1,

            /*Footer Section*/
            'intimate-footer-copyright'  => esc_html__('Copyright All Rights Reserved 2020','intimate'),

            /*Breadcrumb Options*/
            'intimate-extra-breadcrumb' => 1,

            /*Miscellaneous Options*/
            'intimate-front-page-content'=> 1,
            'intimate-breadcrumb-selection-option'=> 'theme-breadcrumb',

        );
return apply_filters( 'intimate_default_theme_options_values', $default_theme_options );
}
endif;
/**
 *  Intimate Theme Options and Settings
 *
 * @since Intimate 1.0.0
 *
 * @param null
 * @return array intimate_get_options_value
 *
 */
if ( !function_exists('intimate_get_options_value') ) :
    function intimate_get_options_value() {
        $intimate_default_theme_options_values = intimate_default_theme_options_values();
        $intimate_get_options_value = get_theme_mod( 'intimate_options');
        if( is_array( $intimate_get_options_value )){
            return array_merge( $intimate_default_theme_options_values, $intimate_get_options_value );
        }
        else{
            return $intimate_default_theme_options_values;
        }
    }
endif;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function intimate_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
    if ( isset( $wp_customize->selective_refresh ) ) {
      $wp_customize->selective_refresh->add_partial( 'blogname', array(
         'selector'        => '.site-title a',
         'render_callback' => 'intimate_customize_partial_blogname',
     ) );
      $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
         'selector'        => '.site-description',
         'render_callback' => 'intimate_customize_partial_blogdescription',
     ) );
  }
  $default = intimate_default_theme_options_values();
  $GLOBALS['intimate_theme_options'] = intimate_get_options_value();
  require get_template_directory() . '/templatesell/theme-settings/theme-settings.php';
  require get_template_directory() . '/templatesell/theme-settings/front-page-options.php';

  /*Getting Home Page Widget Area on Main Panel*/
    $intimate_home_section = $wp_customize->get_section( 'sidebar-widgets-intimate-home-widget-area' );
    if ( ! empty( $intimate_home_section ) ) {
        $intimate_home_section->panel = 'intimate_front_page';
        $intimate_home_section->title = esc_html__( 'Front Page Widgets', 'intimate' );
        $intimate_home_section->priority = 25;
    }
    /*Getting After Slider Widget Area*/
    $intimate_below_slider_section = $wp_customize->get_section( 'sidebar-widgets-below-slider-area' );
    if ( ! empty( $intimate_below_slider_section ) ) {
        $intimate_below_slider_section->panel = 'intimate_front_page';
        $intimate_below_slider_section->title = esc_html__( 'Widget Area Below Slider', 'intimate' );
        $intimate_below_slider_section->priority = 24;
    }

    /*Getting After Slider Widget Area*/
    $intimate_before_footer_section = $wp_customize->get_section( 'sidebar-widgets-before-footer-area' );
    if ( ! empty( $intimate_before_footer_section ) ) {
        $intimate_before_footer_section->panel = 'intimate_front_page';
        $intimate_before_footer_section->title = esc_html__( 'Widget Area Before Footer', 'intimate' );
        $intimate_before_footer_section->priority = 26;
    }

}
add_action( 'customize_register', 'intimate_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function intimate_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function intimate_customize_partial_blogdescription() {
	bloginfo( 'description' );
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function intimate_customize_preview_js() {
	wp_enqueue_script( 'intimate-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20200412', true );
}
add_action( 'customize_preview_init', 'intimate_customize_preview_js' );

/*
** Customizer Styles
*/
function intimate_panels_css() {
     wp_enqueue_style('intimate-customizer-css', get_template_directory_uri() . '/css/customizer-style.css', array(), '4.5.0');
}
add_action( 'customize_controls_enqueue_scripts', 'intimate_panels_css' );