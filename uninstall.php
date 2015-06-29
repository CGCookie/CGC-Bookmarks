<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   CGC_Bookmarks
 * @author    Nick Haskins <nick@cgcookie.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$table = $wpdb->prefix.'cgc_bookmarks';

$wpdb->query("DROP TABLE IF EXISTS $table");