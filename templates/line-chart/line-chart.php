<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_title    = isset($addonext_settings['title']) ? $addonext_settings['title'] : '';
$addonext_data     = isset($addonext_settings['data']) && is_array($addonext_settings['data']) ? $addonext_settings['data'] : [];
$addonext_y_suffix = isset($addonext_settings['y_axis_suffix']) ? $addonext_settings['y_axis_suffix'] : 'k';
$addonext_y_min    = isset($addonext_settings['y_min']) && $addonext_settings['y_min'] !== '' ? (float) $addonext_settings['y_min'] : '';
$addonext_y_max    = isset($addonext_settings['y_max']) && $addonext_settings['y_max'] !== '' ? (float) $addonext_settings['y_max'] : '';

$addonext_labels = [];
$addonext_values = [];
foreach ($addonext_data as $addonext_row) {
    $addonext_labels[] = isset($addonext_row['label']) ? $addonext_row['label'] : '';
    $addonext_values[] = isset($addonext_row['value']) ? (float) $addonext_row['value'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'addonext-line-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $addonext_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $addonext_values));
$this->add_render_attribute('wrapper', 'data-y-suffix', esc_attr($addonext_y_suffix));
$this->add_render_attribute('wrapper', 'data-y-min', $addonext_y_min !== '' ? $addonext_y_min : '');
$this->add_render_attribute('wrapper', 'data-y-max', $addonext_y_max !== '' ? $addonext_y_max : '');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_title !== '') : ?>
        <h3 class="addonext-line-chart__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php endif; ?>
    <div class="addonext-line-chart__canvas" role="img" aria-label="<?php echo esc_attr($addonext_title); ?>"></div>
</div>
