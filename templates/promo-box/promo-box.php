<?php
defined('ABSPATH') || exit;

use Elementor\Plugin;

$veloria_settings = $this->get_settings_for_display();
$veloria_showCounter = isset($veloria_settings['show_counter']) && $veloria_settings['show_counter'] === 'yes';
$veloria_backgroundType = isset($veloria_settings['background_type']) && $veloria_settings['background_type'] === 'solid' ? 'solid' : 'gradient';
$veloria_animationClass = isset($veloria_settings['enable_animation']) && $veloria_settings['enable_animation'] === 'yes' ? ' veloria-promo-box--anim-yes' : '';

$veloria_buttonUrl = '#';
if (!empty($veloria_settings['button_url']['url'])) {
    $veloria_buttonUrl = $veloria_settings['button_url']['url'];
}

$veloria_due = !empty($veloria_settings['due_date_time']) ? (string) $veloria_settings['due_date_time'] : '';
$veloria_expiredText = isset($veloria_settings['expired_text']) ? (string) $veloria_settings['expired_text'] : __('Offer ended', 'veloria');

$veloria_days = 0;
$veloria_hours = 0;
$veloria_minutes = 0;
if ($veloria_due !== '') {
    try {
        $veloria_tz = wp_timezone();
        $veloria_end = new \DateTimeImmutable($veloria_due, $veloria_tz);
        $veloria_now = new \DateTimeImmutable('now', $veloria_tz);
        $veloria_diffSeconds = max(0, $veloria_end->getTimestamp() - $veloria_now->getTimestamp());
        $veloria_days = (int) floor($veloria_diffSeconds / 86400);
        $veloria_hours = (int) floor(($veloria_diffSeconds % 86400) / 3600);
        $veloria_minutes = (int) floor(($veloria_diffSeconds % 3600) / 60);
    } catch (\Exception $veloria_e) {
        $veloria_days = 0;
        $veloria_hours = 0;
        $veloria_minutes = 0;
    }
}

$this->add_render_attribute('badge_text', 'class', 'veloria-promo-box__badge-pill');
$this->add_inline_editing_attributes('badge_text', 'basic');
$this->add_render_attribute('badge_secondary', 'class', 'veloria-promo-box__badge-secondary');
$this->add_inline_editing_attributes('badge_secondary', 'basic');
$this->add_render_attribute('title', 'class', 'veloria-promo-box__title');
$this->add_inline_editing_attributes('title', 'basic');
$this->add_render_attribute('content', 'class', 'veloria-promo-box__description');
$this->add_inline_editing_attributes('content', 'advanced');

$this->add_render_attribute('button_text', 'class', 'veloria-promo-box__button');
$this->add_render_attribute('button_text', 'href', esc_url($veloria_buttonUrl));
if (!empty($veloria_settings['button_url']['is_external'])) {
    $this->add_render_attribute('button_text', 'target', '_blank');
}
if (!empty($veloria_settings['button_url']['nofollow'])) {
    $this->add_render_attribute('button_text', 'rel', 'nofollow');
}
$this->add_inline_editing_attributes('button_text', 'basic');

$this->add_render_attribute('label_days', 'class', 'veloria-promo-box__counter-label');
$this->add_inline_editing_attributes('label_days', 'basic');
$this->add_render_attribute('label_hours', 'class', 'veloria-promo-box__counter-label');
$this->add_inline_editing_attributes('label_hours', 'basic');
$this->add_render_attribute('label_minutes', 'class', 'veloria-promo-box__counter-label');
$this->add_inline_editing_attributes('label_minutes', 'basic');
?>
<div class="veloria-promo-box veloria-promo-box--bg-<?php echo esc_attr($veloria_backgroundType); ?><?php echo esc_attr($veloria_animationClass); ?>">
    <div class="veloria-promo-box__content">
        <div class="veloria-promo-box__badge-row">
            <span <?php $this->print_render_attribute_string('badge_text'); ?>><?php echo esc_html((string) ($veloria_settings['badge_text'] ?? '')); ?></span>
            <span <?php $this->print_render_attribute_string('badge_secondary'); ?>><?php echo esc_html((string) ($veloria_settings['badge_secondary'] ?? '')); ?></span>
        </div>

        <h4 <?php $this->print_render_attribute_string('title'); ?>><?php echo esc_html((string) ($veloria_settings['title'] ?? '')); ?></h4>
        <p <?php $this->print_render_attribute_string('content'); ?>><?php echo wp_kses_post((string) ($veloria_settings['content'] ?? '')); ?></p>

        <div class="veloria-promo-box__footer">
            <?php if (!empty($veloria_settings['button_text']) || Plugin::$instance->editor->is_edit_mode()) : ?>
                <a <?php $this->print_render_attribute_string('button_text'); ?>><?php echo esc_html((string) ($veloria_settings['button_text'] ?? '')); ?></a>
            <?php endif; ?>

            <?php if ($veloria_showCounter) : ?>
                <div class="veloria-promo-box__counter" aria-label="Countdown timer" data-due="<?php echo esc_attr($veloria_due); ?>" data-expired-text="<?php echo esc_attr($veloria_expiredText); ?>">
                    <div class="veloria-promo-box__counter-item veloria-promo-box__counter-item--separator">
                        <span class="veloria-promo-box__counter-value" data-unit="days"><?php echo esc_html(str_pad((string) $veloria_days, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_days'); ?>><?php echo esc_html((string) ($veloria_settings['label_days'] ?? __('Days', 'veloria'))); ?></span>
                    </div>
                    <div class="veloria-promo-box__counter-item veloria-promo-box__counter-item--separator">
                        <span class="veloria-promo-box__counter-value" data-unit="hours"><?php echo esc_html(str_pad((string) $veloria_hours, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_hours'); ?>><?php echo esc_html((string) ($veloria_settings['label_hours'] ?? __('Hours', 'veloria'))); ?></span>
                    </div>
                    <div class="veloria-promo-box__counter-item">
                        <span class="veloria-promo-box__counter-value" data-unit="minutes"><?php echo esc_html(str_pad((string) $veloria_minutes, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_minutes'); ?>><?php echo esc_html((string) ($veloria_settings['label_minutes'] ?? __('Minutes', 'veloria'))); ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <span class="veloria-promo-box__circle veloria-promo-box__circle--one" aria-hidden="true"></span>
    <span class="veloria-promo-box__circle veloria-promo-box__circle--two" aria-hidden="true"></span>
</div>
