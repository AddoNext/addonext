<?php
/**
 * Single payment gateway row (checkout.js compatible).
 *
 * @var \WC_Payment_Gateway $gateway
 */

defined('ABSPATH') or exit;

$id = $gateway->id;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr($id); ?> veloria-co-payment-item veloria-co-tpl-payment-item">
	<input
		id="payment_method_<?php echo esc_attr($id); ?>"
		type="radio"
		class="input-radio"
		name="payment_method"
		value="<?php echo esc_attr($id); ?>"
		<?php checked($gateway->chosen, true); ?>
		data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>"
	/>
	<label for="payment_method_<?php echo esc_attr($id); ?>" class="veloria-co-tpl-payment-label">
		<?php echo wp_kses_post($gateway->get_title()); ?>
		<?php echo $gateway->get_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</label>
	<?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr($id); ?> veloria-co-tpl-payment-box"<?php echo $gateway->chosen ? '' : ' style="display:none;"'; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
