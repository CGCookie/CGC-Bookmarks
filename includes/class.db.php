<?php

class CGC_Bookmarks_DB {


	private $table;
	private $db_version;

	function __construct() {

		global $wpdb;

		$this->table   		= $wpdb->base_prefix . 'cgc_bookmarks';
		$this->db_version = '1.0';

	}

	/**
	*	Add a love
	*
	*	@since 5.0
	*/
	public function add_bookmark( $args = array() ) {

		global $wpdb;

		$defaults = array(
			'user_id'		=> '',
			'post_id'		=> ''
		);

		$args = wp_parse_args( $args, $defaults );

		$add = $wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$this->table} SET
					`user_id`  		= '%d',
					`post_id`  		= '%d'
				;",
				absint( $args['user_id'] ),
				absint( $args['post_id'] )
			)
		);

		do_action( 'cgc_bookmark_added', $args, $wpdb->insert_id );

		if ( $add )
			return $wpdb->insert_id;

		return false;
	}

	/**
	*	Remove a bookmark
	*
	*	@since 5.0
	*/
	public function remove_bookmark( $args = array() ) {

	}

	/**
	*	Get the number of bookmarks for a specific post id
	*
	*	@since 5.0
	*/
	public function get_bookmarks( $post_id = 0 ) {

		global $wpdb;

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM {$this->table} WHERE `post_id` = '%d'; ", absint( $post_id ) ) );

		return $result;
	}

	/**
	*	Get the number of bookmarks for a specific user
	*
	*	@since 5.0
	*/
	public function get_user_bookmarks( $user_id = 0 ) {

		global $wpdb;

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM {$this->table} WHERE `user_id` = '%d'; ", absint( $user_id ) ) );

		return $result;
	}

	/**
	*	Has this user bookmarked a specific post id
	*
	*	@param $user_id int id of the user we're checking for
	*	@param $post_id int id of the post we're cecking to see if the user bookmarked
	*/
	public function has_bookmarked( $user_id = 0, $post_id = 0 ) {

		global $wpdb;

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT user_id FROM {$this->table} WHERE `post_id` = '%d' AND `user_id` = '%d'; ", absint( $post_id ), absint( $user_id ) ) );

		if ( $result )
			return $result;
		else
			return false;
	}

}