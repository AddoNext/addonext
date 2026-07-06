<?php
defined('ABSPATH') || exit;

$addonext_settings       = $this->get_settings_for_display();
$addonext_badge_text    = isset($addonext_settings['badge_text']) ? trim($addonext_settings['badge_text']) : '';
$addonext_secondary_first = (isset($addonext_settings['default_active']) && $addonext_settings['default_active'] === 'secondary');

$this->add_render_attribute('wrapper', 'class', 'addonext-content-toggle');

$this->add_render_attribute('primary_label', 'class', 'addonext-content-toggle__label' . ($addonext_secondary_first ? '' : ' is-active'));
$this->add_render_attribute('primary_label', 'data-toggle', '0');
$this->add_inline_editing_attributes('primary_label', 'none');

$this->add_render_attribute('secondary_label', 'class', 'addonext-content-toggle__label' . ($addonext_secondary_first ? ' is-active' : ''));
$this->add_render_attribute('secondary_label', 'data-toggle', '1');
$this->add_inline_editing_attributes('secondary_label', 'none');

$this->add_render_attribute('primary_content', 'class', 'addonext-content-toggle__panel' . ($addonext_secondary_first ? '' : ' is-active'));
$this->add_render_attribute('primary_content', 'data-toggle', '0');
$this->add_inline_editing_attributes('primary_content', 'advanced');

$this->add_render_attribute('secondary_content', 'class', 'addonext-content-toggle__panel' . ($addonext_secondary_first ? ' is-active' : ''));
$this->add_render_attribute('secondary_content', 'data-toggle', '1');
$this->add_inline_editing_attributes('secondary_content', 'advanced');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="addonext-content-toggle__toggle-row">
        <div class="addonext-content-toggle__labels">
            <span <?php $this->print_render_attribute_string('primary_label'); ?>><?php echo esc_html($addonext_settings['primary_label'] ?? ''); ?></span>
            <span <?php $this->print_render_attribute_string('secondary_label'); ?>><?php echo esc_html($addonext_settings['secondary_label'] ?? ''); ?></span>
        </div>
        <?php if ($addonext_badge_text !== '') : ?>
            <span class="addonext-content-toggle__badge"><?php echo esc_html($addonext_badge_text); ?></span>
        <?php endif; ?>
    </div>
    <div class="addonext-content-toggle__panels">
        <div <?php $this->print_render_attribute_string('primary_content'); ?>><?php echo wp_kses_post($addonext_settings['primary_content'] ?? ''); ?></div>
        <div <?php $this->print_render_attribute_string('secondary_content'); ?>><?php echo wp_kses_post($addonext_settings['secondary_content'] ?? ''); ?></div>
    </div>
</div>
