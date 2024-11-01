<?php
/**
 * Messages class
 *
 * @package Search Engine Marketing
 */

namespace Sitewit\WpPlugin;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Messages class
 */
class Messages {
	/**
	 * Build a message to HTML code to be displayed on a page
	 *
	 * @param string $type        Message type.
	 * @param string $message     Message, translated, to show to user.
	 * @param bool   $dismissible Whether the message is dismissible or not.
	 */
	private static function show_message( $type = 'info', $message = '', $dismissible = false ) {
		$dismiss = $dismissible ? 'is-dismissible' : '';

		// Do not escape the message here as it's already escaped.
		// Mark this as XSS ok for PHPCodesniffer because the message MUST be escaped beforehand.
		printf( '<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>', esc_attr( $type ), esc_attr( $dismiss ), $message ); // WPCS: XSS ok.
	}

	/**
	 * Show an info message
	 *
	 * @param string $message     Message, translated, to be displayed.
	 * @param bool   $dismissible Whether the message is dismissible or not.
	 */
	public static function show_info( $message = '', $dismissible = true ) {
		if ( $message ) {
			self::show_message( 'info', $message, $dismissible );
		}
	}

	/**
	 * Show a success message
	 *
	 * @param string $message     Message, translated, to be displayed.
	 * @param bool   $dismissible Whether the message is dismissible or not.
	 */
	public static function show_success( $message = '', $dismissible = true ) {
		if ( $message ) {
			self::show_message( 'success', $message, $dismissible );
		}
	}

	/**
	 * Show a warning message
	 *
	 * @param string $message     Message, translated, to be displayed.
	 * @param bool   $dismissible Whether the message is dismissible or not.
	 */
	public static function show_warning( $message = '', $dismissible = false ) {
		if ( $message ) {
			self::show_message( 'warning', $message, $dismissible );
		}
	}

	/**
	 * Show an error message
	 *
	 * @param string $message     Message, translated, to be displayed.
	 * @param bool   $dismissible Whether the message is dismissible or not.
	 */
	public static function show_error( $message = '', $dismissible = false ) {
		if ( $message ) {
			self::show_message( 'error', $message, $dismissible );
		}
	}
}
