<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_items = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];
$addonext_percent_use_bar_color = !empty($addonext_settings['percent_color_override']) && $addonext_settings['percent_color_override'] === 'yes';

if (empty($addonext_items)) {
    return;
}
?>
<div class="addonext-progress-bar">
    <div class="addonext-progress-bar__list">
        <?php foreach ($addonext_items as $addonext_item) :
            $addonext_title = isset($addonext_item['title']) ? $addonext_item['title'] : '';
            if (isset($addonext_item['percent']['size']) && is_numeric($addonext_item['percent']['size'])) {
                $addonext_percent = max(0, min(100, (int) $addonext_item['percent']['size']));
            } elseif (isset($addonext_item['percent']) && is_numeric($addonext_item['percent'])) {
                $addonext_percent = max(0, min(100, (int) $addonext_item['percent']));
            } else {
                $addonext_percent = 0;
            }
            $addonext_bar_color = !empty($addonext_item['bar_color']) ? $addonext_item['bar_color'] : '#4285F4';
            $addonext_percent_style = $addonext_percent_use_bar_color ? ' color: ' . esc_attr($addonext_bar_color) . ';' : '';
            $addonext_fill_style = 'width: ' . $addonext_percent . '% !important; background-color: ' . esc_attr($addonext_bar_color) . ';';
        ?>
            <div class="addonext-progress-bar__row">
                <div class="addonext-progress-bar__header">
                    <span class="addonext-progress-bar__title"><?php echo esc_html($addonext_title); ?></span>
                    <span class="addonext-progress-bar__percent" style="<?php echo esc_attr(trim($addonext_percent_style)); ?>"><?php echo (int) $addonext_percent; ?>%</span>
                </div>
                <div class="addonext-progress-bar__track">
                    <div class="addonext-progress-bar__fill" style="<?php echo esc_attr($addonext_fill_style); ?>"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
