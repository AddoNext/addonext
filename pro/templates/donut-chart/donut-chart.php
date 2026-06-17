<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_title = isset($veloria_settings['title']) ? (string) $veloria_settings['title'] : '';
$veloria_data = isset($veloria_settings['data']) && is_array($veloria_settings['data']) ? $veloria_settings['data'] : [];
$veloria_centerPrefix = isset($veloria_settings['center_prefix']) ? (string) $veloria_settings['center_prefix'] : '';
$veloria_centerSuffix = isset($veloria_settings['center_suffix']) ? (string) $veloria_settings['center_suffix'] : '';
$veloria_centerLabel = isset($veloria_settings['center_label']) ? (string) $veloria_settings['center_label'] : '';
$veloria_showLabels = isset($veloria_settings['show_slice_labels']) && $veloria_settings['show_slice_labels'] === 'yes' ? 'yes' : 'no';
$veloria_showLegend = isset($veloria_settings['show_legend']) && $veloria_settings['show_legend'] === 'yes' ? 'yes' : 'no';
$veloria_innerCutout = isset($veloria_settings['inner_cutout']['size']) ? (float) $veloria_settings['inner_cutout']['size'] : 50;

$veloria_labels = [];
$veloria_values = [];
$veloria_colors = [];
foreach ($veloria_data as $veloria_row) {
    $veloria_labels[] = isset($veloria_row['label']) ? (string) $veloria_row['label'] : '';
    $veloria_values[] = isset($veloria_row['value']) ? (float) $veloria_row['value'] : 0;
    $veloria_colors[] = isset($veloria_row['color']) ? (string) $veloria_row['color'] : '#4285F4';
}

$this->add_render_attribute('wrapper', 'class', 'veloria-donut-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $veloria_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $veloria_values));
$this->add_render_attribute('wrapper', 'data-colors', implode('|', $veloria_colors));
$this->add_render_attribute('wrapper', 'data-center-prefix', esc_attr($veloria_centerPrefix));
$this->add_render_attribute('wrapper', 'data-center-suffix', esc_attr($veloria_centerSuffix));
$this->add_render_attribute('wrapper', 'data-center-label', esc_attr($veloria_centerLabel));
$this->add_render_attribute('wrapper', 'data-show-labels', $veloria_showLabels);
$this->add_render_attribute('wrapper', 'data-show-legend', $veloria_showLegend);
$this->add_render_attribute('wrapper', 'data-inner-cutout', (string) $veloria_innerCutout);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_title !== '') : ?>
        <h3 class="veloria-donut-chart__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php endif; ?>
    <div class="veloria-donut-chart__wrap">
        <div class="veloria-donut-chart__canvas" role="img" aria-label="<?php echo esc_attr($veloria_title); ?>"></div>
        <div class="veloria-donut-chart__legend"></div>
    </div>
</div>

<?php
    }
}
