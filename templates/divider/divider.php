<?php
defined('ABSPATH') || exit;

$veloria_settings  = $this->get_settings_for_display();
$veloria_variant   = isset($veloria_settings['variant']) ? $veloria_settings['variant'] : 'line';
$veloria_style     = isset($veloria_settings['style']) ? $veloria_settings['style'] : 'solid';
$veloria_line_color = isset($veloria_settings['color']) ? $veloria_settings['color'] : '#e5e7eb';
$veloria_thickness  = isset($veloria_settings['thickness']['size']) ? $veloria_settings['thickness']['size'] : 1;
$veloria_thickness_unit = isset($veloria_settings['thickness']['unit']) ? $veloria_settings['thickness']['unit'] : 'px';
$veloria_line_style_attr = sprintf(
    'border:none;border-top-width:%s%s;border-top-style:%s;border-top-color:%s;height:0;min-height:0;',
    esc_attr($veloria_thickness),
    esc_attr($veloria_thickness_unit),
    esc_attr($veloria_style),
    esc_attr($veloria_line_color)
);

$this->add_render_attribute('wrapper', 'class', 'veloria-divider-wrapper');
if ($veloria_variant === 'line_icon') {
    $this->add_render_attribute('wrapper', 'class', 'veloria-divider-wrapper--line-icon');
}

$veloria_line_class_left  = 'veloria-divider__line veloria-divider__line--left veloria-divider__line--' . esc_attr($veloria_style);
$veloria_line_class_right = 'veloria-divider__line veloria-divider__line--right veloria-divider__line--' . esc_attr($veloria_style);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_variant === 'line_icon') : ?>
        <span class="<?php echo esc_attr($veloria_line_class_left); ?>" style="<?php echo esc_attr($veloria_line_style_attr); ?>"></span>
        <span class="veloria-divider__icon" aria-hidden="true">
            <?php
            $veloria_icon_settings = !empty($veloria_settings['selected_icon']['value'])
                ? $veloria_settings['selected_icon']
                : ['value' => 'eicon-star', 'library' => 'eicons'];
            \Elementor\Icons_Manager::render_icon($veloria_icon_settings, ['aria-hidden' => 'true']);
            ?>
        </span>
        <span class="<?php echo esc_attr($veloria_line_class_right); ?>" style="<?php echo esc_attr($veloria_line_style_attr); ?>"></span>
    <?php else : ?>
        <span class="veloria-divider__line veloria-divider__line--standalone" style="<?php echo esc_attr($veloria_line_style_attr); ?>"></span>
    <?php endif; ?>
</div>
