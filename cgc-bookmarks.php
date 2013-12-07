<?php
/*
Plugin Name: CG Cookie Bookmarks
Plugin URI: http://pippinspages.com/
Description: Enables user bookmarks for posts and pages
Version: 1.0
Author: Pippin Williamson
Author URI: http://pippinspages.com
*/

// globals
global $wpdb;

// plugin root folder
global $cgcbbaseDir;
$cgcbbaseDir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));

// bug report table
global $cgcb_db_table;
$cgcb_db_table = "cgc_bookmarks";

// table version
global $cgcbdb_table_version;
$cgcbdb_table_version = '1.1';

// bug report setting base
global $base_bookmarks_setting;
$base_bookmarks_setting = 'cgcb';


function cgcb_install()
{
	global $wpdb;
	global $cgcb_db_table;
	global $cgcbdb_table_version;

	if($wpdb->get_var("show tables like '$cgcb_db_table'") != $cgcb_db_table)
	{
		$sql = "CREATE TABLE " . $cgcb_db_table . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id tinytext NOT NULL,
		post_url tinytext NOT NULL,
		post_title text NOT NULL,
		image_url tinytext NOT NULL,
		UNIQUE KEY id (id)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		add_option("cgcb_database_version", $cgcbdb_table_version);
		// enable this is table is upgraded
		//add_option("cgcb_database_updated", 'true');
	}
	// check to see if the slug column needs added for post types
	if(!$wpdb->query("SELECT `image_url` FROM `" . $cgcb_db_table . "`"))
	{
		$wpdb->query("ALTER TABLE `" . $cgcb_db_table . "` ADD `image_url` tinytext");
		update_option('cgcb_database_version', $cgcbdb_table_version );
	}
}
register_activation_hook( __FILE__, 'cgcb_install' );

if(!is_admin()) {
	include(dirname(__FILE__) . '/includes/scripts.php');
	include(dirname(__FILE__) . '/includes/bookmark.php');
	include(dirname(__FILE__) . '/includes/list-bookmarks.php');
	include(dirname(__FILE__) . '/includes/misc-functions.php');
}

