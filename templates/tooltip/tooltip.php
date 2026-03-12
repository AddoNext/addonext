<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$trigger_text = isset($settings['trigger_text']) ? $settings['trigger_text'] : __('Information', 'elementrify');
$tooltip_text = isset($settings['tooltip_text']) ? $settings['tooltip_text'] : __('Get detailed information here', 'elementrify');
$has_icon = !empty($settings['selected_icon']['value']);
$link = isset($settings['link']['url']) ? $settings['link'] : [];
$has_link = !empty($link['url']) && $link['url'] !== '#';

$this->add_render_attribute('wrapper', 'class', 'elementrify-tooltip');

if ($has_link) {
    $this->add_render_attribute('trigger', 'class', 'elementrify-tooltip__trigger');
    $this->add_link_attributes('trigger', $link);
} else {
    $this->add_render_attribute('trigger', 'class', 'elementrify-tooltip__trigger');
    $this->add_render_attribute('trigger', 'tabindex', '0');
    $this->add_render_attribute('trigger', 'role', 'button');
}
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($has_link) : ?>
        <a <?php $this->print_render_attribute_string('trigger'); ?>>
    <?php else : ?>
        <span <?php $this->print_render_attribute_string('trigger'); ?>>
    <?php endif; ?>
        <?php if ($has_icon) : ?>
            <span class="elementrify-tooltip__icon" aria-hidden="true">
                <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
            </span>
        <?php endif; ?>
        <span class="elementrify-tooltip__label"><?php echo esc_html($trigger_text); ?></span>
    <?php if ($has_link) : ?></a><?php else : ?></span><?php endif; ?>
    <span class="elementrify-tooltip__content"><?php echo esc_html($tooltip_text); ?></span>
</div>
