<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_items    = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];

if (empty($veloria_items)) {
    return;
}
?>
<div class="veloria-timeline">
    <?php foreach ($veloria_items as $veloria_index => $veloria_item) :
        $veloria_item_id   = $veloria_item['_id'] ?? '';
        $veloria_year      = isset($veloria_item['year']) ? $veloria_item['year'] : '';
        $veloria_title     = isset($veloria_item['title']) ? $veloria_item['title'] : '';
        $veloria_subtitle  = isset($veloria_item['subtitle']) ? $veloria_item['subtitle'] : '';
        $veloria_dot_color = isset($veloria_item['dot_color']) ? $veloria_item['dot_color'] : '#4A90E2';
        $veloria_is_last   = $veloria_index === count($veloria_items) - 1;
    ?>
        <div class="veloria-timeline__item elementor-repeater-item-<?php echo esc_attr($veloria_item_id); ?>">
            <div class="veloria-timeline__track" style="--timeline-dot: <?php echo esc_attr($veloria_dot_color); ?>;">
                <span class="veloria-timeline__dot"></span>
                <?php if (!$veloria_is_last) : ?>
                    <span class="veloria-timeline__line"></span>
                <?php endif; ?>
            </div>
            <div class="veloria-timeline__content">
                <?php if ($veloria_year !== '') : ?>
                    <span class="veloria-timeline__year" style="color: <?php echo esc_attr($veloria_dot_color); ?>;"><?php echo esc_html($veloria_year); ?></span>
                <?php endif; ?>
                <?php if ($veloria_title !== '') : ?>
                    <h4 class="veloria-timeline__title"><?php echo esc_html($veloria_title); ?></h4>
                <?php endif; ?>
                <?php if ($veloria_subtitle !== '') : ?>
                    <p class="veloria-timeline__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
