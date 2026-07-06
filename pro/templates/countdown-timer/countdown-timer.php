<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_variant  = isset($addonext_settings['variant']) ? $addonext_settings['variant'] : 'blocks';
$addonext_due_date = isset($addonext_settings['due_date']) ? $addonext_settings['due_date'] : '';
$addonext_labels   = !empty($addonext_settings['labels']) && $addonext_settings['labels'] !== 'no';
$addonext_label_days    = isset($addonext_settings['label_days']) ? $addonext_settings['label_days'] : __('Days', 'addonext');
$addonext_label_hours   = isset($addonext_settings['label_hours']) ? $addonext_settings['label_hours'] : __('Hours', 'addonext');
$addonext_label_minutes = isset($addonext_settings['label_minutes']) ? $addonext_settings['label_minutes'] : __('Minutes', 'addonext');
$addonext_label_seconds = isset($addonext_settings['label_seconds']) ? $addonext_settings['label_seconds'] : __('Seconds', 'addonext');
$addonext_shutter_flip  = ($addonext_variant === 'blocks' || $addonext_variant === 'cards') && !empty($addonext_settings['enable_shutter_flip']) && $addonext_settings['enable_shutter_flip'] === 'yes';

$this->add_render_attribute('wrapper', 'class', 'addonext-countdown addonext-countdown--' . esc_attr($addonext_variant));
$this->add_render_attribute('wrapper', 'data-due', esc_attr($addonext_due_date));
if ($addonext_shutter_flip) {
    $this->add_render_attribute('wrapper', 'data-shutter-flip', '1');
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach (['days' => $addonext_label_days, 'hours' => $addonext_label_hours, 'minutes' => $addonext_label_minutes, 'seconds' => $addonext_label_seconds] as $addonext_unit => $addonext_label_text) : ?>
    <span class="addonext-countdown__segment" data-unit="<?php echo esc_attr($addonext_unit); ?>">
        <?php if ($addonext_shutter_flip) : ?>
        <span class="addonext-countdown__value-box">
            <span class="addonext-countdown__value">0</span>
            <span class="addonext-countdown__shutter" aria-hidden="true">
                <span class="addonext-countdown__shutter-value">0</span>
            </span>
        </span>
        <?php else : ?>
        <span class="addonext-countdown__value">0</span>
        <?php endif; ?>
        <?php if ($addonext_labels) : ?><span class="addonext-countdown__label"><?php echo esc_html($addonext_label_text); ?></span><?php endif; ?>
    </span>
    <?php endforeach; ?>
</div>

<?php
    }
}
