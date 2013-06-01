<?php

function pig_user_dashboard_images() {
	global $current_user;
	ob_start();

	// get all network sites
	$network_sites = get_blogs_of_user(1, false);

	$image_args = array(
		'author' => $current_user->ID,
		'post_type' => 'images',
		'posts_per_page' => -1
	);

	foreach( $network_sites as $site ) :

		if( $site->userblog_id == 1 )
			continue;

		switch_to_blog( $site->userblog_id );

		// The Query
		$the_query = new WP_Query($image_args);

		echo '<div class="site-portfolio">';
		if( $the_query->have_posts() ) :
			echo '<h5 class="site-portfolio-name">' . $site->blogname . ' Images</h5>';
			echo '<span class="site-portfolio-controls">';
				echo '<a href="' . network_home_url($site->path . 'profile/' . $current_user->user_login) . '"><i class="icon-eye-open"></i> view gallery</a>';
				echo '<a href="'. $site->siteurl .'/gallery/submit-image" title="Submit a new image"><i class="icon-plus"></i> Add Image</a>';
			echo '</span>';
			echo '<div id="user-portfolio-images" class="gallery clearfix">';
			// The Loop
			while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<div class="pig-grid-image">
					<a href="<?php echo get_post_meta(get_the_ID(), 'pig_image_url', true); ?>" title="View this image">
						<?php if( get_the_post_thumbnail( get_the_ID(), 'pig-image-dashboard') ) { ?>
							<?php the_post_thumbnail('pig-image-dashboard'); ?>
						<?php } else { ?>
							<img class="attachment-pig-image-dashboard" src="<?php echo get_post_meta(get_the_ID(), 'pig_dashboard_image_url', true); ?>"/>
						<?php } ?>
					</a>
					<ul class="gallery-image-controls">
						<li id="<?php echo get_the_ID(); ?>" class="edit-image">
							<a id="image-edit-modal-toggle" href="#" data-reveal-id="image-edit-modal" title="Edit this Image"><i class="icon-pencil"></i></a>
								<div class="image-mature hidden"><?php if(get_post_meta(get_the_ID(), 'pig_mature', true)) { echo 'yes'; } else { echo 'no'; } ?></div>
							<div class="image-title hidden"><?php echo get_the_title(); ?></div>
							<div class="image-description hidden"><?php echo htmlentities(get_the_content()); ?></div>
							<div class="image-subsite-id hidden"><?php echo get_post_meta(get_the_ID(), 'pig_subsite_id', true); ?></div>
							<div class="image-subsite-image-id hidden"><?php echo get_post_meta(get_the_ID(), 'pig_subsite_image_id', true); ?></div>
						</li>
						<li id="remove-<?php echo get_the_ID(); ?>" class="delete-image">
							<a href="#image-delete-modal" name="image-delete-modal" title="Delete this Image"><i class="icon-remove"></i></a>
								<div class="image-subsite-id hidden"><?php echo get_post_meta(get_the_ID(), 'pig_subsite_id', true); ?></div>
							<div class="image-subsite-image-id hidden"><?php echo get_post_meta(get_the_ID(), 'pig_subsite_image_id', true); ?></div>
						</li>
					</ul>
				</div>
			<?php
			endwhile;
			echo '</div><!-- ends #user-portfolio-images -->'; // ends #user-portfolio-images

			// Reset Post Data
			wp_reset_postdata();

		else :

			echo '<h5 class="site-portfolio-name">' . $site->blogname . ' Images</h5>';
			echo '<span class="site-portfolio-controls">';
				echo '<a href="'. $site->siteurl .'/gallery/submit-image" title="Submit a new image"><i class="icon-plus"></i> Add Image</a>';
			echo '</span>';
			echo '<p class="empty">You have no images uploaded to '. $site->blogname .'</p>';
		endif;

		echo '</div><!-- ends .site-portfolio -->'; // ends .site-portfolio

		restore_current_blog();

	endforeach; // foreach sites

	return ob_get_clean();
}

function pig_image_edit_form() {
	ob_start(); ?>

	<form id="pig-image-edit" action="" method="POST"/>

		<input type="hidden" name="pig-image-action" value="edit" />
		<input type="hidden" id="pig-image-id" name="pig-image-id" value="" />
		<input type="hidden" id="pig-subsite-id" name="pig-subsite-id" value="" />
		<input type="hidden" id="pig-subsite-image-id" name="pig-subsite-image-id" value="" />
		<input type="hidden" id="pig-referrer" name="pig-referrer" value="<?php the_permalink(); ?>" />
		<input type="text" id="pig-image-title" name="pig-image-title" value="" />
		<label for="pig-image-title">Enter in a descriptive image title</label>
		<textarea id="pig-image-desc" name="pig-image-desc"></textarea>
		<label for="pig-image-desc">What software was used, how did you make it... things inquiring minds would want to know</label>
		<input type="checkbox" id="pig-image-mature" name="pig-image-mature" value="1" />
		<label for="pig-image-mature">Contains mature content?</label>
		<input type="submit" class="button" id="pig_submit" value="Submit Update" />
		<a href="#" class="close" id="pig_cancel">Cancel</a>
	</form>

	<?php
	return ob_get_clean();
}

function pig_image_remove_form() {
	ob_start(); ?>
	<div id="pig-image-delete-wrap">
		<h3>Are you sure you want to delete</h3>
		<p><strong>Clicking yes will obliterate the data beyond recovery</strong></p>
		<form id="pig-image-remove" action="" method="POST"/>

			<input type="hidden" name="pig-image-action" value="delete" />
			<input type="hidden" id="pig-delete-image-id" name="pig-image-id" value="" />
			<input type="hidden" id="pig-delete-subsite-id" name="pig-subsite-id" value="" />
			<input type="hidden" id="pig-delete-subsite-image-id" name="pig-subsite-image-id" value="" />
			<input type="hidden" id="pig-delete-referrer" name="pig-referrer" value="<?php the_permalink(); ?>" />
			<a href="#" id="pig-image-delete-cancel" class="close">No, I am not sure</a>
			<input type="submit" id="pig_remove_image" value="Yes, please remove the image" />
		</form>
	</div><!--end #pig-image-delete-wrap-->
	<?php
	return ob_get_clean();
}
