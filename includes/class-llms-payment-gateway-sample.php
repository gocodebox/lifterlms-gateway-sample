<?php
/**
 * Main Payment Gateway class file
 *
 * This class is the main payment gateway class that extends the core LLMS_Payment_Gateway abstract
 *
 * @package LifterLMS/Abstracts/Classes
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_Payment_Gateway_Sample
 *
 * @since [version]
 */
class LLMS_Payment_Gateway_Sample extends LLMS_Payment_Gateway {

	/**
	 * Constructor
	 *
	 * This method will configure class variables and attach all necessary hooks.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function __construct() {

		$this->configure_variables();

		// Add Custom Settings Fields to the gateway settings screen on the admin panel.
		add_filter( 'llms_get_gateway_settings_fields', array( $this, 'add_settings_fields' ), 10, 2 );

		// show payment method source description on the view order screen on the student dashboard.
		// add_action( 'lifterlms_view_order_after_payment_method', array( $this, 'output_order_payment_source' ) );

		// add_action( 'lifterlms_checkout_confirm_after_payment_method', array( $this, 'confirm_order_html' ) );

		// add_filter( 'llms_gateway_stripe_show_confirm_order_button', '__return_false' );

	}

	/**
	 * Output custom settings fields on the LifterLMS Gateways Screen
	 *
	 * @since [version]
	 *
	 * @param array $default_fields Array of existing fields.
	 * @param string $gateway_id Id of the gateway.
	 * @return array
	 */
	public function add_settings_fields( $default_fields, $gateway_id ) {

		// Only add fields to this payment gateway
		if ( $this->id === $gateway_id ) {
		
			/**
			 * Include the gateway field settings list.
			 *
			 * We like to define our settings fields in a separate file to help reduce the size of this gateway class.
			 */
			$fields = include LLMS_SAMPLE_GATEWAY_PLUGIN_DIR . 'includes/admin/settings-llms-sample-gateway.php';

			// Add the custom fields to the list of automatically-defined fields.
			$default_fields = array_merge( $default_fields, $fields );

		}
	
		return $default_fields;


	}

	/**
	 * Define class variables
	 *
	 * These variables are setup in the abstract but should be defined by each gateway individually.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	protected function configure_variables() {

		/**
		 * The gateway's unique ID.
		 *
		 * Used to identify the gateway programmatically.
		 *
		 * @var string
		 */
		$this->id = 'sample';

		/**
		 * The title of the gateway.
		 *
		 * This title will be displayed to users on the frontend of the website, on checkout and
		 * order history screens.
		 *
		 * This field can be edited by site admins on the gateway's settings screen.
		 *
		 * In our Stripe gateway the title is "Credit or Debit Card".
		 *
		 * @var string
		 */
		$this->title = _x( 'Sample Payment Gateway', 'Gateway title', 'lifterlms-stripe' );

		/**
		 * The gateway's description displayed to users on the frontend of the website.
		 *
		 * In our Stripe gateway the description is is "Processed by Stripe".
		 * 
		 * This field can be edited by site admins on the gateway's settings screen.
		 *
		 * @var string
		 */
		$this->description = __( 'Secure Sample Purchases', 'lifterlms-stripe' );
		
		/**
		 * The title of the gateway as displayed on the admin panel
		 *
		 * This will probably be the same as the `$title` but may differ if someone chooses to rebrand
		 * the gateway through translation.
		 *
		 * In our Stripe gateway the title is "Stripe".
		 *
		 * @var string
		 */
		$this->admin_title = _x( 'Sample Payment Gateway', 'Gateway admin title', 'lifterlms' );
		
		/**
		 * The payment gateway's description as displayed on the admin panel on settings screens.
		 *
		 * In our Stripe gateway the description is "Allow customers to purchase courses and memberships using Stripe.".
		 *
		 * @var [type]
		 */
		$this->admin_description = __( 'A sample payment gateway used to document requirements for building a LifterLMS payment gateway.', 'lifterlms-stripe' );

		/**
		 * The icon is an optional image displayed next to the title on the checkout screen.
		 *
		 * This variable accepts HTML and can be used to provide a link to information about the gateway, like it's
		 * security page, for example.
		 *
		 * It can be removed or left blank if no image or icon is desired
		 *
		 * @var string
		 */
		$this->icon = '<img src="' . plugins_url( 'assets/img/icon.png', LLMS_SAMPLE_GATEWAY_PLUGIN_FILE ) . '" alt="' . __( 'Sample Payment Gateway Icon', 'lifterlms' ) . '">';

		/**
		 * The title of the gateway's testing/sandbox environment
		 *
		 * Sometimes a gateway doesn't call it's testing environment "testing". This variable allows you
		 * to customize the language used to refer to test mode.
		 * 
		 * For example, PayPal calls it's test environment "Sandbox" while Stripe calls it "Testing".
		 *
		 * @var string
		 */
		$this->test_mode_title       = __( 'Sandbox Mode', 'lifterlms-stripe' );

		/**
		 * A description of the gateway's testing environment.
		 *
		 * This variable accepts HTML anchors, useful for including links to documentation about the gateway's
		 * test environment.
		 *
		 * @var [type]
		 */
		$this->test_mode_description = sprintf( 
			__( 'Sandbox Mode can be used to process test transactions. %1$sLearn More.%2$s', 'lifterlms', ),
			'<a href="#">', // Add a link to the gateway's documentation here.
			'</a>'
		);

		/**
		 * This variable determines what LifterLMS Gateway features are supported by this gateway.
		 *
		 * Each feature is disabled by default so any unsupported features can be deleted or switched to `false`
		 * to explicitly denote a lack of support.
		 * 
		 * @var array
		 */
		$this->supports = array(

			/**
			 * Checkout Fields are HTML fields displayed on the checkout form.
			 *
			 * Some gateways will have a custom form shown on the checkout page while others
			 * have hosted checkout fields that the user will be redirected to off site.
			 */
			'checkout_fields' => true,

			/**
			 * Denotes that users can process refunds from the order screen on the admin panel
			 * of the LifterLMS site.
			 */
			'refunds'  => true,

			/**
			 * Denotes that one-time payment access plans can be processed by this gateway.
			 */
			'single_payments' => true,
			
			/**
			 * Denotes that recurring payment access plans can be processed by this gateway.
			 */
			'recurring_payments' => true,

			/**
			 * Denotes that failed recurring payments are automatically retried by the gateway.
			 */
			'recurring_retry' => true,

			/**
			 * Denotes that the gateway has a test environment or mode which can be used
			 * to run test or sandbox transactions.
			 */
			'test_mode' => true,
		);

		// $this->admin_order_fields = wp_parse_args(
		// 	array(
		// 		'customer' => true,
		// 		'source'   => true,
		// 	),
		// 	$this->admin_order_fields
		// );

	}

	/**
	 * Output gateway's fields on the frontend checkout form
	 *
	 * This function is called automatically by the checkout form template in LifterLMS
	 * for any gateway that declares support for checkout fields.
	 *
	 * @since [version]
	 *
	 * @return string
	 */
	public function get_fields() {

		ob_start();
		llms_get_template(
			'checkout-fields.php',
			array(
				'gateway'  => $this,
				'selected' => ( $this->get_id() === LLMS()->payment_gateways()->get_default_gateway() ),
			),
			'',
			LLMS_SAMPLE_GATEWAY_PLUGIN_DIR . 'templates/'
		);

		/**
		 * This filter is defined in the LifterLMS Core abstract.
		 *
		 * For maximum compatibility with other plugins, themes, etc... this filter should be left intact
		 * to allow customization of the gateway fields.
		 *
		 * @since [version]
		 * 
		 * @param string $html The checkout field HTML.
		 */
		return apply_filters( 'llms_get_gateway_fields', ob_get_clean(), $this->id );

	}

	protected function get_field_data() {

		$errs = new WP_Error();
		$data = array();

		// Retrieve all checkout fields.
		foreach ( array( 'number', 'expiration', 'cvc' ) as $field ) {

			$data[ $field ] =  llms_filter_input( INPUT_POST, 'llms_sg_' . $field, FILTER_SANITIZE_STRING );

			// In our example, all fields are required.
			if ( empty( $data[ $field ] ) ) {
				$errs->add( 'llms_sg_checkout_requied_field_' . $field, sprintf( __( 'Missing required field: %s', 'lifterlms' ), $field  ) );
			}

		}

		/**
		 * Perform other validations.
		 *
		 * Most gateways will probably handle this logic for you but this sample gateway is going to validate the expiration field
		 * as an example for how to add custom validation.
		 *
		 * Additionally: this is not a very good validation on an expiration field. The purpose of this sample gateway is not
		 * to illustrate how to validate a credit card expiration field, it's to illustrate how a LifterLMS Payment Gateway Works.
		 *
		 * For a good validation example go search Stack Overflow like a real developer would!
		 */
		
		if ( ! empty( $data['expiration'] ) ) {

			$exp = array_filter( array_map( 'absint', array_map( 'trim', explode( '/', $data['expiration'] ) ) ) );

			if ( 2 !== count( $exp ) ) {
				$errs->add( 'llms_sg_checkout_invalid_expiration', __( 'Invalid expiration date.', 'lifterlms' ) );
			}

		}

		if ( $errs->has_errors() ) {
			return $errs;
		}

		return $data;

	}

	/**
	 * Handle a "pending" order
	 *
	 * This method is called called by `LLMS_Controller_Orders::create_pending_order()` on checkout form submission
	 * 
	 * All data will be validated before it's passed to this method.
	 *
	 * In this method, the plugin should interact with the payment gateway to process the transaction.
	 * 
	 * If your gateway handles payment processing entirely off site (like PayPal) this function should end with a redirect to the gateway's
	 * payment processing location.
	 *
	 * If it handles payment entirely through an API (like with Stripe) it should call the `complete_transaction()` method which will
	 * finalize the transaction and redirect the user based on logic in the LiftreLMS Core.
	 *
	 * @since [version]
	 * 
	 * @param LLMS_Order        $order   Order object.
	 * @param LLMS_Access_Plan  $plan    Access plan object.
	 * @param LLMS_Student      $student Student object.
	 * @param LLMS_Coupon|false $coupon  Coupon object when a coupon has been applied, otherwise `false.
	 * @return void
	 */
	public function handle_pending_order( $order, $plan, $student, $coupon = false ) {

		/**
		 * We like to add a lot of logs to payment processing methods in order to help debug issues,
		 * both during development and for live customer's sites.
		 *
		 * The `log()` method does nothing when the "Debug Logging" gateway setting is disabled
		 * so adding log calls is inexpensive from a processing standpoint.
		 */
		$this->log( 'Sample Gateway `handle_pending_order()` started', $order, $plan, $student, $coupon );

		// First we'll validate that the credit card form has been submitted.
		$card_info = $this->get_field_data();

		// If an error is returned, log it, add a notice, and return.
		if ( is_wp_error( $card_info ) ) {
			$this->log( 'Sample Gateway `handle_pending_order()` ended with validation errors', $card_info );
			return llms_add_notice( $card_info->get_error_message(), 'error' );
		}













		// // Get the token or saved card id.
		// $token = llms_filter_input( INPUT_POST, 'llms_stripe_token', FILTER_SANITIZE_STRING );
		// if ( ! $token ) {
		// 	$token = llms_filter_input( INPUT_POST, 'llms_stripe_saved_card_id', FILTER_SANITIZE_STRING );
		// }

		// if ( ! $token ) {
		// 	return llms_add_notice( __( 'Missing payment method details.', 'lifterlms-stripe' ), 'error' );
		// }

		// // do some gateway specific validation before proceeding.
		// $total    = $order->get_price( 'total', array(), 'float' );
		// $currency = $order->get( 'currency' );
		// $min      = llms_stripe_get_transaction_minimum( $currency );
		// if ( $total < $min ) {
		// 	// Translators: %1$s = Currency code; %2$s = minimum transaction amount.
		// 	return llms_add_notice( sprintf( _x( 'Stripe cannot process %1$s transactions for less than %2$s.', 'min transaction amount error', 'lifterlms-stripe' ), $currency, llms_price_raw( $min ) ), 'error' );
		// } elseif ( llms_stripe_get_amount( $total, $currency ) > self::MAX_AMOUNT ) {
		// 	// Translators: %1$s = Currency code; %2$s = maximum transaction amount.
		// 	return llms_add_notice( sprintf( _x( 'Stripe cannot process %1$s transactions for more than %2$s.', 'max transaction amount error', 'lifterlms-stripe' ), $currency, llms_price_raw( self::MAX_AMOUNT ) ), 'error' );
		// }

		// // create / update the customer in Stripe.
		// $customer_id = $this->handle_customer( $order->get( 'user_id' ), $token );
		// if ( is_wp_error( $customer_id ) ) {
		// 	$this->log( 'Stripe `handle_pending_order()` finished with errors', '$customer_id', $customer_id );
		// 	return llms_add_notice( $customer_id->get_error_message(), 'error' );
		// }

		// // Translators: %s = Stripe customer ID.
		// $order->add_note( sprintf( __( 'Stripe Customer "%s" created or updated.', 'lifterlms-stripe' ), $customer_id ) );

		// $order->set( 'gateway_customer_id', $customer_id );
		// $order->set( 'gateway_source_id', llms_filter_input( INPUT_POST, 'llms_stripe_card_id', FILTER_SANITIZE_STRING ) );

		// $intents = new LLMS_Stripe_Intents( $order );

		// // Setup the intent for a free trial.
		// if ( floatval( 0 ) === $order->get_initial_price( array(), 'float' ) && $order->has_trial() ) {
		// 	$intent = $intents->setup();
		// } else {
		// 	// create the payment intent.
		// 	$intent = $intents->create( 'initial' );
		// }

		// if ( is_wp_error( $intent ) ) {
		// 	$this->log( 'Stripe `handle_pending_order()` finished with errors', '$intent', $intent );
		// 	return llms_add_notice( $intent->get_error_message(), 'error' );
		// }

		// if ( 'succeeded' === $intent->status ) {

		// 	$intents->complete( $intent, 'initial' );
		// 	$this->log( $intent, 'Stripe `handle_pending_order()` finished' );
		// 	$this->complete_transaction( $order );

		// } elseif ( 'requires_action' === $intent->status ) {

		// 	llms_redirect_and_exit( llms_confirm_payment_url( $order->get( 'order_key' ) ) );

		// }

	}





















	/**
	 * Confirm a Payment
	 * Called by LLMS_Controller_Orders->confirm_pending_order() on confirm form submission
	 * Some validation is performed before passing to this function, as it's not required
	 * gateways will likely doing further validations as are needed
	 *
	 * Not required if a confirmation isn't required by the Gateway
	 * Stripe doesn't require this whereas PayPal does
	 *
	 * @param   obj $order   Instance LLMS_Order for the order being processed
	 * @return  void
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public function confirm_pending_order( $order ) {}

	/**
	 * Gateways can override this to return a URL to a customer permalink on the gateway's website
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @param    string $customer_id  Gateway's customer ID
	 * @param    string $api_mode     Link to either the live or test site for the gateway, where applicable
	 * @return   string
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public function get_customer_url( $customer_id, $api_mode = 'live' ) {
		return $customer_id;
	}

	/**
	 * Gateways can override this to return a URL to a source permalink on the gateway's website
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @param    string $source_id   Gateway's source ID
	 * @param    string $api_mode    Link to either the live or test site for the gateway, where applicable
	 * @return   string
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public function get_source_url( $source_id, $api_mode = 'live' ) {
		return $source_id;
	}

	/**
	 * Gateways can override this to return a URL to a subscription permalink on the gateway's website
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @param    string $subscription_id  Gateway's subscription ID
	 * @param    string $api_mode         Link to either the live or test site for the gateway, where applicable
	 * @return   string
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public function get_subscription_url( $subscription_id, $api_mode = 'live' ) {
		return $subscription_id;
	}

	/**
	 * Gateways can override this to return a URL to a transaction permalink on the gateway's website
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @param    string $transaction_id  Gateway's transaction ID
	 * @param    string $api_mode        Link to either the live or test site for the gateway, where applicable
	 * @return   string
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public function get_transaction_url( $transaction_id, $api_mode = 'live' ) {
		return $transaction_id;
	}

	/**
	 * Called when the Update Payment Method form is submitted from a single order view on the student dashboard
	 *
	 * Gateways should do whatever the gateway needs to do to validate the new payment method and save it to the order
	 * so that future payments on the order will use this new source
	 *
	 * This should be an abstract function but experience has taught me that no one will upgrade follow our instructions
	 * and they'll end up with 500 errors and debug mode disabled and send me giant frustrated question marks
	 *
	 * @since    3.10.0
	 *
	 * @param    obj   $order      Instance of the LLMS_Order
	 * @param    array $form_data  Additional data passed from the submitted form (EG $_POST)
	 *
	 * @return   null
	 */
	public function handle_payment_source_switch( $order, $form_data = array() ) {
		return llms_add_notice( sprintf( esc_html__( 'The selected payment Gateway "%s" does not support payment method switching.', 'lifterlms' ), $this->get_title() ), 'error' );
	}

	/**
	 * Called by scheduled actions to charge an order for a scheduled recurring transaction
	 * This function must be defined by gateways which support recurring transactions
	 *
	 * @param    obj $order   Instance LLMS_Order for the order being processed
	 * @return   mixed
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public function handle_recurring_transaction( $order ) {}

	/**
	 * Called when refunding via a Gateway
	 * This function must be defined by gateways which support refunds
	 * This function is called by LLMS_Transaction->process_refund()
	 *
	 * @param    obj    $transaction  Instance of the LLMS_Transaction
	 * @param    float  $amount       Amount to refund
	 * @param    string $note         Optional refund note to pass to the gateway
	 * @return   mixed
	 * @since    3.0.0
	 * @version  3.0.0
	 */
	public function process_refund( $transaction, $amount = 0, $note = '' ) {}

}
