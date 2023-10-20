<?php
if (!class_exists('TPG_TYRO_PAYMENT_GATEWAY_REGISTER')) {

    class TPG_TYRO_PAYMENT_GATEWAY_REGISTER
    {

        public function __construct()
        {
            add_action('plugins_loaded', 'init_tyro_gateway');
            add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
            add_filter('woocommerce_payment_gateways', 'add_tyro_gateway_woo');
        }
        public function register_scripts()
        {

            if (is_checkout()) {
                //enqueue js
                wp_enqueue_script('tyrojs', 'https://pay.connect.tyro.com/v1/tyro.js', array(), null, false);
                wp_enqueue_script(
                    'tyrocheckoutjs',
                    TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_URL . 'js/tyro-checkout.js',
                    array('tyrojs'),
                    TPG_TYRO_PAYMENT_GATEWAY_VERSION,
                    true
                );
            }
        }
    }

    function init_tyro_gateway()
    {

        class WC_Gateway_Tyro extends WC_Payment_Gateway
        {
            public function __construct()
            {
                $this->id = 'tyro_payment';
                $this->icon = TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_URL . '/icon/icon.png';
                // $this->supports = array('default_credit_card_form');
                $this->method_title = 'Tyro Payment Gateway';
                $this->method_description = 'Tyro Payment descriptions';
                $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable/Disable', 'woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Enable Tyro payment', 'woocommerce'),
                        'default' => 'yes'
                    ),
                    'title' => array(
                        'title' => __('Title', 'woocommerce'),
                        'type' => 'text',
                        'description' => 'Payment gateway title',
                        'default' => __('Cheque Payment', 'woocommerce'),
                        'desc_tip' => true,
                    ),
                    'description' => array(
                        'title' => __('Customer Message', 'woocommerce'),
                        'type' => 'textarea',
                        'default' => ''
                    )
                );
                $this->title = $this->get_option('title');
                $this->description = $this->get_option('description');
                $this->has_fields = true;
                // $this->payment_fields();
                $this->init_settings();
                if (is_admin()) {
                    add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
                }
            }
            function process_payment($order_id)
            {
                global $woocommerce;
                $order = new WC_Order($order_id);
                // Mark as on-hold (we're awaiting the cheque)
                $order->update_status('on-hold', __('Awaiting cheque payment', 'woocommerce'));

                // Remove cart
                $woocommerce->cart->empty_cart();

                // Return thankyou redirect
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order)
                );
            }
        }
    }


    function add_tyro_gateway_woo($methods)
    {
        $methods[] = 'WC_Gateway_Tyro';
        return $methods;
    }
}
