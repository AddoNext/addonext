<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$title = isset($settings['title']) ? (string) $settings['title'] : '';
$rows = isset($settings['data']) && is_array($settings['data']) ? $settings['data'] : [];
$seriesA = isset($settings['series_a_name']) ? (string) $settings['series_a_name'] : __('Current Metrics', 'elementrify');
$seriesB = isset($settings['series_b_name']) ? (string) $settings['series_b_name'] : __('Target Metrics', 'elementrify');
$colorA = isset($settings['series_a_color']) ? (string) $settings['series_a_color'] : '#4285F4';
$colorB = isset($settings['series_b_color']) ? (string) $settings['series_b_color'] : '#24B47E';
$maxValue = isset($settings['max_value']) && $settings['max_value'] !== '' ? (float) $settings['max_value'] : 100;

$labels = [];
$valuesA = [];
$valuesB = [];
foreach ($rows as $row) {
    $labels[] = isset($row['label']) ? (string) $row['label'] : '';
    $valuesA[] = isset($row['value_a']) ? (float) $row['value_a'] : 0;
    $valuesB[] = isset($row['value_b']) ? (float) $row['value_b'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'elementrify-radar-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $labels));
$this->add_render_attribute('wrapper', 'data-values-a', implode('|', $valuesA));
$this->add_render_attribute('wrapper', 'data-values-b', implode('|', $valuesB));
$this->add_render_attribute('wrapper', 'data-series-a-name', esc_attr($seriesA));
$this->add_render_attribute('wrapper', 'data-series-b-name', esc_attr($seriesB));
$this->add_render_attribute('wrapper', 'data-color-a', esc_attr($colorA));
$this->add_render_attribute('wrapper', 'data-color-b', esc_attr($colorB));
$this->add_render_attribute('wrapper', 'data-max-value', (string) $maxValue);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($title !== '') : ?>
        <h3 class="elementrify-radar-chart__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    <div class="elementrify-radar-chart__wrap">
        <div class="elementrify-radar-chart__canvas" role="img" aria-label="<?php echo esc_attr($title); ?>"></div>
        <div class="elementrify-radar-chart__legend"></div>
    </div>
</div>

<?php
    }
}
