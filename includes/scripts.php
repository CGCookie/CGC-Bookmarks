<?php

function cgcb_header_scripts()
{
	global $cgcbbaseDir;
	global $post;
	if(is_singular()) {
		$title = get_the_title($post->ID);
		$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(  $post->ID ), 'related-image' );
		ob_start(); ?>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(function($){

				// bookmark this post function
				$('.cgc_bookmark_add').click(function(){
					var bookmark_url = '<?php echo get_permalink($post->ID); ?>';
					var bookmark_title = '<?php echo addslashes($post->post_title); ?>';
					var user_id = ''+$(this).attr('name').replace('cgc_user_', '');
					var image_url = '<?php echo $image_url[0]; ?>';
					
					var info = 'bookmark_post=&cgcb_post_url=' + bookmark_url + '&cgcb_post_title=' + bookmark_title + '&cgcb_user_id=' + user_id + '&cgc_image_url=' + image_url;
					
					$(this).css({ opacity: 0.5 });
									
					$('#loading').ajaxStart(function() {
					  $(this).show();
					  
					});
					$('#loading').ajaxStop(function() {
						$(this).fadeOut();
						
					});
					
					$.ajax({
						type: "POST",
						url: "<?php echo $cgcbbaseDir;?>includes/process-ajax-data.php",
						data: info,
						success: function() {
							$('.cgc_bookmark').toggle();
							$('a.cgc_bookmark').css({ opacity: 100 });
						}
					});
					
					return false;
				});
				// remove bookmark function
				$('.cgc_bookmark_remove').click(function(){
					
					var bookmark_url = '<?php echo get_permalink($post->ID); ?>';
					var bookmark_title = '<?php echo addslashes($post->post_title); ?>';
					var user_id = ''+$(this).attr('name').replace('cgc_user_', '');
					
					$(this).css({ opacity: 0.5 });
					
					$('#loading').ajaxStart(function() {
					  $(this).show();
					  
					});
					$('#loading').ajaxStop(function() {
						$(this).fadeOut();
					});
					
					var info = 'remove_bookmark=&cgcb_post_url=' + bookmark_url + '&cgcb_post_title=' + bookmark_title + '&cgcb_user_id=' + user_id;
					$.ajax({
						type: "POST",
						url: "<?php echo $cgcbbaseDir;?>includes/process-ajax-data.php",
						data: info,
						success: function() {
							$('.cgc_bookmark').toggle();
							$('a.cgc_bookmark').css({ opacity: 100 });
						}
					});
					
					return false;
				});			

			}); // end jquery(function($))
			//]]> 
		</script>
		<?php
		echo ob_get_clean();
	}
}  
add_action('wp_head', 'cgcb_header_scripts');