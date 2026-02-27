<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$this->add_render_attribute('editor', 'class', 'elementrify-text-editor');
$this->add_inline_editing_attributes('editor', 'advanced');
?>
<div <?php $this->print_render_attribute_string('editor'); ?>><?php echo wp_kses_post($settings['editor']); ?></div>
