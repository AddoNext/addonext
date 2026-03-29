<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$raw      = isset($settings['text']) && $settings['text'] !== ''
    ? (string) $settings['text']
    : (isset($settings['content']) ? (string) $settings['content'] : '');
$allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span'];
$tag       = isset($settings['html_tag']) && in_array($settings['html_tag'], $allowedTags, true)
    ? $settings['html_tag']
    : 'h2';

$mode = isset($settings['gradient_mode']) && $settings['gradient_mode'] === 'text' ? 'text' : 'bg';
$dir  = isset($settings['animation_direction']) && in_array($settings['animation_direction'], ['h', 'v', 'd'], true)
    ? $settings['animation_direction']
    : 'h';
$align = isset($settings['align']) && in_array($settings['align'], ['left', 'center', 'right'], true)
    ? $settings['align']
    : 'left';

$this->add_render_attribute('inner', 'class', 'elementrify-animated-gradient elementrify-animated-gradient__inner');
$this->add_render_attribute('inner', 'class', 'elementrify-animated-gradient--' . $mode);
$this->add_render_attribute('inner', 'class', 'elementrify-animated-gradient--dir-' . $dir);
$this->add_render_attribute('inner', 'class', 'elementrify-animated-gradient--align-' . $align);
$this->add_render_attribute('text', 'class', 'elementrify-animated-gradient__text');
?>
<div <?php $this->print_render_attribute_string('inner'); ?>>
    <<?php echo esc_attr($tag); ?> <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($raw); ?></<?php echo esc_attr($tag); ?>>
</div>
