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

		$namespace = 'sg-mock';
		
		register_rest_route( $namespace, '/transactions', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'create_item' ),
				'args'     => $this->get_endpoint_args_for_item_schema( true ),
			),
		) );

		register_rest_route( $namespace, '/refunds', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'create_item' ),
				'args'     => $this->get_endpoint_args_for_item_schema( true ),
			),
		) );

		register_rest_route( $namespace, '/customers', array(
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'create_item' ),
				'args'     => $this->get_endpoint_args_for_item_schema( true ),
			),
		) );

	}

}