<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_title    = isset($addonext_settings['title']) ? $addonext_settings['title'] : '';
$addonext_data     = isset($addonext_settings['data']) && is_array($addonext_settings['data']) ? $addonext_settings['data'] : [];

$addonext_labels = [];
$addonext_values = [];
$addonext_colors = [];
foreach ($addonext_data as $addonext_row) {
    $addonext_labels[] = isset($addonext_row['label']) ? $addonext_row['label'] : '';
    $addonext_values[] = isset($addonext_row['value']) ? (float) $addonext_row['value'] : 0;
    $addonext_colors[] = isset($addonext_row['color']) ? $addonext_row['color'] : '#2563eb';
}

$this->add_render_attribute('wrapper', 'class', 'addonext-pie-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $addonext_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $addonext_values));
$this->add_render_attribute('wrapper', 'data-colors', implode('|', $addonext_colors));
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_title !== '') : ?>
        <h3 class="addonext-pie-chart__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php endif; ?>
    <div class="addonext-pie-chart__wrap">
        <div class="addonext-pie-chart__canvas" role="img" aria-label="<?php echo esc_attr($addonext_title); ?>"></div>
        <div class="addonext-pie-chart__legend"></div>
    </div>
</div>

<?php
    }
}
