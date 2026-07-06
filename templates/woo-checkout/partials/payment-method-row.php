<?php
/**
 * Single payment gateway row (checkout.js compatible).
 *
 * @var \WC_Payment_Gateway $addonext_gateway
 */

defined('ABSPATH') or exit;

$addonext_id = $addonext_gateway->id;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr($addonext_id); ?> addonext-co-payment-item addonext-co-tpl-payment-item">
	<input
		id="payment_method_<?php echo esc_attr($addonext_id); ?>"
		type="radio"
		class="input-radio"
		name="payment_method"
		value="<?php echo esc_attr($addonext_id); ?>"
		<?php checked($addonext_gateway->chosen, true); ?>
		data-order_button_text="<?php echo esc_attr($addonext_gateway->order_button_text); ?>"
	/>
	<label for="payment_method_<?php echo esc_attr($addonext_id); ?>" class="addonext-co-tpl-payment-label">
		<?php echo wp_kses_post($addonext_gateway->get_title()); ?>
		<?php echo $addonext_gateway->get_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</label>
	<?php if ($addonext_gateway->has_fields() || $addonext_gateway->get_description()) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr($addonext_id); ?> addonext-co-tpl-payment-box"<?php echo $addonext_gateway->chosen ? '' : ' style="display:none;"'; ?>>
			<?php $addonext_gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
