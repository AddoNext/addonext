<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_items = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];

if (empty($addonext_items)) {
    return;
}

$addonext_radius = 16;
$addonext_circumference = 2 * M_PI * $addonext_radius;
?>
<div class="addonext-circular-progress">
    <div class="addonext-circular-progress__list">
        <?php foreach ($addonext_items as $addonext_item) :
            $addonext_percent = isset($addonext_item['percent']['size']) ? max(0, min(100, (int) $addonext_item['percent']['size'])) : 0;
            $addonext_bar_color = !empty($addonext_item['bar_color']) ? $addonext_item['bar_color'] : '#4285F4';
            $addonext_title = isset($addonext_item['title']) ? $addonext_item['title'] : '';
            $addonext_subtitle = isset($addonext_item['subtitle']) ? $addonext_item['subtitle'] : '';
            $addonext_dash = ($addonext_percent / 100) * $addonext_circumference;
        ?>
            <div class="addonext-circular-progress__item">
                <div class="addonext-circular-progress__circle-wrap">
                    <svg class="addonext-circular-progress__svg" viewBox="0 0 36 36">
                        <circle class="addonext-circular-progress__bg" cx="18" cy="18" r="<?php echo (int) $addonext_radius; ?>" fill="none"/>
                        <circle class="addonext-circular-progress__fill" cx="18" cy="18" r="<?php echo (int) $addonext_radius; ?>" fill="none" stroke-dasharray="<?php echo esc_attr($addonext_dash . ', ' . $addonext_circumference); ?>" transform="rotate(-90 18 18)" style="stroke: <?php echo esc_attr($addonext_bar_color); ?>;"/>
                    </svg>
                    <span class="addonext-circular-progress__value"><?php echo (int) $addonext_percent; ?>%</span>
                </div>
                <?php if ($addonext_title !== '') : ?><span class="addonext-circular-progress__title"><?php echo esc_html($addonext_title); ?></span><?php endif; ?>
                <?php if ($addonext_subtitle !== '') : ?><span class="addonext-circular-progress__subtitle"><?php echo esc_html($addonext_subtitle); ?></span><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
    }
}
