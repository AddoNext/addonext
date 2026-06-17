<?php
defined('ABSPATH') || exit;

$veloria_settings    = $this->get_settings_for_display();
$veloria_image_arr   = isset($veloria_settings['image']) ? $veloria_settings['image'] : [];
$veloria_hotspots    = isset($veloria_settings['hotspots']) && is_array($veloria_settings['hotspots']) ? $veloria_settings['hotspots'] : [];
$veloria_trigger     = isset($veloria_settings['tooltip_trigger']) ? $veloria_settings['tooltip_trigger'] : 'hover';
$veloria_tooltip_pos = isset($veloria_settings['tooltip_position']) ? $veloria_settings['tooltip_position'] : 'bottom';
$veloria_pulse       = !empty($veloria_settings['hotspot_animation']) && $veloria_settings['hotspot_animation'] === 'yes';

$veloria_wrapper_classes = [
    'veloria-image-hotspot',
    'veloria-image-hotspot--trigger-' . esc_attr($veloria_trigger),
    'veloria-image-hotspot--tooltip-' . esc_attr($veloria_tooltip_pos),
];
if ($veloria_pulse) {
    $veloria_wrapper_classes[] = 'veloria-image-hotspot--pulse';
}
if ($veloria_trigger === 'click') {
    $veloria_wrapper_classes[] = 'elementor-clickable';
}
$this->add_render_attribute('wrapper', 'class', $veloria_wrapper_classes);
$this->add_render_attribute('wrapper', 'data-trigger', esc_attr($veloria_trigger));

$veloria_image_url = '';
$veloria_image_alt = '';
$veloria_image_id  = isset($veloria_image_arr['id']) ? (int) $veloria_image_arr['id'] : 0;

if ($veloria_image_id) {
    $veloria_image_alt = get_post_meta($veloria_image_id, '_wp_attachment_image_alt', true);
    $veloria_image_url = wp_get_attachment_image_url($veloria_image_id, 'full');
}
if ($veloria_image_url === '' && !empty($veloria_image_arr['url'])) {
    $veloria_image_url = $veloria_image_arr['url'];
}
if ($veloria_image_url === '') {
    $veloria_image_url = \Elementor\Utils::get_placeholder_image_src();
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <img src="<?php echo esc_url($veloria_image_url); ?>" alt="<?php echo esc_attr($veloria_image_alt); ?>" class="veloria-image-hotspot__img" loading="lazy">
    <?php foreach ($veloria_hotspots as $veloria_index => $veloria_h) :
        $veloria_x = isset($veloria_h['x']['size']) ? (float) $veloria_h['x']['size'] : 50;
        $veloria_y = isset($veloria_h['y']['size']) ? (float) $veloria_h['y']['size'] : 50;
        $veloria_color = isset($veloria_h['hotspot_color']) ? esc_attr($veloria_h['hotspot_color']) : '#605BE5';
        $veloria_point_style = sprintf('left:%s%%; top:%s%%; background-color:%s; --hotspot-color:%s;', $veloria_x, $veloria_y, $veloria_color, $veloria_color);
    ?>
    <span class="veloria-image-hotspot__point elementor-repeater-item-<?php echo esc_attr($veloria_h['_id'] ?? $veloria_index); ?>" style="<?php echo esc_attr($veloria_point_style); ?>" data-index="<?php echo esc_attr($veloria_index); ?>">
        <?php if ($veloria_pulse) : ?>
        <span class="veloria-image-hotspot__pulse" aria-hidden="true"></span>
        <span class="veloria-image-hotspot__pulse" aria-hidden="true"></span>
        <span class="veloria-image-hotspot__pulse" aria-hidden="true"></span>
        <?php endif; ?>
        <span class="veloria-image-hotspot__tooltip">
            <?php if (!empty($veloria_h['title'])) : ?><span class="veloria-image-hotspot__tooltip-title"><?php echo wp_kses_post($veloria_h['title']); ?></span><?php endif; ?>
            <?php if (!empty($veloria_h['description'])) : ?><span class="veloria-image-hotspot__tooltip-desc"><?php echo wp_kses_post($veloria_h['description']); ?></span><?php endif; ?>
            <?php if (!empty($veloria_h['price'])) : ?><span class="veloria-image-hotspot__tooltip-price"><?php echo wp_kses_post($veloria_h['price']); ?></span><?php endif; ?>
        </span>
    </span>
    <?php endforeach; ?>
</div>
