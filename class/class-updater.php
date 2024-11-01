<?php
/**
 * Plugin updater class
 *
 * @package Search Engine Marketing
 */

namespace Sitewit\WpPlugin;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Plugin updater class
 */
class Updater {
	/**
	 * Update the tracking script to properly handle HTTPS sites
	 */
	public function update_from1_to2() {
		// Tracking script option.
		$tracking_script = get_option( SW_OPTION_NAME_TRACKING_SCRIPT );

		if ( false === $tracking_script || '' === $tracking_script ) {
			return false;
		}

		$tracking_script = str_replace( '"https" === document.location.protocol', '"https:" === document.location.protocol', $tracking_script );

		update_option( SW_OPTION_NAME_TRACKING_SCRIPT, $tracking_script );

		return true;
	}

	/**
	 * We don't want those pieces anymore
	 */
	public function update_from2_to3() {
		delete_option( 'sw_api_token' );
		delete_option( 'sw_user_token' );
		delete_option( 'sw_master_account' );

		return true;
	}

	/**
	 * We need to replace tracking script with account id only
	 */
	public function update_from3_to4() {
		// Get the account number from the tracking script saved in the database.
		$tracking_script = get_option( 'sw_tracking_script' );

		if ( ! $tracking_script ) {
			return false;
		}

		// Extract the account number.
		$matches = array();
		if ( ! preg_match( '/analytics.sitewit.com\/v3\/(\d+)\/sw.js/', $tracking_script, $matches ) ) {
			return false;
		}

		$account_id = $matches[1];

		if ( ! $account_id || ! ctype_digit( strval( $account_id ) ) ) {
			return false;
		}

		update_option( SW_OPTION_NAME_ACCOUNT_ID, $account_id );

		// Do not need the tracking script saved in the database anymore.
		delete_option( 'sw_tracking_script' );

		return true;
	}
}
