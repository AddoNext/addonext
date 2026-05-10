<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$has_text = ! empty($settings['text']);
$has_icon = ! empty($settings['selected_icon']['value']);

if (! $has_text && ! $has_icon) {
    return;
}

$variant = isset($settings['variant']) ? $settings['variant'] : 'primary';
$size    = isset($settings['size']) && $settings['size'] !== '' ? $settings['size'] : 'md';

$this->add_render_attribute('wrapper', 'class', 'elementrify-button-wrapper');
$this->add_render_attribute('button', 'class', 'elementrify-button');
$this->add_render_attribute('button', 'class', 'elementrify-button--' . esc_attr($size));
$this->add_render_attribute('button', 'class', 'elementrify-button--' . esc_attr($variant));

if (! empty($settings['hover_animation'])) {
    $this->add_render_attribute('button', 'class', 'elementor-animation-' . esc_attr($settings['hover_animation']));
}

if (! empty($settings['link']['url'])) {
    $this->add_link_attributes('button', $settings['link']);
} else {
    $this->add_render_attribute('button', 'role', 'button');
}

if (! empty($settings['button_css_id'])) {
    $this->add_render_attribute('button', 'id', esc_attr($settings['button_css_id']));
}

$this->add_render_attribute('content', 'class', 'elementrify-button__content');
$this->add_render_attribute('text', 'class', 'elementrify-button__text');
$this->add_inline_editing_attributes('text', 'none');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <a <?php $this->print_render_attribute_string('button'); ?>>
        <span <?php $this->print_render_attribute_string('content'); ?>>
            <?php if ($has_icon) : ?>
                <span class="elementrify-button__icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php if ($has_text) : ?>
                <span <?php $this->print_render_attribute_string('text'); ?>><?php echo wp_kses_post($settings['text']); ?></span>
            <?php endif; ?>
        </span>
    </a>
</div>
