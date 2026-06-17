<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_items = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];
$veloria_percent_use_bar_color = !empty($veloria_settings['percent_color_override']) && $veloria_settings['percent_color_override'] === 'yes';

if (empty($veloria_items)) {
    return;
}
?>
<div class="veloria-progress-bar">
    <div class="veloria-progress-bar__list">
        <?php foreach ($veloria_items as $veloria_item) :
            $veloria_title = isset($veloria_item['title']) ? $veloria_item['title'] : '';
            if (isset($veloria_item['percent']['size']) && is_numeric($veloria_item['percent']['size'])) {
                $veloria_percent = max(0, min(100, (int) $veloria_item['percent']['size']));
            } elseif (isset($veloria_item['percent']) && is_numeric($veloria_item['percent'])) {
                $veloria_percent = max(0, min(100, (int) $veloria_item['percent']));
            } else {
                $veloria_percent = 0;
            }
            $veloria_bar_color = !empty($veloria_item['bar_color']) ? $veloria_item['bar_color'] : '#4285F4';
            $veloria_percent_style = $veloria_percent_use_bar_color ? ' color: ' . esc_attr($veloria_bar_color) . ';' : '';
            $veloria_fill_style = 'width: ' . $veloria_percent . '% !important; background-color: ' . esc_attr($veloria_bar_color) . ';';
        ?>
            <div class="veloria-progress-bar__row">
                <div class="veloria-progress-bar__header">
                    <span class="veloria-progress-bar__title"><?php echo esc_html($veloria_title); ?></span>
                    <span class="veloria-progress-bar__percent" style="<?php echo esc_attr(trim($veloria_percent_style)); ?>"><?php echo (int) $veloria_percent; ?>%</span>
                </div>
                <div class="veloria-progress-bar__track">
                    <div class="veloria-progress-bar__fill" style="<?php echo esc_attr($veloria_fill_style); ?>"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
