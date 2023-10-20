<?php
if (!class_exists('TPG_TYRO_PAYMENT_GATEWAY_SETTING')) {
    class TPG_TYRO_PAYMENT_GATEWAY_SETTING
    {
        public function __construct()
        {
            // add_action( 'init', array( $this, 'woo_sc_post_type' ) );
            add_action('admin_menu', array($this, 'add_plugin_for_admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
        }
        function add_plugin_for_admin_menu()
        {
            add_menu_page('Tyro payment', 'Tyro payment', 'manage_options', 'tyro-payment-gateway', array($this, 'render_tyro_setting_page'), 'dashicons-smiley', 25);
        }
        function register_scripts()
        {
            //enqueue css
            wp_enqueue_style('tyro_payment_admin_css', TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_URL . 'css/tyro_payment_admin.css', '', TPG_TYRO_PAYMENT_GATEWAY_VERSION);
            //enqueue js
            // wp_enqueue_script('size_chart_menu_js', TPG_TYRO_PAYMENT_GATEWAY_PLUGIN_URL . 'js/tab.min.js', array('jquery'), TPG_TYRO_PAYMENT_GATEWAY_VERSION);
        }
        public function get_setting_options()
        {
            
            if (isset($_POST['tyro_save_settings'])) {
                if (!empty($_POST['aip-key'])) {
                    $save_option['aip-key'] = $_POST['aip-key'];
                    echo 'co gia tri';
                } else {
                    $save_option['aip-key'] = '';
                    echo 'vo gia tri';
                }
                update_option('tpg_tyro_setting_options', $save_option);
            }
            
        }
        function render_tyro_setting_page()

        {
            $this->get_setting_options();
            $options = get_option('tpg_tyro_setting_options');
?>
            <div class="wrap">
                <h2>Tyro Payment Gateway Settings</h2>
                <div class="tiro-settings-wrap">
                    <form method="post" class="form-setting">
                        <div class="field-aip-key">
                            <label class="api-key-lable" for="aip-key">API key</label>
                            <textarea id="aip-key" name="aip-key" placeholder="Enter API key here!" rows="5"><?php if (isset($options['aip-key']) && $options['aip-key'] !== '') {
                                echo esc_textarea($options["aip-key"]);
                            } ?></textarea>
                        </div>
                        <button type="submit" name="tyro_save_settings" id="tyro_save_settings">Save</button>
                    </form>
                </div>
            </div>
<?php
        }
    }
}
