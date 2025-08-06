<?php
/**
 * Functions to manage breadcrumbs
 */
if (!function_exists('intimate_breadcrumb_options')) :
    function intimate_breadcrumb_options()
    {
        global $intimate_theme_options;
        if (1 == $intimate_theme_options['intimate-extra-breadcrumb']) {
            intimate_breadcrumbs();
        }
    }
endif;
add_action('intimate_breadcrumb_options_hook', 'intimate_breadcrumb_options');

/**
 * BreadCrumb Settings
 */
if (!function_exists('intimate_breadcrumbs')):
    function intimate_breadcrumbs()
    {
        global $intimate_theme_options;
        $breadcrumb_from = $intimate_theme_options['intimate-breadcrumb-selection-option'];        
        if ((function_exists('yoast_breadcrumb')) && ($breadcrumb_from == 'yoast-breadcrumb')) {
            yoast_breadcrumb();
        }elseif('rankmath' == $breadcrumb_from && (function_exists('rank_math_the_breadcrumbs'))) {
          rank_math_the_breadcrumbs();
        }elseif ((function_exists('bcn_display')) && ($breadcrumb_from == 'navxt-breadcrumb')) {
            bcn_display();
        }else{

            if (!function_exists('intimate_breadcrumb_trail')) {
                require get_template_directory() . '/templatesell/breadcrumbs/breadcrumbs.php';
            }
            $breadcrumb_args = array(
                'container' => 'div',
                'show_browse' => false
            );        
            intimate_breadcrumb_trail($breadcrumb_args);
        }
    }
endif;
add_action('intimate_breadcrumbs_hook', 'intimate_breadcrumbs');