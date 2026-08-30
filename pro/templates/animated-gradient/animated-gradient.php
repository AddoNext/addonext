<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_raw      = isset($addonext_settings['text']) && $addonext_settings['text'] !== ''
    ? (string) $addonext_settings['text']
    : (isset($addonext_settings['content']) ? (string) $addonext_settings['content'] : '');
$addonext_allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span'];
$addonext_tag       = isset($addonext_settings['html_tag']) && in_array($addonext_settings['html_tag'], $addonext_allowedTags, true)
    ? $addonext_settings['html_tag']
    : 'h2';

$addonext_mode = isset($addonext_settings['gradient_mode']) && $addonext_settings['gradient_mode'] === 'text' ? 'text' : 'bg';
$addonext_dir  = isset($addonext_settings['animation_direction']) && in_array($addonext_settings['animation_direction'], ['h', 'v', 'd'], true)
    ? $addonext_settings['animation_direction']
    : 'h';
$addonext_align = isset($addonext_settings['align']) && in_array($addonext_settings['align'], ['left', 'center', 'right'], true)
    ? $addonext_settings['align']
    : 'left';

$this->add_render_attribute('inner', 'class', 'addonext-animated-gradient addonext-animated-gradient__inner');
$this->add_render_attribute('inner', 'class', 'addonext-animated-gradient--' . $addonext_mode);
$this->add_render_attribute('inner', 'class', 'addonext-animated-gradient--dir-' . $addonext_dir);
$this->add_render_attribute('inner', 'class', 'addonext-animated-gradient--align-' . $addonext_align);
$this->add_render_attribute('text', 'class', 'addonext-animated-gradient__text');
?>
<div <?php $this->print_render_attribute_string('inner'); ?>>
    <<?php echo esc_attr($addonext_tag); ?> <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($addonext_raw); ?></<?php echo esc_attr($addonext_tag); ?>>
</div>

<?php
    }
}
