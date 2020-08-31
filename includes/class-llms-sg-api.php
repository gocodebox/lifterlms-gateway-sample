<?php
/**
* Make requests to the Sample Payment Gateway API 
 *
 * This makes request to a *MOCK* API that returns random object IDs which
 * can be used to illustrate how to construct a LifterLMS Payment Gateway which (probably)
 * interacts with a real API.
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_SG_API class.
 *
 * @since [version]
 */
class LLMS_SG_API {

	/**
	 * Error message
	 *
	 * @var string
	 */
	private $error_message = null;

	/**
	 * API Error Object
	 *
	 * @var WP_Error
	 */
	private $error_object = null;

	/**
	 * API Error Type
	 *
	 * @var string
	 */
	private $error_type = null;

	/**
	 * API Request Result
	 *
	 * @var obj
	 */
	private $result = null;

	/**
	 * Construct an API call, parameters are passed to private `call()` function
	 *
	 * @since 2.0.0
	 *
	 * @param  string $resource url endpoint or resource to make a request to.
	 * @param  array  $data array of data to pass in the body of the request.
	 * @param  string $method method of request (POST, GET, DELETE, PUT, etc...).
	 * @return void
	 */
	public function __construct( $resource, $data, $method ) {

		$this->call( $resource, $data, $method );

	}


	/**
	 * Make an API call to stripe
	 *
	 * @since 2.0.0
	 * @since 4.4.0 Moved request headers into a separte function.
	 * @since 5.0.0 Use `LLMS_STRIPE_API_VERSION`.
	 *
	 * @param string $resource Url endpoint or resource to make a request to.
	 * @param array  $data Array of data to pass in the body of the request.
	 * @param string $method Method of request (POST, GET, DELETE, PUT, etc...).
	 * @return WP_Error|array
	 */
	private function call( $resource, $data, $method ) {

		// attempt to call the API.
		$response = wp_safe_remote_post(
			'https://api.stripe.com/v1/' . $resource,
			array(
				'body'    => $data,
				'headers' => $this->get_request_headers(),
				'method'  => $method,
				'timeout' => 70,
			)
		);

		// connection error.
		if ( is_wp_error( $response ) ) {

			return $this->set_error( __( 'There was a problem connecting to the payment gateway.', 'lifterlms-stripe' ), 'gateway_connection', $response );

		}

		// empty body.
		if ( empty( $response['body'] ) ) {

			return $this->set_error( __( 'Empty Response.', 'lifterlms-stripe' ), 'empty_response', $response );

		}

		// parse the response body.
		$parsed = json_decode( $response['body'] );

		// Handle response.
		if ( ! empty( $parsed->error ) ) {

			return $this->set_error( $parsed->error->message, $parsed->error->type, $response );

		} else {

			$this->result = $parsed;

		}

		return $parsed;

	}

	/**
	 * Retrive the private "error_message" variable
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_error_message() {

		return $this->error_message;

	}

	/**
	 * Get the private "error_object" variable
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	public function get_error_object() {

		return $this->error_object;

	}


	/**
	 * Retrive the private "error_type" variable
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_error_type() {

		return $this->error_type;

	}

	/**
	 * Retrive the private "result" variable
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	public function get_result() {

		return $this->result;

	}

	/**
	 * Get API request headers
	 *
	 * @since 4.4.0
	 *
	 * @return array
	 */
	private function get_request_headers() {

		$gateway    = LLMS()->payment_gateways()->get_gateway_by_id( 'stripe' );
		$user_agent = $this->get_user_agent();
		$app        = $user_agent['application'];

		/**
		 * Filter the api request headers.
		 *
		 * @since 4.4.0
		 * @since 5.0.0 Use `wp_json_encode` instead of `json_encode`.
		 *                  Use LLMS_STRIPE_API_VERSION instead of LLMS_Stripe_API::API_VERSION
		 *
		 * @param array $headers request headers.
		 */
		return apply_filters(
			'llms_stripe_request_headers',
			array(
				'Authorization'              => 'Basic ' . base64_encode( $gateway->get_secret_key() . ':' ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- This is how it's done though.
				'Stripe-Version'             => apply_filters( 'llms_stripe_api_version', LLMS_STRIPE_API_VERSION ),
				'User-Agent'                 => $app['name'] . '/' . $app['version'] . ' (' . $app['url'] . ')',
				'X-Stripe-Client-User-Agent' => wp_json_encode( $user_agent ),
			)
		);

	}

	/**
	 * Get the "User Agent" info sent for partner attribution
	 *
	 * @since 4.4.0
	 *
	 * @link https://stripe.com/docs/building-plugins#setappinfo
	 * @link https://github.com/stripe/stripe-php/blob/c4e69e7c93c60e84ae552ffdcb39adf508256aca/lib/ApiRequestor.php#L295-L312
	 *
	 * @return array
	 */
	private function get_user_agent() {

		$app = array(
			'name'       => 'LifterLMS Stripe Gateway',
			'partner_id' => 'pp_partner_EeTAvOl1Cm4gbJ',
			'version'    => LLMS_STRIPE_VERSION,
			'url'        => 'https://lifterlms.com/product/stripe-extension/',
		);

		return array(
			'lang'         => 'php',
			'lang_version' => phpversion(),
			'publisher'    => 'lifterlms',
			'uname'        => php_uname(),
			'application'  => $app,
		);

	}

	/**
	 * Determine if the response is an error
	 *
	 * @since 4.0.0
	 *
	 * @return   boolean
	 */
	public function is_error() {

		return is_wp_error( $this->get_result() );

	}

	/**
	 * Set an Error
	 * Sets all error variables and sets the result as a WP_Error so the result can always be tested with `is_wp_error()`
	 *
	 * @since 2.0.0
	 *
	 * @param string $message Error message.
	 * @param string $type Error code or type.
	 * @param object $obj Full error object or api response.
	 * @return void
	 */
	private function set_error( $message, $type, $obj ) {

		$this->result        = new WP_Error( $type, $message, $obj );
		$this->error_type    = $type;
		$this->error_message = $message;
		$this->error_object  = $obj;

	}

}
