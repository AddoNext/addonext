<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_has_text = ! empty($veloria_settings['text']);
$veloria_has_icon = ! empty($veloria_settings['selected_icon']['value']);

if (! $veloria_has_text && ! $veloria_has_icon) {
    return;
}

$veloria_variant = isset($veloria_settings['variant']) ? $veloria_settings['variant'] : 'primary';
$veloria_size    = isset($veloria_settings['size']) && $veloria_settings['size'] !== '' ? $veloria_settings['size'] : 'md';

$this->add_render_attribute('wrapper', 'class', 'veloria-button-wrapper');
$this->add_render_attribute('button', 'class', 'veloria-button');
$this->add_render_attribute('button', 'class', 'veloria-button--' . esc_attr($veloria_size));
$this->add_render_attribute('button', 'class', 'veloria-button--' . esc_attr($veloria_variant));

if (! empty($veloria_settings['hover_animation'])) {
    $this->add_render_attribute('button', 'class', 'elementor-animation-' . esc_attr($veloria_settings['hover_animation']));
}

if (! empty($veloria_settings['link']['url'])) {
    $this->add_link_attributes('button', $veloria_settings['link']);
} else {
    $this->add_render_attribute('button', 'role', 'button');
}

if (! empty($veloria_settings['button_css_id'])) {
    $this->add_render_attribute('button', 'id', esc_attr($veloria_settings['button_css_id']));
}

$this->add_render_attribute('content', 'class', 'veloria-button__content');
$this->add_render_attribute('text', 'class', 'veloria-button__text');
$this->add_inline_editing_attributes('text', 'none');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <a <?php $this->print_render_attribute_string('button'); ?>>
        <span <?php $this->print_render_attribute_string('content'); ?>>
            <?php if ($veloria_has_icon) : ?>
                <span class="veloria-button__icon">
                    <?php \Elementor\Icons_Manager::render_icon($veloria_settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php if ($veloria_has_text) : ?>
                <span <?php $this->print_render_attribute_string('text'); ?>><?php echo wp_kses_post($veloria_settings['text']); ?></span>
            <?php endif; ?>
        </span>
    </a>
</div>
