<?php
/*Promo Section Options*/

$wp_customize->add_section( 'intimate_promo_section', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Highlights News', 'intimate' ),
    'panel'          => 'intimate_front_page',
) );

/*callback functions slider*/
if ( !function_exists('intimate_promo_active_callback') ) :
    function intimate_promo_active_callback(){
        global $intimate_theme_options;
        $enable_promo = absint($intimate_theme_options['intimate_enable_promo'])? absint($intimate_theme_options['intimate_enable_promo']): 0;
        if( 1 == $enable_promo ){
            return true;
        }
        else{
            return false;
        }
    }
endif;

/*Highlights Enable Option*/
$wp_customize->add_setting( 'intimate_options[intimate_enable_promo]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_enable_promo'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate_enable_promo]', array(
    'label'     => __( 'Enable Highlights', 'intimate' ),
    'description' => __('Enable and select the category to show the boxes below slider.', 'intimate'),
    'section'   => 'intimate_promo_section',
    'settings'  => 'intimate_options[intimate_enable_promo]',
    'type'      => 'checkbox',
    'priority'  => 5,

) );

/*Title of Highlights*/
$wp_customize->add_setting( 'intimate_options[intimate_highlights_title]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_highlights_title'],
    'sanitize_callback' => 'sanitize_text_field'
) );

$wp_customize->add_control( 'intimate_options[intimate_highlights_title]', array(
    'label'     => __( 'Title of Highlights', 'intimate' ),
    'description' => __('Enter the suitable title for the highlights.', 'intimate'),
    'section'   => 'intimate_promo_section',
    'settings'  => 'intimate_options[intimate_highlights_title]',
    'type'      => 'text',
    'priority'  => 5,
    'active_callback'=>'intimate_promo_active_callback'

) );

/*Promo Category Selection*/
$wp_customize->add_setting( 'intimate_options[intimate-promo-select-category]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-promo-select-category'],
    'sanitize_callback' => 'absint'

) );

$wp_customize->add_control(
    new Intimate_Customize_Category_Dropdown_Control(
        $wp_customize,
        'intimate_options[intimate-promo-select-category]',
        array(
            'label'     => __( 'Category For Highlights', 'intimate' ),
            'description' => __('From the dropdown select the category for the Highlights. Category having post will display in Highlights section.', 'intimate'),
            'section'   => 'intimate_promo_section',
            'settings'  => 'intimate_options[intimate-promo-select-category]',
            'type'      => 'category_dropdown',
            'priority'  => 5,
            'active_callback'=>'intimate_promo_active_callback'
        )
    )
);