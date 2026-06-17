<?php
/**
 * Veloria checkout coupon block (sidebar). Not WooCommerce notice markup.
 *
 * @var array<string, mixed> $veloria_settings Widget settings.
 */

defined('ABSPATH') or exit;

if (!function_exists('wc_coupons_enabled') || !wc_coupons_enabled()) {
    return;
}
?>
<div class="veloria-wc-checkout__coupon-wrap veloria-co-tpl-coupon">
	<?php
	$veloria_couponTitle = isset($veloria_settings['coupon_section_title']) ? trim((string) $veloria_settings['coupon_section_title']) : '';
	if ($veloria_couponTitle !== '') {
		echo '<h4 class="veloria-wc-checkout__coupon-title">' . esc_html($veloria_couponTitle) . '</h4>';
	}

	$veloria_couponMessage = apply_filters(
		'woocommerce_checkout_coupon_message',
		esc_html__('Have a coupon?', 'veloria') . ' <a href="#" role="button" aria-label="' . esc_attr__('Enter your coupon code', 'veloria') . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon veloria-co-coupon-link">' . esc_html__('Click here to enter your code', 'veloria') . '</a>'
	);
	echo '<p class="veloria-co-coupon-prompt">' . wp_kses_post($veloria_couponMessage) . '</p>';

	$veloria_button_class = 'button veloria-co-tpl-coupon__apply';
	if (function_exists('wc_wp_theme_get_element_class_name') && wc_wp_theme_get_element_class_name('button')) {
		$veloria_button_class .= ' ' . wc_wp_theme_get_element_class_name('button');
	}
	?>
	<form class="checkout_coupon woocommerce-form-coupon veloria-co-coupon-form" method="post" style="display:none" id="woocommerce-checkout-form-coupon">
		<div class="veloria-co-coupon-row">
			<div class="veloria-co-coupon-field">
				<label for="coupon_code" class="screen-reader-text"><?php echo esc_html__('Coupon:', 'veloria'); ?></label>
				<input type="text" name="coupon_code" class="input-text veloria-co-input" placeholder="<?php echo esc_attr__('Coupon code', 'veloria'); ?>" id="coupon_code" value="" />
			</div>
			<div class="veloria-co-coupon-submit">
				<button type="submit" class="<?php echo esc_attr($veloria_button_class); ?>" name="apply_coupon" value="<?php echo esc_attr__('Apply coupon', 'veloria'); ?>"><?php echo esc_html__('Apply coupon', 'veloria'); ?></button>
			</div>
		</div>
	</form>
</div>
