<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_title = isset($veloria_settings['title']) ? (string) $veloria_settings['title'] : '';
$veloria_data = isset($veloria_settings['data']) && is_array($veloria_settings['data']) ? $veloria_settings['data'] : [];
$veloria_y_suffix = isset($veloria_settings['y_axis_suffix']) ? (string) $veloria_settings['y_axis_suffix'] : 'k';
$veloria_y_min = isset($veloria_settings['y_min']) && $veloria_settings['y_min'] !== '' ? (float) $veloria_settings['y_min'] : '';
$veloria_y_max = isset($veloria_settings['y_max']) && $veloria_settings['y_max'] !== '' ? (float) $veloria_settings['y_max'] : '';
$veloria_smooth = !isset($veloria_settings['smooth_line']) || $veloria_settings['smooth_line'] === 'yes' ? 'yes' : 'no';
$veloria_line_color = isset($veloria_settings['line_color']) ? (string) $veloria_settings['line_color'] : '#5b9bd5';
$veloria_area_top_opacity = isset($veloria_settings['area_opacity_top']) && $veloria_settings['area_opacity_top'] !== '' ? (float) $veloria_settings['area_opacity_top'] : 0.4;
$veloria_area_bottom_opacity = isset($veloria_settings['area_opacity_bottom']) && $veloria_settings['area_opacity_bottom'] !== '' ? (float) $veloria_settings['area_opacity_bottom'] : 0.03;

$veloria_labels = [];
$veloria_values = [];
foreach ($veloria_data as $veloria_row) {
    $veloria_labels[] = isset($veloria_row['label']) ? (string) $veloria_row['label'] : '';
    $veloria_values[] = isset($veloria_row['value']) ? (float) $veloria_row['value'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'veloria-area-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $veloria_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $veloria_values));
$this->add_render_attribute('wrapper', 'data-y-suffix', esc_attr($veloria_y_suffix));
$this->add_render_attribute('wrapper', 'data-y-min', $veloria_y_min !== '' ? (string) $veloria_y_min : '');
$this->add_render_attribute('wrapper', 'data-y-max', $veloria_y_max !== '' ? (string) $veloria_y_max : '');
$this->add_render_attribute('wrapper', 'data-smooth', esc_attr($veloria_smooth));
$this->add_render_attribute('wrapper', 'data-line-color', esc_attr($veloria_line_color));
$this->add_render_attribute('wrapper', 'data-area-opacity-top', (string) $veloria_area_top_opacity);
$this->add_render_attribute('wrapper', 'data-area-opacity-bottom', (string) $veloria_area_bottom_opacity);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_title !== '') : ?>
        <h3 class="veloria-area-chart__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php endif; ?>
    <div class="veloria-area-chart__canvas" role="img" aria-label="<?php echo esc_attr($veloria_title); ?>"></div>
</div>

<?php
    }
}
