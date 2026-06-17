<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_class   = 'veloria-text-editor';
if (!empty($veloria_settings['drop_cap'])) {
    $veloria_class .= ' veloria-text-editor--drop-cap';
}
if (!empty($veloria_settings['use_gradient'])) {
    $veloria_class .= ' veloria-text-editor--gradient';
}
if (!empty($veloria_settings['text_3d'])) {
    $veloria_class .= ' veloria-text-editor--3d';
}
$this->add_render_attribute('editor', 'class', $veloria_class);
$this->add_inline_editing_attributes('editor', 'advanced');
?>
<div <?php $this->print_render_attribute_string('editor'); ?>><?php echo wp_kses_post($veloria_settings['editor'] ?? ''); ?></div>
