<?php
/**
 * LifterLMS Sample Payment Gateway Plugin
 *
 * This main file is loaded by WordPress, defines a few constants, and includes the main plugin class.
 *
 * @package LifterLMS_Sample_Gateway/Main
 *
 * @since [version]
 * @version [version]
 *
 * Plugin Name: LifterLMS Sample Payment Gateway
 * Plugin URI: https://github.com/gocodebox/lifterlms-gateway-sample
 * Description: This plugin is a sample payment gateway which exists to demonstrate how to build a payment gateway plugin for LifterLMS
 * Version: 1.0.0
 * Author: LifterLMS
 * Author URI: https://lifterlms.com
 * Text Domain: lifterlms-sample-gateway
 * Domain Path: /i18n
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'LLMS_SAMPLE_GATEWAY_PLUGIN_FILE' ) ) {
	define( 'LLMS_SAMPLE_GATEWAY_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'LLMS_SAMPLE_GATEWAY_PLUGIN_DIR' ) ) {
	define( 'LLMS_SAMPLE_GATEWAY_PLUGIN_DIR', dirname( LLMS_SAMPLE_GATEWAY_PLUGIN_FILE ) . '/' );
}

if ( ! class_exists( 'LifterLMS_Sample_Gateway' ) ) {
	require_once LLMS_SAMPLE_GATEWAY_PLUGIN_DIR . 'class-lifterlms-sample-gateway.php';
}

/**
 * Main gateway instance
 *
 * @since [version]
 *
 * @return LifterLMS_Sample_Gateway
 */
function llms_sample_gateway() {
	return LifterLMS_Sample_Gateway::instance();
}
return llms_sample_gateway();
