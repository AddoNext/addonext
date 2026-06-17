<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_trigger_text = isset($veloria_settings['trigger_text']) ? $veloria_settings['trigger_text'] : __('Information', 'veloria');
$veloria_tooltip_text = isset($veloria_settings['tooltip_text']) ? $veloria_settings['tooltip_text'] : __('Get detailed information here', 'veloria');
$veloria_has_icon = !empty($veloria_settings['selected_icon']['value']);
$veloria_link = isset($veloria_settings['link']['url']) ? $veloria_settings['link'] : [];
$veloria_has_link = !empty($veloria_link['url']) && $veloria_link['url'] !== '#';

$this->add_render_attribute('wrapper', 'class', 'veloria-tooltip');

if ($veloria_has_link) {
    $this->add_render_attribute('trigger', 'class', 'veloria-tooltip__trigger');
    $this->add_link_attributes('trigger', $veloria_link);
} else {
    $this->add_render_attribute('trigger', 'class', 'veloria-tooltip__trigger');
    $this->add_render_attribute('trigger', 'tabindex', '0');
    $this->add_render_attribute('trigger', 'role', 'button');
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_has_link) : ?>
        <a <?php $this->print_render_attribute_string('trigger'); ?>>
    <?php else : ?>
        <span <?php $this->print_render_attribute_string('trigger'); ?>>
    <?php endif; ?>
        <?php if ($veloria_has_icon) : ?>
            <span class="veloria-tooltip__icon" aria-hidden="true">
                <?php \Elementor\Icons_Manager::render_icon($veloria_settings['selected_icon'], ['aria-hidden' => 'true']); ?>
            </span>
        <?php endif; ?>
        <span class="veloria-tooltip__label"><?php echo esc_html($veloria_trigger_text); ?></span>
    <?php if ($veloria_has_link) : ?></a><?php else : ?></span><?php endif; ?>
    <span class="veloria-tooltip__content"><?php echo esc_html($veloria_tooltip_text); ?></span>
</div>
