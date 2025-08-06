<?php
/*Blog Page Options*/
$wp_customize->add_section('intimate_blog_page_section', array(
    'priority' => 20,
    'capability' => 'edit_theme_options',
    'theme_supports' => '',
    'title' => __('Blog Settings', 'intimate'),
    'panel' => 'intimate_panel',
));
/*Blog Page Sidebar Layout*/

$wp_customize->add_setting('intimate_options[intimate-sidebar-blog-page]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-sidebar-blog-page'],
    'sanitize_callback' => 'intimate_sanitize_select'
));

$wp_customize->add_control( new Intimate_Radio_Image_Control(
        $wp_customize,
    'intimate_options[intimate-sidebar-blog-page]', array(
    'choices' => intimate_blog_sidebar_position_array(),
    'label' => __('Blog and Archive Sidebar', 'intimate'),
    'description' => __('This sidebar will work blog and archive pages.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-sidebar-blog-page]',
    'type' => 'select',
    'priority' => 15,
)));

/*Blog Page Show content from*/
$wp_customize->add_setting('intimate_options[intimate-content-show-from]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-content-show-from'],
    'sanitize_callback' => 'intimate_sanitize_select'
));

$wp_customize->add_control('intimate_options[intimate-content-show-from]', array(
    'choices' => array(
        'excerpt' => __('Show from Excerpt', 'intimate'),
        'content' => __('Show from Content', 'intimate'),
    ),
    'label' => __('Select Content Display From', 'intimate'),
    'description' => __('You can enable excerpt from Screen Options inside post section of dashboard', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-content-show-from]',
    'type' => 'select',
    'priority' => 15,
));


/*Blog Page excerpt length*/
$wp_customize->add_setting('intimate_options[intimate-excerpt-length]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-excerpt-length'],
    'sanitize_callback' => 'absint'

));

$wp_customize->add_control('intimate_options[intimate-excerpt-length]', array(
    'label' => __('Excerpt Length', 'intimate'),
    'description' => __('Enter the number per Words to show the content in blog page.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-excerpt-length]',
    'type' => 'number',
    'priority' => 15,
));

/*Exclude Category in Blog Page*/
$wp_customize->add_setting('intimate_options[intimate-blog-exclude-category]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-blog-exclude-category'],
    'sanitize_callback' => 'sanitize_text_field'
));

$wp_customize->add_control('intimate_options[intimate-blog-exclude-category]', array(
    'label' => __('Exclude categories in Blog Listing', 'intimate'),
    'description' => __('Enter categories ids with comma separated eg: 2,7,14,47.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-blog-exclude-category]',
    'type' => 'text',
    'priority' => 15,
));

/*Blog Page Pagination Options*/
$wp_customize->add_setting('intimate_options[intimate-pagination-options]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-pagination-options'],
    'sanitize_callback' => 'intimate_sanitize_select'

));

$wp_customize->add_control('intimate_options[intimate-pagination-options]', array(
    'choices' => array(
        'numeric' => __('Numeric Pagination', 'intimate'),
        'ajax' => __('Ajax Pagination', 'intimate'),
    ),
    'label' => __('Pagination Types', 'intimate'),
    'description' => __('Choose Required Pagination Type', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-pagination-options]',
    'type' => 'select',
    'priority' => 15,
));

/*Blog Page read more text*/
$wp_customize->add_setting('intimate_options[intimate-read-more-text]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-read-more-text'],
    'sanitize_callback' => 'sanitize_text_field'
));

$wp_customize->add_control('intimate_options[intimate-read-more-text]', array(
    'label' => __('Read More Text', 'intimate'),
    'description' => __('Read more text for blog and archive page.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-read-more-text]',
    'type' => 'text',
    'priority' => 15,
));


/*Social Share in blog page*/
$wp_customize->add_setting('intimate_options[intimate-show-hide-share]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-show-hide-share'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
));

$wp_customize->add_control('intimate_options[intimate-show-hide-share]', array(
    'label' => __('Show Social Share', 'intimate'),
    'description' => __('Options to Enable Social Share in blog and archive page.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-show-hide-share]',
    'type' => 'checkbox',
    'priority' => 15,
));

/*Category Show hide*/
$wp_customize->add_setting('intimate_options[intimate-show-hide-category]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-show-hide-category'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
));

$wp_customize->add_control('intimate_options[intimate-show-hide-category]', array(
    'label' => __('Show Category', 'intimate'),
    'description' => __('Option to hide the category on the blog page.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-show-hide-category]',
    'type' => 'checkbox',
    'priority' => 15,
));
/*Date Show hide*/
$wp_customize->add_setting('intimate_options[intimate-show-hide-date]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-show-hide-date'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
));

$wp_customize->add_control('intimate_options[intimate-show-hide-date]', array(
    'label' => __('Show Date', 'intimate'),
    'description' => __('Option to hide the date on the blog page.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-show-hide-date]',
    'type' => 'checkbox',
    'priority' => 15,
));
/*Author Show hide*/
$wp_customize->add_setting('intimate_options[intimate-show-hide-author]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-show-hide-author'],
    'sanitize_callback' => 'intimate_sanitize_checkbox'
));

$wp_customize->add_control('intimate_options[intimate-show-hide-author]', array(
    'label' => __('Show Author', 'intimate'),
    'description' => __('Option to hide the author on the blog page.', 'intimate'),
    'section' => 'intimate_blog_page_section',
    'settings' => 'intimate_options[intimate-show-hide-author]',
    'type' => 'checkbox',
    'priority' => 15,
));

