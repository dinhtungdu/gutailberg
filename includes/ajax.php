<?php
function gutailberg_get_fse_templates() {
	if ( ! class_exists( 'ZipArchive' ) ) {
		return new WP_Error( 'missing_zip_package', __( 'Zip Export not supported.' ) );
	}

	$theme_zip_file = wp_generate_block_templates_export_file();
    $zip            = new ZipArchive();
	$contents       = '';
	$templates      = array();

    if ($zip->open($theme_zip_file) !== true) {
        echo 'Can not open theme export archive!';
        return false;
    }

    // As long as statIndex() does not return false keep iterating
    for ($idx = 0; $zipFile = $zip->statIndex($idx); $idx++) {
		$pattern = '/^(templates|parts|patterns)\/.*\.(html|php)$/';
		if (preg_match($pattern, $zipFile['name'])) {
			$templates[] = $zipFile['name'];
			$contents .= $zip->getFromIndex($idx);
		}

    }

    $zip->close();
	unlink($theme_zip_file);

	wp_send_json_success( array(
		'templates' => $templates,
		'contents'  => $contents,
	) );

	wp_die();
}
add_action( 'wp_ajax_gutailberg_get_fse_templates', 'gutailberg_get_fse_templates' );
