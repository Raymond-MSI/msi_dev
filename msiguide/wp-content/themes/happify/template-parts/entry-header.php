<?php 
/**
 * entry-header
 *
 * @package Happify
 */

if ( defined('FW') ) {
  $hide_title_bar = fw_get_db_post_option(get_the_ID(), 'happify_hide_title', '');
  $header_banner_image = fw_get_db_post_option(get_the_ID(), 'happify_header_banner_image', '');
}

$hide_title_bar = isset( $hide_title_bar ) ? $hide_title_bar : '';
$header_banner_image = isset( $header_banner_image ) ? $header_banner_image : '';

$happify_entry_header = get_custom_header();

if( $header_banner_image ) {
    $entry_header_bg = 'background-image: url('. esc_url( $header_banner_image['url'] ) .');';
} else {
    $entry_header_bg = 'background-image: url('. esc_url( $happify_entry_header->url ) .');';
}

if ( !$hide_title_bar ) { ?>
 <div class="entry-header" style="<?php echo $entry_header_bg; ?>">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
               <h2><?php echo get_the_title(); ?></h2>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</div>
<?php }

