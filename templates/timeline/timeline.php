<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$items    = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];

if (empty($items)) {
    return;
}
?>
<div class="veloria-timeline">
    <?php foreach ($items as $index => $item) :
        $item_id   = $item['_id'] ?? '';
        $year      = isset($item['year']) ? $item['year'] : '';
        $title     = isset($item['title']) ? $item['title'] : '';
        $subtitle  = isset($item['subtitle']) ? $item['subtitle'] : '';
        $dot_color = isset($item['dot_color']) ? $item['dot_color'] : '#4A90E2';
        $is_last   = $index === count($items) - 1;
    ?>
        <div class="veloria-timeline__item elementor-repeater-item-<?php echo esc_attr($item_id); ?>">
            <div class="veloria-timeline__track" style="--timeline-dot: <?php echo esc_attr($dot_color); ?>;">
                <span class="veloria-timeline__dot"></span>
                <?php if (!$is_last) : ?>
                    <span class="veloria-timeline__line"></span>
                <?php endif; ?>
            </div>
            <div class="veloria-timeline__content">
                <?php if ($year !== '') : ?>
                    <span class="veloria-timeline__year" style="color: <?php echo esc_attr($dot_color); ?>;"><?php echo esc_html($year); ?></span>
                <?php endif; ?>
                <?php if ($title !== '') : ?>
                    <h4 class="veloria-timeline__title"><?php echo esc_html($title); ?></h4>
                <?php endif; ?>
                <?php if ($subtitle !== '') : ?>
                    <p class="veloria-timeline__subtitle"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
