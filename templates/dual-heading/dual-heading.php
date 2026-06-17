<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_tag      = !empty($veloria_settings['html_tag']) ? $veloria_settings['html_tag'] : 'h2';
$veloria_hlMode   = isset($veloria_settings['highlight_appearance']) && $veloria_settings['highlight_appearance'] === 'box' ? 'box' : 'text';

$this->add_render_attribute('wrapper', 'class', 'veloria-dual-heading veloria-dual-heading--hl-' . $veloria_hlMode);
$this->add_render_attribute('title', 'class', 'veloria-dual-heading__title');
$this->add_render_attribute('highlight', 'class', 'veloria-dual-heading__highlight');

$this->add_render_attribute('first_line', 'class', 'veloria-dual-heading__general');
$this->add_render_attribute('second_line', 'class', 'veloria-dual-heading__highlight-inner');

$this->add_inline_editing_attributes('first_line', 'none');
$this->add_inline_editing_attributes('second_line', 'none');

$veloria_first  = isset($veloria_settings['first_line']) ? (string) $veloria_settings['first_line'] : '';
$veloria_second = isset($veloria_settings['second_line']) ? (string) $veloria_settings['second_line'] : '';
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <<?php echo esc_attr($veloria_tag); ?> <?php $this->print_render_attribute_string('title'); ?>>
        <span <?php $this->print_render_attribute_string('first_line'); ?>><?php echo esc_html($veloria_first); ?></span><span <?php $this->print_render_attribute_string('highlight'); ?>><span <?php $this->print_render_attribute_string('second_line'); ?>><?php echo esc_html($veloria_second); ?></span></span>
    </<?php echo esc_attr($veloria_tag); ?>>
</div>
