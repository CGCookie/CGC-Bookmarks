<?php

add_action( 'admin_menu', 'pig_admin_menu' );

function pig_admin_menu(){
	add_media_page( 'Image Repair', 'Image Repair', 'delete_posts', 'pig-image-repair', 'pig_admin_image_repair' );
}

function pig_admin_image_repair(){

	ini_set( 'display_errors', '1');
	error_reporting(E_ALL);

	$images = new WP_Query( array(
		'post_type' => 'images',
		'posts_per_page' => '-1',
		'meta_query' => array(
			array(
				'meta_key' => '_pig_image_404',
				'meta_compare' => '!=',
				'meta_value' => ''
			)
		)
	) );


	echo'<pre>';var_dump($images);echo'</pre>';
	exit();

	include( 'admin-page.php' );
}
