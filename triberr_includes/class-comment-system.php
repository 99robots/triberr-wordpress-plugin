<?php
function triberr_comment_template( $comment_template ) {
	global $post;

	if ( 'post' === $post->post_type ) {
		include  'triberr-comments.php';
	}
}
