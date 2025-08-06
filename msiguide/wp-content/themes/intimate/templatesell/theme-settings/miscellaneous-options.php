<?php 
/*Extra Options*/

        $wp_customize->add_section( 'intimate_miscellaneous_options', array(
            'priority'       => 20,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => __( 'Miscellaneous Settings', 'intimate' ),
            'panel'          => 'intimate_panel',
        ) );

        /*Breadcrumb Enable*/
        $wp_customize->add_setting( 'intimate_options[intimate-front-page-content]', array(
            'capability'        => 'edit_theme_options',
            'transport' => 'refresh',
            'default'           => $default['intimate-front-page-content'],
            'sanitize_callback' => 'intimate_sanitize_checkbox'
        ) );

        $wp_customize->add_control( 'intimate_options[intimate-front-page-content]', array(
            'label'     => __( 'Enable Front Page Content', 'intimate' ),
            'description' => __( 'This will help to hide the content in Front Page, blog and home page content.', 'intimate' ),
            'section'   => 'intimate_miscellaneous_options',
            'settings'  => 'intimate_options[intimate-front-page-content]',
            'type'      => 'checkbox',
            'priority'  => 15,
        ) );