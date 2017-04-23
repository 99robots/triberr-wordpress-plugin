<?php
/**
 * Functions for the reblogging feature
 *
 * @package Triberr
 */

/**
 * Create the admin notices
 *
 * @access public
 * @return void
 */
function triberr_admin_setup_notices() {

	// Adding notice to user for updating the plugin to the latest version
	// set the arguments to get latest info from repository via API ##
	$args = array(
		'slug' => 'triberr-wordpress-plugin',
		'fields' => array(
			'version' => true,
		)
	);

	/** Prepare our query */
	$call_api = plugins_api( 'plugin_information', $args );

	/** Check for Errors & Display the results */
	if ( is_wp_error( $call_api ) ) {

		$api_error = $call_api->get_error_message();

	} else {

		//echo $call_api; // everything ##

		if ( ! empty( $call_api->version ) ) {

			$version_latest = $call_api->version;

		}

	}
	global $pagenow;
	if($pagenow != "plugins.php" && $version_latest != $GLOBALS['version_number']) {
    ?>
		<div class="update-nag notice">
			<p><a href="<?php echo admin_url( 'plugins.php' ); ?>">Update Triberr Plugin to the latest version!</a></p>
		</div>
    <?php
	}

    $PINGKEY = get_option('triberr_apikey');

	if($PINGKEY == ""){
		echo "<div class=\"error\"><p>Triberr plugin needs to be configured. Go to <a href=\"" . get_admin_url() . "options-general.php?page=triberr-options\">plugin settings</a>.</p></div>";
	}
}

/**
 * Show a message when a post is imported
 *
 * @access public
 * @return void
 */
function triberr_display_message() {

	if ( get_option('triberr_message') ) {
		echo '<div id="message" class="updated"><p>'.get_option('triberr_message').'</p></div>';
		update_option('triberr_message', '');
	}
}

/**
 * Displays the Triberr link in the sidebar
 *
 * @access public
 * @return void
 */
function triberr_menu() {
	add_options_page('Triberr Options', 'Triberr', 'manage_options', 'triberr-options', 'triberr_options_page');
	add_option( 'triberr_comments_width', '600', '', 'yes' );
	add_option( 'triberr_comments_bg_color', 'ffffff', '', 'yes' );
	add_option( 'triberr_comments_bg_color', 'ffffff', '', 'yes' );
	add_option( 'triberr_comments_status', 'on', '', 'yes' );
}

/**
 * Check if the user has permissions to edit the options page
 *
 * @access public
 * @return void
 */
function triberr_options_page() {

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	?>
	<div class="wrap">
	    <div class="top_nav">
	        <div class="plugin_logo">
	            <img src="https://triberr.com/assets/misc/logoLight.png" />
	        </div>
		</div>

	    <h2>Triberr Plugin Settings</h2>
	    <div id="api" class="" >
	    <h3 >API Blog Token</h3>
	    	<div class="">
	        <form method="post" action="options.php" class="form-table">
	            <?php wp_nonce_field('update-options');
	            $PINGKEY = get_option('triberr_apikey');
	            ?>
	            <ul>
	            	<li>
	                    Your API blog token connects this blog with your Triberr profile. <br />
	                    You can find your token in your Triberr Account Settings on the Blog Settings page. <br /><br />
	                    <small>Hint: Looks like a 35 character random string of numbers and letters</small> <br />
	                    <input id="triberr_apikey" maxlength="35" size="35" name="triberr_apikey" value="<?php echo $PINGKEY; ?>" />
	                    <small> <a href="http://help.triberr.com/how-to-configure-the-triberr-wordpress-plugin/" target="_blank">Help me find it</a></small></li>
	            	</li>
	                <input type="hidden" name="action" value="update" />
	                <input type="hidden" name="page_options" value="triberr_apikey" />
	                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	                <br />
	                <small>After saving, all future posts will automatically be sent to Triberr.</small>
	            </ul>
	        </form>

	        </div>
		</div>
	</div>
	<?php
}