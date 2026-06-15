<?php
defined('ABSPATH') || exit;

$settings   = $this->get_settings_for_display();
$map_source = isset($settings['map_source']) ? $settings['map_source'] : 'address';
$map_url    = '';

if ($map_source === 'url') {
    $map_url = isset($settings['map_url']['url']) ? $settings['map_url']['url'] : '';
} elseif ($map_source === 'coordinates') {
    $lat  = isset($settings['latitude']) ? (float) $settings['latitude'] : 51.5074;
    $lng  = isset($settings['longitude']) ? (float) $settings['longitude'] : -0.1278;
    $zoom = isset($settings['zoom']['size']) ? (int) $settings['zoom']['size'] : 14;
    $map_url = 'https://www.google.com/maps?q=' . $lat . ',' . $lng . '&output=embed&z=' . $zoom;
} else {
    $address = isset($settings['address']) ? $settings['address'] : 'London, UK';
    $zoom   = isset($settings['zoom']['size']) ? (int) $settings['zoom']['size'] : 14;
    $map_url = 'https://www.google.com/maps?q=' . rawurlencode($address) . '&output=embed&z=' . $zoom;
}

$show_address_block = !empty($settings['show_address_block']);
$address_position   = isset($settings['address_position']) && $settings['address_position'] === 'top' ? 'top' : 'bottom';
$position_class     = $address_position === 'top' ? 'is-top' : 'is-bottom';
?>
<div class="veloria-advanced-google-map">
    <?php if ($show_address_block && $address_position === 'top') : ?>
        <div class="veloria-advanced-google-map__address <?php echo esc_attr($position_class); ?>">
            <?php if (!empty($settings['address_title'])) : ?>
                <div class="veloria-advanced-google-map__address-title"><?php echo esc_html($settings['address_title']); ?></div>
            <?php endif; ?>
            <?php if (!empty($settings['address_description'])) : ?>
                <div class="veloria-advanced-google-map__address-desc"><?php echo wp_kses_post($settings['address_description']); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($map_url !== '') : ?>
        <iframe
            class="veloria-advanced-google-map__frame"
            src="<?php echo esc_url($map_url); ?>"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
    <?php else : ?>
        <div class="veloria-advanced-google-map__placeholder"><?php esc_html_e('Set map source in widget settings.', 'veloria'); ?></div>
    <?php endif; ?>

    <?php if ($show_address_block && $address_position === 'bottom') : ?>
        <div class="veloria-advanced-google-map__address <?php echo esc_attr($position_class); ?>">
            <?php if (!empty($settings['address_title'])) : ?>
                <div class="veloria-advanced-google-map__address-title"><?php echo esc_html($settings['address_title']); ?></div>
            <?php endif; ?>
            <?php if (!empty($settings['address_description'])) : ?>
                <div class="veloria-advanced-google-map__address-desc"><?php echo wp_kses_post($settings['address_description']); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
