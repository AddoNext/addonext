<?php
defined('ABSPATH') || exit;

$addonext_settings   = $this->get_settings_for_display();
$addonext_map_source = isset($addonext_settings['map_source']) ? $addonext_settings['map_source'] : 'address';
$addonext_map_url    = '';

if ($addonext_map_source === 'url') {
    $addonext_map_url = isset($addonext_settings['map_url']['url']) ? $addonext_settings['map_url']['url'] : '';
} elseif ($addonext_map_source === 'coordinates') {
    $addonext_lat  = isset($addonext_settings['latitude']) ? (float) $addonext_settings['latitude'] : 51.5074;
    $addonext_lng  = isset($addonext_settings['longitude']) ? (float) $addonext_settings['longitude'] : -0.1278;
    $addonext_zoom = isset($addonext_settings['zoom']['size']) ? (int) $addonext_settings['zoom']['size'] : 14;
    $addonext_map_url = 'https://www.google.com/maps?q=' . $addonext_lat . ',' . $addonext_lng . '&output=embed&z=' . $addonext_zoom;
} else {
    $addonext_address = isset($addonext_settings['address']) ? $addonext_settings['address'] : 'London, UK';
    $addonext_zoom   = isset($addonext_settings['zoom']['size']) ? (int) $addonext_settings['zoom']['size'] : 14;
    $addonext_map_url = 'https://www.google.com/maps?q=' . rawurlencode($addonext_address) . '&output=embed&z=' . $addonext_zoom;
}

$addonext_show_address_block = !empty($addonext_settings['show_address_block']);
$addonext_address_position   = isset($addonext_settings['address_position']) && $addonext_settings['address_position'] === 'top' ? 'top' : 'bottom';
$addonext_position_class     = $addonext_address_position === 'top' ? 'is-top' : 'is-bottom';
?>
<div class="addonext-advanced-google-map">
    <?php if ($addonext_show_address_block && $addonext_address_position === 'top') : ?>
        <div class="addonext-advanced-google-map__address <?php echo esc_attr($addonext_position_class); ?>">
            <?php if (!empty($addonext_settings['address_title'])) : ?>
                <div class="addonext-advanced-google-map__address-title"><?php echo esc_html($addonext_settings['address_title']); ?></div>
            <?php endif; ?>
            <?php if (!empty($addonext_settings['address_description'])) : ?>
                <div class="addonext-advanced-google-map__address-desc"><?php echo wp_kses_post($addonext_settings['address_description']); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($addonext_map_url !== '') : ?>
        <iframe
            class="addonext-advanced-google-map__frame"
            src="<?php echo esc_url($addonext_map_url); ?>"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
    <?php else : ?>
        <div class="addonext-advanced-google-map__placeholder"><?php esc_html_e('Set map source in widget settings.', 'addonext'); ?></div>
    <?php endif; ?>

    <?php if ($addonext_show_address_block && $addonext_address_position === 'bottom') : ?>
        <div class="addonext-advanced-google-map__address <?php echo esc_attr($addonext_position_class); ?>">
            <?php if (!empty($addonext_settings['address_title'])) : ?>
                <div class="addonext-advanced-google-map__address-title"><?php echo esc_html($addonext_settings['address_title']); ?></div>
            <?php endif; ?>
            <?php if (!empty($addonext_settings['address_description'])) : ?>
                <div class="addonext-advanced-google-map__address-desc"><?php echo wp_kses_post($addonext_settings['address_description']); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
