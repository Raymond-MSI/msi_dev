<?php

if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
    'happify_general_settings' => array(
        'title'   => esc_html__( 'General', 'happify' ),
        'type'    => 'tab',
        'options' => array(
            'general-box' => array(
                'title'   => esc_html__( 'General Settings', 'happify' ),
                'type'    => 'box',
                'options' => array(
                    'happify_hide_title'    => array(
                        'label' => esc_html__( 'Hide Title bar', 'happify' ),
                        'desc'  => esc_html__( 'Please turn on if you dont want to show title bar', 'happify' ),
                        'type'  => 'switch',
                        'value' => 'title_bar',
                    ),
                    'happify_header_styles'    => array(
                        'label' => esc_html__( 'Select Header Style', 'happify' ),
                        'desc'  => esc_html__( 'Pleas select header options here', 'happify' ),
                        'type'  => 'image-picker',
                        'value' => 'image-2',
                        'choices' => array(
                            'hs_1' => get_template_directory_uri() .'/assets/images/hs-1.png',
                            'hs_2' => get_template_directory_uri() .'/assets/images/hs-2.png',
                        ),
                    ),
                    'happify_sidebar_style'    => array(
                        'label' => esc_html__( 'Select Sidebar Style', 'happify' ),
                        'desc'  => esc_html__( 'Pleas select sidebar options here', 'happify' ),
                        'type'  => 'image-picker',
                        'value' => 'image-2',
                        'choices' => array(
                            'sidebar_full' => get_template_directory_uri() .'/assets/images/page-1.png',
                            'sidebar_left' => get_template_directory_uri() .'/assets/images/page-2.png',
                            'sidebar_right' => get_template_directory_uri() .'/assets/images/page-3.png',
                        ),
                    ),
                    'happify_header_banner_image' => array(
                        'label' => esc_html__( 'Banner Image', 'happify' ),
                        'desc'  => esc_html__( 'Please Upload a image for specific page header image', 'happify' ),
                        'type'  => 'upload'
                    ),
                    'happify_custom_padding' => array(
                        'label' => esc_html__( 'Cuatom Padding', 'happify' ),
                        'desc'  => esc_html__( 'Please select custom padding for page', 'happify' ),
                        'type'  => 'range-slider',
                        'value' => array(
                          'from' => 50,
                          'to'   => 100,
                        ),
                        'properties' => array(
                            'min' => 20,
                            'max' => 200,
                        ),
                    ),
                    'happify_hide_footer'    => array(
                        'label' => esc_html__( 'Hide Footer', 'happify' ),
                        'desc'  => esc_html__( 'Please turn on if you dont want to show footer', 'happify' ),
                        'type'  => 'switch',
                        'value' => 'footer',
                    ),
                )
            ),
        )
    )
);