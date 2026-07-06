<?php
defined('ABSPATH') || exit;

use Elementor\Plugin;

$addonext_settings = $this->get_settings_for_display();
$addonext_showCounter = isset($addonext_settings['show_counter']) && $addonext_settings['show_counter'] === 'yes';
$addonext_backgroundType = isset($addonext_settings['background_type']) && $addonext_settings['background_type'] === 'solid' ? 'solid' : 'gradient';
$addonext_animationClass = isset($addonext_settings['enable_animation']) && $addonext_settings['enable_animation'] === 'yes' ? ' addonext-promo-box--anim-yes' : '';

$addonext_buttonUrl = '#';
if (!empty($addonext_settings['button_url']['url'])) {
    $addonext_buttonUrl = $addonext_settings['button_url']['url'];
}

$addonext_due = !empty($addonext_settings['due_date_time']) ? (string) $addonext_settings['due_date_time'] : '';
$addonext_expiredText = isset($addonext_settings['expired_text']) ? (string) $addonext_settings['expired_text'] : __('Offer ended', 'addonext');

$addonext_days = 0;
$addonext_hours = 0;
$addonext_minutes = 0;
if ($addonext_due !== '') {
    try {
        $addonext_tz = wp_timezone();
        $addonext_end = new \DateTimeImmutable($addonext_due, $addonext_tz);
        $addonext_now = new \DateTimeImmutable('now', $addonext_tz);
        $addonext_diffSeconds = max(0, $addonext_end->getTimestamp() - $addonext_now->getTimestamp());
        $addonext_days = (int) floor($addonext_diffSeconds / 86400);
        $addonext_hours = (int) floor(($addonext_diffSeconds % 86400) / 3600);
        $addonext_minutes = (int) floor(($addonext_diffSeconds % 3600) / 60);
    } catch (\Exception $addonext_e) {
        $addonext_days = 0;
        $addonext_hours = 0;
        $addonext_minutes = 0;
    }
}

$this->add_render_attribute('badge_text', 'class', 'addonext-promo-box__badge-pill');
$this->add_inline_editing_attributes('badge_text', 'basic');
$this->add_render_attribute('badge_secondary', 'class', 'addonext-promo-box__badge-secondary');
$this->add_inline_editing_attributes('badge_secondary', 'basic');
$this->add_render_attribute('title', 'class', 'addonext-promo-box__title');
$this->add_inline_editing_attributes('title', 'basic');
$this->add_render_attribute('content', 'class', 'addonext-promo-box__description');
$this->add_inline_editing_attributes('content', 'advanced');

$this->add_render_attribute('button_text', 'class', 'addonext-promo-box__button');
$this->add_render_attribute('button_text', 'href', esc_url($addonext_buttonUrl));
if (!empty($addonext_settings['button_url']['is_external'])) {
    $this->add_render_attribute('button_text', 'target', '_blank');
}
if (!empty($addonext_settings['button_url']['nofollow'])) {
    $this->add_render_attribute('button_text', 'rel', 'nofollow');
}
$this->add_inline_editing_attributes('button_text', 'basic');

$this->add_render_attribute('label_days', 'class', 'addonext-promo-box__counter-label');
$this->add_inline_editing_attributes('label_days', 'basic');
$this->add_render_attribute('label_hours', 'class', 'addonext-promo-box__counter-label');
$this->add_inline_editing_attributes('label_hours', 'basic');
$this->add_render_attribute('label_minutes', 'class', 'addonext-promo-box__counter-label');
$this->add_inline_editing_attributes('label_minutes', 'basic');
?>
<div class="addonext-promo-box addonext-promo-box--bg-<?php echo esc_attr($addonext_backgroundType); ?><?php echo esc_attr($addonext_animationClass); ?>">
    <div class="addonext-promo-box__content">
        <div class="addonext-promo-box__badge-row">
            <span <?php $this->print_render_attribute_string('badge_text'); ?>><?php echo esc_html((string) ($addonext_settings['badge_text'] ?? '')); ?></span>
            <span <?php $this->print_render_attribute_string('badge_secondary'); ?>><?php echo esc_html((string) ($addonext_settings['badge_secondary'] ?? '')); ?></span>
        </div>

        <h4 <?php $this->print_render_attribute_string('title'); ?>><?php echo esc_html((string) ($addonext_settings['title'] ?? '')); ?></h4>
        <p <?php $this->print_render_attribute_string('content'); ?>><?php echo wp_kses_post((string) ($addonext_settings['content'] ?? '')); ?></p>

        <div class="addonext-promo-box__footer">
            <?php if (!empty($addonext_settings['button_text']) || Plugin::$instance->editor->is_edit_mode()) : ?>
                <a <?php $this->print_render_attribute_string('button_text'); ?>><?php echo esc_html((string) ($addonext_settings['button_text'] ?? '')); ?></a>
            <?php endif; ?>

            <?php if ($addonext_showCounter) : ?>
                <div class="addonext-promo-box__counter" aria-label="Countdown timer" data-due="<?php echo esc_attr($addonext_due); ?>" data-expired-text="<?php echo esc_attr($addonext_expiredText); ?>">
                    <div class="addonext-promo-box__counter-item addonext-promo-box__counter-item--separator">
                        <span class="addonext-promo-box__counter-value" data-unit="days"><?php echo esc_html(str_pad((string) $addonext_days, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_days'); ?>><?php echo esc_html((string) ($addonext_settings['label_days'] ?? __('Days', 'addonext'))); ?></span>
                    </div>
                    <div class="addonext-promo-box__counter-item addonext-promo-box__counter-item--separator">
                        <span class="addonext-promo-box__counter-value" data-unit="hours"><?php echo esc_html(str_pad((string) $addonext_hours, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_hours'); ?>><?php echo esc_html((string) ($addonext_settings['label_hours'] ?? __('Hours', 'addonext'))); ?></span>
                    </div>
                    <div class="addonext-promo-box__counter-item">
                        <span class="addonext-promo-box__counter-value" data-unit="minutes"><?php echo esc_html(str_pad((string) $addonext_minutes, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_minutes'); ?>><?php echo esc_html((string) ($addonext_settings['label_minutes'] ?? __('Minutes', 'addonext'))); ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <span class="addonext-promo-box__circle addonext-promo-box__circle--one" aria-hidden="true"></span>
    <span class="addonext-promo-box__circle addonext-promo-box__circle--two" aria-hidden="true"></span>
</div>
