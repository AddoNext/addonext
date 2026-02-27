<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$class   = 'elementrify-text-editor';
if (!empty($settings['drop_cap'])) {
    $class .= ' elementrify-text-editor--drop-cap';
}
if (!empty($settings['use_gradient'])) {
    $class .= ' elementrify-text-editor--gradient';
}
if (!empty($settings['text_3d'])) {
    $class .= ' elementrify-text-editor--3d';
}
$this->add_render_attribute('editor', 'class', $class);
$this->add_inline_editing_attributes('editor', 'advanced');
?>
<div <?php $this->print_render_attribute_string('editor'); ?>><?php echo wp_kses_post($settings['editor'] ?? ''); ?></div>
