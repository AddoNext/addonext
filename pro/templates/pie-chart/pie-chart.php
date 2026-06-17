<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_title    = isset($veloria_settings['title']) ? $veloria_settings['title'] : '';
$veloria_data     = isset($veloria_settings['data']) && is_array($veloria_settings['data']) ? $veloria_settings['data'] : [];

$veloria_labels = [];
$veloria_values = [];
$veloria_colors = [];
foreach ($veloria_data as $veloria_row) {
    $veloria_labels[] = isset($veloria_row['label']) ? $veloria_row['label'] : '';
    $veloria_values[] = isset($veloria_row['value']) ? (float) $veloria_row['value'] : 0;
    $veloria_colors[] = isset($veloria_row['color']) ? $veloria_row['color'] : '#2563eb';
}

$this->add_render_attribute('wrapper', 'class', 'veloria-pie-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $veloria_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $veloria_values));
$this->add_render_attribute('wrapper', 'data-colors', implode('|', $veloria_colors));
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_title !== '') : ?>
        <h3 class="veloria-pie-chart__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php endif; ?>
    <div class="veloria-pie-chart__wrap">
        <div class="veloria-pie-chart__canvas" role="img" aria-label="<?php echo esc_attr($veloria_title); ?>"></div>
        <div class="veloria-pie-chart__legend"></div>
    </div>
</div>

<?php
    }
}
