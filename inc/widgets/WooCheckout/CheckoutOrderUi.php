<?php
namespace Elementrify\Widgets\WooCheckout;

defined('ABSPATH') or exit;

/**
 * Custom order review markup (lines + totals) for Elementrify checkout — no review-order template.
 */
class CheckoutOrderUi
{
    public static function render(): string
    {
        if (!function_exists('WC') || !WC()->cart) {
            return '';
        }

        ob_start();

        echo '<div class="elementrify-co-order-review">';
        do_action('woocommerce_review_order_before_cart_contents');
        echo '<div class="elementrify-co-lines">';

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                continue;
            }

            $thumb = $_product->get_image([72, 72], ['class' => 'elementrify-co-line__img']);
            $name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
            ?>
<div class="<?php echo esc_attr(trim('elementrify-co-line ' . apply_filters('woocommerce_cart_item_class', '', $cart_item, $cart_item_key))); ?>">
	<div class="elementrify-co-line__thumb"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<div class="elementrify-co-line__mid">
		<span class="elementrify-co-line__title"><?php echo wp_kses_post($name); ?></span>
		<span class="elementrify-co-line__qty"><?php
			echo esc_html(
				sprintf(
					/* translators: %s: cart line quantity. */
					__('Qty: %s', 'elementrify'),
					$cart_item['quantity']
				)
			);
			?></span>
			<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="elementrify-co-line__price"><?php echo $subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
</div>
			<?php
        }

        echo '</div>';
        do_action('woocommerce_review_order_after_cart_contents');

        echo '<div class="elementrify-co-totals">';
        echo '<table class="elementrify-co-totals__table"><tbody>';

        echo '<tr class="cart-subtotal"><th>' . esc_html__('Subtotal', 'elementrify') . '</th><td>';
        wc_cart_totals_subtotal_html();
        echo '</td></tr>';

        foreach (WC()->cart->get_coupons() as $code => $coupon) {
            echo '<tr class="cart-discount coupon-' . esc_attr(sanitize_title($code)) . '"><th>';
            wc_cart_totals_coupon_label($coupon);
            echo '</th><td>';
            wc_cart_totals_coupon_html($coupon);
            echo '</td></tr>';
        }

        if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) {
            do_action('woocommerce_review_order_before_shipping');
            wc_cart_totals_shipping_html();
            do_action('woocommerce_review_order_after_shipping');
        }

        foreach (WC()->cart->get_fees() as $fee) {
            echo '<tr class="fee"><th>' . esc_html($fee->name) . '</th><td>';
            wc_cart_totals_fee_html($fee);
            echo '</td></tr>';
        }

        if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) {
            if ('itemized' === get_option('woocommerce_tax_total_display')) {
                foreach (WC()->cart->get_tax_totals() as $code => $tax) {
                    echo '<tr class="tax-rate tax-rate-' . esc_attr(sanitize_title($code)) . '"><th>' . esc_html($tax->label) . '</th><td>' . wp_kses_post($tax->formatted_amount) . '</td></tr>';
                }
            } else {
                echo '<tr class="tax-total"><th>' . esc_html(WC()->countries->tax_or_vat()) . '</th><td>';
                wc_cart_totals_taxes_total_html();
                echo '</td></tr>';
            }
        }

        do_action('woocommerce_review_order_before_order_total');

        echo '<tr class="order-total"><th>' . esc_html__('Total', 'elementrify') . '</th><td>';
        wc_cart_totals_order_total_html();
        echo '</td></tr>';

        do_action('woocommerce_review_order_after_order_total');

        echo '</tbody></table>';
        echo '</div>';
        echo '</div>';

        return ob_get_clean();
    }
}
