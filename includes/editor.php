<?php
add_action( 'enqueue_block_editor_assets', function() {
	if ( file_exists( dirname( __DIR__ ) . '/build/editor.asset.php' ) ) {
		$asset = require dirname( __DIR__ ) . '/build/editor.asset.php';
		wp_enqueue_script(
			'gutailberg-editor',
			plugins_url( '/build/editor.js', __DIR__ ),
			$asset['dependencies'],
			$asset['version']
		);
	}
} );
