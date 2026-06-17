<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_variant  = isset($veloria_settings['variant']) ? $veloria_settings['variant'] : 'blocks';
$veloria_due_date = isset($veloria_settings['due_date']) ? $veloria_settings['due_date'] : '';
$veloria_labels   = !empty($veloria_settings['labels']) && $veloria_settings['labels'] !== 'no';
$veloria_label_days    = isset($veloria_settings['label_days']) ? $veloria_settings['label_days'] : __('Days', 'veloria');
$veloria_label_hours   = isset($veloria_settings['label_hours']) ? $veloria_settings['label_hours'] : __('Hours', 'veloria');
$veloria_label_minutes = isset($veloria_settings['label_minutes']) ? $veloria_settings['label_minutes'] : __('Minutes', 'veloria');
$veloria_label_seconds = isset($veloria_settings['label_seconds']) ? $veloria_settings['label_seconds'] : __('Seconds', 'veloria');
$veloria_shutter_flip  = ($veloria_variant === 'blocks' || $veloria_variant === 'cards') && !empty($veloria_settings['enable_shutter_flip']) && $veloria_settings['enable_shutter_flip'] === 'yes';

$this->add_render_attribute('wrapper', 'class', 'veloria-countdown veloria-countdown--' . esc_attr($veloria_variant));
$this->add_render_attribute('wrapper', 'data-due', esc_attr($veloria_due_date));
if ($veloria_shutter_flip) {
    $this->add_render_attribute('wrapper', 'data-shutter-flip', '1');
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach (['days' => $veloria_label_days, 'hours' => $veloria_label_hours, 'minutes' => $veloria_label_minutes, 'seconds' => $veloria_label_seconds] as $veloria_unit => $veloria_label_text) : ?>
    <span class="veloria-countdown__segment" data-unit="<?php echo esc_attr($veloria_unit); ?>">
        <?php if ($veloria_shutter_flip) : ?>
        <span class="veloria-countdown__value-box">
            <span class="veloria-countdown__value">0</span>
            <span class="veloria-countdown__shutter" aria-hidden="true">
                <span class="veloria-countdown__shutter-value">0</span>
            </span>
        </span>
        <?php else : ?>
        <span class="veloria-countdown__value">0</span>
        <?php endif; ?>
        <?php if ($veloria_labels) : ?><span class="veloria-countdown__label"><?php echo esc_html($veloria_label_text); ?></span><?php endif; ?>
    </span>
    <?php endforeach; ?>
</div>

<?php
    }
}
