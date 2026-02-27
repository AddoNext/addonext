<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$tag      = !empty($settings['html_tag']) ? $settings['html_tag'] : 'span';
$this->add_render_attribute('badge', 'class', 'elementrify-badge');
$this->add_inline_editing_attributes('text', 'none');
?>
<<?php echo esc_attr($tag); ?> <?php $this->print_render_attribute_string('badge'); ?> <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($settings['text']); ?></<?php echo esc_attr($tag); ?>>
