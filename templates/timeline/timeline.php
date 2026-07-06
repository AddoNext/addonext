<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_items    = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];

if (empty($addonext_items)) {
    return;
}
?>
<div class="addonext-timeline">
    <?php foreach ($addonext_items as $addonext_index => $addonext_item) :
        $addonext_item_id   = $addonext_item['_id'] ?? '';
        $addonext_year      = isset($addonext_item['year']) ? $addonext_item['year'] : '';
        $addonext_title     = isset($addonext_item['title']) ? $addonext_item['title'] : '';
        $addonext_subtitle  = isset($addonext_item['subtitle']) ? $addonext_item['subtitle'] : '';
        $addonext_dot_color = isset($addonext_item['dot_color']) ? $addonext_item['dot_color'] : '#4A90E2';
        $addonext_is_last   = $addonext_index === count($addonext_items) - 1;
    ?>
        <div class="addonext-timeline__item elementor-repeater-item-<?php echo esc_attr($addonext_item_id); ?>">
            <div class="addonext-timeline__track" style="--timeline-dot: <?php echo esc_attr($addonext_dot_color); ?>;">
                <span class="addonext-timeline__dot"></span>
                <?php if (!$addonext_is_last) : ?>
                    <span class="addonext-timeline__line"></span>
                <?php endif; ?>
            </div>
            <div class="addonext-timeline__content">
                <?php if ($addonext_year !== '') : ?>
                    <span class="addonext-timeline__year" style="color: <?php echo esc_attr($addonext_dot_color); ?>;"><?php echo esc_html($addonext_year); ?></span>
                <?php endif; ?>
                <?php if ($addonext_title !== '') : ?>
                    <h4 class="addonext-timeline__title"><?php echo esc_html($addonext_title); ?></h4>
                <?php endif; ?>
                <?php if ($addonext_subtitle !== '') : ?>
                    <p class="addonext-timeline__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
