<?php
/**
 * WooCommerce Thank You widget template.
 *
 * @var array<string, mixed> $veloria_settings Elementor settings.
 * @var string $veloria_order_number
 * @var string $veloria_order_date
 * @var string $veloria_order_total HTML
 * @var string $veloria_payment_label
 */

defined('ABSPATH') || exit;

$veloria_show_icon = isset($veloria_settings['show_success_icon']) && $veloria_settings['show_success_icon'] === 'yes';
$veloria_show_details = isset($veloria_settings['show_details']) && $veloria_settings['show_details'] === 'yes';
$veloria_heading = isset($veloria_settings['heading']) ? (string) $veloria_settings['heading'] : '';
$veloria_description = isset($veloria_settings['description']) ? (string) $veloria_settings['description'] : '';
?>
<div class="veloria-wty">
    <div class="veloria-wty__surface">
        <?php if ($veloria_show_icon) : ?>
            <div class="veloria-wty__success-icon" aria-hidden="true">
                <?php
                $veloria_success_icon = isset($veloria_settings['success_icon']) && is_array($veloria_settings['success_icon']) ? $veloria_settings['success_icon'] : [];
                $this->render_inline_icon($veloria_success_icon, 'fas fa-check');
                ?>
            </div>
        <?php endif; ?>

        <?php if ($veloria_heading !== '') : ?>
            <h2 class="veloria-wty__heading"><?php echo esc_html($veloria_heading); ?></h2>
        <?php endif; ?>

        <?php if ($veloria_description !== '') : ?>
            <p class="veloria-wty__description"><?php echo esc_html($veloria_description); ?></p>
        <?php endif; ?>

        <?php if ($veloria_show_details) : ?>
            <div class="veloria-wty__details">
                <div class="veloria-wty__detail">
                    <span class="veloria-wty__detail-label"><?php echo esc_html((string) ($veloria_settings['label_order_number'] ?? '')); ?></span>
                    <span class="veloria-wty__detail-value"><?php echo esc_html($veloria_order_number); ?></span>
                </div>
                <div class="veloria-wty__detail">
                    <span class="veloria-wty__detail-label"><?php echo esc_html((string) ($veloria_settings['label_date'] ?? '')); ?></span>
                    <span class="veloria-wty__detail-value"><?php echo esc_html($veloria_order_date); ?></span>
                </div>
                <div class="veloria-wty__detail">
                    <span class="veloria-wty__detail-label"><?php echo esc_html((string) ($veloria_settings['label_total'] ?? '')); ?></span>
                    <span class="veloria-wty__detail-value"><?php echo wp_kses_post($veloria_order_total); ?></span>
                </div>
                <div class="veloria-wty__detail">
                    <span class="veloria-wty__detail-label"><?php echo esc_html((string) ($veloria_settings['label_payment'] ?? '')); ?></span>
                    <span class="veloria-wty__detail-value"><?php echo esc_html($veloria_payment_label); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="veloria-wty__actions">
        <?php if (!empty($veloria_settings['show_track']) && $veloria_settings['show_track'] === 'yes') : ?>
            <a class="veloria-wty-btn veloria-wty-btn--track" <?php
            echo $this->get_render_attribute_string('veloria_wty_track'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="veloria-wty-btn__icon" aria-hidden="true">
                    <?php
                    $veloria_ti = isset($veloria_settings['track_icon']) && is_array($veloria_settings['track_icon']) ? $veloria_settings['track_icon'] : [];
                    $this->render_inline_icon($veloria_ti, 'fas fa-truck');
                    ?>
                </span>
                <span class="veloria-wty-btn__label"><?php echo esc_html((string) ($veloria_settings['track_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>

        <?php if (!empty($veloria_settings['show_continue']) && $veloria_settings['show_continue'] === 'yes') : ?>
            <a class="veloria-wty-btn veloria-wty-btn--continue" <?php
            echo $this->get_render_attribute_string('veloria_wty_continue'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="veloria-wty-btn__icon" aria-hidden="true">
                    <?php
                    $veloria_ci = isset($veloria_settings['continue_icon']) && is_array($veloria_settings['continue_icon']) ? $veloria_settings['continue_icon'] : [];
                    $this->render_inline_icon($veloria_ci, 'fas fa-shopping-bag');
                    ?>
                </span>
                <span class="veloria-wty-btn__label"><?php echo esc_html((string) ($veloria_settings['continue_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>

        <?php if (!empty($veloria_settings['show_receipt']) && $veloria_settings['show_receipt'] === 'yes') : ?>
            <a class="veloria-wty-btn veloria-wty-btn--receipt" <?php
            echo $this->get_render_attribute_string('veloria_wty_receipt'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="veloria-wty-btn__icon" aria-hidden="true">
                    <?php
                    $veloria_ri = isset($veloria_settings['receipt_icon']) && is_array($veloria_settings['receipt_icon']) ? $veloria_settings['receipt_icon'] : [];
                    $this->render_inline_icon($veloria_ri, 'fas fa-download');
                    ?>
                </span>
                <span class="veloria-wty-btn__label"><?php echo esc_html((string) ($veloria_settings['receipt_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>
