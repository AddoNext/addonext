<?php
defined('ABSPATH') || exit;

$settings       = $this->get_settings_for_display();
$badge_text    = isset($settings['badge_text']) ? trim($settings['badge_text']) : '';
$secondary_first = (isset($settings['default_active']) && $settings['default_active'] === 'secondary');

$this->add_render_attribute('wrapper', 'class', 'elementrify-content-toggle');

$this->add_render_attribute('primary_label', 'class', 'elementrify-content-toggle__label' . ($secondary_first ? '' : ' is-active'));
$this->add_render_attribute('primary_label', 'data-toggle', '0');
$this->add_inline_editing_attributes('primary_label', 'none');

$this->add_render_attribute('secondary_label', 'class', 'elementrify-content-toggle__label' . ($secondary_first ? ' is-active' : ''));
$this->add_render_attribute('secondary_label', 'data-toggle', '1');
$this->add_inline_editing_attributes('secondary_label', 'none');

$this->add_render_attribute('primary_content', 'class', 'elementrify-content-toggle__panel' . ($secondary_first ? '' : ' is-active'));
$this->add_render_attribute('primary_content', 'data-toggle', '0');
$this->add_inline_editing_attributes('primary_content', 'advanced');

$this->add_render_attribute('secondary_content', 'class', 'elementrify-content-toggle__panel' . ($secondary_first ? ' is-active' : ''));
$this->add_render_attribute('secondary_content', 'data-toggle', '1');
$this->add_inline_editing_attributes('secondary_content', 'advanced');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="elementrify-content-toggle__toggle-row">
        <div class="elementrify-content-toggle__labels">
            <span <?php $this->print_render_attribute_string('primary_label'); ?>><?php echo esc_html($settings['primary_label'] ?? ''); ?></span>
            <span <?php $this->print_render_attribute_string('secondary_label'); ?>><?php echo esc_html($settings['secondary_label'] ?? ''); ?></span>
        </div>
        <?php if ($badge_text !== '') : ?>
            <span class="elementrify-content-toggle__badge"><?php echo esc_html($badge_text); ?></span>
        <?php endif; ?>
    </div>
    <div class="elementrify-content-toggle__panels">
        <div <?php $this->print_render_attribute_string('primary_content'); ?>><?php echo wp_kses_post($settings['primary_content'] ?? ''); ?></div>
        <div <?php $this->print_render_attribute_string('secondary_content'); ?>><?php echo wp_kses_post($settings['secondary_content'] ?? ''); ?></div>
    </div>
</div>
