<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_tag      = !empty($addonext_settings['html_tag']) ? $addonext_settings['html_tag'] : 'h2';
$addonext_hlMode   = isset($addonext_settings['highlight_appearance']) && $addonext_settings['highlight_appearance'] === 'box' ? 'box' : 'text';

$this->add_render_attribute('wrapper', 'class', 'addonext-dual-heading addonext-dual-heading--hl-' . $addonext_hlMode);
$this->add_render_attribute('title', 'class', 'addonext-dual-heading__title');
$this->add_render_attribute('highlight', 'class', 'addonext-dual-heading__highlight');

$this->add_render_attribute('first_line', 'class', 'addonext-dual-heading__general');
$this->add_render_attribute('second_line', 'class', 'addonext-dual-heading__highlight-inner');

$this->add_inline_editing_attributes('first_line', 'none');
$this->add_inline_editing_attributes('second_line', 'none');

$addonext_first  = isset($addonext_settings['first_line']) ? (string) $addonext_settings['first_line'] : '';
$addonext_second = isset($addonext_settings['second_line']) ? (string) $addonext_settings['second_line'] : '';
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <<?php echo esc_attr($addonext_tag); ?> <?php $this->print_render_attribute_string('title'); ?>>
        <span <?php $this->print_render_attribute_string('first_line'); ?>><?php echo esc_html($addonext_first); ?></span><span <?php $this->print_render_attribute_string('highlight'); ?>><span <?php $this->print_render_attribute_string('second_line'); ?>><?php echo esc_html($addonext_second); ?></span></span>
    </<?php echo esc_attr($addonext_tag); ?>>
</div>
