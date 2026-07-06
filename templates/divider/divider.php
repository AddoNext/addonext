<?php
defined('ABSPATH') || exit;

$addonext_settings  = $this->get_settings_for_display();
$addonext_variant   = isset($addonext_settings['variant']) ? $addonext_settings['variant'] : 'line';
$addonext_style     = isset($addonext_settings['style']) ? $addonext_settings['style'] : 'solid';
$addonext_line_color = isset($addonext_settings['color']) ? $addonext_settings['color'] : '#e5e7eb';
$addonext_thickness  = isset($addonext_settings['thickness']['size']) ? $addonext_settings['thickness']['size'] : 1;
$addonext_thickness_unit = isset($addonext_settings['thickness']['unit']) ? $addonext_settings['thickness']['unit'] : 'px';
$addonext_line_style_attr = sprintf(
    'border:none;border-top-width:%s%s;border-top-style:%s;border-top-color:%s;height:0;min-height:0;',
    esc_attr($addonext_thickness),
    esc_attr($addonext_thickness_unit),
    esc_attr($addonext_style),
    esc_attr($addonext_line_color)
);

$this->add_render_attribute('wrapper', 'class', 'addonext-divider-wrapper');
if ($addonext_variant === 'line_icon') {
    $this->add_render_attribute('wrapper', 'class', 'addonext-divider-wrapper--line-icon');
}

$addonext_line_class_left  = 'addonext-divider__line addonext-divider__line--left addonext-divider__line--' . esc_attr($addonext_style);
$addonext_line_class_right = 'addonext-divider__line addonext-divider__line--right addonext-divider__line--' . esc_attr($addonext_style);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_variant === 'line_icon') : ?>
        <span class="<?php echo esc_attr($addonext_line_class_left); ?>" style="<?php echo esc_attr($addonext_line_style_attr); ?>"></span>
        <span class="addonext-divider__icon" aria-hidden="true">
            <?php
            $addonext_icon_settings = !empty($addonext_settings['selected_icon']['value'])
                ? $addonext_settings['selected_icon']
                : ['value' => 'eicon-star', 'library' => 'eicons'];
            \Elementor\Icons_Manager::render_icon($addonext_icon_settings, ['aria-hidden' => 'true']);
            ?>
        </span>
        <span class="<?php echo esc_attr($addonext_line_class_right); ?>" style="<?php echo esc_attr($addonext_line_style_attr); ?>"></span>
    <?php else : ?>
        <span class="addonext-divider__line addonext-divider__line--standalone" style="<?php echo esc_attr($addonext_line_style_attr); ?>"></span>
    <?php endif; ?>
</div>
