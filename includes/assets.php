<?php
add_action( 'wp_loaded', 'gutailberg_register_assets' );
add_action( 'wp_enqueue_scripts', 'gutailberg_enqueue_frontend_assets' );
add_action( 'admin_enqueue_scripts', 'gutailberg_enqueue_admin_assets' );
add_action( 'enqueue_block_editor_assets', 'gutailberg_enqueue_block_editor_assets' );
add_action( 'enqueue_block_assets', 'gutailberg_enqueue_block_assets' );
add_filter( 'option_gutailberg_options', 'gutailberg_default_options' );

function gutailberg_print_tailwind_custom_css() {
	$custom_css = '';

	if ( gutailberg_get_tailwind_custom_css_paths() ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		$filesystem = new WP_Filesystem_Direct( true );
		$custom_css = '';
		foreach ( (array) gutailberg_get_tailwind_custom_css_paths() as $path ) {
			if ( $filesystem->exists( $path ) ) {
				$custom_css .= $filesystem->get_contents( $path )	;
			}
		}
	}

	if ( ! $custom_css ) {
		$options = get_option( 'gutailberg_options' );
		$custom_css = $options['gutailberg_field_tailwind_custom_css'];
	}

	if ( $custom_css ) {
		printf( '<style type="text/tailwindcss">%s</style>', $custom_css );
	}
}

function gutailberg_enqueue_frontend_assets() {
	$options = get_option( 'gutailberg_options' );
	if (
		is_admin() ||
		empty( $options['gutailberg_field_tailwind_output'] ) ||
		isset( $_GET['tailwindcss'] )
	) {
		wp_enqueue_script( 'gutailberg-tailwindcss-cdn' );
		wp_enqueue_script( 'gutailberg-tailwindcss-config' );
		gutailberg_print_tailwind_custom_css();
	} else {
		wp_enqueue_style( 'gutailberg-generated-css' );
	}
}

function gutailberg_enqueue_admin_assets() {
	if ( 'tools_page_gutailberg' !== get_current_screen()->id ) {
		return;
	}

	wp_enqueue_script( 'gutailberg-tailwindcss-cdn' );
	wp_enqueue_script( 'gutailberg-tailwindcss-config' );
	wp_enqueue_script( 'gutailberg-settings' );
	gutailberg_print_tailwind_custom_css();
}

function gutailberg_enqueue_block_editor_assets() {
	wp_enqueue_script( 'gutailberg-editor' );
}

function gutailberg_enqueue_block_assets() {
	if ( ! is_admin() ) {
		return;
	}

	$options = get_option( 'gutailberg_options' );

	if ( $options['gutailberg_field_tailwind_editor'] ?? false ) {
		wp_enqueue_script( 'gutailberg-tailwindcss-cdn' );
		wp_enqueue_script( 'gutailberg-tailwindcss-config' );
		add_action( 'wp_print_styles', 'gutailberg_print_tailwind_custom_css' );
	}

}

function gutailberg_get_tailwind_config_url() {
	return apply_filters( 'gutailberg_tailwind_config_url', null );
}

function gutailberg_get_tailwind_custom_css_paths() {
	return apply_filters( 'gutailberg_tailwind_custom_css_path', array() );
}

function gutailberg_register_assets() {
	$options = get_option( 'gutailberg_options' );

	wp_register_style( 'gutailberg-generated-css', false );
    wp_add_inline_style( 'gutailberg-generated-css', $options['gutailberg_field_tailwind_output'] );

	wp_register_script( 'gutailberg-tailwindcss-cdn', 'https://cdn.tailwindcss.com', array(), null );
	wp_register_script( 'gutailberg-tailwindcss-context', plugins_url( '/assets/js/tailwind-context.js', __DIR__ ), array(), null );

	if ( gutailberg_get_tailwind_config_url() ) {
		wp_register_script( 'gutailberg-tailwindcss-config', gutailberg_get_tailwind_config_url(), array(), null  );
	} else {
		wp_register_script( 'gutailberg-tailwindcss-config', false );
		$config = 'window.tailwind = window.tailwind ?? {};' . $options['gutailberg_field_tailwind_config'];
		wp_add_inline_script( 'gutailberg-tailwindcss-config', $config );
	}

	if ( file_exists( dirname( __DIR__ ) . '/build/settings.asset.php' ) ) {
		$asset = require dirname( __DIR__ ) . '/build/settings.asset.php';
		wp_register_script( 'gutailberg-settings', plugins_url( '/build/settings.js', __DIR__ ), $asset['dependencies'], $asset['version'] );
	}

	if ( file_exists( dirname( __DIR__ ) . '/build/editor.asset.php' ) ) {
		$asset = require dirname( __DIR__ ) . '/build/editor.asset.php';
		if ( $options['gutailberg_field_tailwind_suggestion'] ?? false ) {
			$asset['dependencies'][] = 'gutailberg-tailwindcss-context';
			$asset['dependencies'][] = 'gutailberg-tailwindcss-config';
		}
		wp_register_script( 'gutailberg-editor', plugins_url( '/build/editor.js', __DIR__ ), $asset['dependencies'], $asset['version'] );
	}
}

function gutailberg_default_options( $options ) {
	$default_config = 'tailwind.config = {
		corePlugins: {
			preflight: false
		}
	}';

	return wp_parse_args(
		$options,
		array(
			'gutailberg_field_tailwind_config'     => $default_config,
			'gutailberg_field_tailwind_custom_css' => '',
			'gutailberg_field_tailwind_output'     => '',
			'gutailberg_field_tailwind_editor'     => false,
			'gutailberg_field_tailwind_suggestion' => false,
		)
	);
}
