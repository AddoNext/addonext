<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$items = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];
$percent_use_bar_color = !empty($settings['percent_color_override']) && $settings['percent_color_override'] === 'yes';

if (empty($items)) {
    return;
}
?>
<div class="veloria-progress-bar">
    <div class="veloria-progress-bar__list">
        <?php foreach ($items as $item) :
            $title = isset($item['title']) ? $item['title'] : '';
            if (isset($item['percent']['size']) && is_numeric($item['percent']['size'])) {
                $percent = max(0, min(100, (int) $item['percent']['size']));
            } elseif (isset($item['percent']) && is_numeric($item['percent'])) {
                $percent = max(0, min(100, (int) $item['percent']));
            } else {
                $percent = 0;
            }
            $bar_color = !empty($item['bar_color']) ? $item['bar_color'] : '#4285F4';
            $percent_style = $percent_use_bar_color ? ' color: ' . esc_attr($bar_color) . ';' : '';
            $fill_style = 'width: ' . $percent . '% !important; background-color: ' . esc_attr($bar_color) . ';';
        ?>
            <div class="veloria-progress-bar__row">
                <div class="veloria-progress-bar__header">
                    <span class="veloria-progress-bar__title"><?php echo esc_html($title); ?></span>
                    <span class="veloria-progress-bar__percent" style="<?php echo esc_attr(trim($percent_style)); ?>"><?php echo (int) $percent; ?>%</span>
                </div>
                <div class="veloria-progress-bar__track">
                    <div class="veloria-progress-bar__fill" style="<?php echo esc_attr($fill_style); ?>"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
