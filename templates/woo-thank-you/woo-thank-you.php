<?php
/**
 * WooCommerce Thank You widget template.
 *
 * @var array<string, mixed> $settings Elementor settings.
 * @var string $order_number
 * @var string $order_date
 * @var string $order_total HTML
 * @var string $payment_label
 */

defined('ABSPATH') || exit;

$show_icon = isset($settings['show_success_icon']) && $settings['show_success_icon'] === 'yes';
$show_details = isset($settings['show_details']) && $settings['show_details'] === 'yes';
$heading = isset($settings['heading']) ? (string) $settings['heading'] : '';
$description = isset($settings['description']) ? (string) $settings['description'] : '';
?>
<div class="elementrify-wty">
    <div class="elementrify-wty__surface">
        <?php if ($show_icon) : ?>
            <div class="elementrify-wty__success-icon" aria-hidden="true">
                <?php
                $success_icon = isset($settings['success_icon']) && is_array($settings['success_icon']) ? $settings['success_icon'] : [];
                $this->render_inline_icon($success_icon, 'fas fa-check');
                ?>
            </div>
        <?php endif; ?>

        <?php if ($heading !== '') : ?>
            <h2 class="elementrify-wty__heading"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>

        <?php if ($description !== '') : ?>
            <p class="elementrify-wty__description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>

        <?php if ($show_details) : ?>
            <div class="elementrify-wty__details">
                <div class="elementrify-wty__detail">
                    <span class="elementrify-wty__detail-label"><?php echo esc_html((string) ($settings['label_order_number'] ?? '')); ?></span>
                    <span class="elementrify-wty__detail-value"><?php echo esc_html($order_number); ?></span>
                </div>
                <div class="elementrify-wty__detail">
                    <span class="elementrify-wty__detail-label"><?php echo esc_html((string) ($settings['label_date'] ?? '')); ?></span>
                    <span class="elementrify-wty__detail-value"><?php echo esc_html($order_date); ?></span>
                </div>
                <div class="elementrify-wty__detail">
                    <span class="elementrify-wty__detail-label"><?php echo esc_html((string) ($settings['label_total'] ?? '')); ?></span>
                    <span class="elementrify-wty__detail-value"><?php echo wp_kses_post($order_total); ?></span>
                </div>
                <div class="elementrify-wty__detail">
                    <span class="elementrify-wty__detail-label"><?php echo esc_html((string) ($settings['label_payment'] ?? '')); ?></span>
                    <span class="elementrify-wty__detail-value"><?php echo esc_html($payment_label); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="elementrify-wty__actions">
        <?php if (!empty($settings['show_track']) && $settings['show_track'] === 'yes') : ?>
            <a class="elementrify-wty-btn elementrify-wty-btn--track" <?php
            echo $this->get_render_attribute_string('elementrify_wty_track'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="elementrify-wty-btn__icon" aria-hidden="true">
                    <?php
                    $ti = isset($settings['track_icon']) && is_array($settings['track_icon']) ? $settings['track_icon'] : [];
                    $this->render_inline_icon($ti, 'fas fa-truck');
                    ?>
                </span>
                <span class="elementrify-wty-btn__label"><?php echo esc_html((string) ($settings['track_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>

        <?php if (!empty($settings['show_continue']) && $settings['show_continue'] === 'yes') : ?>
            <a class="elementrify-wty-btn elementrify-wty-btn--continue" <?php
            echo $this->get_render_attribute_string('elementrify_wty_continue'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="elementrify-wty-btn__icon" aria-hidden="true">
                    <?php
                    $ci = isset($settings['continue_icon']) && is_array($settings['continue_icon']) ? $settings['continue_icon'] : [];
                    $this->render_inline_icon($ci, 'fas fa-shopping-bag');
                    ?>
                </span>
                <span class="elementrify-wty-btn__label"><?php echo esc_html((string) ($settings['continue_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>

        <?php if (!empty($settings['show_receipt']) && $settings['show_receipt'] === 'yes') : ?>
            <a class="elementrify-wty-btn elementrify-wty-btn--receipt" <?php
            echo $this->get_render_attribute_string('elementrify_wty_receipt'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="elementrify-wty-btn__icon" aria-hidden="true">
                    <?php
                    $ri = isset($settings['receipt_icon']) && is_array($settings['receipt_icon']) ? $settings['receipt_icon'] : [];
                    $this->render_inline_icon($ri, 'fas fa-download');
                    ?>
                </span>
                <span class="elementrify-wty-btn__label"><?php echo esc_html((string) ($settings['receipt_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>
