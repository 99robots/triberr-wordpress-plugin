<?php
/**
 * Plugin Name: Triberr
 * Plugin URI: https://triberr.com/subdomains/plugins/wordpress/
 * Description: Instantly send posts from your blog from Triberr.
 * Version: 4.0.7
 * Author: Triberr
 * Author URI: https://Triberr.com/
 * License: GPL2
 */

$GLOBALS['version_number'] = '4.0.7';
require_once( 'triberr_includes/class-admin-functionality.php' );

// Include calls for xml-rpc
require_once( 'triberr_includes/class-api.php' );

// Listen for RPC's
add_action( 'publish_post', 'triberr_submit_post' );
add_action( 'publish_future_post', 'triberr_submit_post' );

// Check of the plugin has been configured, if not show message
add_action( 'admin_notices','triberr_admin_setup_notices' );
add_action( 'admin_footer', 'triberr_display_message' );

// Create the sidebar link
add_action( 'admin_menu', 'triberr_menu' );

/**
 * Add the setting page as a link on the all plugins page
 *
 * @param mixed $links
 * @return void
 */
function triberr_action_links( $links ) {

	$mylinks = array( '<a href="' . admin_url( 'options-general.php?page=triberr-options' ) . '">' . esc_html__( 'Settings', 'triberr' ) . '</a>' );

	return array_merge( $links, $mylinks );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'triberr_action_links' );
