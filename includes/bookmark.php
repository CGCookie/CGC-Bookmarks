<?php
/*************************************************************************
this file contains all of the functions used to add and delete bookmarks
************************************************************************/

function cgc_add_remove_bookmark(
	$links = array(
		'add' => '<i class="icon-bookmark icon-bookmark-empty"></i>',
		'remove' => '<i class="icon-bookmark"></i>'
	) ) {


	global $post;
	global $wpdb;
	global $user_ID;
	global $cgcb_db_table;

	$current_post_title = $post->post_title;

	$user_bookmarks = array();
	$bookmarks = $wpdb->get_results("SELECT post_title FROM " . $cgcb_db_table . " WHERE user_id='" . $user_ID . "';");
	foreach(  $bookmarks as $bookmark ) {
		$user_bookmarks[] = $bookmark->post_title;
	}

	if(!in_array($post->post_title, $user_bookmarks)) {
		$link = '<a href="#" class="cgc_bookmark cgc_bookmark_add" title="Add Bookmark" rel="nofollow" name="cgc_user_' . $user_ID . '">' . $links['add'] . '</a>';
	} else {
		$link = '<a href="#" class="cgc_bookmark cgc_bookmark_remove" title="Remove Bookmark" rel="nofollow" name="cgc_user_' . $user_ID . '">' . $links['remove'] . '</a>';
	}
	return apply_filters('cgc_add_remove_bookmark', $link);
}
