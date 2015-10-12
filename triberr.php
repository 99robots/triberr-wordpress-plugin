<?php
/*
Plugin Name: Triberr
Plugin URI: http://triberr.com/subdomains/plugins/wordpress/
Description: Instantly send posts from your blog from Triberr.
Version: 4.0.0
Author: Triberr
Author URI: http://Triberr.com/
License: GPL2
*/

$GLOBALS['version_number'] = "4.0.0";
require_once('triberr_includes/class-admin-functionality.php');

// Include calls for xml-rpc
require_once('triberr_includes/class-api.php');

// Listen for RPC's
add_action ('publish_post', 'triberr_submit_post');
add_action ('publish_future_post', 'triberr_submit_post');

// Check of the plugin has been configured, if not show message
add_action ('admin_notices','triberr_admin_setup_notices');
add_action ('admin_footer', 'triberr_display_message');

// Create the sidebar link
add_action('admin_menu', 'triberr_menu');