<?php

add_filter('woocommerce_gateway_description', 'tyro_payment_fields', 20, 2);
function tyro_payment_fields($description, $payment_id)
{
    if ('tyro_payment' !== $payment_id) {
        return $description;
    }
    ob_start();
?>
    <div id="error-message" style="display:none"></div>
    <div id="pay-form-submitting-overlay" style="display:none">
        <div class="loading-holder">
            <div class="lds-ring">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <form id="pay-form">
        <div id="tyro-pay-form">
        </div>
        <button id="pay-form-submit">Pay</button>
    </form>
    <div id="success-message" style="display:none">Thank you,<br />Your order has been successfully paid.</div>

<?php
    if (is_null($description)) {
        $description = '';
    }
    $description .= ob_get_clean();
    return $description;
}
