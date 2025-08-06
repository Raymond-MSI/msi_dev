<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package happify
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

if ( is_page() ) {
	$happify_sidebar_id = 'sidebar-2';
} else {
	$happify_sidebar_id = 'sidebar-1';
}

?>
<div class="sidebar-wrapper">
	<aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( $happify_sidebar_id ); ?>
	</aside><!-- #secondary -->
</div>







