<?php

/**
*	Class responsible for processing the ajax call to bookmark or unbookmark a post
*	@since 5.0
*/
class cgcProcessBookmarking {

	public function __construct(){

		add_action('wp_ajax_process_bookmark',		array($this,'process_bookmarking'));
		add_action('wp_ajax_process_unbookmark',		array($this,'process_bookmarking'));

	}

	public function process_bookmarking(){

		if ( isset( $_POST['action'] ) ) {

	    	$user_id 	= get_current_user_id();

	    	$post_id 	= isset( $_POST['post_id'] ) ? $_POST['post_id'] : false;

	    	if ( empty ( $post_id ) )
	    		return;

			if ( $_POST['action'] == 'process_bookmark' && wp_verify_nonce( $_POST['nonce'], 'process_bookmark' )  ) {

				// bail out if this user has already bookmarked this item
				if ( cgc_has_user_bookmarked( $user_id, $post_id ) ) {

					wp_send_json_success( array('message' => 'already-bookmarked') );

				} else {

					cgc_bookmark_something( $user_id, $post_id );

					do_action('cgc_user_bookmarked', $user_id, $post_id );

					wp_send_json_success( array( 'message'=> 'bookmarked' ) );
				}

			} elseif ( $_POST['action'] == 'process_unbookmark' && wp_verify_nonce( $_POST['nonce'], 'process_bookmark' ) ) {

	    		// do unlovin

		        wp_send_json_success();

			} else {

				wp_send_json_error();

			}

		} else {

			wp_send_json_error();

		}

	}

}
new cgcProcessBookmarking;