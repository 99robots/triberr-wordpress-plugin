<?php
function triberr_comment_template( $comment_template ) {
	global $post;
	
	if($post->post_type == 'post'){
	include  'triberr-comments.php';
	
	}
	
}	
?>