<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$tag      = !empty($settings['html_tag']) ? $settings['html_tag'] : 'h2';
$this->add_render_attribute('title', 'class', 'elementrify-advanced-heading__title');
$this->add_inline_editing_attributes('title', 'none');
?>
<div class="elementrify-advanced-heading">
    <<?php echo esc_attr($tag); ?> <?php $this->print_render_attribute_string('title'); ?>><?php echo wp_kses_post($settings['title']); ?></<?php echo esc_attr($tag); ?>>
</div>
