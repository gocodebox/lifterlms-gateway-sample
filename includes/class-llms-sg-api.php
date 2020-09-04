<?php
/**
* Make requests to the Sample Payment Gateway API 
 *
 * This makes request to a *MOCK* API that returns random object IDs which
 * can be used to illustrate how to construct a LifterLMS Payment Gateway which (probably)
 * interacts with a real API.
 *
 * You could also of course not use this at all and use a helper library or SDK provided
 * by the payment provider.
 *
 * Our internal gateways stay away from the SDKs because in most scenarios the SDKs are large
 * dependencies to require in order to make a (generally) small number of API requests.
 *
 * That's a preference, it's not right or wrong.
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
class LLMS_SG_API extends LLMS_Abstract_API_Handler {

	/**
	 * Parse the body of the response and set a success/error
	 * 
	 * @since 1.0.0
	 * 
	 * @param array $response Raw API response.
	 * @return array
	 */
	protected function parse_response( $response ) {

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		// API Responded with an error.
		if ( $code > 201 ) {
			return $this->set_error( 
				! empty( $body['message'] ) ? $body['message'] : __( 'Unknown Error', 'lifterlms' ),
				! empty( $body['code'] ) ? $body['code'] : 'unknown-error',
				$response
			);
		}

		// Success.
		return $this->set_result( $body );

	}

	/**
	 * Set request body
	 *
	 * Our mock API doesn't actually do anything so there's no data to pass into it.
	 *
	 * This method can be used to format the arguments passed to the API.
	 *
	 * It may be fine to pass an array directly to the API or maybe it needs to be massaged in some
	 * way, that'll be up to you to determine based on the API you're interacting with.
	 * 
	 * @since 1.0.0
	 * 
	 * @param array  $data     Request body.
	 * @param string $method   Request method.
	 * @param string $resource Requested resource.
	 * @return array
	 */
	protected function set_request_body( $data, $method, $resource ) {
		return $data;
	}

	/**
	 * Set request headers
	 *
	 * @since [version]
	 * 
	 * @param array  $headers  Default request headers.
	 * @param string $resource Requested resource.
	 * @param string $method   Request method.
	 * @return array
	 */
	protected function set_request_headers( $headers, $resource, $method ) {

		$headers['X-API-KEY'] = llms_sample_gateway()->get_gateway()->get_api_key();
		return parent::set_request_headers( $headers, $resource, $method );

	}

	/**
	 * Set the request URL
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $resource Requested resource.
	 * @param string $method   Request method.
	 * @return string
	 */
	protected function set_request_url( $resource, $method ) {
		return rest_url( sprintf( 'sg-mock/v1%s', $resource )  );
	}

}
