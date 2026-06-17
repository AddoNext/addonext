<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_raw      = isset($veloria_settings['text']) && $veloria_settings['text'] !== ''
    ? (string) $veloria_settings['text']
    : (isset($veloria_settings['content']) ? (string) $veloria_settings['content'] : '');
$veloria_allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span'];
$veloria_tag       = isset($veloria_settings['html_tag']) && in_array($veloria_settings['html_tag'], $veloria_allowedTags, true)
    ? $veloria_settings['html_tag']
    : 'h2';

$veloria_mode = isset($veloria_settings['gradient_mode']) && $veloria_settings['gradient_mode'] === 'text' ? 'text' : 'bg';
$veloria_dir  = isset($veloria_settings['animation_direction']) && in_array($veloria_settings['animation_direction'], ['h', 'v', 'd'], true)
    ? $veloria_settings['animation_direction']
    : 'h';
$veloria_align = isset($veloria_settings['align']) && in_array($veloria_settings['align'], ['left', 'center', 'right'], true)
    ? $veloria_settings['align']
    : 'left';

$this->add_render_attribute('inner', 'class', 'veloria-animated-gradient veloria-animated-gradient__inner');
$this->add_render_attribute('inner', 'class', 'veloria-animated-gradient--' . $veloria_mode);
$this->add_render_attribute('inner', 'class', 'veloria-animated-gradient--dir-' . $veloria_dir);
$this->add_render_attribute('inner', 'class', 'veloria-animated-gradient--align-' . $veloria_align);
$this->add_render_attribute('text', 'class', 'veloria-animated-gradient__text');
?>
<div <?php $this->print_render_attribute_string('inner'); ?>>
    <<?php echo esc_attr($veloria_tag); ?> <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($veloria_raw); ?></<?php echo esc_attr($veloria_tag); ?>>
</div>

<?php
    }
}
