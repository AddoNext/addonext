<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$this->add_render_attribute('wrapper', 'class', 'elementrify-icon-box');
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('description', 'none');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if (!empty($settings['selected_icon']['value'])) : ?>
        <div class="elementrify-icon-box__icon">
            <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
        </div>
    <?php endif; ?>
    <h3 class="elementrify-icon-box__title" <?php $this->print_render_attribute_string('title'); ?>><?php echo wp_kses_post($settings['title'] ?? ''); ?></h3>
    <p class="elementrify-icon-box__description" <?php $this->print_render_attribute_string('description'); ?>><?php echo wp_kses_post($settings['description'] ?? ''); ?></p>
</div>
