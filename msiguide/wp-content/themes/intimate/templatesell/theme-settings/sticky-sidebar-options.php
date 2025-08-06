<?php 
/*Sticky Sidebar*/
$wp_customize->add_section( 'intimate_sticky_sidebar', array(
   'priority'       => 20,
   'capability'     => 'edit_theme_options',
   'theme_supports' => '',
   'title'          => __( 'Sticky Sidebar Settings', 'intimate' ),
   'panel' 		 => 'intimate_panel',
) );

/*Sticky Sidebar Setting*/
$wp_customize->add_setting( 'intimate_options[intimate-enable-sticky-sidebar]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate-enable-sticky-sidebar'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
) );

$wp_customize->add_control( 'intimate_options[intimate-enable-sticky-sidebar]', array(
    'label'     => __( 'Enable Sticky Sidebar', 'intimate' ),
    'description' => __('Enable and Disable sticky sidebar from this section.', 'intimate'),
    'section'   => 'intimate_sticky_sidebar',
    'settings'  => 'intimate_options[intimate-enable-sticky-sidebar]',
    'type'      => 'checkbox',
    'priority'  => 15,
) );