<?php
/**
 * This is the main sample gateway plugin class
 *
 * This singleton acts as a bootstrap to load files, add initializing actions, etc...
 *
 * @package LifterLMS_Sample_Gateway/Classes
 *
 * @since 2020-09-04
 * @version 2020-09-04
 */

defined( 'ABSPATH' ) || exit;

/**
 * LifterLMS Gateway Sample
 */
final class LifterLMS_Sample_Gateway {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $version = '2020-09-04';

	/**
	 * Singleton instance of the class
	 *
	 * @var LifterLMS_Sample_Gateway
	 */
	private static $instance = null;

	/**
	 * Singleton Instance of the LifterLMS_Stripe class
	 *
	 * @since 2020-09-04
	 *
	 * @return LifterLMS_Sample_Gateway
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Constructor.
	 *
	 * @since 2020-09-04
	 *
	 * @return void
	 */
	private function __construct() {

		if ( ! defined( 'LLMS_SAMPLE_GATEWAY_VERSION' ) ) {
			define( 'LLMS_SAMPLE_GATEWAY_VERSION', $this->version );
		}

		// Load translations.
		add_action( 'init', array( $this, 'load_textdomain' ), 0 );

		// Load the plugin.
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		// Cleanup.
		register_deactivation_hook( LLMS_SAMPLE_GATEWAY_PLUGIN_FILE, array( $this, 'deactivate' ) );

		// Register the mock REST API used by the LLMS_SG_API class. When creating your own gateway you don't need this and can delete this hook/function/class/containing directory.
		add_action(
			'rest_api_init',
			function() {
				require_once LLMS_SAMPLE_GATEWAY_PLUGIN_DIR . 'fake-rest-api/class-llms-sg-mock-rest.php';
				$api = new LLMS_SG_Mock_REST();
				$api->register_routes();
			}
		);

	}

	/**
	 * Initialize and make a new API request.
	 *
	 * @since 2020-09-04
	 *
	 * @param string $resource Resource endpoint/path for the request.
	 * @param array  $data     Associative array of data to pass in the request body.
	 * @param string $method   Request method.
	 * @return LLMS_SG_API
	 */
	public function api( $resource, $data, $method = 'POST' ) {
		return new LLMS_SG_API( $resource, $data, $method );
	}

	/**
	 * Determines whether or not the plugin's dependencies are met
	 *
	 * This stub checks to see if the minimum required version of LifterLMS is installed
	 *
	 * @since 2020-09-04
	 *
	 * @return boolean
	 */
	public function are_plugin_requirements_met() {

		return ( function_exists( 'llms' ) && version_compare( '4.0.0', llms()->version, '<=' ) );

	}

	/**
	 * Plugin deactivation.
	 *
	 * This method can be used to delete plugin data such as options or custom post types.
	 *
	 * @since 2020-09-04
	 *
	 * @return void
	 */
	public function deactivate() {}

	/**
	 * Retrieves an instance of the gateway itself
	 *
	 * This function isn't strictly necessary but is useful to quickly retrieve an instance of the gateway.
	 *
	 * @since 2020-09-04
	 *
	 * @example llms_sample_gateway()->get_gateway()
	 *
	 * @return LLMS_Payment_Gateway_Sample
	 */
	public function get_gateway() {
		return llms()->payment_gateways()->get_gateway_by_id( 'sample' );
	}

	/**
	 * Include all required files and classes
	 *
	 * @since 2020-09-04
	 *
	 * @return void
	 */
	public function init() {

		// Only load the plugin if the plugin's requirements have been met.
		if ( $this->are_plugin_requirements_met() ) {

			// Register the payment gateway with LifterLMS.
			add_filter( 'lifterlms_payment_gateways', array( $this, 'register_gateway' ) );

			// Load all plugin files.
			$this->includes();

		}

	}

	/**
	 * Include all required files
	 *
	 * @since 2020-09-04
	 *
	 * @return void
	 */
	private function includes() {

		require_once LLMS_SAMPLE_GATEWAY_PLUGIN_DIR . 'includes/class-llms-payment-gateway-sample.php';
		require_once LLMS_SAMPLE_GATEWAY_PLUGIN_DIR . 'includes/class-llms-sg-api.php';

	}

	/**
	 * Load Localization files
	 *
	 * The first loaded file takes priority.
	 *
	 * Files can be found in the following order:
	 *      WP_LANG_DIR/lifterlms/lifterlms-sample-gateway-LOCALE.mo
	 *      WP_LANG_DIR/plugins/lifterlms-sample-gateway-LOCALE.mo
	 *
	 * @since 2020-09-04
	 *
	 * @return void
	 */
	public function load_textdomain() {

		// Load locale.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'lifterlms-sample-gateway' );

		// Load a lifterlms specific locale file if one exists.
		load_textdomain( 'lifterlms-sample-gateway', WP_LANG_DIR . '/lifterlms/lifterlms-sample-gateway-' . $locale . '.mo' );

		// Load localization files.
		load_plugin_textdomain( 'lifterlms-sample-gateway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	}

	/**
	 * Register the gateway with LifterLMS
	 *
	 * @since 2020-09-04
	 *
	 * @param string[] $gateways Array of currently registered gateway class names.
	 * @return string[]
	 */
	public function register_gateway( $gateways ) {

		$gateways[] = 'LLMS_Payment_Gateway_Sample';
		return $gateways;

	}

}
