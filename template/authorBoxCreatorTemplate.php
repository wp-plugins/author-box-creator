<?php 
	global $post;
	      	
	$author_id = $post->post_author;
	$author_image = get_avatar(get_the_author_meta('user_email', $author_id), '100')
?>
<div id="authorBox" style="background-color:<?php echo author_box_creator_get_option('background_color'); ?>">
	<div id="authorBoxTitle"><?php echo author_box_creator_get_option('title'); ?></div>
	<div id="authorBoxLeft">
		
		<?php
			if($author_image){
		?>
		<div id="authorBoxImage"><?php echo $author_image; ?></div>
		<?php
			} //end if($author_image)
		?>
		<div id="authorBoxName"><?php echo get_the_author_meta( 'display_name', $author_id ); ?></div>
	</div>
	<div id="authorBoxDescription"><?php echo wpautop(get_the_author_meta( 'description', $author_id )); ?></div>
</div>
