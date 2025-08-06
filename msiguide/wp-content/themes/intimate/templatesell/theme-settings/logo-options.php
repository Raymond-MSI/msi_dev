<?php 
/*Logo Section*/
$wp_customize->add_setting( 'intimate_options[intimate_logo_width_option]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['intimate_logo_width_option'],
    'sanitize_callback' => 'absint'
) );
$wp_customize->add_control( 'intimate_options[intimate_logo_width_option]', array(
   'label'     => __( 'Logo Width', 'intimate' ),
   'description' => __('Adjust the logo width. Minimum is 100px and maximum is 600px.', 'intimate'),
   'section'   => 'title_tagline',
   'settings'  => 'intimate_options[intimate_logo_width_option]',
   'type'      => 'range',
   'priority'  => 30,
   'input_attrs' => array(
          'min' => 100,
          'max' => 600,
        ),
) );

/*Logo Option*/
$wp_customize->add_setting('intimate_options[intimate-logo-position]', array(
    'capability' => 'edit_theme_options',
    'transport' => 'refresh',
    'default' => $default['intimate-logo-position'],
    'sanitize_callback' => 'intimate_sanitize_select'
));

$wp_customize->add_control('intimate_options[intimate-logo-position]', array(
    'choices' => array(
        'center-logo' => __('Center Logo', 'intimate'),
        'left-logo' => __('Left Logo', 'intimate'),
    ),
    'label' => __('Logo Position in Header', 'intimate'),
    'description' => __('Logo Position in the header, left or in center.', 'intimate'),
    'section' => 'title_tagline',
    'settings' => 'intimate_options[intimate-logo-position]',
    'type' => 'select',
    'priority' => 30,
));