<?php

/**
*	Bookmark something
*
*	@param $user_id int id of the current user doing the loving
*	@param $post_id int id of the post the user is loving
*	@since 5.0
*/
function cgc_add_bookmark( $user_id = 0, $post_id = 0 ) {

	// if user is empty grab the current
	if ( empty( $user_id ) )
		$user_id = get_current_user_ID();

	// if teh post id is empty grab the current
	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	// bail out if this user has already bookmarked this item
	if ( false !== cgc_has_user_bookmarked( $user_id, $post_id ) )
		return;

	// add the love
	$db = new CGC_Bookmarks_DB;
	$out =  $db->add_bookmark( array( 'user_id' => $user_id, 'post_id' => $post_id ) );
}


/**
*	Remove a bookmark
*
*	@param $user_id int id of the users to delete the bookmark for
*	@param $post_id int id of the bookmarked post
*	@since 5.0
*/
function cgc_remove_bookmark( $user_id = 0, $post_id = 0 ){

	if ( empty( $user_id ) || empty( $post_id ) )
		return;

	$db = new CGC_Bookmarks_DB;

	$args = array(
		'user_id' 	=> $user_id,
		'post_id'	=> $post_id
	);

	$db->remove_bookmark( $args );

}

/**
*	Get the number of bookmarks for a post id
*	@param $post_id int id of the post that we're getting the bookmarks for
*	@since 5.0
*/
function cgc_get_bookmarks( $post_id = 0, $count = true ) {

	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	$db = new CGC_Bookmarks_DB;
	$out = $db->get_bookmarks( $post_id );

	return true == $count ? count( $out ) : $out;

}

/**
*	Get the items a specific user has bookmarked
*	@param $user_id int id of the user that we're getting items for
*	@since 5.0
*/
function cgc_get_users_bookmarks( $user_id = 0, $count = false ) {

	if ( empty( $user_id ) )
		return;

	$db = new CGC_Bookmarks_DB;
	$out = $db->get_user_bookmarks( $user_id );

	return true == $count ? count( $out ) : $out;

}

/**
*	Check if a user has bookmarked something
*	@param $user_id int id of the user that we're checking for
*	@param $post_id int id of the post that we're checking
*	@since 5.0
*/
function cgc_has_user_bookmarked( $user_id = 0, $post_id = 0 ) {

	// if user is empty grab the current
	if ( empty( $user_id ) )
		$user_id = get_current_user_ID();

	// if teh post id is empty grab the current
	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	// return result
	$db = new CGC_Bookmarks_DB;
	$out = $db->has_bookmarked( $user_id , $post_id );

	return $out;
}