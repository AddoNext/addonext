<?php
defined('ABSPATH') || exit;

$veloria_settings       = $this->get_settings_for_display();
$veloria_badge_text    = isset($veloria_settings['badge_text']) ? trim($veloria_settings['badge_text']) : '';
$veloria_secondary_first = (isset($veloria_settings['default_active']) && $veloria_settings['default_active'] === 'secondary');

$this->add_render_attribute('wrapper', 'class', 'veloria-content-toggle');

$this->add_render_attribute('primary_label', 'class', 'veloria-content-toggle__label' . ($veloria_secondary_first ? '' : ' is-active'));
$this->add_render_attribute('primary_label', 'data-toggle', '0');
$this->add_inline_editing_attributes('primary_label', 'none');

$this->add_render_attribute('secondary_label', 'class', 'veloria-content-toggle__label' . ($veloria_secondary_first ? ' is-active' : ''));
$this->add_render_attribute('secondary_label', 'data-toggle', '1');
$this->add_inline_editing_attributes('secondary_label', 'none');

$this->add_render_attribute('primary_content', 'class', 'veloria-content-toggle__panel' . ($veloria_secondary_first ? '' : ' is-active'));
$this->add_render_attribute('primary_content', 'data-toggle', '0');
$this->add_inline_editing_attributes('primary_content', 'advanced');

$this->add_render_attribute('secondary_content', 'class', 'veloria-content-toggle__panel' . ($veloria_secondary_first ? ' is-active' : ''));
$this->add_render_attribute('secondary_content', 'data-toggle', '1');
$this->add_inline_editing_attributes('secondary_content', 'advanced');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="veloria-content-toggle__toggle-row">
        <div class="veloria-content-toggle__labels">
            <span <?php $this->print_render_attribute_string('primary_label'); ?>><?php echo esc_html($veloria_settings['primary_label'] ?? ''); ?></span>
            <span <?php $this->print_render_attribute_string('secondary_label'); ?>><?php echo esc_html($veloria_settings['secondary_label'] ?? ''); ?></span>
        </div>
        <?php if ($veloria_badge_text !== '') : ?>
            <span class="veloria-content-toggle__badge"><?php echo esc_html($veloria_badge_text); ?></span>
        <?php endif; ?>
    </div>
    <div class="veloria-content-toggle__panels">
        <div <?php $this->print_render_attribute_string('primary_content'); ?>><?php echo wp_kses_post($veloria_settings['primary_content'] ?? ''); ?></div>
        <div <?php $this->print_render_attribute_string('secondary_content'); ?>><?php echo wp_kses_post($veloria_settings['secondary_content'] ?? ''); ?></div>
    </div>
</div>
