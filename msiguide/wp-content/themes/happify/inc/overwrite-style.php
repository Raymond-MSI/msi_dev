<?php
/*
 *  Custom Style
 */

/* All Dynamic CSS Styles */
if ( ! function_exists( 'happify_dynamic_styles' ) ) {
  function happify_dynamic_styles() {


  ob_start();
  global $post;

  if ( defined('FW') ) {
    $happify_menu_color = fw_get_db_customizer_option('happify_menu_color');
    $happify_menu_bg_color = fw_get_db_customizer_option('happify_menu_bg_color');
    $happify_menu_logo_color = fw_get_db_customizer_option('happify_menu_logo_color');
    $happify_title_text_color = fw_get_db_customizer_option('happify_title_text_color');
    $happify_title_bg_color = fw_get_db_customizer_option('happify_title_bg_color');
    $happify_footer_copywrite_color = fw_get_db_customizer_option('happify_footer_copywrite_color');
    $happify_footer_copywrite_bg_color = fw_get_db_customizer_option('happify_footer_copywrite_bg_color');
    $body_typography = fw_get_db_customizer_option('body_typography');
    $heading_typography = fw_get_db_customizer_option('heading_typography');
  }
  $happify_menu_color = isset( $happify_menu_color ) ? $happify_menu_color : '';
  $happify_menu_bg_color = isset( $happify_menu_bg_color ) ? $happify_menu_bg_color : '';
  $happify_menu_logo_color = isset( $happify_menu_logo_color ) ? $happify_menu_logo_color : '';
  $happify_title_text_color = isset( $happify_title_text_color ) ? $happify_title_text_color : '';
  $happify_title_bg_color = isset( $happify_title_bg_color ) ? $happify_title_bg_color : '';
  $happify_footer_copywrite_color = isset( $happify_footer_copywrite_color ) ? $happify_footer_copywrite_color : '';
  $happify_footer_copywrite_bg_color = isset( $happify_footer_copywrite_bg_color ) ? $happify_footer_copywrite_bg_color : '';
  $body_typography = isset( $body_typography['family'] ) ? $body_typography['family'] : '';
  $heading_typography = isset( $heading_typography['family'] ) ? $heading_typography['family'] : '';

  if ( $happify_menu_color ) {?>
    .site-header #navbar>ul>li>a {
      color: <?php echo esc_attr( $happify_menu_color ); ?>;
    }
  <?php
  }

  if ( $happify_menu_bg_color ) {?>
    .site-header .navigation {
      background-color: <?php echo esc_attr( $happify_menu_bg_color ); ?>;
    }
  <?php
  }
 
  if ( $happify_menu_logo_color ) {?>
    .site-header .navbar-header .site-branding h2 a, .site-header .navbar-header .site-branding p a {
      color: <?php echo esc_attr( $happify_menu_logo_color ); ?>;
    }
  <?php
  }

  if ( $happify_title_text_color ) {?>
    .entry-header h2 {
      color: <?php echo esc_attr( $happify_title_text_color ); ?>;
    }
  <?php
  }

  if ( $happify_title_bg_color ) {?>
    .entry-header:before {
      background-color: <?php echo esc_attr( $happify_title_bg_color ); ?>;
    }
  <?php
  }

  if ( $happify_footer_copywrite_color ) {?>
    .site-footer .site-info, .site-footer .site-info a {
      color: <?php echo esc_attr( $happify_footer_copywrite_color ); ?>;
    }
  <?php
  }

  if ( $happify_footer_copywrite_bg_color ) {?>
    .site-footer {
      background-color: <?php echo esc_attr( $happify_footer_copywrite_bg_color ); ?>;
    }
  <?php
  }

  if ( $body_typography ) {?>
     body {
      font-family: <?php echo esc_attr( $body_typography ); ?>;
    }
  <?php
  }
  if ( $heading_typography ) {?>
     h1, h2, h3, h4, h5, h6 {
      font-family: <?php echo esc_attr( $heading_typography ); ?>;
    }
  <?php
  }

  $output = ob_get_clean();
  return $output;

  }

}

/* Custom Styles */
if( ! function_exists( 'happify_custom_css' ) ) {
  function happify_custom_css() {
    wp_enqueue_style('happify-default-style', get_template_directory_uri() . '/style.css');
    $output = happify_dynamic_styles();
    $custom_css = happify_compress_css_lines( $output );

    wp_add_inline_style( 'happify-default-style', $custom_css );
  }
  add_action( 'wp_enqueue_scripts', 'happify_custom_css' );
}