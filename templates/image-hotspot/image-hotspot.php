<?php
defined('ABSPATH') || exit;

$settings    = $this->get_settings_for_display();
$image_arr   = isset($settings['image']) ? $settings['image'] : [];
$hotspots    = isset($settings['hotspots']) && is_array($settings['hotspots']) ? $settings['hotspots'] : [];
$trigger     = isset($settings['tooltip_trigger']) ? $settings['tooltip_trigger'] : 'hover';
$tooltip_pos = isset($settings['tooltip_position']) ? $settings['tooltip_position'] : 'bottom';
$pulse       = !empty($settings['hotspot_animation']) && $settings['hotspot_animation'] === 'yes';

$wrapper_classes = [
    'elementrify-image-hotspot',
    'elementrify-image-hotspot--trigger-' . esc_attr($trigger),
    'elementrify-image-hotspot--tooltip-' . esc_attr($tooltip_pos),
];
if ($pulse) {
    $wrapper_classes[] = 'elementrify-image-hotspot--pulse';
}
if ($trigger === 'click') {
    $wrapper_classes[] = 'elementor-clickable';
}
$this->add_render_attribute('wrapper', 'class', $wrapper_classes);
$this->add_render_attribute('wrapper', 'data-trigger', esc_attr($trigger));

$image_url = '';
$image_alt = '';
$image_id  = isset($image_arr['id']) ? (int) $image_arr['id'] : 0;

if ($image_id) {
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    $image_url = wp_get_attachment_image_url($image_id, 'full');
}
if ($image_url === '' && !empty($image_arr['url'])) {
    $image_url = $image_arr['url'];
}
if ($image_url === '') {
    $image_url = \Elementor\Utils::get_placeholder_image_src();
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="elementrify-image-hotspot__img" loading="lazy">
    <?php foreach ($hotspots as $index => $h) :
        $x = isset($h['x']['size']) ? (float) $h['x']['size'] : 50;
        $y = isset($h['y']['size']) ? (float) $h['y']['size'] : 50;
        $color = isset($h['hotspot_color']) ? esc_attr($h['hotspot_color']) : '#605BE5';
        $point_style = sprintf('left:%s%%; top:%s%%; background-color:%s; --hotspot-color:%s;', $x, $y, $color, $color);
    ?>
    <span class="elementrify-image-hotspot__point elementor-repeater-item-<?php echo esc_attr($h['_id'] ?? $index); ?>" style="<?php echo esc_attr($point_style); ?>" data-index="<?php echo esc_attr($index); ?>">
        <?php if ($pulse) : ?>
        <span class="elementrify-image-hotspot__pulse" aria-hidden="true"></span>
        <span class="elementrify-image-hotspot__pulse" aria-hidden="true"></span>
        <span class="elementrify-image-hotspot__pulse" aria-hidden="true"></span>
        <?php endif; ?>
        <span class="elementrify-image-hotspot__tooltip">
            <?php if (!empty($h['title'])) : ?><span class="elementrify-image-hotspot__tooltip-title"><?php echo wp_kses_post($h['title']); ?></span><?php endif; ?>
            <?php if (!empty($h['description'])) : ?><span class="elementrify-image-hotspot__tooltip-desc"><?php echo wp_kses_post($h['description']); ?></span><?php endif; ?>
            <?php if (!empty($h['price'])) : ?><span class="elementrify-image-hotspot__tooltip-price"><?php echo wp_kses_post($h['price']); ?></span><?php endif; ?>
        </span>
    </span>
    <?php endforeach; ?>
</div>
