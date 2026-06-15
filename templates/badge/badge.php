<?php
defined('ABSPATH') || exit;

$settings     = $this->get_settings_for_display();
$tag          = ! empty($settings['html_tag']) ? $settings['html_tag'] : 'span';
$icon_value   = isset($settings['selected_icon']['value']) ? $settings['selected_icon']['value'] : null;
$show_icon    = $icon_value !== null ? $icon_value !== '' : true;
$default_icon = ['value' => 'fas fa-crown', 'library' => 'fa-solid'];
$icon_settings = ($show_icon && ! empty($icon_value)) ? $settings['selected_icon'] : $default_icon;

$this->add_render_attribute('wrap', 'class', 'veloria-badge__wrap');
$this->add_render_attribute('badge', 'class', 'veloria-badge');
if ($show_icon) {
    $this->add_render_attribute('badge', 'class', 'veloria-badge--has-icon');
}
$this->add_render_attribute('text', 'class', 'veloria-badge__text');
$this->add_inline_editing_attributes('text', 'none');
?>
<div <?php $this->print_render_attribute_string('wrap'); ?>>
<<?php echo esc_attr($tag); ?> <?php $this->print_render_attribute_string('badge'); ?>>
	<?php if ($show_icon) : ?>
		<span class="veloria-badge__icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon($icon_settings, ['aria-hidden' => 'true', 'width' => 16]); ?></span>
	<?php endif; ?>
	<span <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($settings['text']); ?></span>
</<?php echo esc_attr($tag); ?>>
</div>
