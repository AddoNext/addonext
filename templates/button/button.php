<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_has_text = ! empty($addonext_settings['text']);
$addonext_has_icon = ! empty($addonext_settings['selected_icon']['value']);

if (! $addonext_has_text && ! $addonext_has_icon) {
    return;
}

$addonext_variant = isset($addonext_settings['variant']) ? $addonext_settings['variant'] : 'primary';
$addonext_size    = isset($addonext_settings['size']) && $addonext_settings['size'] !== '' ? $addonext_settings['size'] : 'md';

$this->add_render_attribute('wrapper', 'class', 'addonext-button-wrapper');
$this->add_render_attribute('button', 'class', 'addonext-button');
$this->add_render_attribute('button', 'class', 'addonext-button--' . esc_attr($addonext_size));
$this->add_render_attribute('button', 'class', 'addonext-button--' . esc_attr($addonext_variant));

if (! empty($addonext_settings['hover_animation'])) {
    $this->add_render_attribute('button', 'class', 'elementor-animation-' . esc_attr($addonext_settings['hover_animation']));
}

if (! empty($addonext_settings['link']['url'])) {
    $this->add_link_attributes('button', $addonext_settings['link']);
} else {
    $this->add_render_attribute('button', 'role', 'button');
}

if (! empty($addonext_settings['button_css_id'])) {
    $this->add_render_attribute('button', 'id', esc_attr($addonext_settings['button_css_id']));
}

$this->add_render_attribute('content', 'class', 'addonext-button__content');
$this->add_render_attribute('text', 'class', 'addonext-button__text');
$this->add_inline_editing_attributes('text', 'none');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <a <?php $this->print_render_attribute_string('button'); ?>>
        <span <?php $this->print_render_attribute_string('content'); ?>>
            <?php if ($addonext_has_icon) : ?>
                <span class="addonext-button__icon">
                    <?php \Elementor\Icons_Manager::render_icon($addonext_settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php if ($addonext_has_text) : ?>
                <span <?php $this->print_render_attribute_string('text'); ?>><?php echo wp_kses_post($addonext_settings['text']); ?></span>
            <?php endif; ?>
        </span>
    </a>
</div>
