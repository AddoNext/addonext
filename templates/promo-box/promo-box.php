<?php
defined('ABSPATH') || exit;

use Elementor\Plugin;

$settings = $this->get_settings_for_display();
$showCounter = isset($settings['show_counter']) && $settings['show_counter'] === 'yes';
$backgroundType = isset($settings['background_type']) && $settings['background_type'] === 'solid' ? 'solid' : 'gradient';
$animationClass = isset($settings['enable_animation']) && $settings['enable_animation'] === 'yes' ? ' veloria-promo-box--anim-yes' : '';

$buttonUrl = '#';
if (!empty($settings['button_url']['url'])) {
    $buttonUrl = $settings['button_url']['url'];
}

$due = !empty($settings['due_date_time']) ? (string) $settings['due_date_time'] : '';
$expiredText = isset($settings['expired_text']) ? (string) $settings['expired_text'] : __('Offer ended', 'veloria');

$days = 0;
$hours = 0;
$minutes = 0;
if ($due !== '') {
    try {
        $tz = wp_timezone();
        $end = new \DateTimeImmutable($due, $tz);
        $now = new \DateTimeImmutable('now', $tz);
        $diffSeconds = max(0, $end->getTimestamp() - $now->getTimestamp());
        $days = (int) floor($diffSeconds / 86400);
        $hours = (int) floor(($diffSeconds % 86400) / 3600);
        $minutes = (int) floor(($diffSeconds % 3600) / 60);
    } catch (\Exception $e) {
        $days = 0;
        $hours = 0;
        $minutes = 0;
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
$this->add_render_attribute('button_text', 'href', esc_url($buttonUrl));
if (!empty($settings['button_url']['is_external'])) {
    $this->add_render_attribute('button_text', 'target', '_blank');
}
if (!empty($settings['button_url']['nofollow'])) {
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
<div class="veloria-promo-box veloria-promo-box--bg-<?php echo esc_attr($backgroundType); ?><?php echo esc_attr($animationClass); ?>">
    <div class="veloria-promo-box__content">
        <div class="veloria-promo-box__badge-row">
            <span <?php $this->print_render_attribute_string('badge_text'); ?>><?php echo esc_html((string) ($settings['badge_text'] ?? '')); ?></span>
            <span <?php $this->print_render_attribute_string('badge_secondary'); ?>><?php echo esc_html((string) ($settings['badge_secondary'] ?? '')); ?></span>
        </div>

        <h4 <?php $this->print_render_attribute_string('title'); ?>><?php echo esc_html((string) ($settings['title'] ?? '')); ?></h4>
        <p <?php $this->print_render_attribute_string('content'); ?>><?php echo wp_kses_post((string) ($settings['content'] ?? '')); ?></p>

        <div class="veloria-promo-box__footer">
            <?php if (!empty($settings['button_text']) || Plugin::$instance->editor->is_edit_mode()) : ?>
                <a <?php $this->print_render_attribute_string('button_text'); ?>><?php echo esc_html((string) ($settings['button_text'] ?? '')); ?></a>
            <?php endif; ?>

            <?php if ($showCounter) : ?>
                <div class="veloria-promo-box__counter" aria-label="Countdown timer" data-due="<?php echo esc_attr($due); ?>" data-expired-text="<?php echo esc_attr($expiredText); ?>">
                    <div class="veloria-promo-box__counter-item veloria-promo-box__counter-item--separator">
                        <span class="veloria-promo-box__counter-value" data-unit="days"><?php echo esc_html(str_pad((string) $days, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_days'); ?>><?php echo esc_html((string) ($settings['label_days'] ?? __('Days', 'veloria'))); ?></span>
                    </div>
                    <div class="veloria-promo-box__counter-item veloria-promo-box__counter-item--separator">
                        <span class="veloria-promo-box__counter-value" data-unit="hours"><?php echo esc_html(str_pad((string) $hours, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_hours'); ?>><?php echo esc_html((string) ($settings['label_hours'] ?? __('Hours', 'veloria'))); ?></span>
                    </div>
                    <div class="veloria-promo-box__counter-item">
                        <span class="veloria-promo-box__counter-value" data-unit="minutes"><?php echo esc_html(str_pad((string) $minutes, 2, '0', STR_PAD_LEFT)); ?></span>
                        <span <?php $this->print_render_attribute_string('label_minutes'); ?>><?php echo esc_html((string) ($settings['label_minutes'] ?? __('Minutes', 'veloria'))); ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <span class="veloria-promo-box__circle veloria-promo-box__circle--one" aria-hidden="true"></span>
    <span class="veloria-promo-box__circle veloria-promo-box__circle--two" aria-hidden="true"></span>
</div>
