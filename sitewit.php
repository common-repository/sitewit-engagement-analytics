<?php

/**
Plugin Name: Search Engine Marketing
Plugin URI: http://www.sitewit.com
Description: SiteWit is a DIY online marketing platform. Start with FREE website analytics and SEO keyword ranking.
Version: 2.6.3
Author: SiteWit
Author URI: http://www.sitewit.com
Text Domain: sitewit-engagement-analytics
Domain path: /languages
License: GPLv2 or later

@package Search Engine Marketing
 */

// This plugin uses PHP 5.3 features, so need to exit right away if the PHP version of the host is < 5.3.
define( 'SW_PHP_MIN_VERSION', '5.3.0' );
if ( version_compare( PHP_VERSION, SW_PHP_MIN_VERSION, '<' ) ) {
	if ( is_admin() ) {
		exit( esc_html__( 'This plugin requires PHP version 5.3 and later!' ) );
	}
}

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'SW_PLUGIN_SCHEMA_VERSION', '4' );
define( 'SW_PLUGIN_FILE', __FILE__ );
define( 'SW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SW_NAMESPACE', 'Sitewit\WpPlugin' );
define( 'SW_SETTING_PAGE', 'sitewitconfig' );
define( 'SW_HOST', 'https://login.sitewit.com/' );
define( 'SW_REST_API_URL', 'https://rest.sitewit.com/api/' );

define( 'SW_OPTION_NAME_AFFILIATE_ID', 'sw_affiliate_id' );
define( 'SW_OPTION_NAME_SCHEMA_VERSION', 'sw_schema_version' );
define( 'SW_OPTION_NAME_ACCOUNT_ID', 'sw_account_id' );
define( 'SW_OPTION_NAME_INVITATION_CODE', 'sw_invitation_code' );
define( 'SW_OPTION_NAME_API_TOKEN', 'sw_api_token' );

define( 'SW_AJAX_NONCE_NAME', 'swAjaxNonce' );
define( 'SW_AJAX_NONCE_LINK_ACCOUNT', 'sw-link-account-nonce' );
define( 'SW_AJAX_NONCE_RESET_ACCOUNT', 'sw-reset-account-nonce' );

/**
 * Support for internationalization.
 */
function sw_load_textdomain() {
	load_plugin_textdomain( 'sitewit-engagement-analytics', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * This should contain all the checks to ensure the plugin will operate properly.
 */
function sw_activation_check() {
	// Check for cURL extension availability so we can connect to our API.
	if ( ! function_exists( 'curl_init' ) ) {
		wp_die( esc_html__( 'This plugin requires cURL PHP extension to be enabled. Please contact your hosting provider to enable it.', 'sitewit-engagement-analytics' ) );
	}

	// Check if the site already has tracking code injected by cPanel.
	// Parsing the .htaccess file and find the inject code. Need test to make sure we can read the .htaccess file.
	$htaccess_file = get_home_path() . '.htaccess';
	if ( true === file_exists( $htaccess_file ) ) {
		$content = file_get_contents( $htaccess_file );
		if ( false !== strpos( $content, 'AddOutputFilterByType SUBSTITUTE text/html' )
			&& 1 === preg_match( '/sitewit.com\/v3\/\d+\/sw\.js/', $content ) ) {
			wp_die( esc_html__( 'This site seems to already have tracking code injected by cPanel. Please go to cPanel for SiteWit Reports.', 'sitewit-engagement-analytics' ) );
		}
	}
}

/**
 * Run on plugin deactivation
 */
function sw_deactivation_check() {
	sw_plugin_db_cleanup( false );
}

/**
 * Run on plugin uninstallation
 */
function sw_uninstallation_check() {
	sw_plugin_db_cleanup( true );
}

/**
 * Clean up database used by the plugin
 *
 * @param boolean $uninstall Whether this is deactivation or uninstallation.
 * @return void
 */
function sw_plugin_db_cleanup( $uninstall = true ) {
	// Remove all database options so if the user install the plugin again, they will be starting fresh.
	delete_option( SW_OPTION_NAME_ACCOUNT_ID );
	delete_option( SW_OPTION_NAME_API_TOKEN );

	// Legacy. We don't store those anymore, and so do the consts. Those delete won't hurt though.
	delete_option( 'sw_user_token' );
	delete_option( 'sw_master_account' );
	delete_option( 'sw_tracking_script' );

	if ( true === $uninstall ) {
		delete_option( SW_OPTION_NAME_AFFILIATE_ID );
		delete_option( SW_OPTION_NAME_SCHEMA_VERSION );
		delete_option( SW_OPTION_NAME_INVITATION_CODE );
	}
}

add_action( 'plugins_loaded', 'sw_load_textdomain' );

register_activation_hook( __FILE__, 'sw_activation_check' );
register_deactivation_hook( __FILE__, 'sw_deactivation_check' );
register_uninstall_hook( __FILE__, 'sw_uninstallation_check' );

require_once 'init.php';
