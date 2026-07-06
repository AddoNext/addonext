<?php
defined('ABSPATH') || exit;

$addonext_settings      = $this->get_settings_for_display();
$addonext_trigger_label = $addonext_settings['trigger_text'] ?? __('Open panel', 'addonext');
$addonext_trigger_icon  = $addonext_settings['trigger_icon'] ?? [];
$addonext_panel_title   = $addonext_settings['panel_title'] ?? '';
$addonext_content       = $addonext_settings['panel_content'] ?? '';
$addonext_content_source = $addonext_settings['content_source'] ?? 'text';
$addonext_template_id    = isset($addonext_settings['panel_template_id']) ? (int) $addonext_settings['panel_template_id'] : 0;
$addonext_position_raw  = $addonext_settings['panel_position'] ?? 'right';
$addonext_allowed_positions = ['right', 'left', 'top', 'bottom', 'center'];
$addonext_position      = in_array($addonext_position_raw, $addonext_allowed_positions, true) ? $addonext_position_raw : 'right';
$addonext_close_overlay = !empty($addonext_settings['close_on_overlay']) && $addonext_settings['close_on_overlay'] === 'yes';

$addonext_animation_raw = $addonext_settings['panel_animation'] ?? 'slide';
$addonext_allowed_animations = ['slide', 'slide_fade', 'fade', 'zoom'];
$addonext_animation = in_array($addonext_animation_raw, $addonext_allowed_animations, true) ? $addonext_animation_raw : 'slide';
$addonext_animation_class = 'addonext-off-canvas--anim-' . str_replace('_', '-', $addonext_animation);

$addonext_header_layout_raw = $addonext_settings['panel_header_layout'] ?? 'title_and_close';
$addonext_header_layout = $addonext_header_layout_raw === 'close_only' ? 'close_only' : 'title_and_close';

$addonext_wrapper_classes = [
    'addonext-off-canvas',
    'addonext-off-canvas--' . $addonext_position,
    $addonext_animation_class,
];
?>
<div class="<?php echo esc_attr(implode(' ', $addonext_wrapper_classes)); ?>" data-close-on-overlay="<?php echo $addonext_close_overlay ? 'yes' : 'no'; ?>">
    <button type="button" class="addonext-off-canvas__trigger" aria-expanded="false" aria-controls="<?php echo esc_attr($this->get_id() . '-panel'); ?>">
        <?php if (!empty($addonext_trigger_icon['value'])) : ?>
            <span class="addonext-off-canvas__trigger-icon" aria-hidden="true">
                <?php \Elementor\Icons_Manager::render_icon($addonext_trigger_icon, ['aria-hidden' => 'true']); ?>
            </span>
        <?php endif; ?>
        <span class="addonext-off-canvas__trigger-label"><?php echo esc_html($addonext_trigger_label); ?></span>
    </button>
    <div class="addonext-off-canvas__overlay" aria-hidden="true"></div>
    <div id="<?php echo esc_attr($this->get_id() . '-panel'); ?>" class="addonext-off-canvas__panel" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="addonext-off-canvas__panel-inner<?php echo $addonext_header_layout === 'close_only' ? ' addonext-off-canvas__panel-inner--close-only' : ''; ?>">
            <div class="addonext-off-canvas__panel-header<?php echo $addonext_header_layout === 'close_only' ? ' addonext-off-canvas__panel-header--close-only' : ''; ?>">
                <?php if ($addonext_header_layout === 'title_and_close' && $addonext_panel_title !== '') : ?>
                    <h3 class="addonext-off-canvas__panel-title"><?php echo esc_html($addonext_panel_title); ?></h3>
                <?php endif; ?>
                <button type="button" class="addonext-off-canvas__close" aria-label="<?php echo esc_attr__('Close panel', 'addonext'); ?>">×</button>
            </div>
            <div class="addonext-off-canvas__panel-body">
                <?php
                if ($addonext_content_source === 'template' && $addonext_template_id > 0) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor library template HTML.
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($addonext_template_id);
                } else {
                    echo wp_kses_post($addonext_content);
                }
                ?>
            </div>
        </div>
    </div>
</div>
