<?php
defined('ABSPATH') || exit;

$veloria_settings      = $this->get_settings_for_display();
$veloria_trigger_label = $veloria_settings['trigger_text'] ?? __('Open panel', 'veloria');
$veloria_trigger_icon  = $veloria_settings['trigger_icon'] ?? [];
$veloria_panel_title   = $veloria_settings['panel_title'] ?? '';
$veloria_content       = $veloria_settings['panel_content'] ?? '';
$veloria_content_source = $veloria_settings['content_source'] ?? 'text';
$veloria_template_id    = isset($veloria_settings['panel_template_id']) ? (int) $veloria_settings['panel_template_id'] : 0;
$veloria_position_raw  = $veloria_settings['panel_position'] ?? 'right';
$veloria_allowed_positions = ['right', 'left', 'top', 'bottom', 'center'];
$veloria_position      = in_array($veloria_position_raw, $veloria_allowed_positions, true) ? $veloria_position_raw : 'right';
$veloria_close_overlay = !empty($veloria_settings['close_on_overlay']) && $veloria_settings['close_on_overlay'] === 'yes';

$veloria_animation_raw = $veloria_settings['panel_animation'] ?? 'slide';
$veloria_allowed_animations = ['slide', 'slide_fade', 'fade', 'zoom'];
$veloria_animation = in_array($veloria_animation_raw, $veloria_allowed_animations, true) ? $veloria_animation_raw : 'slide';
$veloria_animation_class = 'veloria-off-canvas--anim-' . str_replace('_', '-', $veloria_animation);

$veloria_header_layout_raw = $veloria_settings['panel_header_layout'] ?? 'title_and_close';
$veloria_header_layout = $veloria_header_layout_raw === 'close_only' ? 'close_only' : 'title_and_close';

$veloria_wrapper_classes = [
    'veloria-off-canvas',
    'veloria-off-canvas--' . $veloria_position,
    $veloria_animation_class,
];
?>
<div class="<?php echo esc_attr(implode(' ', $veloria_wrapper_classes)); ?>" data-close-on-overlay="<?php echo $veloria_close_overlay ? 'yes' : 'no'; ?>">
    <button type="button" class="veloria-off-canvas__trigger" aria-expanded="false" aria-controls="<?php echo esc_attr($this->get_id() . '-panel'); ?>">
        <?php if (!empty($veloria_trigger_icon['value'])) : ?>
            <span class="veloria-off-canvas__trigger-icon" aria-hidden="true">
                <?php \Elementor\Icons_Manager::render_icon($veloria_trigger_icon, ['aria-hidden' => 'true']); ?>
            </span>
        <?php endif; ?>
        <span class="veloria-off-canvas__trigger-label"><?php echo esc_html($veloria_trigger_label); ?></span>
    </button>
    <div class="veloria-off-canvas__overlay" aria-hidden="true"></div>
    <div id="<?php echo esc_attr($this->get_id() . '-panel'); ?>" class="veloria-off-canvas__panel" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="veloria-off-canvas__panel-inner<?php echo $veloria_header_layout === 'close_only' ? ' veloria-off-canvas__panel-inner--close-only' : ''; ?>">
            <div class="veloria-off-canvas__panel-header<?php echo $veloria_header_layout === 'close_only' ? ' veloria-off-canvas__panel-header--close-only' : ''; ?>">
                <?php if ($veloria_header_layout === 'title_and_close' && $veloria_panel_title !== '') : ?>
                    <h3 class="veloria-off-canvas__panel-title"><?php echo esc_html($veloria_panel_title); ?></h3>
                <?php endif; ?>
                <button type="button" class="veloria-off-canvas__close" aria-label="<?php echo esc_attr__('Close panel', 'veloria'); ?>">×</button>
            </div>
            <div class="veloria-off-canvas__panel-body">
                <?php
                if ($veloria_content_source === 'template' && $veloria_template_id > 0) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor library template HTML.
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($veloria_template_id);
                } else {
                    echo wp_kses_post($veloria_content);
                }
                ?>
            </div>
        </div>
    </div>
</div>
