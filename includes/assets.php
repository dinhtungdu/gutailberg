<?php
add_action( 'wp_enqueue_scripts', 'enqueue_tailwind_script' );
add_action( 'admin_enqueue_scripts', 'admin_enqueue_tailwind_script' );

function enqueue_tailwind_output() {
	$options = get_option( 'gutailberg_options', array() );

	if ( empty( $options['gutailberg_field_tailwind_output'] ) ) {
		return;
	}

	wp_register_style( 'dummy-tailwind', false );
	wp_enqueue_style( 'dummy-tailwind' );
    wp_add_inline_style( 'dummpy-tailwind', $options['gutailberg_field_tailwind_output'] );
}

function enqueue_tailwind_cdn() {
	wp_enqueue_script( 'tailwind-cdn', 'https://cdn.tailwindcss.com' );
	$options = get_option( 'gutailberg_options', array() );
	if ( ! empty( $options['gutailberg_field_tailwind_config'] ) ) {
		wp_add_inline_script(
			'tailwind-cdn',
			$options['gutailberg_field_tailwind_config']
		);
	}
}


function enqueue_tailwind_script() {
	$options = get_option( 'gutailberg_options', array() );
	if (
		empty( $options['gutailberg_field_tailwind_output'] ) ||
		isset( $_GET['tailwindcss'] )
	) {
		enqueue_tailwind_cdn();
	} else {
		enqueue_tailwind_output();
	}
}

function admin_enqueue_tailwind_script() {
	if ( 'tools_page_gutailberg' !== get_current_screen()->id ) {
		return;
	}

	if ( file_exists( dirname( __DIR__ ) . '/build/settings.asset.php' ) ) {
		$asset = require dirname( __DIR__ ) . '/build/settings.asset.php';
		enqueue_tailwind_cdn();
		wp_enqueue_script(
			'gutailberg-settings',
			plugins_url( '/build/settings.js', __DIR__ ),
			$asset['dependencies'],
			$asset['version']
		);
	}
}
