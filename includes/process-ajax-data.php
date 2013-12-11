<?php
$oldURL = dirname( __FILE__ );
$newURL = str_replace( DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'cgc-bookmarks' . DIRECTORY_SEPARATOR . 'includes', '', $oldURL );
include $newURL . DIRECTORY_SEPARATOR . 'wp-load.php';

global $wpdb, $user_ID;
$cgcb_db_table = "cgc_bookmarks";

$post = ( !empty( $_POST ) ) ? true : false;
if ( $post ) // if data is being sent
{
	// delete post type
	if ( isset( $_POST['bookmark_post'] ) ) {
		$add_bookmark = $wpdb->insert(
			$cgcb_db_table,
			array(
				'user_id' => $_POST['cgcb_user_id'],
				'post_url' => $_POST['cgcb_post_url'],
				'post_title' => $_POST['cgcb_post_title'],
				'image_url' => $_POST['cgc_image_url']
			),
			array( '%d', '%s', '%s', '%s' )
		);
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		
		switch_to_blog( 1 );
		global $user_ID;
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		restore_current_blog();
		
		if ( class_exists( 'CWS_Fragment_Cache' ) ) {
			$frag = new CWS_Fragment_Cache( 'cgc-recent-bookmarks-' . $user_ID, 3600 );
			$frag->flush();
		}
		if ( $add_bookmark )
			die( '1' );
		else
			die( '0' );
	}
	// delete post type
	if ( isset( $_POST['remove_bookmark'] ) ) {
		$url = str_replace( network_home_url(), '', $_POST['cgcb_post_url'] );
		$remove = $wpdb->query( $wpdb->prepare( "DELETE FROM " . $cgcb_db_table . " WHERE user_id='%d' AND post_url LIKE '%%s%%';", absint( $_POST['cgcb_user_id'] ), $url ) );
		
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		
		switch_to_blog( 1 );
		global $user_ID;
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_images_count' );
		delete_transient( 'cgc_user_' . $user_ID . '_bookmarked_posts_count' );
		restore_current_blog();
		
		if ( class_exists( 'CWS_Fragment_Cache' ) ) {
			$frag = new CWS_Fragment_Cache( 'cgc-recent-bookmarks-' . $user_ID, 3600 );
			$frag->flush();
		}
		if ( $remove )
			echo '1';
		else
			echo '0';

		exit;

	}
}
