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
			$mature    			= strip_tags( stripslashes( $_POST['pig-image-mature'] ) );   // get the image maturity
			$url                = isset( $_POST['pig-redirect-to'] ) && $_POST['pig-redirect-to'] ? sanitize_text_field( $_POST['pig-redirect-to'] ) : network_home_url( '/dashboard/' );
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

				update_post_meta( $image_id, 'pig_mature', $mature );

				restore_current_blog();

				// the IMAGE post was created okay
				if ( $updated_sub_site_image_id ) {

					if( class_exists( 'CWS_Fragment_Cache' ) ) {
						// Flush user profile cache
						$frag = new CWS_Fragment_Cache( 'cgc-profile-' . get_current_user_id(), 3600 );
						$frag->flush();
					}

					wp_redirect( $url . '?image-updated=1#manage_images' ); exit;
				} else {
					wp_redirect( $url . $url . '?image-updated=0#manage_images' ); exit;
				}
			} else {
				// if there's an error
				header( "Location: " . $url . '?image-updated=0&fields-empty=1#manage_images' );
			}
		} else if ( isset( $_POST['pig-image-action'] ) && $_POST['pig-image-action'] == 'delete' ) {
			// delete an image
			$image_id    		= strip_tags( stripslashes( $_POST['pig-image-id'] ) );   // get the image ID on the main site
			$site_id    		= strip_tags( stripslashes( $_POST['pig-subsite-id'] ) );   // get the ID of the site the image belongs to
			$url                = isset( $_POST['pig-delete-redirect-to'] ) && $_POST['pig-delete-redirect-to'] ? sanitize_text_field( $_POST['pig-delete-redirect-to'] ) : network_home_url( '/dashboard/' );
			$error    			= NULL;

			if ( ! $image_id ) {
				$error .= 'Something went wrong.<br/>';
			}

			// everything ok
			if ( ! $error ) {

				// remove the image from the sub site

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

				if( class_exists( 'CWS_Fragment_Cache' ) ) {
					// Flush user profile cache
					$frag = new CWS_Fragment_Cache( 'cgc-profile-' . get_current_user_id(), 3600 );
					$frag->flush();
				}

				header( "Location: " . $url . '?image-removed=1#manage_images' );

			} else {
				// if there's an error
				header( "Location: " . $url . '?image-removed=0#manage_images' );
			}
		}
	}
}
add_action( 'init', 'pig_process_image_edit', 999 );
