<?php
/**
 * WooCommerce Thank You widget template.
 *
 * @var array<string, mixed> $addonext_settings Elementor settings.
 * @var string $addonext_order_number
 * @var string $addonext_order_date
 * @var string $addonext_order_total HTML
 * @var string $addonext_payment_label
 */

defined('ABSPATH') || exit;

$addonext_show_icon = isset($addonext_settings['show_success_icon']) && $addonext_settings['show_success_icon'] === 'yes';
$addonext_show_details = isset($addonext_settings['show_details']) && $addonext_settings['show_details'] === 'yes';
$addonext_heading = isset($addonext_settings['heading']) ? (string) $addonext_settings['heading'] : '';
$addonext_description = isset($addonext_settings['description']) ? (string) $addonext_settings['description'] : '';
?>
<div class="addonext-wty">
    <div class="addonext-wty__surface">
        <?php if ($addonext_show_icon) : ?>
            <div class="addonext-wty__success-icon" aria-hidden="true">
                <?php
                $addonext_success_icon = isset($addonext_settings['success_icon']) && is_array($addonext_settings['success_icon']) ? $addonext_settings['success_icon'] : [];
                $this->render_inline_icon($addonext_success_icon, 'fas fa-check');
                ?>
            </div>
        <?php endif; ?>

        <?php if ($addonext_heading !== '') : ?>
            <h2 class="addonext-wty__heading"><?php echo esc_html($addonext_heading); ?></h2>
        <?php endif; ?>

        <?php if ($addonext_description !== '') : ?>
            <p class="addonext-wty__description"><?php echo esc_html($addonext_description); ?></p>
        <?php endif; ?>

        <?php if ($addonext_show_details) : ?>
            <div class="addonext-wty__details">
                <div class="addonext-wty__detail">
                    <span class="addonext-wty__detail-label"><?php echo esc_html((string) ($addonext_settings['label_order_number'] ?? '')); ?></span>
                    <span class="addonext-wty__detail-value"><?php echo esc_html($addonext_order_number); ?></span>
                </div>
                <div class="addonext-wty__detail">
                    <span class="addonext-wty__detail-label"><?php echo esc_html((string) ($addonext_settings['label_date'] ?? '')); ?></span>
                    <span class="addonext-wty__detail-value"><?php echo esc_html($addonext_order_date); ?></span>
                </div>
                <div class="addonext-wty__detail">
                    <span class="addonext-wty__detail-label"><?php echo esc_html((string) ($addonext_settings['label_total'] ?? '')); ?></span>
                    <span class="addonext-wty__detail-value"><?php echo wp_kses_post($addonext_order_total); ?></span>
                </div>
                <div class="addonext-wty__detail">
                    <span class="addonext-wty__detail-label"><?php echo esc_html((string) ($addonext_settings['label_payment'] ?? '')); ?></span>
                    <span class="addonext-wty__detail-value"><?php echo esc_html($addonext_payment_label); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="addonext-wty__actions">
        <?php if (!empty($addonext_settings['show_track']) && $addonext_settings['show_track'] === 'yes') : ?>
            <a class="addonext-wty-btn addonext-wty-btn--track" <?php
            echo $this->get_render_attribute_string('addonext_wty_track'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="addonext-wty-btn__icon" aria-hidden="true">
                    <?php
                    $addonext_ti = isset($addonext_settings['track_icon']) && is_array($addonext_settings['track_icon']) ? $addonext_settings['track_icon'] : [];
                    $this->render_inline_icon($addonext_ti, 'fas fa-truck');
                    ?>
                </span>
                <span class="addonext-wty-btn__label"><?php echo esc_html((string) ($addonext_settings['track_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>

        <?php if (!empty($addonext_settings['show_continue']) && $addonext_settings['show_continue'] === 'yes') : ?>
            <a class="addonext-wty-btn addonext-wty-btn--continue" <?php
            echo $this->get_render_attribute_string('addonext_wty_continue'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="addonext-wty-btn__icon" aria-hidden="true">
                    <?php
                    $addonext_ci = isset($addonext_settings['continue_icon']) && is_array($addonext_settings['continue_icon']) ? $addonext_settings['continue_icon'] : [];
                    $this->render_inline_icon($addonext_ci, 'fas fa-shopping-bag');
                    ?>
                </span>
                <span class="addonext-wty-btn__label"><?php echo esc_html((string) ($addonext_settings['continue_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>

        <?php if (!empty($addonext_settings['show_receipt']) && $addonext_settings['show_receipt'] === 'yes') : ?>
            <a class="addonext-wty-btn addonext-wty-btn--receipt" <?php
            echo $this->get_render_attribute_string('addonext_wty_receipt'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attributes.
            ?>>
                <span class="addonext-wty-btn__icon" aria-hidden="true">
                    <?php
                    $addonext_ri = isset($addonext_settings['receipt_icon']) && is_array($addonext_settings['receipt_icon']) ? $addonext_settings['receipt_icon'] : [];
                    $this->render_inline_icon($addonext_ri, 'fas fa-download');
                    ?>
                </span>
                <span class="addonext-wty-btn__label"><?php echo esc_html((string) ($addonext_settings['receipt_label'] ?? '')); ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>
