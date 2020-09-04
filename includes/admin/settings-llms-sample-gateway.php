<?php
/**
 * LifterLMS Stripe Gateway Settings
 *
 * @package LifterLMS_Sample_Gateway/Admin/Settings
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

$gateway = llms_sample_gateway()->get_gateway();

$fields = array();

/**
 * Allow users to enter an API Key to use the gateway.
 *
 * Some gateways may have multiple authentication keys, for example
 * Stripe uses both a secret and publishable key. You can add
 * as many authentication fields as needed but our example gateway
 * is going to use a single key.
 */
$fields[] = array(
	/**
	 * Each setting maps to an option in the `wp_options` database table.
	 *
	 * Using the `get_option_name()` method from the abstract makes it so you don't need
	 * to use the gateway's prefix for each option.
	 *
	 * This will end up creating a setting `llms_gateway_sample_live_api_key`.
	 */
	'id'            => $gateway->get_option_name( 'live_api_key' ),

	/**
	 * The title of the setting shown to users on the admin panel.
	 */
	'title'         => __( 'Live Mode API Key', 'lifterlms-sample-gateway' ),

	/**
	 * An optional option description.
	 *
	 * Allows HTML which might be useful for adding links to documentation related to the option.
	 */
	'desc'          => '<br>' . sprintf( __( 'Need help finding your API key? %1$sLearn how.%2$s', 'lifterlms-sample-gateway' ), '<a href="#documentation-link">', '</a>' ),

	/**
	 * The type of setting field.
	 *
	 * Accepts HTML5 text input types like text, password, email, etc...
	 *
	 * Also accepts "textarea", "select", "checkbox", etc...
	 */
	'type'          => 'text',

	/**
	 * The `secure_option` is designed to enable the definition of credentials in
	 * either environment variables or constants (which can be stored in the site's wp-config.php).
	 *
	 * When a constant is defined this field will automatically erase the value stored in the database
	 * and use the value defined in the constant instead.
	 *
	 * We *all know* that storing credentials in plaintext in the database is a *bad idea* but we also
	 * know that we all have users who cannot handle adding credentials via PHP in a config file.
	 *
	 * The secure option is the recommended way but we know some users will never do that.
	 *
	 * We recommend defining a secure option for your credentials and then LifterLMS will automatically
	 * look for an environment variable, constant, and fallback to the database as a last resort.
	 */
	'secure_option' => 'LLMS_SAMPLE_GATEWAY_LIVE_API_KEY',
);

/**
 * Our sample gateway has fake support for a test mode that uses
 * a different API key.
 *
 * If your gateway doesn't have test mode delete this field.
 */
$fields[] = array(
	'id'            => $gateway->get_option_name( 'test_api_key' ),
	'title'         => __( 'Sandbox API Key', 'lifterlms-sample-gateway' ),
	'desc'          => '<br>' . __( 'Use API Key "SECRET" for automatic success with the mock API. Leave blank or enter anything else to see error handlers.', 'lifterlms-sample-gateway' ),
	'type'          => 'text',
	'secure_option' => 'LLMS_SAMPLE_GATEWAY_TEST_API_KEY',
);

/**
 * Checkbox settings will automatically save "yes" when the box is checked and "no" when the box is not checked.
 *
 * You can check whether or not the setting is enabled using `llms_parse_bool()`.
 */
$fields[] = array(
	'id'    => $gateway->get_option_name( 'checkbox_option' ),
	'title' => __( 'Toggleable Gateway Setting', 'lifterlms-sample-gateway' ),
	'desc'  => __( 'Enable an optional gateway feature with a checkbox', 'lifterlms-sample-gateway' ),
	'type'  => 'checkbox',
);

/**
 *
 */
$fields[] = array(
	'id'      => $gateway->get_option_name( 'select_option' ),
	'title'   => __( 'Multiple Option Setting', 'lifterlms-sample-gateway' ),
	'desc'    => '<br>' . __( 'Add a gateway option with a dropdown.', 'lifterlms-sample-gateway' ),
	'type'    => 'select',
	'options' => array(
		'one'   => esc_html__( 'Option One', 'lifterlms-sample-gateway' ),
		'two'   => esc_html__( 'Option Two', 'lifterlms-sample-gateway' ),
		'three' => esc_html__( 'Option Three', 'lifterlms-sample-gateway' ),
	),
);

return $fields;
