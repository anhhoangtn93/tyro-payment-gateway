<?php
/*
 * Plugin Name: Tyro payment gateway
 * Plugin URI: https://www.arrowhitech.com/
 * Description: add payment gateway for woocommerce.
 * Version: 1.0.0
 * Author URI: https://www.arrowhitech.com/
 * Author: AHT
 * Copyright 2023-2024 AHT. All rights reserved.
 * Tested up to: 6.2.2
 * WC requires at least: 6.0
 * WC tested up to: 7.8.0
 * Requires PHP: 8.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//define constant
define( 'TPG_TYRO_PAYMENT_GATEWAY_VERSION', '1.0.0' );
define( 'TPG_TYRO_PAYMENT_GATEWAY_MINIUM_WP_VERSION', '5.0' );
define( 'TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_FILE', __FILE__ );
define( 'TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		require( TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_DIR . 'includes/define.php' );
}
?>