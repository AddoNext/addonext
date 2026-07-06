<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_trigger_text = isset($addonext_settings['trigger_text']) ? $addonext_settings['trigger_text'] : __('Information', 'addonext');
$addonext_tooltip_text = isset($addonext_settings['tooltip_text']) ? $addonext_settings['tooltip_text'] : __('Get detailed information here', 'addonext');
$addonext_has_icon = !empty($addonext_settings['selected_icon']['value']);
$addonext_link = isset($addonext_settings['link']['url']) ? $addonext_settings['link'] : [];
$addonext_has_link = !empty($addonext_link['url']) && $addonext_link['url'] !== '#';

$this->add_render_attribute('wrapper', 'class', 'addonext-tooltip');

if ($addonext_has_link) {
    $this->add_render_attribute('trigger', 'class', 'addonext-tooltip__trigger');
    $this->add_link_attributes('trigger', $addonext_link);
} else {
    $this->add_render_attribute('trigger', 'class', 'addonext-tooltip__trigger');
    $this->add_render_attribute('trigger', 'tabindex', '0');
    $this->add_render_attribute('trigger', 'role', 'button');
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_has_link) : ?>
        <a <?php $this->print_render_attribute_string('trigger'); ?>>
    <?php else : ?>
        <span <?php $this->print_render_attribute_string('trigger'); ?>>
    <?php endif; ?>
        <?php if ($addonext_has_icon) : ?>
            <span class="addonext-tooltip__icon" aria-hidden="true">
                <?php \Elementor\Icons_Manager::render_icon($addonext_settings['selected_icon'], ['aria-hidden' => 'true']); ?>
            </span>
        <?php endif; ?>
        <span class="addonext-tooltip__label"><?php echo esc_html($addonext_trigger_text); ?></span>
    <?php if ($addonext_has_link) : ?></a><?php else : ?></span><?php endif; ?>
    <span class="addonext-tooltip__content"><?php echo esc_html($addonext_tooltip_text); ?></span>
</div>
