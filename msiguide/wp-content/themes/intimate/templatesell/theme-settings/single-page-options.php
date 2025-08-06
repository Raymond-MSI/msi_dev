<?php
/*Single Page Options*/
$wp_customize->add_section('intimate_single_page_section', array(
    'priority' => 20,
    'capability' => 'edit_theme_options',
    'theme_supports' => '',
    'title' => __('Single Page Settings', 'intimate'),
    'panel' => 'intimate_panel',
));

/*Featured Image Option*/
$wp_customize->add_setting('intimate_options[intimate-single-page-featured-image]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-single-page-featured-image'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
));

$wp_customize->add_control('intimate_options[intimate-single-page-featured-image]', array(
    'label' => __('Enable Featured Image on Single Posts', 'intimate'),
    'description' => __('You can hide images on single post from here.', 'intimate'),
    'section' => 'intimate_single_page_section',
    'settings' => 'intimate_options[intimate-single-page-featured-image]',
    'type' => 'checkbox',
    'priority' => 15,
));

/*Single Page Sidebar Layout*/
$wp_customize->add_setting('intimate_options[intimate-sidebar-single-page]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-sidebar-single-page'],
    'sanitize_callback' => 'intimate_sanitize_select'
));

$wp_customize->add_control( 
    new Intimate_Radio_Image_Control(
        $wp_customize,
    'intimate_options[intimate-sidebar-single-page]', array(
    'choices' => intimate_sidebar_position_array(),
    'label' => __('Select Sidebar', 'intimate'),
    'description' => __('From Appearance > Customize > Widgets and add the widgets on the respected widget areas.', 'intimate'),
    'section' => 'intimate_single_page_section',
    'settings' => 'intimate_options[intimate-sidebar-single-page]',
    'type' => 'select',
    'priority' => 15,
)));

/*Related Post Options*/
$wp_customize->add_setting('intimate_options[intimate-single-page-related-posts]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-single-page-related-posts'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
));

$wp_customize->add_control('intimate_options[intimate-single-page-related-posts]', array(
    'label' => __('Related Posts', 'intimate'),
    'description' => __('2 posts of same category will appear.', 'intimate'),
    'section' => 'intimate_single_page_section',
    'settings' => 'intimate_options[intimate-single-page-related-posts]',
    'type' => 'checkbox',
    'priority' => 15,
));


/*callback functions related posts*/
if (!function_exists('intimate_related_post_callback')) :
    function intimate_related_post_callback()
    {
        global $intimate_theme_options;
        $related_posts = absint($intimate_theme_options['intimate-single-page-related-posts'])? absint($intimate_theme_options['intimate-single-page-related-posts']): 0;
        if (1 == $related_posts) {
            return true;
        } else {
            return false;
        }
    }
endif;

/*Related Post Title*/
$wp_customize->add_setting('intimate_options[intimate-single-page-related-posts-title]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-single-page-related-posts-title'],
    'sanitize_callback' => 'sanitize_text_field'
));

$wp_customize->add_control('intimate_options[intimate-single-page-related-posts-title]', array(
    'label' => __('Related Posts Title', 'intimate'),
    'description' => __('Enter the suitable title.', 'intimate'),
    'section' => 'intimate_single_page_section',
    'settings' => 'intimate_options[intimate-single-page-related-posts-title]',
    'type' => 'text',
    'priority' => 15,
    'active_callback' => 'intimate_related_post_callback'
));

/*Social Share Options*/
$wp_customize->add_setting('intimate_options[intimate-single-social-share]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-single-social-share'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
));

$wp_customize->add_control('intimate_options[intimate-single-social-share]', array(
    'label' => __('Social Sharing', 'intimate'),
    'description' => __('Enable Social Sharing on Single Posts.', 'intimate'),
    'section' => 'intimate_single_page_section',
    'settings' => 'intimate_options[intimate-single-social-share]',
    'type' => 'checkbox',
    'priority' => 15,
));
