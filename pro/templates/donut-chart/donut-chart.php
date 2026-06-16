<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$title = isset($settings['title']) ? (string) $settings['title'] : '';
$data = isset($settings['data']) && is_array($settings['data']) ? $settings['data'] : [];
$centerPrefix = isset($settings['center_prefix']) ? (string) $settings['center_prefix'] : '';
$centerSuffix = isset($settings['center_suffix']) ? (string) $settings['center_suffix'] : '';
$centerLabel = isset($settings['center_label']) ? (string) $settings['center_label'] : '';
$showLabels = isset($settings['show_slice_labels']) && $settings['show_slice_labels'] === 'yes' ? 'yes' : 'no';
$showLegend = isset($settings['show_legend']) && $settings['show_legend'] === 'yes' ? 'yes' : 'no';
$innerCutout = isset($settings['inner_cutout']['size']) ? (float) $settings['inner_cutout']['size'] : 50;

$labels = [];
$values = [];
$colors = [];
foreach ($data as $row) {
    $labels[] = isset($row['label']) ? (string) $row['label'] : '';
    $values[] = isset($row['value']) ? (float) $row['value'] : 0;
    $colors[] = isset($row['color']) ? (string) $row['color'] : '#4285F4';
}

$this->add_render_attribute('wrapper', 'class', 'veloria-donut-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $values));
$this->add_render_attribute('wrapper', 'data-colors', implode('|', $colors));
$this->add_render_attribute('wrapper', 'data-center-prefix', esc_attr($centerPrefix));
$this->add_render_attribute('wrapper', 'data-center-suffix', esc_attr($centerSuffix));
$this->add_render_attribute('wrapper', 'data-center-label', esc_attr($centerLabel));
$this->add_render_attribute('wrapper', 'data-show-labels', $showLabels);
$this->add_render_attribute('wrapper', 'data-show-legend', $showLegend);
$this->add_render_attribute('wrapper', 'data-inner-cutout', (string) $innerCutout);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($title !== '') : ?>
        <h3 class="veloria-donut-chart__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    <div class="veloria-donut-chart__wrap">
        <div class="veloria-donut-chart__canvas" role="img" aria-label="<?php echo esc_attr($title); ?>"></div>
        <div class="veloria-donut-chart__legend"></div>
    </div>
</div>

<?php
    }
}
