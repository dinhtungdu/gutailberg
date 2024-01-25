<?php
/**
 * Plugin Name:       Gutailberg
 * Description:       Use Tailwind in Gutenberg.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Tung Du
 * Author URI:        https://tungdu.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gutailberg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/includes/assets.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/editor.php';
require_once __DIR__ . '/includes/frontend.php';
require_once __DIR__ . '/includes/ajax.php';
