<?php
defined('ABSPATH') || exit;

$veloria_settings     = $this->get_settings_for_display();
$veloria_tag          = ! empty($veloria_settings['html_tag']) ? $veloria_settings['html_tag'] : 'span';
$veloria_icon_value   = isset($veloria_settings['selected_icon']['value']) ? $veloria_settings['selected_icon']['value'] : null;
$veloria_show_icon    = $veloria_icon_value !== null ? $veloria_icon_value !== '' : true;
$veloria_default_icon = ['value' => 'fas fa-crown', 'library' => 'fa-solid'];
$veloria_icon_settings = ($veloria_show_icon && ! empty($veloria_icon_value)) ? $veloria_settings['selected_icon'] : $veloria_default_icon;

$this->add_render_attribute('wrap', 'class', 'veloria-badge__wrap');
$this->add_render_attribute('badge', 'class', 'veloria-badge');
if ($veloria_show_icon) {
    $this->add_render_attribute('badge', 'class', 'veloria-badge--has-icon');
}
$this->add_render_attribute('text', 'class', 'veloria-badge__text');
$this->add_inline_editing_attributes('text', 'none');
?>
<div <?php $this->print_render_attribute_string('wrap'); ?>>
<<?php echo esc_attr($veloria_tag); ?> <?php $this->print_render_attribute_string('badge'); ?>>
	<?php if ($veloria_show_icon) : ?>
		<span class="veloria-badge__icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon($veloria_icon_settings, ['aria-hidden' => 'true', 'width' => 16]); ?></span>
	<?php endif; ?>
	<span <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($veloria_settings['text']); ?></span>
</<?php echo esc_attr($veloria_tag); ?>>
</div>
