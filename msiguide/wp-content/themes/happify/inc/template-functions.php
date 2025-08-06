<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package happify
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function happify_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'happify_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function happify_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'happify_pingback_header' );




/* Compress CSS */
if ( ! function_exists( 'happify_compress_css_lines' ) ) {
  function happify_compress_css_lines( $css ) {
    $css  = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
    $css  = str_replace( ': ', ':', $css );
    $css  = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
    return $css;
  }
}

/* Inline Style */
global $happify_all_inline_styles;
$happify_all_inline_styles = array();
if( ! function_exists( 'happify_add_inline_style' ) ) {
  function happify_add_inline_style( $style ) {
    global $happify_all_inline_styles;
    array_push( $happify_all_inline_styles, $style );
  }
}

/* Enqueue Inline Styles */
if ( ! function_exists( 'happify_enqueue_inline_styles' ) ) {
  function happify_enqueue_inline_styles() {

    global $happify_all_inline_styles;

    if ( ! empty( array_filter($happify_all_inline_styles) ) ) {
      echo '<style id="happify-inline-style" type="text/css">'. happify_compress_css_lines( join( '', $happify_all_inline_styles ) ) .'</style>';
    }

  }
  add_action( 'wp_footer', 'happify_enqueue_inline_styles' );
}



// footer widget

if ( ! function_exists( 'happify_widget_init' ) ) {
  function happify_widget_init() {
    if ( function_exists( 'register_sidebar' ) ) {

      // Footer Widgets
      if ( defined('FW') ) {
        $footer_widgets = fw_get_db_customizer_option('happify_footer_widget_layout');
      }
      $footer_widgets = isset( $footer_widgets ) ? $footer_widgets : '';
      
      if( $footer_widgets ) {

        switch ( $footer_widgets ) {
          case 3:
            $length = 3;
          break;

          case 4:
            $length = 4;
          break;

          default:
            $length = $footer_widgets;
          break;
        }

        for( $i = 0; $i < $length; $i++ ) {

          $num = ( $i+1 );

          register_sidebar( array(
            'id'            => 'footer-' . $num,
            'name'          => esc_html__( 'Footer Widget ', 'happify' ). $num,
            'description'   => esc_html__( 'Appears on footer section.', 'happify' ),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '<div class="clear"></div></div> <!-- end widget -->',
            'before_title'  => '<div class="widget-title"><h3>',
            'after_title'   => '</h3></div>'
          ) );

        }

      }
      // Footer Widgets


    }
  }
  add_action( 'widgets_init', 'happify_widget_init' );
}


/* Widget Layouts */
if ( ! function_exists( 'happify_footer_widgets' ) ) {
  function happify_footer_widgets() {

    $output = '';
    if ( defined('FW') ) {
      $happify_footer_widget_layout = fw_get_db_customizer_option('happify_footer_widget_layout');
    }
    $happify_footer_widget_layout = isset( $happify_footer_widget_layout ) ? $happify_footer_widget_layout : '';

    if( $happify_footer_widget_layout ) {

      switch ( $happify_footer_widget_layout ) {
        case 1: $widget = array('piece' => 1, 'class' => 'col col-lg-12'); break;
        case 2: $widget = array('piece' => 2, 'class' => 'col col-lg-6'); break;
        case 3: $widget = array('piece' => 3, 'class' => 'col col-lg-4'); break;
        case 4: $widget = array('piece' => 4, 'class' => 'col col-lg-3 col-md-3 col-sm-6'); break;
        default : $widget = array('piece' => 4, 'class' => 'col-lg-3'); break;
      }

      for( $i = 1; $i < $widget["piece"]+1; $i++ ) {

        $widget_class = ( isset( $widget["queue"] ) && $widget["queue"] == $i ) ? $widget["layout"] : $widget["class"];

        if (is_active_sidebar('footer-'. $i)) {
          $output .= '<div class="'. $widget_class .'">';
          ob_start();
            dynamic_sidebar( 'footer-'. $i );
          $output .= ob_get_clean();
          $output .= '</div>';
        }

      }
    }

    return $output;

  }
}