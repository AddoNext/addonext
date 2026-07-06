<?php
/**
 * AddoNext checkout coupon block (sidebar). Not WooCommerce notice markup.
 *
 * @var array<string, mixed> $addonext_settings Widget settings.
 */

defined('ABSPATH') or exit;

if (!function_exists('wc_coupons_enabled') || !wc_coupons_enabled()) {
    return;
}
?>
<div class="addonext-wc-checkout__coupon-wrap addonext-co-tpl-coupon">
	<?php
	$addonext_couponTitle = isset($addonext_settings['coupon_section_title']) ? trim((string) $addonext_settings['coupon_section_title']) : '';
	if ($addonext_couponTitle !== '') {
		echo '<h4 class="addonext-wc-checkout__coupon-title">' . esc_html($addonext_couponTitle) . '</h4>';
	}

	$addonext_couponMessage = apply_filters(
		'woocommerce_checkout_coupon_message',
		esc_html__('Have a coupon?', 'addonext') . ' <a href="#" role="button" aria-label="' . esc_attr__('Enter your coupon code', 'addonext') . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon addonext-co-coupon-link">' . esc_html__('Click here to enter your code', 'addonext') . '</a>'
	);
	echo '<p class="addonext-co-coupon-prompt">' . wp_kses_post($addonext_couponMessage) . '</p>';

	$addonext_button_class = 'button addonext-co-tpl-coupon__apply';
	if (function_exists('wc_wp_theme_get_element_class_name') && wc_wp_theme_get_element_class_name('button')) {
		$addonext_button_class .= ' ' . wc_wp_theme_get_element_class_name('button');
	}
	?>
	<form class="checkout_coupon woocommerce-form-coupon addonext-co-coupon-form" method="post" style="display:none" id="woocommerce-checkout-form-coupon">
		<div class="addonext-co-coupon-row">
			<div class="addonext-co-coupon-field">
				<label for="coupon_code" class="screen-reader-text"><?php echo esc_html__('Coupon:', 'addonext'); ?></label>
				<input type="text" name="coupon_code" class="input-text addonext-co-input" placeholder="<?php echo esc_attr__('Coupon code', 'addonext'); ?>" id="coupon_code" value="" />
			</div>
			<div class="addonext-co-coupon-submit">
				<button type="submit" class="<?php echo esc_attr($addonext_button_class); ?>" name="apply_coupon" value="<?php echo esc_attr__('Apply coupon', 'addonext'); ?>"><?php echo esc_html__('Apply coupon', 'addonext'); ?></button>
			</div>
		</div>
	</form>
</div>
