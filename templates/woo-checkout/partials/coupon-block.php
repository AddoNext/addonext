<?php
/**
 * Elementrify checkout coupon block (sidebar). Not WooCommerce notice markup.
 *
 * @var array<string, mixed> $settings Widget settings.
 */

defined('ABSPATH') or exit;

if (!function_exists('wc_coupons_enabled') || !wc_coupons_enabled()) {
    return;
}
?>
<div class="elementrify-wc-checkout__coupon-wrap elementrify-co-tpl-coupon">
	<?php
	$couponTitle = isset($settings['coupon_section_title']) ? trim((string) $settings['coupon_section_title']) : '';
	if ($couponTitle !== '') {
		echo '<h4 class="elementrify-wc-checkout__coupon-title">' . esc_html($couponTitle) . '</h4>';
	}

	$couponMessage = apply_filters(
		'woocommerce_checkout_coupon_message',
		esc_html__('Have a coupon?', 'woocommerce') . ' <a href="#" role="button" aria-label="' . esc_attr__('Enter your coupon code', 'woocommerce') . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon elementrify-co-coupon-link">' . esc_html__('Click here to enter your code', 'woocommerce') . '</a>'
	);
	echo '<p class="elementrify-co-coupon-prompt">' . wp_kses_post($couponMessage) . '</p>';

	$button_class = 'button elementrify-co-tpl-coupon__apply';
	if (function_exists('wc_wp_theme_get_element_class_name') && wc_wp_theme_get_element_class_name('button')) {
		$button_class .= ' ' . wc_wp_theme_get_element_class_name('button');
	}
	?>
	<form class="checkout_coupon woocommerce-form-coupon elementrify-co-coupon-form" method="post" style="display:none" id="woocommerce-checkout-form-coupon">
		<div class="elementrify-co-coupon-row">
			<div class="elementrify-co-coupon-field">
				<label for="coupon_code" class="screen-reader-text"><?php echo esc_html__('Coupon:', 'woocommerce'); ?></label>
				<input type="text" name="coupon_code" class="input-text elementrify-co-input" placeholder="<?php echo esc_attr__('Coupon code', 'woocommerce'); ?>" id="coupon_code" value="" />
			</div>
			<div class="elementrify-co-coupon-submit">
				<button type="submit" class="<?php echo esc_attr($button_class); ?>" name="apply_coupon" value="<?php echo esc_attr__('Apply coupon', 'woocommerce'); ?>"><?php echo esc_html__('Apply coupon', 'woocommerce'); ?></button>
			</div>
		</div>
	</form>
</div>
