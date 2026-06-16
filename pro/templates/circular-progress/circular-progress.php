<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$items = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];

if (empty($items)) {
    return;
}

$radius = 16;
$circumference = 2 * M_PI * $radius;
?>
<div class="veloria-circular-progress">
    <div class="veloria-circular-progress__list">
        <?php foreach ($items as $item) :
            $percent = isset($item['percent']['size']) ? max(0, min(100, (int) $item['percent']['size'])) : 0;
            $bar_color = !empty($item['bar_color']) ? $item['bar_color'] : '#4285F4';
            $title = isset($item['title']) ? $item['title'] : '';
            $subtitle = isset($item['subtitle']) ? $item['subtitle'] : '';
            $dash = ($percent / 100) * $circumference;
        ?>
            <div class="veloria-circular-progress__item">
                <div class="veloria-circular-progress__circle-wrap">
                    <svg class="veloria-circular-progress__svg" viewBox="0 0 36 36">
                        <circle class="veloria-circular-progress__bg" cx="18" cy="18" r="<?php echo (int) $radius; ?>" fill="none"/>
                        <circle class="veloria-circular-progress__fill" cx="18" cy="18" r="<?php echo (int) $radius; ?>" fill="none" stroke-dasharray="<?php echo esc_attr($dash . ', ' . $circumference); ?>" transform="rotate(-90 18 18)" style="stroke: <?php echo esc_attr($bar_color); ?>;"/>
                    </svg>
                    <span class="veloria-circular-progress__value"><?php echo (int) $percent; ?>%</span>
                </div>
                <?php if ($title !== '') : ?><span class="veloria-circular-progress__title"><?php echo esc_html($title); ?></span><?php endif; ?>
                <?php if ($subtitle !== '') : ?><span class="veloria-circular-progress__subtitle"><?php echo esc_html($subtitle); ?></span><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
    }
}
