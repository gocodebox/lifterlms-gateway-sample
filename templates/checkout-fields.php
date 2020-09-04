<?php
/**
 * File Summary
 *
 * File description.
 *
 * @package LifterLMS/Classes
 *
 * @since 2020-09-04
 * @version 2020-09-04
 *
 * @property LLMS_Payment_Gateway_Sample $gateway  Gateway class instance
 * @property boolean                     $selected Whether or not this gateway is the "selected" payment gateway on page load.
 */

defined( 'ABSPATH' ) || exit;

llms_print_notice( 'Use card number "4242424242424242" for automatic "success". Any other card number will be "declined".', 'debug' );

llms_form_field(
	array(
		'columns'         => 12,
		'disabled'        => $selected ? false : true,
		'id'              => 'llms_sg_number',
		'label'           => __( 'Card Number', 'lifterlms-sample-gateway' ),
		'last_column'     => true,
		'max_length'      => 19,
		'placeholder'     => '•••• •••• •••• ••••',
		'required'        => true,
		'type'            => 'tel',
		'wrapper_classes' => 'llms-auth-net-cc-number',
	)
);

llms_form_field(
	array(
		'columns'     => 6,
		'disabled'    => $selected ? false : true,
		'id'          => 'llms_sg_expiration',
		'label'       => __( 'Expiration', 'lifterlms-sample-gateway' ),
		'last_column' => false,
		'max_length'  => 9,
		'placeholder' => __( 'MM / YYYY', 'lifterlms-sample-gateway' ),
		'required'    => true,
		'type'        => 'tel',
	)
);

llms_form_field(
	array(
		'columns'     => 6,
		'disabled'    => $selected ? false : true,
		'id'          => 'llms_sg_cvc',
		'label'       => __( 'CVC', 'lifterlms-sample-gateway' ),
		'last_column' => true,
		'max_length'  => 4,
		'required'    => true,
		'type'        => 'tel',
	)
);
