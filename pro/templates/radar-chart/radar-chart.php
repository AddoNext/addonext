<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_title = isset($addonext_settings['title']) ? (string) $addonext_settings['title'] : '';
$addonext_rows = isset($addonext_settings['data']) && is_array($addonext_settings['data']) ? $addonext_settings['data'] : [];
$addonext_seriesA = isset($addonext_settings['series_a_name']) ? (string) $addonext_settings['series_a_name'] : __('Current Metrics', 'addonext');
$addonext_seriesB = isset($addonext_settings['series_b_name']) ? (string) $addonext_settings['series_b_name'] : __('Target Metrics', 'addonext');
$addonext_colorA = isset($addonext_settings['series_a_color']) ? (string) $addonext_settings['series_a_color'] : '#4285F4';
$addonext_colorB = isset($addonext_settings['series_b_color']) ? (string) $addonext_settings['series_b_color'] : '#24B47E';
$addonext_maxValue = isset($addonext_settings['max_value']) && $addonext_settings['max_value'] !== '' ? (float) $addonext_settings['max_value'] : 100;

$addonext_labels = [];
$addonext_valuesA = [];
$addonext_valuesB = [];
foreach ($addonext_rows as $addonext_row) {
    $addonext_labels[] = isset($addonext_row['label']) ? (string) $addonext_row['label'] : '';
    $addonext_valuesA[] = isset($addonext_row['value_a']) ? (float) $addonext_row['value_a'] : 0;
    $addonext_valuesB[] = isset($addonext_row['value_b']) ? (float) $addonext_row['value_b'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'addonext-radar-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $addonext_labels));
$this->add_render_attribute('wrapper', 'data-values-a', implode('|', $addonext_valuesA));
$this->add_render_attribute('wrapper', 'data-values-b', implode('|', $addonext_valuesB));
$this->add_render_attribute('wrapper', 'data-series-a-name', esc_attr($addonext_seriesA));
$this->add_render_attribute('wrapper', 'data-series-b-name', esc_attr($addonext_seriesB));
$this->add_render_attribute('wrapper', 'data-color-a', esc_attr($addonext_colorA));
$this->add_render_attribute('wrapper', 'data-color-b', esc_attr($addonext_colorB));
$this->add_render_attribute('wrapper', 'data-max-value', (string) $addonext_maxValue);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_title !== '') : ?>
        <h3 class="addonext-radar-chart__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php endif; ?>
    <div class="addonext-radar-chart__wrap">
        <div class="addonext-radar-chart__canvas" role="img" aria-label="<?php echo esc_attr($addonext_title); ?>"></div>
        <div class="addonext-radar-chart__legend"></div>
    </div>
</div>

<?php
    }
}
