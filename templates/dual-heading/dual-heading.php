<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$tag      = !empty($settings['html_tag']) ? $settings['html_tag'] : 'h2';
$hlMode   = isset($settings['highlight_appearance']) && $settings['highlight_appearance'] === 'box' ? 'box' : 'text';

$this->add_render_attribute('wrapper', 'class', 'elementrify-dual-heading elementrify-dual-heading--hl-' . $hlMode);
$this->add_render_attribute('title', 'class', 'elementrify-dual-heading__title');
$this->add_render_attribute('highlight', 'class', 'elementrify-dual-heading__highlight');

$this->add_render_attribute('first_line', 'class', 'elementrify-dual-heading__general');
$this->add_render_attribute('second_line', 'class', 'elementrify-dual-heading__highlight-inner');

$this->add_inline_editing_attributes('first_line', 'none');
$this->add_inline_editing_attributes('second_line', 'none');

$first  = isset($settings['first_line']) ? (string) $settings['first_line'] : '';
$second = isset($settings['second_line']) ? (string) $settings['second_line'] : '';
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <<?php echo esc_attr($tag); ?> <?php $this->print_render_attribute_string('title'); ?>>
        <span <?php $this->print_render_attribute_string('first_line'); ?>><?php echo esc_html($first); ?></span><span <?php $this->print_render_attribute_string('highlight'); ?>><span <?php $this->print_render_attribute_string('second_line'); ?>><?php echo esc_html($second); ?></span></span>
    </<?php echo esc_attr($tag); ?>>
</div>
