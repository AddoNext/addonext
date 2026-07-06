<?php
defined('ABSPATH') || exit;

$addonext_settings    = $this->get_settings_for_display();
$addonext_image_arr   = isset($addonext_settings['image']) ? $addonext_settings['image'] : [];
$addonext_hotspots    = isset($addonext_settings['hotspots']) && is_array($addonext_settings['hotspots']) ? $addonext_settings['hotspots'] : [];
$addonext_trigger     = isset($addonext_settings['tooltip_trigger']) ? $addonext_settings['tooltip_trigger'] : 'hover';
$addonext_tooltip_pos = isset($addonext_settings['tooltip_position']) ? $addonext_settings['tooltip_position'] : 'bottom';
$addonext_pulse       = !empty($addonext_settings['hotspot_animation']) && $addonext_settings['hotspot_animation'] === 'yes';

$addonext_wrapper_classes = [
    'addonext-image-hotspot',
    'addonext-image-hotspot--trigger-' . esc_attr($addonext_trigger),
    'addonext-image-hotspot--tooltip-' . esc_attr($addonext_tooltip_pos),
];
if ($addonext_pulse) {
    $addonext_wrapper_classes[] = 'addonext-image-hotspot--pulse';
}
if ($addonext_trigger === 'click') {
    $addonext_wrapper_classes[] = 'elementor-clickable';
}
$this->add_render_attribute('wrapper', 'class', $addonext_wrapper_classes);
$this->add_render_attribute('wrapper', 'data-trigger', esc_attr($addonext_trigger));

$addonext_image_url = '';
$addonext_image_alt = '';
$addonext_image_id  = isset($addonext_image_arr['id']) ? (int) $addonext_image_arr['id'] : 0;

if ($addonext_image_id) {
    $addonext_image_alt = get_post_meta($addonext_image_id, '_wp_attachment_image_alt', true);
    $addonext_image_url = wp_get_attachment_image_url($addonext_image_id, 'full');
}
if ($addonext_image_url === '' && !empty($addonext_image_arr['url'])) {
    $addonext_image_url = $addonext_image_arr['url'];
}
if ($addonext_image_url === '') {
    $addonext_image_url = \Elementor\Utils::get_placeholder_image_src();
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <img src="<?php echo esc_url($addonext_image_url); ?>" alt="<?php echo esc_attr($addonext_image_alt); ?>" class="addonext-image-hotspot__img" loading="lazy">
    <?php foreach ($addonext_hotspots as $addonext_index => $addonext_h) :
        $addonext_x = isset($addonext_h['x']['size']) ? (float) $addonext_h['x']['size'] : 50;
        $addonext_y = isset($addonext_h['y']['size']) ? (float) $addonext_h['y']['size'] : 50;
        $addonext_color = isset($addonext_h['hotspot_color']) ? esc_attr($addonext_h['hotspot_color']) : '#605BE5';
        $addonext_point_style = sprintf('left:%s%%; top:%s%%; background-color:%s; --hotspot-color:%s;', $addonext_x, $addonext_y, $addonext_color, $addonext_color);
    ?>
    <span class="addonext-image-hotspot__point elementor-repeater-item-<?php echo esc_attr($addonext_h['_id'] ?? $addonext_index); ?>" style="<?php echo esc_attr($addonext_point_style); ?>" data-index="<?php echo esc_attr($addonext_index); ?>">
        <?php if ($addonext_pulse) : ?>
        <span class="addonext-image-hotspot__pulse" aria-hidden="true"></span>
        <span class="addonext-image-hotspot__pulse" aria-hidden="true"></span>
        <span class="addonext-image-hotspot__pulse" aria-hidden="true"></span>
        <?php endif; ?>
        <span class="addonext-image-hotspot__tooltip">
            <?php if (!empty($addonext_h['title'])) : ?><span class="addonext-image-hotspot__tooltip-title"><?php echo wp_kses_post($addonext_h['title']); ?></span><?php endif; ?>
            <?php if (!empty($addonext_h['description'])) : ?><span class="addonext-image-hotspot__tooltip-desc"><?php echo wp_kses_post($addonext_h['description']); ?></span><?php endif; ?>
            <?php if (!empty($addonext_h['price'])) : ?><span class="addonext-image-hotspot__tooltip-price"><?php echo wp_kses_post($addonext_h['price']); ?></span><?php endif; ?>
        </span>
    </span>
    <?php endforeach; ?>
</div>
