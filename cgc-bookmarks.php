<?php
/**
 *
 * @package   CGC Bookmarks
 * @author    Nick Haskins <nick@cgcookie.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 *
 * Plugin Name:       CGC Bookmarks
 * Plugin URI:        http://cgcookie.com
 * Description:       Creates a bookmarking system
 * Version:           5.0
 * GitHub Plugin URI: https://github.com/cgcookie/cgc-bookmarks
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Set some constants
define('CGC_BOOKMARKS_VERSION', '5.0');
define('CGC_BOOKMARKS_DIR', plugin_dir_path( __FILE__ ));
define('CGC_BOOKMARKS_URL', plugins_url( '', __FILE__ ));

require_once( plugin_dir_path( __FILE__ ) . 'public/class-cgc-bookmarks.php' );

register_activation_hook( __FILE__, array( 'CGC_Bookmarks', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CGC_Bookmarks', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'CGC_Bookmarks', 'get_instance' ) );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-cgc-bookmarks-admin.php' );
	add_action( 'plugins_loaded', array( 'CGC_Bookmarks_Admin', 'get_instance' ) );

}
