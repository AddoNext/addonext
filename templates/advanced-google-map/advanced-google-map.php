<?php
defined('ABSPATH') || exit;

$veloria_settings   = $this->get_settings_for_display();
$veloria_map_source = isset($veloria_settings['map_source']) ? $veloria_settings['map_source'] : 'address';
$veloria_map_url    = '';

if ($veloria_map_source === 'url') {
    $veloria_map_url = isset($veloria_settings['map_url']['url']) ? $veloria_settings['map_url']['url'] : '';
} elseif ($veloria_map_source === 'coordinates') {
    $veloria_lat  = isset($veloria_settings['latitude']) ? (float) $veloria_settings['latitude'] : 51.5074;
    $veloria_lng  = isset($veloria_settings['longitude']) ? (float) $veloria_settings['longitude'] : -0.1278;
    $veloria_zoom = isset($veloria_settings['zoom']['size']) ? (int) $veloria_settings['zoom']['size'] : 14;
    $veloria_map_url = 'https://www.google.com/maps?q=' . $veloria_lat . ',' . $veloria_lng . '&output=embed&z=' . $veloria_zoom;
} else {
    $veloria_address = isset($veloria_settings['address']) ? $veloria_settings['address'] : 'London, UK';
    $veloria_zoom   = isset($veloria_settings['zoom']['size']) ? (int) $veloria_settings['zoom']['size'] : 14;
    $veloria_map_url = 'https://www.google.com/maps?q=' . rawurlencode($veloria_address) . '&output=embed&z=' . $veloria_zoom;
}

$veloria_show_address_block = !empty($veloria_settings['show_address_block']);
$veloria_address_position   = isset($veloria_settings['address_position']) && $veloria_settings['address_position'] === 'top' ? 'top' : 'bottom';
$veloria_position_class     = $veloria_address_position === 'top' ? 'is-top' : 'is-bottom';
?>
<div class="veloria-advanced-google-map">
    <?php if ($veloria_show_address_block && $veloria_address_position === 'top') : ?>
        <div class="veloria-advanced-google-map__address <?php echo esc_attr($veloria_position_class); ?>">
            <?php if (!empty($veloria_settings['address_title'])) : ?>
                <div class="veloria-advanced-google-map__address-title"><?php echo esc_html($veloria_settings['address_title']); ?></div>
            <?php endif; ?>
            <?php if (!empty($veloria_settings['address_description'])) : ?>
                <div class="veloria-advanced-google-map__address-desc"><?php echo wp_kses_post($veloria_settings['address_description']); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($veloria_map_url !== '') : ?>
        <iframe
            class="veloria-advanced-google-map__frame"
            src="<?php echo esc_url($veloria_map_url); ?>"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
    <?php else : ?>
        <div class="veloria-advanced-google-map__placeholder"><?php esc_html_e('Set map source in widget settings.', 'veloria'); ?></div>
    <?php endif; ?>

    <?php if ($veloria_show_address_block && $veloria_address_position === 'bottom') : ?>
        <div class="veloria-advanced-google-map__address <?php echo esc_attr($veloria_position_class); ?>">
            <?php if (!empty($veloria_settings['address_title'])) : ?>
                <div class="veloria-advanced-google-map__address-title"><?php echo esc_html($veloria_settings['address_title']); ?></div>
            <?php endif; ?>
            <?php if (!empty($veloria_settings['address_description'])) : ?>
                <div class="veloria-advanced-google-map__address-desc"><?php echo wp_kses_post($veloria_settings['address_description']); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
