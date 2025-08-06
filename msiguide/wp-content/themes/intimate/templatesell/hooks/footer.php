<?php
/**
 * Goto Top functions
 *
 * @since Intimate 1.0.0
 *
 */

if (!function_exists('intimate_go_to_top')) :
    function intimate_go_to_top()
    {
    ?>
            <a id="toTop" class="go-to-top" href="#" title="<?php esc_attr_e('Go to Top', 'intimate'); ?>">
                <i class="fa fa-angle-double-up"></i>
            </a>
<?php
    } endif;
add_action('intimate_go_to_top_hook', 'intimate_go_to_top', 10, 1);