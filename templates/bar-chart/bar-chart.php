<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_title    = isset($veloria_settings['title']) ? $veloria_settings['title'] : '';
$veloria_data     = isset($veloria_settings['data']) && is_array($veloria_settings['data']) ? $veloria_settings['data'] : [];
$veloria_y_suffix = isset($veloria_settings['y_axis_suffix']) ? $veloria_settings['y_axis_suffix'] : 'k';
$veloria_y_min    = isset($veloria_settings['y_min']) && $veloria_settings['y_min'] !== '' ? (float) $veloria_settings['y_min'] : '';
$veloria_y_max    = isset($veloria_settings['y_max']) && $veloria_settings['y_max'] !== '' ? (float) $veloria_settings['y_max'] : '';

$veloria_labels = [];
$veloria_values = [];
$veloria_colors = [];
foreach ($veloria_data as $veloria_row) {
    $veloria_labels[] = isset($veloria_row['label']) ? $veloria_row['label'] : '';
    $veloria_values[] = isset($veloria_row['value']) ? (float) $veloria_row['value'] : 0;
    $veloria_colors[] = isset($veloria_row['bar_color']) ? $veloria_row['bar_color'] : '#2563eb';
}

$this->add_render_attribute('wrapper', 'class', 'veloria-bar-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $veloria_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $veloria_values));
$this->add_render_attribute('wrapper', 'data-colors', implode('|', $veloria_colors));
$this->add_render_attribute('wrapper', 'data-y-suffix', esc_attr($veloria_y_suffix));
$this->add_render_attribute('wrapper', 'data-y-min', $veloria_y_min !== '' ? $veloria_y_min : '');
$this->add_render_attribute('wrapper', 'data-y-max', $veloria_y_max !== '' ? $veloria_y_max : '');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_title !== '') : ?>
        <h3 class="veloria-bar-chart__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php endif; ?>
    <div class="veloria-bar-chart__canvas" role="img" aria-label="<?php echo esc_attr($veloria_title); ?>"></div>
</div>
