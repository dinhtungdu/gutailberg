<?php
add_action( 'enqueue_block_editor_assets', function() {
	if ( file_exists( dirname( __DIR__ ) . '/build/index.asset.php' ) ) {
		$asset = require dirname( __DIR__ ) . '/build/index.asset.php';
		wp_enqueue_script(
			'gutailberg',
			plugins_url( '/build/index.js', __DIR__ ),
			$asset['dependencies'],
			$asset['version']
		);
	}
} );
