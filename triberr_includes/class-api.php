<?php
/**
 * Functions for api calls
 *
 * @package Triberr
 */

/**
 * A post was published so lets submit it to triberr
 *
 * @param mixed $post_id
 * @return void
 */
function triberr_submit_post( $post_id ) {

	$this_post = get_post( $post_id, ARRAY_A );

	if ( ! is_null( get_permalink( $post_id ) ) ) {
		$triberr_fields = triberr_build_url( $post_id );
		$url = 'https://triberr.com/subdomains/api/';
		$triberr_msg = triberr_connect( $url, $triberr_fields );
	}

	update_option( 'triberr_message', $triberr_msg );
}

/**
 * ACTUALLY PING THE PUBLISHED POST TO triberr
 *
 * @param mixed $post_id
 * @return void
 */
function triberr_build_url( $post_id ) {

	$this_post = get_post( $post_id, ARRAY_A );
	$triberr_id = get_post_meta( $post_id, '_triberr_id', true );

	if ( '' === $triberr_id ) {
		$triberr_id = 'empty';
	}

	$fields = array(
		'act'             => urlencode( 'add' ),
		'key'             => urlencode( get_option( 'triberr_apikey' ) ),
		'url'             => urlencode( get_permalink( $this_post['ID'] ) ),
		'title'           => urlencode( $this_post['post_title'] ),
		'body'            => urlencode( apply_filters( 'the_content',$this_post['post_content'] ) ),
		'status'          => urlencode( $this_post['post_status'] ),
		'guid'            => urlencode( $this_post['guid'] ),
		'post_date'       => urlencode( $this_post['post_date'] ),
		'post_id'         => urlencode( $this_post['ID'] ),
		'post_type'       => urlencode( $this_post['post_type'] ),
		'triberr_id'      => $triberr_id,
		'plugin_version'  => urlencode( $GLOBALS['version_number'] ),
	);

	return $fields;
}

/**
 * Setup the options page
 *
 * @param mixed $url
 * @param mixed $fields
 * @return void
 */
function triberr_connect( $url, $fields ) {

	// set up our fields string variable
	$fields_string = '';

	//url-ify the data for the POST
	foreach ( $fields as $key => $value ) {
		$fields_string .= $key . '=' . $value . '&';
	}

	rtrim( $fields_string, '&' );

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt( $ch, CURLOPT_URL,$url );
	curl_setopt( $ch, CURLOPT_POST,count( $fields ) );
	curl_setopt( $ch, CURLOPT_POSTFIELDS,$fields_string );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );

	//execute post
	$result = curl_exec( $ch );

	//close connection
	curl_close( $ch );

	return $result;
}
