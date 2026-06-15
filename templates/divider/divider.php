<?php
defined('ABSPATH') || exit;

$settings  = $this->get_settings_for_display();
$variant   = isset($settings['variant']) ? $settings['variant'] : 'line';
$style     = isset($settings['style']) ? $settings['style'] : 'solid';
$line_color = isset($settings['color']) ? $settings['color'] : '#e5e7eb';
$thickness  = isset($settings['thickness']['size']) ? $settings['thickness']['size'] : 1;
$thickness_unit = isset($settings['thickness']['unit']) ? $settings['thickness']['unit'] : 'px';
$line_style_attr = sprintf(
    'border:none;border-top-width:%s%s;border-top-style:%s;border-top-color:%s;height:0;min-height:0;',
    esc_attr($thickness),
    esc_attr($thickness_unit),
    esc_attr($style),
    esc_attr($line_color)
);

$this->add_render_attribute('wrapper', 'class', 'veloria-divider-wrapper');
if ($variant === 'line_icon') {
    $this->add_render_attribute('wrapper', 'class', 'veloria-divider-wrapper--line-icon');
}

$line_class_left  = 'veloria-divider__line veloria-divider__line--left veloria-divider__line--' . esc_attr($style);
$line_class_right = 'veloria-divider__line veloria-divider__line--right veloria-divider__line--' . esc_attr($style);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($variant === 'line_icon') : ?>
        <span class="<?php echo esc_attr($line_class_left); ?>" style="<?php echo esc_attr($line_style_attr); ?>"></span>
        <span class="veloria-divider__icon" aria-hidden="true">
            <?php
            $icon_settings = !empty($settings['selected_icon']['value'])
                ? $settings['selected_icon']
                : ['value' => 'eicon-star', 'library' => 'eicons'];
            \Elementor\Icons_Manager::render_icon($icon_settings, ['aria-hidden' => 'true']);
            ?>
        </span>
        <span class="<?php echo esc_attr($line_class_right); ?>" style="<?php echo esc_attr($line_style_attr); ?>"></span>
    <?php else : ?>
        <span class="veloria-divider__line veloria-divider__line--standalone" style="<?php echo esc_attr($line_style_attr); ?>"></span>
    <?php endif; ?>
</div>
