<?php

function cgcb_header_scripts()
{
	global $cgcbbaseDir;
	global $post;
	if(is_singular()) {
		$title = get_the_title($post->ID);
		$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(  $post->ID ), 'medium-thumb' );
		ob_start(); ?>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(function($){

				// bookmark this post function
				$('.post-controls').on('click', '.cgc_bookmark_add', function(){
					$this = $(this);
					var bookmark_url = '<?php echo get_permalink($post->ID); ?>';
					var bookmark_title = '<?php echo addslashes($post->post_title); ?>';
					var user_id = ''+$(this).attr('name').replace('cgc_user_', '');
					var image_url = '<?php echo $image_url[0]; ?>';

					var info = 'bookmark_post=1&cgcb_post_url=' + bookmark_url + '&cgcb_post_title=' + bookmark_title + '&cgcb_user_id=' + user_id + '&cgc_image_url=' + image_url;

					var buttonIcon = $this.find('[class^="icon-"], [class*=" icon-"]');
					var buttonClass = buttonIcon.attr('class');
					$this.css('opacity', .5);

					$.ajax({
						type: "POST",
						url: "<?php echo $cgcbbaseDir;?>includes/process-ajax-data.php",
						data: info,
						success: function(response) {
							if( response == '1' ) {
								var newClass = buttonClass.replace('-empty','');
								console.log(buttonClass);
								console.log(newClass);
								$this.removeClass('cgc_bookmark_add').addClass('cgc_bookmark_remove').attr('title', 'Remove Bookmark').css('opacity', 1.0);	
								buttonIcon.removeClass().addClass(newClass);
							} else {
								alert( 'It seems the gremlins have gotten in the way again. Please try again and then contat support if the little buggers are still there.' )
							}
						}
					});

					return false;
				});
				// remove bookmark function
				$('.post-controls').on('click', '.cgc_bookmark_remove', function(){
					$this = $(this);

					var bookmark_url = '<?php echo get_permalink($post->ID); ?>';
					var bookmark_title = '<?php echo addslashes($post->post_title); ?>';
					var user_id = ''+$(this).attr('name').replace('cgc_user_', '');

					var buttonIcon = $this.find('[class^="icon-"], [class*=" icon-"]');
					var buttonClass = buttonIcon.attr('class');
					
					$this.css('opacity', .5);

					var info = 'remove_bookmark=1&cgcb_post_url=' + bookmark_url + '&cgcb_post_title=' + bookmark_title + '&cgcb_user_id=' + user_id;
					$.ajax({
						type: "POST",
						url: "<?php echo $cgcbbaseDir;?>includes/process-ajax-data.php",
						data: info,
						success: function(response) {
							if( response == '1' ) {
								var newClass = buttonClass + '-empty';
								$this.removeClass('cgc_bookmark_remove').addClass('cgc_bookmark_add').attr('title', 'Add Bookmark').css('opacity', 1.0);
								buttonIcon.removeClass().addClass(newClass);
							} else {
								alert( 'It seems the gremlins have gotten in the way again. Please try again and then contat support if the little buggers are still there.' )
							}
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
