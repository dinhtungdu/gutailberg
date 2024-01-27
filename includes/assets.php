<?php
add_action( 'wp_enqueue_scripts', 'gutailberg_enqueue_tailwind_script' );
add_action( 'admin_enqueue_scripts', 'gutailberg_enqueue_admin_script' );
add_action( 'enqueue_block_editor_assets', 'gutailberg_enqueue_block_editor_assets' );
add_action( 'enqueue_block_assets', 'gutailberg_enqueue_block_assets' );
add_filter( 'option_gutailberg_options', 'gutailberg_default_options' );

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

function gutailberg_enqueue_tailwind_output() {
	$options = get_option( 'gutailberg_options', array() );

	if ( empty( $options['gutailberg_field_tailwind_output'] ) ) {
		return;
	}

	wp_register_style( 'dummy-tailwind', false );
	wp_enqueue_style( 'dummy-tailwind' );
    wp_add_inline_style( 'dummy-tailwind', $options['gutailberg_field_tailwind_output'] );
}

function gutailberg_enqueue_tailwind_cdn() {
	wp_enqueue_script( 'tailwind-cdn', 'https://cdn.tailwindcss.com', array(), null );
	$options = get_option( 'gutailberg_options', array() );

	wp_add_inline_script( 'tailwind-cdn', $options['gutailberg_field_tailwind_config'] );
}

function gutailberg_enqueue_tailwind_context() {
	wp_enqueue_script(
		'tailwind-context',
		plugins_url( '/assets/js/tailwind-context.js', __DIR__ ),
		array(),
		null
	);
	$options = get_option( 'gutailberg_options', array() );
	$config = 'window.tailwind = window.tailwind ?? {};' . $options['gutailberg_field_tailwind_config'];
	wp_add_inline_script( 'tailwind-context', $config );
}

function gutailberg_enqueue_tailwind_script() {
	$options = get_option( 'gutailberg_options', array() );
	if (
		is_admin() ||
		empty( $options['gutailberg_field_tailwind_output'] ) ||
		isset( $_GET['tailwindcss'] )
	) {
		gutailberg_enqueue_tailwind_cdn();
	} else {
		gutailberg_enqueue_tailwind_output();
	}
}

function gutailberg_enqueue_admin_script() {
	if (
		'tools_page_gutailberg' !== get_current_screen()->id ||
		! file_exists( dirname( __DIR__ ) . '/build/settings.asset.php' )
	) {
		return;
	}

	gutailberg_enqueue_tailwind_cdn();

	$asset = require dirname( __DIR__ ) . '/build/settings.asset.php';
	wp_enqueue_script(
		'gutailberg-settings',
		plugins_url( '/build/settings.js', __DIR__ ),
		$asset['dependencies'],
		$asset['version']
	);
}

function gutailberg_enqueue_block_editor_assets() {
	if ( ! file_exists( dirname( __DIR__ ) . '/build/editor.asset.php' ) ) {
		return;
	}

	gutailberg_enqueue_tailwind_context();

	$asset = require dirname( __DIR__ ) . '/build/editor.asset.php';
	wp_enqueue_script(
		'gutailberg-editor',
		plugins_url( '/build/editor.js', __DIR__ ),
		$asset['dependencies'],
		$asset['version']
	);
}

function gutailberg_enqueue_block_assets() {
	if ( ! is_admin() ) {
		return;
	}

	$options = get_option( 'gutailberg_options', array() );

	if ( $options['gutailberg_field_tailwind_editor'] ?? false ) {
		gutailberg_enqueue_tailwind_cdn();
	}
}
