<?php
defined('ABSPATH') || exit;

$addonext_settings     = $this->get_settings_for_display();
$addonext_tag          = ! empty($addonext_settings['html_tag']) ? $addonext_settings['html_tag'] : 'span';
$addonext_icon_value   = isset($addonext_settings['selected_icon']['value']) ? $addonext_settings['selected_icon']['value'] : null;
$addonext_show_icon    = $addonext_icon_value !== null ? $addonext_icon_value !== '' : true;
$addonext_default_icon = ['value' => 'fas fa-crown', 'library' => 'fa-solid'];
$addonext_icon_settings = ($addonext_show_icon && ! empty($addonext_icon_value)) ? $addonext_settings['selected_icon'] : $addonext_default_icon;

$this->add_render_attribute('wrap', 'class', 'addonext-badge__wrap');
$this->add_render_attribute('badge', 'class', 'addonext-badge');
if ($addonext_show_icon) {
    $this->add_render_attribute('badge', 'class', 'addonext-badge--has-icon');
}
$this->add_render_attribute('text', 'class', 'addonext-badge__text');
$this->add_inline_editing_attributes('text', 'none');
?>
<div <?php $this->print_render_attribute_string('wrap'); ?>>
<<?php echo esc_attr($addonext_tag); ?> <?php $this->print_render_attribute_string('badge'); ?>>
	<?php if ($addonext_show_icon) : ?>
		<span class="addonext-badge__icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon($addonext_icon_settings, ['aria-hidden' => 'true', 'width' => 16]); ?></span>
	<?php endif; ?>
	<span <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($addonext_settings['text']); ?></span>
</<?php echo esc_attr($addonext_tag); ?>>
</div>
