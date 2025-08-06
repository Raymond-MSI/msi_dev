<?php
/*Slider Options*/

$wp_customize->add_section( 'intimate_slider_section', array(
   'priority'       => 20,
   'capability'     => 'edit_theme_options',
   'theme_supports' => '',
   'title'          => __( 'Featured Slider Settings', 'intimate' ),
   'panel' 		 => 'intimate_front_page',
) );

/*callback functions slider*/
if ( !function_exists('intimate_slider_active_callback') ) :
  function intimate_slider_active_callback(){
      global $intimate_theme_options;
      $enable_slider = absint($intimate_theme_options['intimate_enable_slider'])? absint($intimate_theme_options['intimate_enable_slider']): 0;
      if( 1 == $enable_slider ){
          return true;
      }
      else{
          return false;
      }
  }
endif;

/*Slider Enable Option*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_slider]', array(
   'capability'        => 'edit_theme_options',
   'transport' => 'refresh',
   'default'           => $default['intimate_enable_slider'],
   'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control(
    'intimate_options[intimate_enable_slider]', 
    array(
       'label'     => __( 'Enable Featured Section', 'intimate' ),
       'description' => __('You can select the category for the slider and other settings below. More Options are available on premium version.', 'intimate'),
       'section'   => 'intimate_slider_section',
       'settings'  => 'intimate_options[intimate_enable_slider]',
        'type'      => 'checkbox',
       'priority'  => 15,
   )
 );        

/*Slider Category Selection*/
$wp_customize->add_setting( 'intimate_options[intimate-select-category]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-select-category'],
    'sanitize_callback' => 'absint'

) );

$wp_customize->add_control(
    new Intimate_Customize_Category_Dropdown_Control(
        $wp_customize,
        'intimate_options[intimate-select-category]',
        array(
            'label'     => __( 'Select Category For Slider', 'intimate' ),
            'description' => __('Choose one category to show the slider. More settings are in pro version.', 'intimate'),
            'section'   => 'intimate_slider_section',
            'settings'  => 'intimate_options[intimate-select-category]',
            'type'      => 'category_dropdown',
            'priority'  => 15,
            'active_callback'=> 'intimate_slider_active_callback',
        )
    )

);

/*Slider Right Category Selection*/
$wp_customize->add_setting( 'intimate_options[intimate-select-category-slider-right]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-select-category-slider-right'],
    'sanitize_callback' => 'absint'

) );

$wp_customize->add_control(
    new Intimate_Customize_Category_Dropdown_Control(
        $wp_customize,
        'intimate_options[intimate-select-category-slider-right]',
        array(
            'label'     => __( 'Select Category For Slider Right', 'intimate' ),
            'description' => __('The two post of same category will be displayed right to the slider. More options are in premium version.', 'intimate'),
            'section'   => 'intimate_slider_section',
            'settings'  => 'intimate_options[intimate-select-category-slider-right]',
            'type'      => 'category_dropdown',
            'priority'  => 15,
            'active_callback'=> 'intimate_slider_active_callback',
        )
    )

);

/*Slider Category for trending Selection*/
$wp_customize->add_setting( 'intimate_options[intimate-select-category-trending]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-select-category-trending'],
    'sanitize_callback' => 'absint'

) );

$wp_customize->add_control(
    new Intimate_Customize_Category_Dropdown_Control(
        $wp_customize,
        'intimate_options[intimate-select-category-trending]',
        array(
            'label'     => __( 'Select Category for Trending Tabs', 'intimate' ),
            'description' => __('Popular posts comes on the basis of comments number and the recent post is from recent post. You need to select the category for trending.', 'intimate'),
            'section'   => 'intimate_slider_section',
            'settings'  => 'intimate_options[intimate-select-category-trending]',
            'type'      => 'category_dropdown',
            'priority'  => 15,
            'active_callback'=> 'intimate_slider_active_callback',
        )
    )

);