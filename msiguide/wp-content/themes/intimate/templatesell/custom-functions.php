<?php
/**
 * Function to list categories of a post
 *
 * @param int $post_id
 * @return void Lists of categories with its link
 *
 * @since 1.0.0
 *
 */
if (!function_exists('intimate_list_category')) :
    function intimate_list_category($post_id = 0)
    {

        if (0 == $post_id) {
            global $post;
            if (isset($post->ID)) {
                $post_id = $post->ID;
            }
        }
        if (0 == $post_id) {
            return null;
        }
        $categories = get_the_category($post_id);
        $separator = '';
        $output = '';
        if ($categories) {
            $output .= '<ul class="list-inline cat-links">';
            foreach ($categories as $category) {
                $output .= '<li class="list-inline-item"><a href="' . esc_url(get_category_link($category->term_id)) . '"  rel="category tag">' . esc_html($category->cat_name) . '</a></li>' . $separator;
            }
            $output .= '</ul>';
            echo trim($output, $separator);
        }

    }
endif;

/*add menu description*/
if (!function_exists('intimate_add_menu_description')) :
function intimate_add_menu_description( $item_output, $item, $depth, $args ) {

    if( 'menu-1' == $args->theme_location  && $item->description )
        $item_output = str_replace( '</a>', '<span class="menu-description">' . $item->description . '</span></a>', $item_output );

    return $item_output;
}
endif;
add_filter( 'walker_nav_menu_start_el', 'intimate_add_menu_description', 10, 4 );