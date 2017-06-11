<?php
/**
 * Custom XML-RPC server for Triberr
 *
 * @package Triberr
 */
// Include the XML-RPC files
require_once( ABSPATH . WPINC . '/class-IXR.php' );
require_once( ABSPATH . WPINC . '/class-wp-xmlrpc-server.php' );

/**
 * Triberr XMLRPC server extension.
 *
 * Maintain's compatibility with all APIs wordpress supports and allows
 * Triberr's authors to reblog and report back to Triberr when they do.
 *
 *
 * @package Triberr
 * @subpackage Frictionless Guest Posting (reblogging)
 * @since
 */
class triberr_xmlrpc_server extends wp_xmlrpc_server {

	function __construct() {

		// This filter will add the new methods we're building
		add_filter( 'xmlrpc_methods', array( $this, 'triberr_appendMethods' ) );
		parent::__construct();
	}

	/**
	 * Returns an updated methods array to be updated on xmlrpc_methods
	 * @param $methods
	 */
	public function triberr_appendMethods( $methods ) {

		$methods += array(
			// Methods here.
			'Triberr.reblog' => 'this:triberr_reblog',
			'Triberr.getSourceID' => 'this:triberr_getSourceID',
			'Triberr.setSourceID' => 'this:triberr_setSourceID',
		);

		return $methods;
	}

	/**
	 * triberr_reblog
	 *
	 * Creates a new post and stores the triberr_id of the post being
	 * reblogged using a hidden custom meta field
	 *
	 * @param array $args Method parameters. Contains:
	 *  - blog_id
	 *  - username
	 *  - password
	 *  - content_struct
	 *  - publish
	 *  - source_id (triberr_id of post being reblogged)
	 *
	 * @return int Posts' id
	 */
	public function triberr_reblog( $args ) {

		$source_id = $args[5];

		// this is a hack to go around triberr's xmlrpc client encoding issue.
		// must be updated
		$args[3]['title'] = base64_decode( $args[3]['title'] );
		$args[3]['description'] = base64_decode( $args[3]['description'] );

		$this->escape( $source_id ); // the rest of the arguments will be escaped at mw_newPost

		// Delegate post creation to the Meta Weblog API
		$post_id = $this->mw_newPost( $args );

		// See if we got an error
		if ( is_a( $post_id, 'IXR_Error' ) ) {
			return $post_id; // return the Meta Weblog API generated error
		}

		do_action( 'xmlrpc_call', 'Triberr.reblog' ); // create new hook now that mw_newPost is done

		add_post_meta( $post_id, '_triberr_id', $source_id, true );

		$this->sendSourceId( $post_id, $source_id );

		return $post_id;
	}

	/**
	 * triberr_getSourceID
	 *
	 * Returns the triberr_id of the post that was reblogged in post_id
	 *
	 * @param array $args Method parameters. Contains:
	 * 	- post_id Must be an id of a reblogged article, otherwise it will return an empty string.
	 */
	function triberr_getSourceID( $args ) {
		$this->escape( $args ); // passed $args reference

		$post_id = $args;
		// create a hook in case we need it
		do_action( 'xmlrpc_call', 'Triberr.getSourceID' );

		// this data does not require authentication
		$triberr_id = get_post_meta( $post_id, '_triberr_id', true );

		logIO( 'O', "Retreived Triber ID ! ID: $triberr_id" );

		return $triberr_id;
	}

	 /**
	 * triberr_setSourceID
	 *
	 * Assigns the triberr's rss_ext_id to the post
	 *
	 * @param array $args Method parameters. Contains:
	 * 	- post_id
	 *  - source_id
	 */
	function triberr_setSourceID( $args ) {

		$this->escape( $args ); // passed $args reference

		$post_id   = $args[0];
		$source_id = $args[1];

		// create a hook in case we need it
		do_action( 'xmlrpc_call', 'Triberr.setSourceID' );

		// this data does not require authentication
		add_post_meta( $post_id, '_triberr_id', $source_id, true );

		logIO( 'O', "Assigned Triber ID: $source_id to post $post_id" );

		return true;
	}


	/**
	 * Sends the source_id back to Triberr after receiving a reblog.
	 */
	function sendSourceID( $post_id, $source_id ) {
		$url = 'http://triberr.com/triber/subdomains/api/';

		$fields = array(
			'act'		=> 'assignsource',
			'key'		=> urlencode( get_option( 'triberr_apikey' ) ),
			'post_id'	=> urlencode( $post_id ),
			'source_id'	=> urlencode( $source_id ),
		);

		$result = triberr_connect( $url, $fields );

		return $result;
	}
}

// change the server. Use our custom implementation
add_filter( 'wp_xmlrpc_server_class', create_function( '', 'return "triberr_xmlrpc_server";' ) );
