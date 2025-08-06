<?php 
/* Front Page Options Panel */
    $wp_customize->add_panel( 'intimate_front_page', array(
        'priority' => 30,
        'capability' => 'edit_theme_options',
        'title' => __( 'Intimate Front Page Options', 'intimate' ),
) );

$wp_customize->add_section( 'intimate_front_page_grid_posts', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Grid Posts Slider', 'intimate' ),
    'panel'          => 'intimate_front_page',
) );

/*Enable Grid Post Option*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_grid_post_front]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_enable_grid_post_front'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate_enable_grid_post_front]', array(
    'label'     => __( 'Enable Grid Post Slider', 'intimate' ),
    'description' => __('Posts of the selected category will appear as a slider.', 'intimate'),
    'section'   => 'intimate_front_page_grid_posts',
    'settings'  => 'intimate_options[intimate_enable_grid_post_front]',
    'type'      => 'checkbox',
    'priority'  => 5,

) );

/*callback functions slider*/
if ( !function_exists('intimate_grid_slider_active_callback') ) :
    function intimate_grid_slider_active_callback(){
        global $intimate_theme_options;
        $enable_grid = absint($intimate_theme_options['intimate_enable_grid_post_front'])? absint($intimate_theme_options['intimate_enable_grid_post_front']): 0;
        if( 1 == $enable_grid ){
            return true;
        }
        else{
            return false;
        }
    }
endif;

/*Title Grid Post Option*/
$wp_customize->add_setting( 'intimate_options[intimate_title_grid_post_front]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_title_grid_post_front'],
    'sanitize_callback' => 'sanitize_text_field'
) );

$wp_customize->add_control( 'intimate_options[intimate_title_grid_post_front]', array(
    'label'     => __( 'Title Grid Post Slider', 'intimate' ),
    'description' => __('Enter the title for this section.', 'intimate'),
    'section'   => 'intimate_front_page_grid_posts',
    'settings'  => 'intimate_options[intimate_title_grid_post_front]',
    'type'      => 'text',
    'priority'  => 5,
    'active_callback'=> 'intimate_grid_slider_active_callback',

) );

/*Category Selection*/
$wp_customize->add_setting( 'intimate_options[intimate-grid-slider-select-category]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-grid-slider-select-category'],
    'sanitize_callback' => 'absint'

) );

$wp_customize->add_control(
    new Intimate_Customize_Category_Dropdown_Control(
        $wp_customize,
        'intimate_options[intimate-grid-slider-select-category]',
        array(
            'label'     => __( 'Category For Grid Slider', 'intimate' ),
            'description' => __('From the dropdown select the category for the grid slider. Category having post will display in grid section.', 'intimate'),
            'section'   => 'intimate_front_page_grid_posts',
            'settings'  => 'intimate_options[intimate-grid-slider-select-category]',
            'type'      => 'category_dropdown',
            'priority'  => 5,
            'active_callback'=>'intimate_grid_slider_active_callback'
        )
    )
);

//Footer you may missed section
$wp_customize->add_section( 'intimate_front_page_you_may_missed', array(
    'priority'       => 30,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'You May Missed', 'intimate' ),
    'panel'          => 'intimate_front_page',
) );

/*Enable you may Post Option*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_missed_post_front]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_enable_missed_post_front'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate_enable_missed_post_front]', array(
    'label'     => __( 'Enable You May Missed', 'intimate' ),
    'description' => __('This section will appear on the footer section.', 'intimate'),
    'section'   => 'intimate_front_page_you_may_missed',
    'settings'  => 'intimate_options[intimate_enable_missed_post_front]',
    'type'      => 'checkbox',
    'priority'  => 5,

) );

/*callback functions you may missed*/
if ( !function_exists('intimate_you_may_missed_active_callback') ) :
    function intimate_you_may_missed_active_callback(){
        global $intimate_theme_options;
        $enable_missed = absint($intimate_theme_options['intimate_enable_missed_post_front'])? absint($intimate_theme_options['intimate_enable_missed_post_front']): 0;
        if( 1 == $enable_missed ){
            return true;
        }
        else{
            return false;
        }
    }
endif;

/*Title you may missed Post Option*/
$wp_customize->add_setting( 'intimate_options[intimate_title_you_missed_post_front]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_title_you_missed_post_front'],
    'sanitize_callback' => 'sanitize_text_field'
) );

$wp_customize->add_control( 'intimate_options[intimate_title_you_missed_post_front]', array(
    'label'     => __( 'Title You May Missed', 'intimate' ),
    'description' => __('Enter the title for this section.', 'intimate'),
    'section'   => 'intimate_front_page_you_may_missed',
    'settings'  => 'intimate_options[intimate_title_you_missed_post_front]',
    'type'      => 'text',
    'priority'  => 5,
    'active_callback'=> 'intimate_you_may_missed_active_callback',

) );

/*Category Selection*/
$wp_customize->add_setting( 'intimate_options[intimate-you-missed-select-category]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-you-missed-select-category'],
    'sanitize_callback' => 'absint'

) );

$wp_customize->add_control(
    new Intimate_Customize_Category_Dropdown_Control(
        $wp_customize,
        'intimate_options[intimate-you-missed-select-category]',
        array(
            'label'     => __( 'Category For Missed Section', 'intimate' ),
            'description' => __('From the dropdown select the category for the you may missed. Category having post will display in missed section.', 'intimate'),
            'section'   => 'intimate_front_page_you_may_missed',
            'settings'  => 'intimate_options[intimate-you-missed-select-category]',
            'type'      => 'category_dropdown',
            'priority'  => 5,
            'active_callback'=>'intimate_you_may_missed_active_callback'
        )
    )
);