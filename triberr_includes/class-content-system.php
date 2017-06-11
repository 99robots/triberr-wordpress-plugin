<?php
// Make sure the Wordpress HTTP class is available in order to be able to talk to Triberr
if ( ! class_exists( 'WP_Http' ) ) {
	include_once( ABSPATH . WPINC . '/class-http.php' );
}

// Write the comment plugin to our code
class triberr_endorse_post {

	function triberr_endorse_post_function( $content ) {

		global $post;

		if ( 'post' === $post->post_type ) {
			$triberr_id = get_post_meta( $post->ID, '_triberr_id', true );
			$url = get_permalink( $post->ID );

			$triberr_endorsement = triberr_endorse_post::_getEndorsementMessage( $triberr_id );

			$content = $triberr_endorsement . $content;
		}

		return $content;
	}

	/**
	 * Call the triberr JSon API, get the endorsement database row and return.
	 * The endorsement message
	 */
	private function _getEndorsementMessage( $triberr_id ) {

		$url = 'http://triberr.com/api/v0.1/post/?action=getEndorsementFor&post_id=' . intval( $triberr_id );
		$response = wp_remote_get( $url );

		// In case we get an error during the HTTP call (server timeout, WP HTTP is corrupted, etc)
		// we just return an empty string so we don't break the user's blog. An attempt to write a
		// message to the blogger's error log is made to help troubleshoot issues
		if ( is_wp_error( $response ) ) {

			error_log( 'wp_remote_get failed for post ' . $triberr_id );
			return '';
		}

		// No endorsement was found for this post. Return an empty string so the content is displayed unchanged
		if ( ! $response['body'] ) {
			return '';
		} else {
			$endorsement = json_decode( $response['body'], true );
			$endorsement = stripslashes_deep( $endorsement );
			return '<div id="triberr_endorsement">' . $endorsement['end_message'] . '</div>';
		}
	}
}
