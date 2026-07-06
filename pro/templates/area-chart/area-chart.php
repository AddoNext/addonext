<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_title = isset($addonext_settings['title']) ? (string) $addonext_settings['title'] : '';
$addonext_data = isset($addonext_settings['data']) && is_array($addonext_settings['data']) ? $addonext_settings['data'] : [];
$addonext_y_suffix = isset($addonext_settings['y_axis_suffix']) ? (string) $addonext_settings['y_axis_suffix'] : 'k';
$addonext_y_min = isset($addonext_settings['y_min']) && $addonext_settings['y_min'] !== '' ? (float) $addonext_settings['y_min'] : '';
$addonext_y_max = isset($addonext_settings['y_max']) && $addonext_settings['y_max'] !== '' ? (float) $addonext_settings['y_max'] : '';
$addonext_smooth = !isset($addonext_settings['smooth_line']) || $addonext_settings['smooth_line'] === 'yes' ? 'yes' : 'no';
$addonext_line_color = isset($addonext_settings['line_color']) ? (string) $addonext_settings['line_color'] : '#5b9bd5';
$addonext_area_top_opacity = isset($addonext_settings['area_opacity_top']) && $addonext_settings['area_opacity_top'] !== '' ? (float) $addonext_settings['area_opacity_top'] : 0.4;
$addonext_area_bottom_opacity = isset($addonext_settings['area_opacity_bottom']) && $addonext_settings['area_opacity_bottom'] !== '' ? (float) $addonext_settings['area_opacity_bottom'] : 0.03;

$addonext_labels = [];
$addonext_values = [];
foreach ($addonext_data as $addonext_row) {
    $addonext_labels[] = isset($addonext_row['label']) ? (string) $addonext_row['label'] : '';
    $addonext_values[] = isset($addonext_row['value']) ? (float) $addonext_row['value'] : 0;
}

$this->add_render_attribute('wrapper', 'class', 'addonext-area-chart');
$this->add_render_attribute('wrapper', 'data-labels', implode('|', $addonext_labels));
$this->add_render_attribute('wrapper', 'data-values', implode('|', $addonext_values));
$this->add_render_attribute('wrapper', 'data-y-suffix', esc_attr($addonext_y_suffix));
$this->add_render_attribute('wrapper', 'data-y-min', $addonext_y_min !== '' ? (string) $addonext_y_min : '');
$this->add_render_attribute('wrapper', 'data-y-max', $addonext_y_max !== '' ? (string) $addonext_y_max : '');
$this->add_render_attribute('wrapper', 'data-smooth', esc_attr($addonext_smooth));
$this->add_render_attribute('wrapper', 'data-line-color', esc_attr($addonext_line_color));
$this->add_render_attribute('wrapper', 'data-area-opacity-top', (string) $addonext_area_top_opacity);
$this->add_render_attribute('wrapper', 'data-area-opacity-bottom', (string) $addonext_area_bottom_opacity);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_title !== '') : ?>
        <h3 class="addonext-area-chart__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php endif; ?>
    <div class="addonext-area-chart__canvas" role="img" aria-label="<?php echo esc_attr($addonext_title); ?>"></div>
</div>

<?php
    }
}
