<?php
/*Footer Options*/
$wp_customize->add_section('intimate_footer_section', array(
    'priority' => 20,
    'capability' => 'edit_theme_options',
    'theme_supports' => '',
    'title' => __('Footer Settings', 'intimate'),
    'panel' => 'intimate_panel',
));


/*Copyright Setting*/
$wp_customize->add_setting('intimate_options[intimate-footer-copyright]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-footer-copyright'],
    'sanitize_callback' => 'sanitize_text_field'
));

$wp_customize->add_control('intimate_options[intimate-footer-copyright]', array(
    'label' => __('Copyright Text', 'intimate'),
    'description' => __('Enter your own copyright text.', 'intimate'),
    'section' => 'intimate_footer_section',
    'settings' => 'intimate_options[intimate-footer-copyright]',
    'type' => 'text',
    'priority' => 15,
));
