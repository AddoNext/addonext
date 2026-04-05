<?php
namespace Elementrify\Widgets\WooCheckout;

defined('ABSPATH') or exit;

/**
 * Custom payment gateway list markup compatible with WooCommerce checkout.js (no payment-method.php template).
 */
class CheckoutPaymentUi
{
    public static function render_gateway_list(): string
    {
        if (!function_exists('WC') || !WC()->cart) {
            return '';
        }

        ob_start();

        if (WC()->cart->needs_payment()) {
            $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
            WC()->payment_gateways()->set_current_gateway($available_gateways);
        } else {
            $available_gateways = [];
        }

        echo '<ul class="wc_payment_methods payment_methods methods elementrify-co-payment-methods">';

        if ($available_gateways !== []) {
            foreach ($available_gateways as $gateway) {
                CheckoutTemplate::partial('payment-method-row', ['gateway' => $gateway]);
            }
        } else {
            $msg = apply_filters(
                'woocommerce_no_available_payment_methods_message',
                WC()->customer->get_billing_country()
                    ? esc_html__('Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')
                    : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')
            );
            echo '<li class="elementrify-co-payment-item elementrify-co-tpl-payment-empty"><p class="elementrify-co-tpl-payment-msg">' . wp_kses_post($msg) . '</p></li>';
        }

        echo '</ul>';

        return ob_get_clean();
    }
}
