<?php
/**
 * Functions for api calls
 *
 * @package Triberr
 */

/**
 * A post was published so lets submit it to triberr
 *
 * @access public
 * @param mixed $post_ID
 * @return void
 */
function triberr_submit_post($post_ID) {
	$thisPost = get_post($post_ID, ARRAY_A);
			if(get_permalink( $post_ID ) != NULL) {
				$triberrFields = triberr_build_url($post_ID);
				$url = 'http://triberr.com/subdomains/api/';
				$triberrMSG = triberr_connect($url, $triberrFields);
			}

	update_option('triberr_message', $triberrMSG);
}

/**
 * ACTUALLY PING THE PUBLISHED POST TO triberr
 *
 * @access public
 * @param mixed $post_ID
 * @return void
 */
function triberr_build_url($post_ID) {
	$thisPost = get_post($post_ID, ARRAY_A);

	$triberr_id = get_post_meta($post_ID, '_triberr_id', true);
	if($triberr_id == ""){
		$triberr_id = "empty";
	}

	$fields = array(
		'act'=>urlencode("add"),
		'key'=>urlencode(get_option('triberr_apikey')),
		'url'=>urlencode(get_permalink(  $thisPost['ID'] )),
		'title'=>urlencode($thisPost['post_title']),
		'body'=>urlencode(apply_filters('the_content',$thisPost['post_content'])),
		'status'=>urlencode($thisPost['post_status']),
		'guid'=>urlencode($thisPost['guid']),
		'post_date'=>urlencode($thisPost['post_date']),
		'post_id'=>urlencode( $thisPost['ID']),
		'post_type'=>urlencode($thisPost['post_type']),
		'triberr_id'=>$triberr_id,
		'plugin_version'=>urlencode($GLOBALS['version_number']),
		);
	return $fields;
}

/**
 * Setup the options page
 *
 * @access public
 * @param mixed $url
 * @param mixed $fields
 * @return void
 */
function triberr_connect($url, $fields) {

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		return $result;
}