<?php

add_action( 'wp_enqueue_scripts', function () {
    if( isset( $_GET['tailwind'] ) ) {
        wp_enqueue_script( 'tailwind-cdn', 'https://cdn.tailwindcss.com' );
		$options = get_option( 'gutailberg_options', array() );
		if ( ! empty( $options['gutailberg_field_tailwind_config'] ) ) {
			wp_add_inline_script(
				'tailwind-cdn',
				$options['gutailberg_field_tailwind_config']
			);
		}
    // } else {
    //     wp_enqueue_style( 'tailwind', get_template_directory_uri() . '/assets/styles/tailwind.css', array(), time() );
    }
} );
