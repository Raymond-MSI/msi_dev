<?php
/**
 * Added intimate Page.
*/

/**
 * Add a new page under Appearance
 */
function intimate_menu() {
	add_menu_page( __( 'Intimate Theme Options', 'intimate' ), __( 'Intimate Theme Options', 'intimate' ), 'edit_theme_options', 'intimate-theme', 'intimate_page');
}
add_action( 'admin_menu', 'intimate_menu');

/**
 * Enqueue styles for the help page.
 */
function intimate_admin_scripts() {
	if(is_admin()){
		wp_enqueue_style( 'intimate-admin-style', get_template_directory_uri() . '/templatesell/about/about.css', array(), '' );
 }
} 
add_action( 'admin_enqueue_scripts', 'intimate_admin_scripts' );

/**
 * Add the theme page
 */
function intimate_page() {
	?>
	<div class="das-wrap">
		<div class="intimate-panel">
			<div class="intimate-logo">
				<img class="ts-logo" src="<?php echo esc_url( get_template_directory_uri() . '/templatesell/about/images/intimate-logo.png' ); ?>">
			</div>
			<a href="https://www.templatesell.com/item/intimate-plus/" target="_blank" class="btn btn-success pull-right"><?php esc_html_e( 'Upgrade Pro $59', 'intimate' ); ?></a>
			<p>
			<?php esc_html_e( 'A perfect theme for news and magazine site. With grid layout, multiple custom widgets, demo importer, it is the best theme for you. It has multiple home page multiple blog page layout, this theme is the awesome and minimal theme.', 'intimate' ); ?></p>
			<a class="btn btn-primary" href="<?php echo esc_url (admin_url( '/customize.php?' ));
				?>"><?php esc_html_e( 'Theme Options - Click Here', 'intimate' ); ?></a>
		</div>

		<div class="intimate-panel">
			<div class="intimate-panel-content">
				<div class="theme-title">
					<h3><?php esc_html_e( 'Looking for theme Documentation?', 'intimate' ); ?></h3>
				</div>
				<a href="http://www.docs.templatesell.net/intimate" target="_blank" class="btn btn-secondary"><?php esc_html_e( 'Documentation - Click Here', 'intimate' ); ?></a>
			</div>
		</div>
		<div class="intimate-panel">
			<div class="intimate-panel-content">
				<div class="theme-title">
					<h3><?php esc_html_e( 'If you like the theme, please leave a review', 'intimate' ); ?></h3>
				</div>
				<a href="https://wordpress.org/support/theme/intimate/reviews/#new-post" target="_blank" class="btn btn-secondary"><?php esc_html_e( 'Rate this theme', 'intimate' ); ?></a>
			</div>
		</div>
	</div>
	<?php
}
