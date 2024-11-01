<?php
/**
 * Api class
 *
 * @package Search Engine Marketing
 */

namespace Sitewit\WpPlugin;

use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;
use Sitewit\WpPlugin\Exception\Api_Exception;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Api class
 */
class Api {
	/**
	 * Call API to get account information using API Token and User Token
	 *
	 * @param string $api_token  Api token to provide to API call.
	 * @param string $user_token User token to provide to API call.
	 * @return string JSON response from API
	 * @throws Api_Exception Exception contain the message returned by server.
	 */
	public static function get_account( $api_token, $user_token ) {
		// Tell Httpful to decode response as array.
		$json_handler = new \Httpful\Handlers\JsonHandler( array( 'decode_as_array' => true ) );
		\Httpful\Httpful::register( \Httpful\Mime::JSON, $json_handler );

		$response = Request::get( SW_REST_API_URL . 'account/getaccount' )
			->addHeader( 'AccountAuth', base64_encode( "{$api_token}:{$user_token}" ) )
			->expectsJson()
			->send();

		if ( 200 !== $response->code ) {
			throw new Api_Exception( $response->raw_body );
		}

		// Returned JSON will be detected automatically and decoded into "body" property.
		return $response->body;
	}

	/**
	 * Call API to get account information using API Token and Invivation Code
	 *
	 * @param string $api_token       Api token to provide to API call.
	 * @param string $invitation_code Invitation code to provide to API call.
	 * @return string JSON response from API
	 * @throws Api_Exception Exception contain the message returned by server.
	 */
	public static function get_account_from_invitation( $api_token, $invitation_code ) {
		// Tell Httpful to decode response as array.
		$json_handler = new \Httpful\Handlers\JsonHandler( array( 'decode_as_array' => true ) );
		\Httpful\Httpful::register( \Httpful\Mime::JSON, $json_handler );

		$response = Request::post( SW_REST_API_URL . 'auth/invite' )
			->sendsJson()
			->body(
				wp_json_encode(
					array(
						'AccountToken' => $api_token,
						'Code'         => $invitation_code,
					)
				)
			)
			->expectsJson()
			->send();

		if ( 200 !== $response->code ) {
			throw new Api_Exception( $response->raw_body );
		}

		return $response->body;
	}
}
