<?php
/**
 * Functions for the reblogging feature
 *
 * @package Triberr
 */

/**
 * Create the admin notices
 *
 * @return void
 */
function triberr_admin_setup_notices()
{

    // Adding notice to user for updating the plugin to the latest version
    $args     = (object) array( 'slug' => 'triberr-wordpress-plugin' );
    $request  = array( 'action' => 'plugin_information', 'timeout' => 15, 'request' => serialize( $args ) );
    $url      = 'https://api.wordpress.org/plugins/info/1.0/';
    $response = (array) wp_remote_post( $url, array( 'body' => $request ) );

    if ( !empty( $response['response']['code'] ) && $response['response']['code'] == "200" ) {
        $plugin_info = unserialize( $response['body'] );

        $version_latest = $plugin_info->version;

        global $pagenow;
        if ( 'plugins.php' !== $pagenow && $version_latest > $GLOBALS['version_number'] ) {
            ?>
            <div class="update-nag notice">
                <p>
                    <a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>">
                        <?php esc_html_e( 'Update Triberr Plugin to the latest version!', 'triberr' ) ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }

    $ping_key = get_option( 'triberr_apikey' );

    if ( '' === $ping_key ) {

        ?>
        <div class="error">
            <p>
                <?php esc_html_e( 'Triberr plugin needs to be configured. Go to ', 'triberr' ) ?>
                <a href="<?php echo esc_url( admin_url( 'options-general.php?page=triberr-options' ) ); ?>">
                    <?php esc_html_e( 'plugin settings', 'triberr' ) ?>
                </a>
            </p>
        </div>
        <?php
    }
}

/**
 * Show a message when a post is imported
 *
 * @return void
 */
function triberr_display_message()
{

    if ( get_option( 'triberr_message' ) ) {
        echo '<div id="message" class="updated"><p>' . esc_html( get_option( 'triberr_message' ) ) . '</p></div>';
        update_option( 'triberr_message', '' );
    }
}

/**
 * Displays the Triberr link in the sidebar
 *
 * @return void
 */
function triberr_menu()
{
    add_options_page( esc_html__( 'Triberr Options', 'triberr' ), esc_html__( 'Triberr', 'triberr' ), 'manage_options', 'triberr-options', 'triberr_options_page' );
    add_option( 'triberr_comments_width', '600', '', 'yes' );
    add_option( 'triberr_comments_bg_color', 'ffffff', '', 'yes' );
    add_option( 'triberr_comments_bg_color', 'ffffff', '', 'yes' );
    add_option( 'triberr_comments_status', 'on', '', 'yes' );
}

/**
 * Check if the user has permissions to edit the options page
 *
 * @return void
 */
function triberr_options_page()
{

    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'triberr' ) );
    }
    ?>
    <div class="wrap">
        <div class="top_nav">
            <div class="plugin_logo">
                <img src="https://triberr.com/assets/misc/triberr-logo.png"/>
            </div>
        </div>

        <h2><?php esc_html_e( 'Triberr Plugin Settings', 'triberr' ) ?></h2>
        <div id="api" class="">
            <h3><?php esc_html_e( 'API Blog Token', 'triberr' ) ?></h3>
            <div class="">
                <form method="post" action="options.php" class="form-table">
                    <?php
                    wp_nonce_field( 'update-options' );
                    $ping_key = get_option( 'triberr_apikey' );
                    ?>
                    <ul>
                        <li>
                            Your API blog token connects this blog with your Triberr profile. <br/>
                            You can find your token in your Triberr Account Settings on the Blog Settings page.
                            <br/><br/>
                            <small>Hint: Looks like a 35 character random string of numbers and letters</small> <br/>
                            <input id="triberr_apikey" maxlength="35" size="35" name="triberr_apikey"
                                   value="<?php echo esc_html($ping_key); ?>"/>
                            <small> <a href="http://help.triberr.com/how-to-configure-the-triberr-wordpress-plugin/"
                                       target="_blank">Help me find it</a></small></li>
                        </li>
                        <input type="hidden" name="action" value="update"/>
                        <input type="hidden" name="page_options" value="triberr_apikey"/>
                        <input type="submit" class="button-primary"
                               value="<?php esc_html_e( 'Save Changes', 'triberr' ) ?>"/>
                        <br/>
                        <small>After saving, all future posts will automatically be sent to Triberr.</small>
                    </ul>
                </form>
            </div>
        </div>
    </div>
    <?php
}
