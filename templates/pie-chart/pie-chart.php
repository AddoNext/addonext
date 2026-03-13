<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$title    = isset($settings['title']) ? $settings['title'] : '';
$data     = isset($settings['data']) && is_array($settings['data']) ? $settings['data'] : [];

$labels = [];
$values = [];
$colors = [];
foreach ($data as $row) {
    $labels[] = isset($row['label']) ? $row['label'] : '';
    $values[] = isset($row['value']) ? (float) $row['value'] : 0;
    $colors[] = isset($row['color']) ? $row['color'] : '#2563eb';
}

$this->add_render_attribute('wrapper', 'class', 'elementrify-pie-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $values));
$this->add_render_attribute('wrapper', 'data-colors', implode('|', $colors));
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($title !== '') : ?>
        <h3 class="elementrify-pie-chart__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    <div class="elementrify-pie-chart__wrap">
        <div class="elementrify-pie-chart__canvas" role="img" aria-label="<?php echo esc_attr($title); ?>"></div>
        <div class="elementrify-pie-chart__legend"></div>
    </div>
</div>
