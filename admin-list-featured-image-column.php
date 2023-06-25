<?php
/**
 * Admin List Featured Image Column
 *
 * @package           Admin List Featured Image Column
 * @author            Dan Poynor
 * @link              https://danpoynor.com
 * @version           1.0.0
 * @copyright         2023 Dan Poynor
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Admin List Featured Image Column
 * Plugin URI:        https://danpoynor.com
 * Description:       Enables post-thumbnails and adds featured image thumbnails to admin list columns for selected post types. Use Settings > Admin List Featured Image Column to select which post types show the column, define column label, and set the column thumbnail max width.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Dan Poynor
 * Author URI:        https://danpoynor.com
 * Text Domain:       admin-list-featured-image-column
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

 if ( ! defined( 'ABSPATH' ) ) {
    // If this file is called directly, abort
    exit;
}

if ( is_admin() ) {
    // We are in admin mode
    require_once __DIR__ . '/admin/admin-list-featured-image-column-admin.php';
} else {
    // We are not in admin mode
    exit;
}