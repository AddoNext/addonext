<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_class   = 'addonext-text-editor';
if (!empty($addonext_settings['drop_cap'])) {
    $addonext_class .= ' addonext-text-editor--drop-cap';
}
if (!empty($addonext_settings['use_gradient'])) {
    $addonext_class .= ' addonext-text-editor--gradient';
}
if (!empty($addonext_settings['text_3d'])) {
    $addonext_class .= ' addonext-text-editor--3d';
}
$this->add_render_attribute('editor', 'class', $addonext_class);
$this->add_inline_editing_attributes('editor', 'advanced');
?>
<div <?php $this->print_render_attribute_string('editor'); ?>><?php echo wp_kses_post($addonext_settings['editor'] ?? ''); ?></div>
