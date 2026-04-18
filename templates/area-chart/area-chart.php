<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$title = isset($settings['title']) ? (string) $settings['title'] : '';
$data = isset($settings['data']) && is_array($settings['data']) ? $settings['data'] : [];
$y_suffix = isset($settings['y_axis_suffix']) ? (string) $settings['y_axis_suffix'] : 'k';
$y_min = isset($settings['y_min']) && $settings['y_min'] !== '' ? (float) $settings['y_min'] : '';
$y_max = isset($settings['y_max']) && $settings['y_max'] !== '' ? (float) $settings['y_max'] : '';
$smooth = !isset($settings['smooth_line']) || $settings['smooth_line'] === 'yes' ? 'yes' : 'no';
$line_color = isset($settings['line_color']) ? (string) $settings['line_color'] : '#5b9bd5';
$area_top_opacity = isset($settings['area_opacity_top']) && $settings['area_opacity_top'] !== '' ? (float) $settings['area_opacity_top'] : 0.4;
$area_bottom_opacity = isset($settings['area_opacity_bottom']) && $settings['area_opacity_bottom'] !== '' ? (float) $settings['area_opacity_bottom'] : 0.03;

$labels = [];
$values = [];
foreach ($data as $row) {
    $labels[] = isset($row['label']) ? (string) $row['label'] : '';
    $values[] = isset($row['value']) ? (float) $row['value'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'elementrify-area-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $values));
$this->add_render_attribute('wrapper', 'data-y-suffix', esc_attr($y_suffix));
$this->add_render_attribute('wrapper', 'data-y-min', $y_min !== '' ? (string) $y_min : '');
$this->add_render_attribute('wrapper', 'data-y-max', $y_max !== '' ? (string) $y_max : '');
$this->add_render_attribute('wrapper', 'data-smooth', esc_attr($smooth));
$this->add_render_attribute('wrapper', 'data-line-color', esc_attr($line_color));
$this->add_render_attribute('wrapper', 'data-area-opacity-top', (string) $area_top_opacity);
$this->add_render_attribute('wrapper', 'data-area-opacity-bottom', (string) $area_bottom_opacity);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($title !== '') : ?>
        <h3 class="elementrify-area-chart__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    <div class="elementrify-area-chart__canvas" role="img" aria-label="<?php echo esc_attr($title); ?>"></div>
</div>

<?php
    }
}
