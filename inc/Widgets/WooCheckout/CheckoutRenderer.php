<?php
namespace Veloria\Widgets\WooCheckout;

defined('ABSPATH') or exit;

/**
 * Composes Veloria checkout: custom DOM for fields, order lines, and gateways; WooCommerce handles values and processing.
 */
class CheckoutRenderer
{
    /**
     * @return array{methods: string, place_order: string}
     */
    public static function split_payment_markup(string $html): array
    {
        $result = [
            'methods'     => '',
            'place_order' => '',
        ];

        $html = trim($html);
        if ($html === '') {
            return $result;
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $paymentRoot = $xpath->query("//*[@id='payment']")->item(0);
        if (!$paymentRoot instanceof \DOMElement) {
            $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' woocommerce-checkout-payment ')]");
            $paymentRoot = $nodes->length > 0 ? $nodes->item(0) : null;
        }

        if (!$paymentRoot instanceof \DOMElement) {
            return $result;
        }

        foreach ($paymentRoot->childNodes as $child) {
            if (!$child instanceof \DOMElement) {
                continue;
            }
            $class = $child->getAttribute('class');
            if ($child->tagName === 'ul' && strpos($class, 'wc_payment_methods') !== false) {
                $result['methods'] = $dom->saveHTML($child);
                continue;
            }
            if (strpos($class, 'place-order') !== false && strpos($class, 'form-row') !== false) {
                $result['place_order'] = $dom->saveHTML($child);
            }
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public static function render_form(\WC_Checkout $checkout, array $settings): void
    {
        ob_start();
        woocommerce_checkout_payment();
        $paymentFull = ob_get_clean();
        $paymentParts = self::split_payment_markup($paymentFull);

        $billingTitle = isset($settings['billing_section_title']) && $settings['billing_section_title'] !== ''
            ? (string) $settings['billing_section_title']
            : __('Billing details', 'veloria');
        $summaryTitle = isset($settings['order_summary_title']) && $settings['order_summary_title'] !== ''
            ? (string) $settings['order_summary_title']
            : __('Order summary', 'veloria');
        $paymentTitle = isset($settings['payment_section_title']) && $settings['payment_section_title'] !== ''
            ? (string) $settings['payment_section_title']
            : __('Payment method', 'veloria');

        $showCoupon = !empty($settings['show_coupon_sidebar']) && $settings['show_coupon_sidebar'] === 'yes';

        $gatewayMarkup = CheckoutPaymentUi::render_gateway_list();

        echo '<form name="checkout" method="post" class="checkout woocommerce-checkout" action="' . esc_url(wc_get_checkout_url()) . '" enctype="multipart/form-data" aria-label="' . esc_attr__('Checkout', 'veloria') . '">';
        echo '<input type="hidden" name="veloria_checkout" value="1" />';
        $showPaymentDescriptions = !empty($settings['show_payment_descriptions']) && $settings['show_payment_descriptions'] === 'yes';
        echo '<input type="hidden" name="veloria_show_payment_descriptions" value="' . esc_attr($showPaymentDescriptions ? '1' : '0') . '" />';

        echo '<div class="veloria-wc-checkout__layout">';

        echo '<div class="veloria-wc-checkout__col veloria-wc-checkout__col--main">';

        if ($checkout->get_checkout_fields()) {
            echo '<div id="customer_details" class="veloria-wc-checkout__customer-root">';
            do_action('woocommerce_checkout_before_customer_details');

            echo '<div class="veloria-wc-checkout__card veloria-wc-checkout__card--billing">';
            echo '<h3 class="veloria-wc-checkout__section-title veloria-wc-checkout__section-title--lg">' . esc_html($billingTitle) . '</h3>';
            echo '<div class="woocommerce-billing-fields">';
            do_action('woocommerce_before_checkout_billing_form', $checkout);
            echo '<div class="veloria-co-fields-grid woocommerce-billing-fields__field-wrapper">';
            CheckoutCustomFields::render_fieldset($checkout, 'billing');
            echo '</div>';
            self::render_account_fields($checkout);
            self::render_order_notes_fields($checkout);

            echo '<div class="veloria-wc-checkout__billing-payment">';
            echo '<h4 class="veloria-wc-checkout__section-title veloria-wc-checkout__section-title--payment">' . esc_html($paymentTitle) . '</h4>';
            echo '<div id="payment" class="woocommerce-checkout-payment veloria-wc-checkout__payment-methods-slot veloria-wc-checkout__payment-outer veloria-co-payment-root">';
            echo $gatewayMarkup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</div>';
            echo '</div>';

            do_action('woocommerce_after_checkout_billing_form', $checkout);
            echo '</div>';
            echo '</div>';

            if (self::needs_shipping_card($checkout)) {
                echo '<div class="veloria-wc-checkout__card veloria-wc-checkout__card--additional">';
                self::render_shipping_fields_only($checkout);
                echo '</div>';
            }

            do_action('woocommerce_checkout_after_customer_details');
            echo '</div>';
        }

        echo '</div>';

        echo '<div class="veloria-wc-checkout__col veloria-wc-checkout__col--sidebar">';
        echo '<div class="veloria-wc-checkout__card veloria-wc-checkout__card--summary">';
        do_action('woocommerce_checkout_before_order_review_heading');
        echo '<h3 class="veloria-wc-checkout__section-title veloria-wc-checkout__section-title--lg">' . esc_html($summaryTitle) . '</h3>';

        if ($showCoupon) {
            CheckoutTemplate::partial('coupon-block', ['settings' => $settings]);
        }

        echo '<div id="order_review" class="woocommerce-checkout-review-order">';
        do_action('woocommerce_checkout_before_order_review');
        echo CheckoutOrderUi::render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<div class="veloria-wc-checkout__place-order-slot">';
        echo $paymentParts['place_order']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
        echo '</div>';
        do_action('woocommerce_checkout_after_order_review');

        echo '</div>';
        echo '</div>';

        echo '</div>';
        echo '</form>';
    }

    private static function render_account_fields(\WC_Checkout $checkout): void
    {
        if (is_user_logged_in() || !$checkout->is_registration_enabled()) {
            return;
        }

        echo '<div class="woocommerce-account-fields">';
        if (!$checkout->is_registration_required()) {
            echo '<p class="form-row form-row-wide create-account">';
            echo '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">';
            echo '<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" type="checkbox" name="createaccount" value="1" ' . checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true, false) . ' /> ';
            echo '<span>' . esc_html__('Create an account?', 'veloria') . '</span>';
            echo '</label></p>';
        }

        do_action('woocommerce_before_checkout_registration_form', $checkout);

        $accountFields = $checkout->get_checkout_fields('account');
        if ($accountFields !== []) {
            echo '<div class="create-account">';
            foreach ($accountFields as $key => $field) {
                CheckoutCustomFields::render_field($key, $field, $checkout->get_value($key));
            }
            echo '<div class="clear"></div></div>';
        }

        do_action('woocommerce_after_checkout_registration_form', $checkout);
        echo '</div>';
    }

    private static function needs_shipping_card(\WC_Checkout $checkout): bool
    {
        return function_exists('WC') && WC()->cart && true === WC()->cart->needs_shipping_address();
    }

    private static function render_order_notes_fields(\WC_Checkout $checkout): void
    {
        if (!apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) {
            return;
        }

        $orderFields = $checkout->get_checkout_fields('order');
        if ($orderFields === []) {
            return;
        }

        echo '<div class="woocommerce-additional-fields veloria-wc-checkout__order-notes">';
        do_action('woocommerce_before_order_notes', $checkout);
        echo '<div class="veloria-co-fields-grid veloria-co-fields-grid--order-notes woocommerce-additional-fields__field-wrapper">';
        foreach ($orderFields as $key => $field) {
            CheckoutCustomFields::render_field($key, $field, $checkout->get_value($key));
        }
        echo '</div>';
        do_action('woocommerce_after_order_notes', $checkout);
        echo '</div>';
    }

    private static function render_shipping_fields_only(\WC_Checkout $checkout): void
    {
        echo '<div class="col-2-set veloria-wc-checkout__additional-set">';

        echo '<div class="woocommerce-shipping-fields">';
        echo '<h3 id="ship-to-different-address">';
        echo '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">';
        echo '<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" name="ship_to_different_address" value="1" ' . checked(apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0), 1, false) . ' /> ';
        echo '<span>' . esc_html__('Ship to a different address?', 'veloria') . '</span>';
        echo '</label></h3>';

        echo '<div class="shipping_address">';
        do_action('woocommerce_before_checkout_shipping_form', $checkout);
        echo '<div class="veloria-co-fields-grid woocommerce-shipping-fields__field-wrapper">';
        CheckoutCustomFields::render_fieldset($checkout, 'shipping');
        echo '</div>';
        do_action('woocommerce_after_checkout_shipping_form', $checkout);
        echo '</div>';
        echo '</div>';

        echo '</div>';
    }
}
