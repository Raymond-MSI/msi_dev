<?php
/*Header Options*/
$wp_customize->add_section('intimate_header_section', array(
    'priority' => 20,
    'capability' => 'edit_theme_options',
    'theme_supports' => '',
    'title' => __('Header Settings', 'intimate'),
    'panel' => 'intimate_panel',
));


/*Header Search Enable Option*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_search]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_enable_search'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate_enable_search]', array(
    'label'     => __( 'Enable Search', 'intimate' ),
    'description' => __('It will help to display the search in Menu.', 'intimate'),
    'section'   => 'intimate_header_section',
    'settings'  => 'intimate_options[intimate_enable_search]',
    'type'      => 'checkbox',
    'priority'  => 5,

) );


/*Header Offcanvas Enable Option*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_offcanvas]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_enable_offcanvas'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate_enable_offcanvas]', array(
    'label'     => __( 'Enable Offcanvas Sidebar', 'intimate' ),
    'description' => __('It will help to display the Offcanvas sidebar in Menu.', 'intimate'),
    'section'   => 'intimate_header_section',
    'settings'  => 'intimate_options[intimate_enable_offcanvas]',
    'type'      => 'checkbox',
    'priority'  => 5,

) );

/*Header Advertisement Enable Option*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_header_ads]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_enable_header_ads'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate_enable_header_ads]', array(
    'label'     => __( 'Enable Header Advertisement', 'intimate' ),
    'description' => __('You can add the header ads image after enabling it.', 'intimate'),
    'section'   => 'intimate_header_section',
    'settings'  => 'intimate_options[intimate_enable_header_ads]',
    'type'      => 'checkbox',
    'priority'  => 5,
) );

/*callback functions header section*/
if ( !function_exists('intimate_header_ads_active_callback') ) :
  function intimate_header_ads_active_callback(){
      global $intimate_theme_options;
      $enable_header = absint($intimate_theme_options['intimate_enable_header_ads'])? absint($intimate_theme_options['intimate_enable_header_ads']): 0;
      if( 1 == $enable_header ){
          return true;
      }
      else{
          return false;
      }
  }
endif;

/*Header Ads Image*/
$wp_customize->add_setting( 'intimate_options[intimate-header-ads-image]', array(
    'capability'    => 'edit_theme_options',
    'default'     => $default['intimate-header-ads-image'],
    'sanitize_callback' => 'intimate_sanitize_image'
) );
$wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'intimate_options[intimate-header-ads-image]',
        array(
            'label'   => __( 'Header Ad Image', 'intimate' ),
            'section'   => 'intimate_header_section',
            'settings'  => 'intimate_options[intimate-header-ads-image]',
            'type'      => 'image',
            'priority'  => 5,
            'active_callback' => 'intimate_header_ads_active_callback',
            'description' => __( 'Recommended image size of 728*90', 'intimate' )
        )
    )
);

/*Ads Image Link*/
$wp_customize->add_setting( 'intimate_options[intimate-header-ads-image-link]', array(
    'capability'    => 'edit_theme_options',
    'default'     => $default['intimate-header-ads-image-link'],
    'sanitize_callback' => 'esc_url_raw',
) );
$wp_customize->add_control( 'intimate_options[intimate-header-ads-image-link]', array(
    'label'   => __( 'Header Ads Image Link', 'intimate' ),
    'section'   => 'intimate_header_section',
    'settings'  => 'intimate_options[intimate-header-ads-image-link]',
    'type'      => 'url',
    'active_callback' => 'intimate_header_ads_active_callback',
    'priority'  => 5
) );

/*Trending News Below Slider*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_trending_news_big]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_enable_trending_news_big'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate_enable_trending_news_big]', array(
    'label'     => __( 'Enable Trending News Below Menu', 'intimate' ),
    'description' => __('You can enable the trending news from the category or recent posts.', 'intimate'),
    'section'   => 'intimate_header_section',
    'settings'  => 'intimate_options[intimate_enable_trending_news_big]',
    'type'      => 'checkbox',
    'priority'  => 5,
) );

/*callback functions slider*/
if ( !function_exists('intimate_trending_active_callback') ) :
  function intimate_trending_active_callback(){
      global $intimate_theme_options;
      $enable_trending = absint($intimate_theme_options['intimate_enable_trending_news_big'])? absint($intimate_theme_options['intimate_enable_trending_news_big']): 0;
      if( 1 == $enable_trending ){
          return true;
      }
      else{
          return false;
      }
  }
endif;

/*Slider Category Selection*/
$wp_customize->add_setting( 'intimate_options[intimate-select-big-trending-category]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-select-big-trending-category'],
    'sanitize_callback' => 'absint'

) );

$wp_customize->add_control(
    new Intimate_Customize_Category_Dropdown_Control(
        $wp_customize,
        'intimate_options[intimate-select-big-trending-category]',
        array(
            'label'     => __( 'Select Category For Trending', 'intimate' ),
            'description' => __('Choose one category to show the trending. More settings are in pro version.', 'intimate'),
            'section'   => 'intimate_header_section',
            'settings'  => 'intimate_options[intimate-select-big-trending-category]',
            'type'      => 'category_dropdown',
            'priority'  => 5,
            'active_callback'=> 'intimate_trending_active_callback',
        )
    )

);