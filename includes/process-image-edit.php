<?php

/************************************
* Process Image Edits and Deletions
************************************/

function pig_process_image_edit() {

	global $blog_id;
	if( ! empty( $_POST ) ) {
		if ( isset( $_POST['pig-image-action'] ) && $_POST['pig-image-action'] == 'edit' ) {

			if ( ! is_user_logged_in() )
				return;


			// edit an image
			$image_id    		= strip_tags( stripslashes( $_POST['pig-image-id'] ) );   // get the image ID on the main site
			$site_id    		= strip_tags( stripslashes( $_POST['pig-subsite-id'] ) );   // get the ID of the site the image belongs to
			$name     			= strip_tags( stripslashes( $_POST['pig-image-title'] ) );   // get the name of the image
			$desc    			= strip_tags( stripslashes( $_POST['pig-image-desc'] ) );   // get the image description
			$url                = network_home_url( '/dashboard/' );
			$error    			= NULL;

			if ( !$name || $name == '' ) {
				$error .= 'Please enter a name for the image.<br/>';
			}
			if ( !$desc || $desc == 'Describe your image' ) {
				$error .= 'Please enter a description.<br/>';
			}

			$mature = isset( $_POST['pig-image-mature'] ) ? 'on' : false;

			// everything ok
			if ( ! $error ) {

				switch_to_blog( $site_id );

				$image = get_post( $image_id );

				if( ! $image )
					wp_die( 'Image not found!', 'Error' );

				if ( get_current_user_id() !== intval( $image->post_author ) )
					wp_die( 'You do not have permission to edit this image.', 'Error' );

				// update the image on the sub site
				$updated_sub_site_image_id = wp_update_post( array(
						'ID'   => $image_id,
						'post_title' => $name,
						'post_content' => $desc
					)
				);

				update_post_meta( $subsite_image_id, 'pig_mature', $mature );

				restore_current_blog();

				// the IMAGE post was created okay
				if ( $updated_sub_site_image_id ) {
					wp_redirect( $url . '?image-updated=1#gallery_tab' ); exit;
				} else {
					wp_redirect( $url . $url . '?image-updated=0#gallery_tab' ); exit;
				}
			} else {
				// if there's an error
				header( "Location: " . $url . '?image-updated=0&fields-empty=1#gallery_tab' );
			}
		} else if ( isset( $_POST['pig-image-action'] ) && $_POST['pig-image-action'] == 'delete' ) {
			// delete an image
			$image_id    		= strip_tags( stripslashes( $_POST['pig-image-id'] ) );   // get the image ID on the main site
			$site_id    		= strip_tags( stripslashes( $_POST['pig-subsite-id'] ) );   // get the ID of the site the image belongs to
			$url                = network_home_url( '/dashboard/' );
			$error    			= NULL;

			if ( ! $image_id ) {
				$error .= 'Something went wrong.<br/>';
			}

			// everything ok
			if ( ! $error ) {

				// remove the image from the main site

				switch_to_blog( $site_id );

				$image = get_post( $image_id );

				if( ! $image )
					wp_die( 'Image not found!', 'Error' );

				if ( get_current_user_id() !== intval( $image->post_author ) )
					wp_die( 'You do not have permission to delete this image.', 'Error' );

				$file_id = get_post_thumbnail_id( $image_id );

				wp_delete_post( $image_id );

				if( ! empty( $file_id ) )
					wp_delete_attachment( $file_id );

				restore_current_blog();

				header( "Location: " . $url . '?image-removed=1#gallery_tab' );

			} else {
				// if there's an error
				header( "Location: " . $url . '?image-removed=0#gallery_tab' );
			}
		}
	}
}
add_action( 'init', 'pig_process_image_edit', 999 );
