<?php

if ( ! function_exists( 'intimate_load_widgets' ) ) :

    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function intimate_load_widgets() {

        // Recent Post.
        register_widget( 'Intimate_Recent_Post_Sidebar' );

        // Author Widget.
        register_widget( 'Intimate_Author_Widget' );
		
		// Social Widget.
        register_widget( 'Intimate_Social_Widget' );

        //Post Slider
        register_widget( 'Intimate_Post_Slider' );

        //Tabbed Widget
        register_widget( 'Intimate_Tabbed' );

        //Grid Widget
        register_widget( 'Intimate_Post_Grid' );

        //Featured Widget
        register_widget( 'Intimate_Featured_Post_Content');

        //Featured Widget Slider
        register_widget( 'Intimate_Featured_Post_Slider');

        //Post Column Widget
        register_widget( 'Intimate_Post_Column');

        //Post Column Widget
        register_widget( 'Intimate_Latest_Post');
    }
endif;
add_action( 'widgets_init', 'intimate_load_widgets' );


