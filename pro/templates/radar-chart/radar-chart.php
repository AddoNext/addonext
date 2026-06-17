<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_title = isset($veloria_settings['title']) ? (string) $veloria_settings['title'] : '';
$veloria_rows = isset($veloria_settings['data']) && is_array($veloria_settings['data']) ? $veloria_settings['data'] : [];
$veloria_seriesA = isset($veloria_settings['series_a_name']) ? (string) $veloria_settings['series_a_name'] : __('Current Metrics', 'veloria');
$veloria_seriesB = isset($veloria_settings['series_b_name']) ? (string) $veloria_settings['series_b_name'] : __('Target Metrics', 'veloria');
$veloria_colorA = isset($veloria_settings['series_a_color']) ? (string) $veloria_settings['series_a_color'] : '#4285F4';
$veloria_colorB = isset($veloria_settings['series_b_color']) ? (string) $veloria_settings['series_b_color'] : '#24B47E';
$veloria_maxValue = isset($veloria_settings['max_value']) && $veloria_settings['max_value'] !== '' ? (float) $veloria_settings['max_value'] : 100;

$veloria_labels = [];
$veloria_valuesA = [];
$veloria_valuesB = [];
foreach ($veloria_rows as $veloria_row) {
    $veloria_labels[] = isset($veloria_row['label']) ? (string) $veloria_row['label'] : '';
    $veloria_valuesA[] = isset($veloria_row['value_a']) ? (float) $veloria_row['value_a'] : 0;
    $veloria_valuesB[] = isset($veloria_row['value_b']) ? (float) $veloria_row['value_b'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'veloria-radar-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $veloria_labels));
$this->add_render_attribute('wrapper', 'data-values-a', implode('|', $veloria_valuesA));
$this->add_render_attribute('wrapper', 'data-values-b', implode('|', $veloria_valuesB));
$this->add_render_attribute('wrapper', 'data-series-a-name', esc_attr($veloria_seriesA));
$this->add_render_attribute('wrapper', 'data-series-b-name', esc_attr($veloria_seriesB));
$this->add_render_attribute('wrapper', 'data-color-a', esc_attr($veloria_colorA));
$this->add_render_attribute('wrapper', 'data-color-b', esc_attr($veloria_colorB));
$this->add_render_attribute('wrapper', 'data-max-value', (string) $veloria_maxValue);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_title !== '') : ?>
        <h3 class="veloria-radar-chart__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php endif; ?>
    <div class="veloria-radar-chart__wrap">
        <div class="veloria-radar-chart__canvas" role="img" aria-label="<?php echo esc_attr($veloria_title); ?>"></div>
        <div class="veloria-radar-chart__legend"></div>
    </div>
</div>

<?php
    }
}
