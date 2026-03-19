<?php
defined('ABSPATH') || exit;

$settings      = $this->get_settings_for_display();
$trigger_label = $settings['trigger_text'] ?? __('Open panel', 'elementrify');
$trigger_icon  = $settings['trigger_icon'] ?? [];
$panel_title   = $settings['panel_title'] ?? '';
$content       = $settings['panel_content'] ?? '';
$content_source = $settings['content_source'] ?? 'text';
$template_id    = isset($settings['panel_template_id']) ? (int) $settings['panel_template_id'] : 0;
$position_raw  = $settings['panel_position'] ?? 'right';
$allowed_positions = ['right', 'left', 'top', 'bottom', 'center'];
$position      = in_array($position_raw, $allowed_positions, true) ? $position_raw : 'right';
$close_overlay = !empty($settings['close_on_overlay']) && $settings['close_on_overlay'] === 'yes';

$animation_raw = $settings['panel_animation'] ?? 'slide';
$allowed_animations = ['slide', 'slide_fade', 'fade', 'zoom'];
$animation = in_array($animation_raw, $allowed_animations, true) ? $animation_raw : 'slide';
$animation_class = 'elementrify-off-canvas--anim-' . str_replace('_', '-', $animation);

$header_layout_raw = $settings['panel_header_layout'] ?? 'title_and_close';
$header_layout = $header_layout_raw === 'close_only' ? 'close_only' : 'title_and_close';

$wrapper_classes = [
    'elementrify-off-canvas',
    'elementrify-off-canvas--' . $position,
    $animation_class,
];
?>
<div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" data-close-on-overlay="<?php echo $close_overlay ? 'yes' : 'no'; ?>">
    <button type="button" class="elementrify-off-canvas__trigger" aria-expanded="false" aria-controls="<?php echo esc_attr($this->get_id() . '-panel'); ?>">
        <?php if (!empty($trigger_icon['value'])) : ?>
            <span class="elementrify-off-canvas__trigger-icon" aria-hidden="true">
                <?php \Elementor\Icons_Manager::render_icon($trigger_icon, ['aria-hidden' => 'true']); ?>
            </span>
        <?php endif; ?>
        <span class="elementrify-off-canvas__trigger-label"><?php echo esc_html($trigger_label); ?></span>
    </button>
    <div class="elementrify-off-canvas__overlay" aria-hidden="true"></div>
    <div id="<?php echo esc_attr($this->get_id() . '-panel'); ?>" class="elementrify-off-canvas__panel" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="elementrify-off-canvas__panel-inner<?php echo $header_layout === 'close_only' ? ' elementrify-off-canvas__panel-inner--close-only' : ''; ?>">
            <div class="elementrify-off-canvas__panel-header<?php echo $header_layout === 'close_only' ? ' elementrify-off-canvas__panel-header--close-only' : ''; ?>">
                <?php if ($header_layout === 'title_and_close' && $panel_title !== '') : ?>
                    <h3 class="elementrify-off-canvas__panel-title"><?php echo esc_html($panel_title); ?></h3>
                <?php endif; ?>
                <button type="button" class="elementrify-off-canvas__close" aria-label="<?php echo esc_attr__('Close panel', 'elementrify'); ?>">×</button>
            </div>
            <div class="elementrify-off-canvas__panel-body">
                <?php
                if ($content_source === 'template' && $template_id > 0) {
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id);
                } else {
                    echo wp_kses_post($content);
                }
                ?>
            </div>
        </div>
    </div>
</div>
