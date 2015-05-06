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


				cgc_add_bookmark( $user_id, $post_id );

				wp_send_json_success();


			} elseif ( $_POST['action'] == 'process_unbookmark' && wp_verify_nonce( $_POST['nonce'], 'process_bookmark' ) ) {

	    		cgc_remove_bookmark( $user_id, $post_id );

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