<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_items = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];

if (empty($veloria_items)) {
    return;
}

$veloria_radius = 16;
$veloria_circumference = 2 * M_PI * $veloria_radius;
?>
<div class="veloria-circular-progress">
    <div class="veloria-circular-progress__list">
        <?php foreach ($veloria_items as $veloria_item) :
            $veloria_percent = isset($veloria_item['percent']['size']) ? max(0, min(100, (int) $veloria_item['percent']['size'])) : 0;
            $veloria_bar_color = !empty($veloria_item['bar_color']) ? $veloria_item['bar_color'] : '#4285F4';
            $veloria_title = isset($veloria_item['title']) ? $veloria_item['title'] : '';
            $veloria_subtitle = isset($veloria_item['subtitle']) ? $veloria_item['subtitle'] : '';
            $veloria_dash = ($veloria_percent / 100) * $veloria_circumference;
        ?>
            <div class="veloria-circular-progress__item">
                <div class="veloria-circular-progress__circle-wrap">
                    <svg class="veloria-circular-progress__svg" viewBox="0 0 36 36">
                        <circle class="veloria-circular-progress__bg" cx="18" cy="18" r="<?php echo (int) $veloria_radius; ?>" fill="none"/>
                        <circle class="veloria-circular-progress__fill" cx="18" cy="18" r="<?php echo (int) $veloria_radius; ?>" fill="none" stroke-dasharray="<?php echo esc_attr($veloria_dash . ', ' . $veloria_circumference); ?>" transform="rotate(-90 18 18)" style="stroke: <?php echo esc_attr($veloria_bar_color); ?>;"/>
                    </svg>
                    <span class="veloria-circular-progress__value"><?php echo (int) $veloria_percent; ?>%</span>
                </div>
                <?php if ($veloria_title !== '') : ?><span class="veloria-circular-progress__title"><?php echo esc_html($veloria_title); ?></span><?php endif; ?>
                <?php if ($veloria_subtitle !== '') : ?><span class="veloria-circular-progress__subtitle"><?php echo esc_html($veloria_subtitle); ?></span><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
    }
}
