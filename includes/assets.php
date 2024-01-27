<?php
add_action( 'wp_loaded', 'gutailberg_register_assets' );
add_action( 'wp_enqueue_scripts', 'gutailberg_enqueue_frontend_assets' );
add_action( 'admin_enqueue_scripts', 'gutailberg_enqueue_admin_assets' );
add_action( 'enqueue_block_editor_assets', 'gutailberg_enqueue_block_editor_assets' );
add_action( 'enqueue_block_assets', 'gutailberg_enqueue_block_assets' );
add_filter( 'option_gutailberg_options', 'gutailberg_default_options' );

function gutailberg_enqueue_frontend_assets() {
	$options = get_option( 'gutailberg_options', array() );
	if (
		is_admin() ||
		empty( $options['gutailberg_field_tailwind_output'] ) ||
		isset( $_GET['tailwindcss'] )
	) {
		wp_enqueue_script( 'gutailberg-tailwindcss-cdn' );
	} else {
		wp_enqueue_style( 'gutailberg-generated-css' );
	}
}

function gutailberg_enqueue_admin_assets() {
	if ( 'tools_page_gutailberg' !== get_current_screen()->id ) {
		return;
	}

	wp_enqueue_script( 'gutailberg-tailwindcss-cdn' );
	wp_enqueue_script( 'gutailberg-settings' );
}

function gutailberg_enqueue_block_editor_assets() {
	wp_enqueue_script( 'gutailberg-tailwindcss-context' );
	wp_enqueue_script( 'gutailberg-editor' );
}

function gutailberg_enqueue_block_assets() {
	if ( ! is_admin() ) {
		return;
	}

	$options = get_option( 'gutailberg_options', array() );

	if ( $options['gutailberg_field_tailwind_editor'] ?? false ) {
		wp_enqueue_script( 'gutailberg-tailwindcss-cdn' );
	}
}

function gutailberg_register_assets() {
	$options = get_option( 'gutailberg_options', array() );

	wp_register_style( 'gutailberg-generated-css', false );
    wp_add_inline_style( 'gutailberg-generated-css', $options['gutailberg_field_tailwind_output'] );

	wp_register_script( 'gutailberg-tailwindcss-cdn', 'https://cdn.tailwindcss.com', array(), null );
	wp_add_inline_script( 'gutailberg-tailwindcss-cdn', $options['gutailberg_field_tailwind_config'] );

	wp_register_script( 'gutailberg-tailwindcss-context', plugins_url( '/assets/js/tailwind-context.js', __DIR__ ), array(), null );
	$config = 'window.tailwind = window.tailwind ?? {};' . $options['gutailberg_field_tailwind_config'];
	wp_add_inline_script( 'gutailberg-tailwindcss-context', $config );

	if ( file_exists( dirname( __DIR__ ) . '/build/settings.asset.php' ) ) {
		$asset = require dirname( __DIR__ ) . '/build/settings.asset.php';
		wp_register_script( 'gutailberg-settings', plugins_url( '/build/settings.js', __DIR__ ), $asset['dependencies'], $asset['version'] );
	}

	if ( file_exists( dirname( __DIR__ ) . '/build/editor.asset.php' ) ) {
		$asset = require dirname( __DIR__ ) . '/build/editor.asset.php';
		wp_register_script( 'gutailberg-editor', plugins_url( '/build/editor.js', __DIR__ ), $asset['dependencies'], $asset['version'] );
	}
}

function gutailberg_default_options( $options ) {
	if ( ! empty( $options['gutailberg_field_tailwind_config'] ) ) {
		return $options;
	}

	$options['gutailberg_field_tailwind_config'] ?? 'tailwind.config = {
		corePlugins: {
			preflight: false
		}
	}';

	return $options;
}
