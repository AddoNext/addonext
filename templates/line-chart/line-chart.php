<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$title    = isset($settings['title']) ? $settings['title'] : '';
$data     = isset($settings['data']) && is_array($settings['data']) ? $settings['data'] : [];
$y_suffix = isset($settings['y_axis_suffix']) ? $settings['y_axis_suffix'] : 'k';
$y_min    = isset($settings['y_min']) && $settings['y_min'] !== '' ? (float) $settings['y_min'] : '';
$y_max    = isset($settings['y_max']) && $settings['y_max'] !== '' ? (float) $settings['y_max'] : '';

$labels = [];
$values = [];
foreach ($data as $row) {
    $labels[] = isset($row['label']) ? $row['label'] : '';
    $values[] = isset($row['value']) ? (float) $row['value'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'veloria-line-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $values));
$this->add_render_attribute('wrapper', 'data-y-suffix', esc_attr($y_suffix));
$this->add_render_attribute('wrapper', 'data-y-min', $y_min !== '' ? $y_min : '');
$this->add_render_attribute('wrapper', 'data-y-max', $y_max !== '' ? $y_max : '');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($title !== '') : ?>
        <h3 class="veloria-line-chart__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    <div class="veloria-line-chart__canvas" role="img" aria-label="<?php echo esc_attr($title); ?>"></div>
</div>
