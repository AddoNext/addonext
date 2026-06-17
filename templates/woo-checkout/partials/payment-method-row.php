<?php
/**
 * Single payment gateway row (checkout.js compatible).
 *
 * @var \WC_Payment_Gateway $veloria_gateway
 */

defined('ABSPATH') or exit;

$veloria_id = $veloria_gateway->id;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr($veloria_id); ?> veloria-co-payment-item veloria-co-tpl-payment-item">
	<input
		id="payment_method_<?php echo esc_attr($veloria_id); ?>"
		type="radio"
		class="input-radio"
		name="payment_method"
		value="<?php echo esc_attr($veloria_id); ?>"
		<?php checked($veloria_gateway->chosen, true); ?>
		data-order_button_text="<?php echo esc_attr($veloria_gateway->order_button_text); ?>"
	/>
	<label for="payment_method_<?php echo esc_attr($veloria_id); ?>" class="veloria-co-tpl-payment-label">
		<?php echo wp_kses_post($veloria_gateway->get_title()); ?>
		<?php echo $veloria_gateway->get_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</label>
	<?php if ($veloria_gateway->has_fields() || $veloria_gateway->get_description()) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr($veloria_id); ?> veloria-co-tpl-payment-box"<?php echo $veloria_gateway->chosen ? '' : ' style="display:none;"'; ?>>
			<?php $veloria_gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
