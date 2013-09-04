<?php
$oldURL = dirname( __FILE__ );
$newURL = str_replace( DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'cgc-bookmarks' . DIRECTORY_SEPARATOR . 'includes', '', $oldURL );
include $newURL . DIRECTORY_SEPARATOR . 'wp-load.php';

global $wpdb, $user_ID;
$cgcb_db_table = "cgc_bookmarks";

if ( $user_ID != absint( $_POST['cgcb_user_id'] ) ) {
	die( 'Nice try buddy' );
}

$post = ( !empty( $_POST ) ) ? true : false;
if ( $post ) // if data is being sent
{
	// delete post type
	if ( isset( $_POST['bookmark_post'] ) ) {
		$add_bookmark = $wpdb->insert(
			$cgcb_db_table,
			array(
				'user_id'    => absint( $_POST['cgcb_user_id'] ),
				'post_url'   => $_POST['cgcb_post_url'],
				'post_title' => $_POST['cgcb_post_title'],
				'image_url'  => $_POST['cgc_image_url']
			),
			array( '%d', '%s', '%s', '%s' )
		);
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		switch_to_blog( 1 );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		restore_current_blog();
	}
	// delete post type
	if ( isset( $_POST['remove_bookmark'] ) ) {
		$remove = $wpdb->query( $wpdb->prepare( "DELETE FROM " . $cgcb_db_table . " WHERE user_id='%d' AND post_url='%s';", absint( $_POST['cgcb_user_id'], sanitize_text_field( $_POST['cgcb_post_url'] ) ) ) );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		switch_to_blog( 1 );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		restore_current_blog();
	}
}
