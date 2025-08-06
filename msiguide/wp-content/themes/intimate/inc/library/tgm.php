<?php
/**
 * Recommended plugins
 *
 * @package Intimate 1.0.3
 */

if ( ! function_exists( 'intimate_recommended_plugins' ) ) :

    /**
     * Recommend plugin list.
     *
     * @since 1.0.3
     */
    function intimate_recommended_plugins() {

        $plugins = array(
            array(
                'name'     => esc_html__( 'One Click Demo Import', 'intimate' ),
                'slug'     => 'one-click-demo-import',
                'required' => false,
            ),
            array(
                'name'     => __( 'Template Sell Demo Importer', 'intimate' ),
                'slug'     => 'template-sell-demo-importer',
                'required' => false,
            ),
        );

        tgmpa( $plugins );

    }

endif;

add_action( 'tgmpa_register', 'intimate_recommended_plugins' );
