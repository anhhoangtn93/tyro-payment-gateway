<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require( TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_DIR . 'admin/admin.php' );
require( TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_DIR . 'admin/setting-page.php' );
require( TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_DIR . 'admin/tyro-checkout-description-fields.php' );
error_log('tyro-checkout-description-fields.php đã được chạy.');
if ( class_exists( 'TPG_TYRO_PAYMENT_GATEWAY_REGISTER' ) ) {
	new TPG_TYRO_PAYMENT_GATEWAY_REGISTER();
}
if ( class_exists( 'TPG_TYRO_PAYMENT_GATEWAY_SETTING' ) ) {
	new TPG_TYRO_PAYMENT_GATEWAY_SETTING();
}
?>