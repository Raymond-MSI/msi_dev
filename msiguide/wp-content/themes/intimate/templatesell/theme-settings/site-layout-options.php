<?php
/*Site Layout Options*/

$wp_customize->add_section( 'intimate_site_layout_section', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __( 'Site Layout Settings', 'intimate' ),
    'panel'          => 'intimate_panel',
) );

$wp_customize->add_setting( 'intimate_options[intimate_container_width_options]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_container_width_options'],
    'sanitize_callback' => 'absint'
) );
$wp_customize->add_control( 'intimate_options[intimate_container_width_options]', array(
   'label'     => __( 'Site Width', 'intimate' ),
   'description' => __('Width of the site container. The range is from 70-100 %.', 'intimate'),
   'section'   => 'intimate_site_layout_section',
   'settings'  => 'intimate_options[intimate_container_width_options]',
   'type'      => 'range',
   'priority'  => 15,
   'input_attrs' => array(
          'min' => 70,
          'max' => 100,
        ),
) );