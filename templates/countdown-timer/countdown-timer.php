<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$variant  = isset($settings['variant']) ? $settings['variant'] : 'blocks';
$due_date = isset($settings['due_date']) ? $settings['due_date'] : '';
$labels   = !empty($settings['labels']) && $settings['labels'] !== 'no';
$label_days    = isset($settings['label_days']) ? $settings['label_days'] : __('Days', 'elementrify');
$label_hours   = isset($settings['label_hours']) ? $settings['label_hours'] : __('Hours', 'elementrify');
$label_minutes = isset($settings['label_minutes']) ? $settings['label_minutes'] : __('Minutes', 'elementrify');
$label_seconds = isset($settings['label_seconds']) ? $settings['label_seconds'] : __('Seconds', 'elementrify');
$shutter_flip  = ($variant === 'blocks' || $variant === 'cards') && !empty($settings['enable_shutter_flip']) && $settings['enable_shutter_flip'] === 'yes';

$this->add_render_attribute('wrapper', 'class', 'elementrify-countdown elementrify-countdown--' . esc_attr($variant));
$this->add_render_attribute('wrapper', 'data-due', esc_attr($due_date));
if ($shutter_flip) {
    $this->add_render_attribute('wrapper', 'data-shutter-flip', '1');
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach (['days' => $label_days, 'hours' => $label_hours, 'minutes' => $label_minutes, 'seconds' => $label_seconds] as $unit => $label_text) : ?>
    <span class="elementrify-countdown__segment" data-unit="<?php echo esc_attr($unit); ?>">
        <?php if ($shutter_flip) : ?>
        <span class="elementrify-countdown__value-box">
            <span class="elementrify-countdown__value">0</span>
            <span class="elementrify-countdown__shutter" aria-hidden="true">
                <span class="elementrify-countdown__shutter-value">0</span>
            </span>
        </span>
        <?php else : ?>
        <span class="elementrify-countdown__value">0</span>
        <?php endif; ?>
        <?php if ($labels) : ?><span class="elementrify-countdown__label"><?php echo esc_html($label_text); ?></span><?php endif; ?>
    </span>
    <?php endforeach; ?>
</div>
