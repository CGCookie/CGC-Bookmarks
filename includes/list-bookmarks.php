<?php
/*************************************************************************
this file contains all of the functions used display bookmarks
************************************************************************/

function cgc_list_bookmarks( $delete_link = true, $number = 999 ) {
	global $current_user;
	global $wpdb;
	global $cgcb_db_table;
	global $cgcbbaseDir;
	
	
	if(is_user_logged_in()) {
		// echo the delete script
		if($delete_link == true ) {
		?>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(function($){

				// remove bookmark function
				$('.cgc_bookmark_delete a').click(function(){
					var bookmark_url = $(this).attr('href');
					var user_id = $(this).attr('class');
					var row_id = '.'+$(this).attr('id').replace('remove_', '');
					var info = 'remove_bookmark=&cgcb_post_url=' + bookmark_url + '&cgcb_user_id=' + user_id;
					
					if(confirm('Do you really want to remove this favorite?')) {
						$.ajax({
							type: "POST",
							url: "<?php echo $cgcbbaseDir;?>includes/process-ajax-data.php",
							data: info,
							success: function() {
								$(row_id).fadeOut().remove();
								if($('#bookmark-stats').length) {
									var post_count = parseInt($('#bookmark-stats li.tutorial-count em').text());
									post_count--;
									$('#bookmark-stats li.tutorial-count em').text(post_count);
								}
							}
						});
					}
					return false;
				});			

			}); // end jquery(function($))
			//]]> 
		</script>	
		<?php
		}
		
		$display .= '<div class="cgc_bookmarks_list bookmarked_images">';
		$display .= '<h3>Fav Images</h3>';
		$display .= '<p>These are your favorite eye-candy.</p>';
		$display .= '<ul>';
			$bookmarks = $wpdb->get_results("SELECT * FROM " . $cgcb_db_table . " WHERE (user_id='" . $current_user->ID . "' AND post_url LIKE '%images/%') LIMIT $number;");
			$i = 1;
			if($bookmarks) {
				foreach( $bookmarks as $bookmark) {
					$last = '';
					$image = $bookmark->image_url != '' ? $bookmark->image_url : get_bloginfo("stylesheet_directory") . '/images/image_missing.jpg';
					if($i % 3 == 0) { $last = ' last'; }
					$display .= '<li class="bookmark-link bookmarked-image bookmark_' . $bookmark->id . $last . '">';
						$display .= '<a href="' . $bookmark->post_url . '"><img src="' . $image . '" width="118" height="80" class="image_bookmark"/></a>';
					$display .= '</li>';
					$i++;
				}
			} else {
				$display .= '<li>You do not have any favorited images.</li>';
			}
		$display .= '</ul></div>';
		
		$display .= '<div class="cgc_bookmarks_list bookmarked_tutorials">';
		$display .= '<h3>Fav Tutorials</h3>';
		$display .= '<p>Tutorials you\'ve favorited across the CG Cookie Network.</p>';
		$display .= '<ul>';
			$bookmarks = $wpdb->get_results("SELECT * FROM " . $cgcb_db_table . " WHERE (user_id='" . $current_user->ID . "' AND post_url NOT LIKE '%images/%') LIMIT $number;");
			if($bookmarks) {
				foreach( $bookmarks as $bookmark) {

					$blog = strrchr($bookmark->post_url, '.com');
					$blog = substr($blog, 5);
					$blog = cgcb_rstrstr($blog, '/');
					
					$image = $bookmark->image_url != '' ? $bookmark->image_url : get_bloginfo("stylesheet_directory") . '/images/oldImage_message.jpg';

					$display .= '<li class="bookmark-link bookmark_' . $bookmark->id . '">';
						$display .= '<a href="' . $bookmark->post_url . '"><em class="' . $blog . '"></em><img src="' . $image . '" width="118" height="80" class="image_bookmark"/>' . stripslashes($bookmark->post_title) . '</a>';
						if($delete_link == true ) {
							$display .= '<span class="cgc_bookmark_delete"> - <a id="remove_bookmark_' . $bookmark->id . '" class="' . $bookmark->user_id . '" href="' .$bookmark->post_url . '">remove</a></span>';
						}
					$display .= '</li>';
			
				}
			} else {
				$display .= '<li>You do not have any bookmarked tutorials.</li>';
			}
		$display .= '</ul></div>';
		
	}
	else {
		$display .= 'You must be logged in to view your bookmarks.';
	}
	
	return $display;
	
}

function cgc_list_bookmarked_images($number = 999 ) {
	global $user_ID;
	global $wpdb;
	global $cgcb_db_table;
	
	$display = '';
	
	if(is_user_logged_in()) {

		$display .= '<ul>';
			$bookmarks = $wpdb->get_results("SELECT * FROM " . $cgcb_db_table . " WHERE (user_id='" . $user_ID . "' AND post_url LIKE '%images/%') LIMIT $number;");
			$i = 1;
			if($bookmarks) {
				foreach( $bookmarks as $bookmark) {
					$last = '';
					$image = $bookmark->image_url != '' ? $bookmark->image_url : get_bloginfo("stylesheet_directory") . '/images/image_missing.jpg';
					if($i % 3 == 0) { $last = ' last'; }
					$display .= '<li class="bookmark-link bookmarked-image bookmark_' . $bookmark->id . $last . '">';
						$display .= '<a href="' . $bookmark->post_url . '" title="' . stripslashes($bookmark->post_title) . '"><img src="' . $image . '" width="118" height="80" class="image_bookmark"/></a>';
					$display .= '</li>';
					$i++;
				}
			} else {
				$display .= '<li>You do not have any favorited images.</li>';
			}
		$display .= '</ul>';
		
	}
	else {
		$display .= 'You must be logged in to view your bookmarks.';
	}
	
	return $display;
	
}

function cgc_list_bookmarked_posts($number = 999 ) {
	global $user_ID;
	global $wpdb;
	global $cgcb_db_table;
	
	$display = '';
	
	if(is_user_logged_in()) {
		
		$display .= '<ul>';
			$bookmarks = $wpdb->get_results("SELECT * FROM " . $cgcb_db_table . " WHERE (user_id='" . $user_ID . "' AND post_url NOT LIKE '%images/%') LIMIT $number;");
			if($bookmarks) {
				foreach( $bookmarks as $bookmark) {
					$blog = strrchr($bookmark->post_url, '.com');
					$blog = substr($blog, 5);
					$blog = cgcb_rstrstr($blog, '/');
					if(strlen($bookmark->post_title) > 40) {
						$title = substr($bookmark->post_title, 0, 40) . '...';	
					} else {
						$title = $bookmark->post_title;	
					}
					$display .= '<li class="bookmark-link bookmark_' . $bookmark->id . '">';
						$display .= '<a href="' . $bookmark->post_url . '"><em class="' . $blog . '"></em>Tutorial - ' . stripslashes($title) . '</a>';
					$display .= '</li>';
			
				}
			} else {
				$display .= '<li>You do not have any favorited tutorials.</li>';
			}
		$display .= '</ul>';
		
	}
	else {
		$display .= 'You must be logged in to view your favorites.';
	}
	
	return $display;
	
}