<?php
function cgcb_rstrstr($haystack,$needle)
{
    return substr($haystack, 0,strpos($haystack, $needle));
}

function cgc_bookmarked_images_count() {
	
	global $wpdb, $cgcb_db_table, $user_ID;
	$images = get_transient('cgc_user_' . $user_ID . '_bookmarked_images_count');
	if($images === false) {
		$images = $wpdb->get_results( "SELECT id FROM " . $cgcb_db_table . " WHERE (user_id='" . $user_ID . "' AND post_url LIKE '%images/%');");
		set_transient('cgc_user_' . $user_ID . '_bookmarked_images_count', $images, 7200);
	}
	if($images) {
		return count($images);
	}
	
	return 0;
}

function cgc_bookmarked_posts_count() {
	
	global $wpdb, $cgcb_db_table, $user_ID;
	
	$bookmarks = get_transient('cgc_user_' . $user_ID . '_bookmarked_posts_count');
	if($bookmarks === false) {
		$bookmarks = $wpdb->get_results( "SELECT id FROM " . $cgcb_db_table . " WHERE (user_id='" . $user_ID . "' AND post_url NOT LIKE '%images/%');");
		set_transient('cgc_user_' . $user_ID . '_bookmarked_posts_count', $bookmarks, 7200);
	}

	if($bookmarks) {
		return count($bookmarks);
	}
	
	return 0;
}