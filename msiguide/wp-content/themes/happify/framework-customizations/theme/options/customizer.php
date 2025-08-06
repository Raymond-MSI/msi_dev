<?php if (!defined( 'FW' )) die('Forbidden');

$options = array(
    'setting_panel' => array(
        'title' => esc_html__('Happify Panel', 'happify'),
        'options' => array(

            'happify_settings' => array(
                'title' => esc_html__('General Setting', 'happify'),
                'options' => array(

                    'happify_preloader' => array(
                        'type'  => 'switch',
                        'label' => esc_html__('Happify Preloader', 'happify'),
                        'desc' => esc_html__('Turn off if you dont want to show preloader', 'happify'),
                    ),

                ),
            ),
            // Blog Settings
            'happify_blog_settings' => array(
                'title' => esc_html__('Blog Setting', 'happify'),
                'options' => array(
                    'happify_blog_sidebar_style'    => array(
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
                    'happify_post_meta' => array(
                        'type'  => 'switch',
                        'value' => false,
                        'label' => esc_html__('Hide Post Meta', 'happify'),
                        'desc' => esc_html__('Turn On if you dont want to show post meta', 'happify'),
                    ),
                    'happify_readmore_text' => array(
                        'type'  => 'text',
                        'value' => 'Read More',
                        'label' => esc_html__('Change Read More Text', 'happify'),
                        'desc' => esc_html__('Change Read More Post text. Just Type that you want.', 'happify'),
                    ),

                ),
            ),
            // Footer Settings
            'happify_footer_settings' => array(
                'title' => esc_html__('Footer Setting', 'happify'),
                'options' => array(
                    'happify_footer_widget_block' => array(
                        'type'  => 'switch',
                        'label' => esc_html__('Footer widget', 'happify'),
                        'desc' => esc_html__('Turn off if you dont want to show Footer widget', 'happify'),
                    ),
                    'happify_footer_widget_layout'    => array(
                        'label' => esc_html__( 'Select Footer widget', 'happify' ),
                        'desc'  => esc_html__( 'Pleas select Footer widget options here', 'happify' ),
                        'type'  => 'image-picker',
                        'value' => 'footer-2',
                        'choices' => array(
                            '1' => get_template_directory_uri() .'/assets/images/footer-1.png',
                            '2' => get_template_directory_uri() .'/assets/images/footer-2.png',
                            '3' => get_template_directory_uri() .'/assets/images/footer-3.png',
                            '4' => get_template_directory_uri() .'/assets/images/footer-4.png',
                        ),
                    ),
                ),
            ),

            // Typography Settings
            'happify_typography_settings' => array(
                'title' => esc_html__('Typography Setting', 'happify'),
                'options' => array(
                    'body_typography' => array(
                      'type'  => 'typography',
                      'value' => array(
                          'family' => 'segoe-ui',
                      ),
                     'components' => array(
                          'family' => true,
                          'size'   => false,
                          'color'  => false
                      ),
                      'label' => esc_html__('Body Typography', 'happify'),
                  ),
                    'heading_typography' => array(
                      'type'  => 'typography',
                      'value' => array(
                          'family' => 'Josefin Sans',
                      ),
                     'components' => array(
                          'family' => true,
                          'size'   => false,
                          'color'  => false
                      ),
                     'label' => esc_html__('Title Typography', 'happify'),
                  )

                ),
            ),

        ),
        'wp-customizer-args' => array(
            'priority' => 3,
        ),
    ),

    // Color Panel
    'happify_color_panel' => array(
        'title' => esc_html__('Happify Colors', 'happify'),
        'options' => array(

            'happify_menu_color_section' => array(
                'title' => esc_html__('Menu Color', 'happify'),
                'options' => array(

                    'happify_menu_color' => array(
                        'type' => 'color-picker',
                        'value' => '#274054',
                        'label' => esc_html__('Menu Color', 'happify'),
                        'desc' => esc_html__('Set Color for menu text', 'happify'),
                    ),
                    'happify_menu_bg_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => 'rgba(255,255,255,0.9)',
                        'label' => esc_html__('Menu Background Color', 'happify'),
                        'desc' => esc_html__('Set Color for menu background', 'happify'),
                    ),

                    'happify_menu_logo_color' => array(
                        'type' => 'color-picker',
                        'value' => '#83ba3b',
                        'label' => esc_html__('Logo Text Color', 'happify'),
                        'desc' => esc_html__('Set Color for text logo', 'happify'),
                    ),

                ),
            ),

            'happify_titlebar_color_section' => array(
                'title' => esc_html__('Titlbar Color', 'happify'),
                'options' => array(

                    'happify_title_text_color' => array(
                        'type' => 'color-picker',
                        'value' => '#ffffff',
                        'label' => esc_html__('Title Text Color', 'happify'),
                        'desc' => esc_html__('Set Color for titlebar text', 'happify'),
                    ),
                    'happify_title_bg_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => 'rgba(131, 186, 59,0.7)',
                        'label' => esc_html__('Title Bar Background', 'happify'),
                        'desc' => esc_html__('Set Color for titlebar Background', 'happify'),
                    ),

                ),
            ),
            'happify_footer_color_section' => array(
                'title' => esc_html__('Footer Color', 'happify'),
                'options' => array(

                    'happify_footer_copywrite_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => '#ffffff',
                        'label' => esc_html__('Copywrite Text', 'happify'),
                        'desc' => esc_html__('Set Color for Footer Copywrite text', 'happify'),
                    ),
                    'happify_footer_copywrite_bg_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => '#2b2e3a',
                        'label' => esc_html__('Copywrite Background', 'happify'),
                        'desc' => esc_html__('Set Color for Footer Copywrite Background', 'happify'),
                    ),

                ),
            ),

        ),
        'wp-customizer-args' => array(
            'priority' => 4,
        ),
    ),
    // Color Panel End
);

