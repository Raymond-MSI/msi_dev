<?php
/**
 * Add sidebar class in body
 *
 * @since Intimate 1.0.0
 *
 */

add_filter('body_class', 'intimate_body_class');
function intimate_body_class($classes)
{
    $classes[] = 'at-sticky-sidebar';
    global $intimate_theme_options;
    
    if (is_singular()) {
        $sidebar = $intimate_theme_options['intimate-sidebar-single-page'];
        if ($sidebar == 'single-no-sidebar') {
            $classes[] = 'single-no-sidebar';
        } elseif ($sidebar == 'single-left-sidebar') {
            $classes[] = 'single-left-sidebar';
        } elseif ($sidebar == 'single-middle-column') {
            $classes[] = 'single-middle-column';
        } else {
            $classes[] = 'single-right-sidebar';
        }
    }
    
    $sidebar = $intimate_theme_options['intimate-sidebar-blog-page'];
    if ($sidebar == 'no-sidebar') {
        $classes[] = 'no-sidebar';
    } elseif ($sidebar == 'left-sidebar') {
        $classes[] = 'left-sidebar';
    } elseif ($sidebar == 'middle-column') {
        $classes[] = 'middle-column';
    } else {
        $classes[] = 'right-sidebar';
    }
    return $classes;
}