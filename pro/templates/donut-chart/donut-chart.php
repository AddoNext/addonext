<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_title = isset($addonext_settings['title']) ? (string) $addonext_settings['title'] : '';
$addonext_data = isset($addonext_settings['data']) && is_array($addonext_settings['data']) ? $addonext_settings['data'] : [];
$addonext_centerPrefix = isset($addonext_settings['center_prefix']) ? (string) $addonext_settings['center_prefix'] : '';
$addonext_centerSuffix = isset($addonext_settings['center_suffix']) ? (string) $addonext_settings['center_suffix'] : '';
$addonext_centerLabel = isset($addonext_settings['center_label']) ? (string) $addonext_settings['center_label'] : '';
$addonext_showLabels = isset($addonext_settings['show_slice_labels']) && $addonext_settings['show_slice_labels'] === 'yes' ? 'yes' : 'no';
$addonext_showLegend = isset($addonext_settings['show_legend']) && $addonext_settings['show_legend'] === 'yes' ? 'yes' : 'no';
$addonext_innerCutout = isset($addonext_settings['inner_cutout']['size']) ? (float) $addonext_settings['inner_cutout']['size'] : 50;

$addonext_labels = [];
$addonext_values = [];
$addonext_colors = [];
foreach ($addonext_data as $addonext_row) {
    $addonext_labels[] = isset($addonext_row['label']) ? (string) $addonext_row['label'] : '';
    $addonext_values[] = isset($addonext_row['value']) ? (float) $addonext_row['value'] : 0;
    $addonext_colors[] = isset($addonext_row['color']) ? (string) $addonext_row['color'] : '#4285F4';
}

$this->add_render_attribute('wrapper', 'class', 'addonext-donut-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $addonext_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $addonext_values));
$this->add_render_attribute('wrapper', 'data-colors', implode('|', $addonext_colors));
$this->add_render_attribute('wrapper', 'data-center-prefix', esc_attr($addonext_centerPrefix));
$this->add_render_attribute('wrapper', 'data-center-suffix', esc_attr($addonext_centerSuffix));
$this->add_render_attribute('wrapper', 'data-center-label', esc_attr($addonext_centerLabel));
$this->add_render_attribute('wrapper', 'data-show-labels', $addonext_showLabels);
$this->add_render_attribute('wrapper', 'data-show-legend', $addonext_showLegend);
$this->add_render_attribute('wrapper', 'data-inner-cutout', (string) $addonext_innerCutout);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_title !== '') : ?>
        <h3 class="addonext-donut-chart__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php endif; ?>
    <div class="addonext-donut-chart__wrap">
        <div class="addonext-donut-chart__canvas" role="img" aria-label="<?php echo esc_attr($addonext_title); ?>"></div>
        <div class="addonext-donut-chart__legend"></div>
    </div>
</div>

<?php
    }
}
