<?php
/**
 * This class adds a mock REST API which is used by the example gateway.
 *
 * This API is not part of the example gateway that would be necessary to review
 * when creating a payment gateway plugin.
 *
 * This exists only to make it so the example gateway has something to communicate with.
 *
 * In a real-world scenario the payment gateway this plugin interacts with would be a full
 * api provided by the payment provider.
 *
 * @package LifterLMS/Classes
 * 
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_SG_Mock_REST class.
 *
 * @since [version]
 */
class LLMS_SG_Mock_REST extends WP_REST_Controller {


	public function register_routes() {

		$namespace = 'sg-mock/v1';
		
		register_rest_route( $namespace, '/transactions', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'transactions' ),
				'args'     => $this->get_endpoint_args_for_item_schema( true ),
				'permission_callback' => array( $this, 'permission_callback' ),
			),
		) );

		register_rest_route( $namespace, '/refunds', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'refunds' ),
				'args'     => $this->get_endpoint_args_for_item_schema( true ),
				'permission_callback' => array( $this, 'permission_callback' ),
			),
		) );

	}

	public function permission_callback( $request ) {

		$key = $request->get_header( 'x_api_key' );
		if ( 'SECRET' !== $key ) {
			return new WP_Error( 'api-key-error', __( 'Missing or invalid API Key required.', 'lifterlms' ), array( 'status' => 401 ) );
 		}

		return true;
	}

	public function refunds( $request ) {
		return rest_ensure_response( array(
			'id'      => uniqid( 'refund_' ),
			'created' => time(),
			'amount'  => $request['amount'],
		) );
	}

	public function transactions( $request ) {

		//
		$status = 'declined';

		// Success on initial payment and make all recurring transactions automatically succeed.
		if ( '4242424242424242' === $request['number'] || 0 === strpos( $request['source'], 'source_' ) ) {
			$status = 'success';
		}

		return rest_ensure_response( array(
			'id'          => uniqid( 'transaction_' ),
			'created'     => time(),
			'customer_id' => uniqid( 'customer_' ),
			'source_id'   => uniqid( 'source_' ),
			'amount'      => $request['amount'],
			'status'      => $status,
		) );
	}

}