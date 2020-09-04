<?php
/**
 * Main Payment Gateway class file
 *
 * This class is the main payment gateway class that extends the core LLMS_Payment_Gateway abstract
 *
 * @package LifterLMS_Sample_Gateway/Classes
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
	 * Each option defined in the gateway settings should be defined as a protected class variable
	 *
	 * The value defined here is used as the default when no value is stored in the database.
	 */

	/**
	 * Checkbox option.
	 *
	 * @var string
	 */
	protected $checkbox_option = '';

	/**
	 * Live mode API key option.
	 *
	 * @var string
	 */
	protected $live_api_key = '';

	/**
	 * Select option
	 *
	 * @var string
	 */
	protected $select_option = '';

	/**
	 * Test mode API key option.
	 *
	 * @var string
	 */
	protected $test_api_key = '';

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

	}

	/**
	 * Output custom settings fields on the LifterLMS Gateways Screen
	 *
	 * @since [version]
	 *
	 * @param array  $default_fields Array of existing fields.
	 * @param string $gateway_id Id of the gateway.
	 * @return array
	 */
	public function add_settings_fields( $default_fields, $gateway_id ) {

		// Only add fields to this payment gateway.
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
		$this->title = _x( 'Sample Payment Gateway', 'Gateway title', 'lifterlms-sample-gateway' );

		/**
		 * The gateway's description displayed to users on the frontend of the website.
		 *
		 * In our Stripe gateway the description is is "Processed by Stripe".
		 *
		 * This field can be edited by site admins on the gateway's settings screen.
		 *
		 * @var string
		 */
		$this->description = __( 'Secure Sample Purchases', 'lifterlms-sample-gateway' );

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
		$this->admin_title = _x( 'Sample Payment Gateway', 'Gateway admin title', 'lifterlms-sample-gateway' );

		/**
		 * The payment gateway's description as displayed on the admin panel on settings screens.
		 *
		 * In our Stripe gateway the description is "Allow customers to purchase courses and memberships using Stripe.".
		 *
		 * @var [type]
		 */
		$this->admin_description = __( 'A sample payment gateway used to document requirements for building a LifterLMS payment gateway.', 'lifterlms-sample-gateway' );

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
		$this->icon = '<img src="' . plugins_url( 'assets/img/icon.png', LLMS_SAMPLE_GATEWAY_PLUGIN_FILE ) . '" alt="' . __( 'Sample Payment Gateway Icon', 'lifterlms-sample-gateway' ) . '">';

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
		$this->test_mode_title = __( 'Sandbox Mode', 'lifterlms-sample-gateway' );

		/**
		 * A description of the gateway's testing environment.
		 *
		 * This variable accepts HTML anchors, useful for including links to documentation about the gateway's
		 * test environment.
		 *
		 * @var [type]
		 */
		$this->test_mode_description = sprintf(
			// Translators: %1$s = opening anchor tag; %2$s = closing anchor tag.
			__( 'Sandbox Mode can be used to process test transactions. %1$sLearn More.%2$s', 'lifterlms-sample-gateway' ),
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
			'checkout_fields'    => true,

			/**
			 * Denotes that users can process refunds from the order screen on the admin panel
			 * of the LifterLMS site.
			 */
			'refunds'            => true,

			/**
			 * Denotes that one-time payment access plans can be processed by this gateway.
			 */
			'single_payments'    => true,

			/**
			 * Denotes that recurring payment access plans can be processed by this gateway.
			 */
			'recurring_payments' => true,

			/**
			 * Denotes that failed recurring payments are automatically retried by the gateway.
			 */
			'recurring_retry'    => true,

			/**
			 * Denotes that the gateway has a test environment or mode which can be used
			 * to run test or sandbox transactions.
			 */
			'test_mode'          => true,
		);

		/**
		 * Configure fields that are supported by the gateway
		 *
		 * Supported fields are displayed on the admin panel with the order
		 * under "Gateway Information" and are editable by the end user.
		 *
		 * @var boolean[]
		 */
		$this->admin_order_fields = wp_parse_args(
			array(
				// Used to save the gateway's customer ID.
				'customer'     => true,
				// Used to save the gateway's payment source ID (card, bank account, etc...).
				'source'       => true,
				// Used to save the gateway's subscription ID.
				'subscription' => false,
			),
			// Merge with defaults from the core abstract for forward compatibility.
			$this->admin_order_fields
		);
	}

	/**
	 * Retrieve the API Key for the current API mode
	 *
	 * @since [version]
	 *
	 * @return string
	 */
	public function get_api_key() {
		$mode          = $this->get_api_mode();
		$option        = sprintf( '%s_api_key', $mode ); // Option name.
		$secure_option = sprintf( 'LLMS_SAMPLE_GATEWAY_%s_API_KEY', strtoupper( $mode ) ); // "Secure" option name.
		return $this->get_option( $option, $secure_option );
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

	/**
	 * Retrieves user-submitted fields from the $_POST array
	 *
	 * Performs validation and returns the data in a structured array.
	 *
	 * @since [version]
	 *
	 * @return array|WP_Error
	 */
	protected function get_field_data() {

		$errs = new WP_Error();
		$data = array();

		// Retrieve all checkout fields.
		foreach ( array( 'number', 'expiration', 'cvc' ) as $field ) {

			$data[ $field ] = llms_filter_input( INPUT_POST, 'llms_sg_' . $field, FILTER_SANITIZE_STRING );

			// In our example, all fields are required.
			if ( empty( $data[ $field ] ) ) {
				// Translators: %s = field key.
				$errs->add( 'llms_sg_checkout_requied_field_' . $field, sprintf( __( 'Missing required field: %s', 'lifterlms-sample-gateway' ), $field ) );
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
				$errs->add( 'llms_sg_checkout_invalid_expiration', __( 'Invalid expiration date.', 'lifterlms-sample-gateway' ) );
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
	 * @return null|void
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

		/**
		 * We can also add additional custom validations that may not have anything to do with the card.
		 *
		 * For example, the gateway may have minimum and maximum transaction values.
		 *
		 * For our example gateway we can only process transactions greater than $0.50 and less than $1000.00.
		 *
		 * This obviously gets more complicated when you take currency into account because gateways probably
		 * have different requirements based on currency.
		 */
		$total    = $order->get_price( 'total', array(), 'float' );
		$currency = $order->get( 'currency' );
		if ( $total < 0.50 ) {
			$this->log( 'Sample Gateway `handle_pending_order()` ended with validation errors', 'Less than minimum order amount.' );
			// Translators: %1$s = currency code; %2$s = price.
			return llms_add_notice( sprintf( _x( 'This gateway cannot process %1$s transactions for less than %2$s.', 'min transaction amount error', 'lifterlms-sample-gateway' ), $currency, llms_price_raw( $min ) ), 'error' );
		} elseif ( $total > 1000.00 ) {
			$this->log( 'Sample Gateway `handle_pending_order()` ended with validation errors', 'Greater than minimum order amount.' );
			// Translators: %1$s = currency code; %2$s = price.
			return llms_add_notice( sprintf( _x( 'This gateway cannot process %1$s transactions for more than %2$s.', 'max transaction amount error', 'lifterlms-sample-gateway' ), $currency, llms_price_raw( self::MAX_AMOUNT ) ), 'error' );
		}

		/**
		 * Make the API request to the gateway provider.
		 *
		 * **Remember that our example is not a real API!**
		 *
		 * You'll need to add data based on the provider's requirements
		 * and will likely need to handle recurring and one-time payments differently.
		 *
		 * You can determine if it's a one-time or recurring payment using
		 * `$plan->is_recurring()`
		 */
		$req = llms_sample_gateway()->api(
			'/transactions',
			array_merge(
				array(
					'email'  => $student->get( 'email' ),
					'amount' => $total,
				),
				$card_info
			)
		);

		/**
		 * Handle Errors.
		 *
		 * Try checkout with no API key saved in the Sample Gateway settings to see it in action.
		 */
		if ( $req->is_error() ) {
			$this->log( 'Sample Gateway `handle_pending_order()` ended with api request errors', $req->get_result() );
			return llms_add_notice( $req->get_error_message(), 'error' );
		}

		$res = $req->get_result();

		/**
		 * Our Mock API will automatically succeed when using CC number 4242424242424242
		 *
		 * Any other card number is automatically declined.
		 *
		 * If the API doesn't return a success we'll output an error.
		 */
		if ( 'success' !== $res['status'] ) {
			$this->log( 'Sample Gateway `handle_pending_order()` ended with card errors', $res );
			return llms_add_notice(
				sprintf(
				// Translators: %s = card error code.
					__( 'Card error: %s', 'lifterlms-sample-gateway' ),
					$res['status']
				),
				'error'
			);
		}

		/**
		 * Success!
		 *
		 * Record gateway data on the order and complete the transaction.
		 */

		// You can add notes to the order.
		$order->add_note(
			sprintf(
			// Translators: %s = gateway customer id.
				__( 'Gateway customer "%s" created or updated.', 'lifterlms-sample-gateway' ),
				$res['customer_id']
			)
		);

		/**
		 * Store a customer ID
		 *
		 * This is displayed on the WP admin panel and can be used to create links to the customer
		 * on the gateway provider's admin panel or dashboard.
		 */
		$order->set( 'gateway_customer_id', $res['customer_id'] );

		/**
		 * Store a source ID
		 *
		 * The source might be a credit or debit card, a payment token, bank account, etc...
		 *
		 * This is displayed on the WP admin panel and can be used to create links to the source
		 * on the gateway provider's admin panel or dashboard.
		 */
		$order->set( 'gateway_source_id', $res['source_id'] );

		/**
		 * Store a subscription ID
		 *
		 * If the gateway has it's own subscription ID this can be stored here with this meta key.
		 *
		 * We aren't using this in our example but it's here to document all potential keys LifterLMS
		 * looks for and uses.
		 */
		// $order->set( 'gateway_subscription_id', $res['subscription_id'] );

		// Record the transaction.
		$this->record_transaction( $order, $res, 'initial' );

		/**
		 * Trigger order "completion".
		 */
		$this->complete_transaction( $order );

	}

	/**
	 * Record a transaction on the order
	 *
	 * This is used by `handle_pending_order()` during all transactions and later by `handle_recurring_transaction()`
	 * when a recurring payment is triggered by the background process scheduler.
	 *
	 * @since [version]
	 *
	 * @param LLMS_Order $order              Order object.
	 * @param array      $gateway_txn_result Associative array of transaction result data from our mock api.
	 * @param string     $type               The type of payment. Either "initial" for the first payment on an order or "recurring" for recurring payments.
	 * @return LLMS_Transaction
	 */
	protected function record_transaction( $order, $gateway_txn_result, $type = 'initial' ) {

		$payment_type = 'single';
		if ( $order->is_recurring() ) {
			$payment_type = ( $order->has_trial() && 'initial' === $type ) ? 'trial' : 'recurring';
		}

		$args = array(
			'amount'       => $gateway_txn_result['amount'],
			'customer_id'  => $order->get( 'gateway_customer_id' ),
			'status'       => sprintf( 'llms-txn-%s', 'success' === $gateway_txn_result['status'] ? 'succeeded' : 'failed' ),
			'payment_type' => $payment_type,
		);

		$args['completed_date']     = gmdate( 'Y-m-d H:i:s', $gateway_txn_result['created'] );
		$args['source_id']          = $gateway_txn_result['source_id'];
		$args['source_description'] = 'Visa ending in 4242'; // This is a human-readable name for the card. Don't save the card number in the DB, okay!
		$args['transaction_id']     = $gateway_txn_result['id'];

		if ( 'succeeded' === $gateway_txn_result['status'] ) {

			$order->add_note(
				sprintf(
					// Translators: %1$s = Payment type; $2$s = Payment source ID; %3$s = Charge ID.
					__( 'Charge attempt for %1$s payment succeeded! [Charge ID: %2$s]', 'lifterlms-sample-gateway' ),
					$payment_type,
					$gateway_txn_result['id']
				)
			);

		} else {

			$order->add_note(
				sprintf(
					// Translators: %1$s = Payment type; $2$s = Payment source ID; $3$s = Error message; %4$s = Charge ID.
					__( 'Charge attempt for %1$s failed. [Charge ID: %2$s]', 'lifterlms-sample-gateway' ),
					$payment_type,
					$gateway_txn_result['id']
				)
			);

		}

		return $order->record_transaction( $args );

	}

	/**
	 * Gateways can override this to return a URL to a customer permalink on the gateway's website
	 *
	 * View a completed order on the WP admin panel to see the stored IDs converted to clickable links.
	 *
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @since 3.0.0
	 *
	 * @param string $customer_id Gateway's customer ID.
	 * @param string $api_mode    Link to either the live or test site for the gateway, where applicable.
	 * @return string
	 */
	public function get_customer_url( $customer_id, $api_mode = 'live' ) {
		return sprintf( 'https://dashboard.myfakepaymentprovider.com/%1$s/customers/%2$s', $api_mode, $customer_id );
	}

	/**
	 * Gateways can override this to return a URL to a source permalink on the gateway's website
	 *
	 * View a completed order on the WP admin panel to see the stored IDs converted to clickable links.
	 *
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @since 3.0.0
	 *
	 * @param string $source_id Gateway's source ID.
	 * @param string $api_mode  Link to either the live or test site for the gateway, where applicable.
	 * @return string
	 */
	public function get_source_url( $source_id, $api_mode = 'live' ) {
		return sprintf( 'https://dashboard.myfakepaymentprovider.com/%1$s/sources/%2$s', $api_mode, $source_id );
	}

	/**
	 * Gateways can override this to return a URL to a subscription permalink on the gateway's website
	 *
	 * View a completed order on the WP admin panel to see the stored IDs converted to clickable links.
	 *
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @since 3.0.0
	 *
	 * @param string $subscription_id Gateway's source ID.
	 * @param string $api_mode        Link to either the live or test site for the gateway, where applicable.
	 * @return string
	 */
	public function get_subscription_url( $subscription_id, $api_mode = 'live' ) {
		return sprintf( 'https://dashboard.myfakepaymentprovider.com/%1$s/subscriptions/%2$s', $api_mode, $subscription_id );
	}

	/**
	 * Gateways can override this to return a URL to a transaction permalink on the gateway's website
	 *
	 * View a completed order on the WP admin panel to see the stored IDs converted to clickable links.
	 *
	 * If this is not defined, it will just return the supplied ID
	 *
	 * @since 3.0.0
	 *
	 * @param string $transaction_id Gateway's source ID.
	 * @param string $api_mode       Link to either the live or test site for the gateway, where applicable.
	 * @return string
	 */
	public function get_transaction_url( $transaction_id, $api_mode = 'live' ) {
		return sprintf( 'https://dashboard.myfakepaymentprovider.com/%1$s/transactions/%2$s', $api_mode, $transaction_id );
	}

	/**
	 * Called by scheduled actions to charge an order for a scheduled recurring transaction
	 *
	 * This function must be defined by gateways which support recurring transactions.
	 *
	 * @param LLMS_Order $order Order object.
	 * @return   mixed
	 */
	public function handle_recurring_transaction( $order ) {

		$req = llms_sample_gateway()->api(
			'/transactions',
			array(
				'source'   => $order->get( 'gateway_source_id' ),
				'customer' => $order->get( 'gateway_customer_id' ),
				'amount'   => $order->get_price( 'total', array(), 'float' ),
			)
		);

		/**
		 * Record the transaction
		 *
		 * Both successful and failed transactions *should be recorded*.
		 *
		 * If recurring retries are enabled for the gateway you'll see the next payment automatically rescheduled
		 * according to the LifterLMS Automatic Retry rules.
		 *
		 * Notifications are *automatically sent* in this scenario.
		 */
		$this->record_transaction( $order, $req->get_result(), 'initial' );

	}

	/**
	 * Called when refunding via a Gateway
	 *
	 * Refunds for a transaction can be always be made manually. In this situation the admin recrords the refund
	 * in LifterLMS and is expected to also manually process a refund to the customer.
	 *
	 * If the payment provider supports refunds via an API request you can also enable "automatic" refunds. When
	 * the gateway supports refunds there will be an additional button "Refund via ${$this->admin_title}".
	 *
	 * When a refund is processed via this button then this method will be called which is responsible for processing
	 * the refund via the provider's API and recording the data back to the WP database.
	 *
	 * @since 3.0.0
	 *
	 * @param LLMS_Transaction $transaction Transaction object.
	 * @param float            $amount      Amount to refund.
	 * @param string           $note        Optional refund note to pass to the gateway.
	 * @return mixed
	 */
	public function process_refund( $transaction, $amount = 0, $note = '' ) {

		$this->log( 'Sample gateway `process_refund()` started', $transaction, $amount, $note );

		$req = llms_sample_gateway()->api(
			'/refunds',
			array(
				'amount' => $amount,
				'reason' => $note,
			)
		);

		$res = $req->get_result();

		if ( $req->is_error() ) {
			$this->log( 'Sample gateway `process_refund()` finished with errors', $req );
			return $res;
		}

		$this->log( 'Sample gateway `process_refund()` finished', $req );
		return $res['id'];

	}

}
