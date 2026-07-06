<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_tag      = ! empty($addonext_settings['html_tag']) ? $addonext_settings['html_tag'] : 'h2';

$this->add_render_attribute('wrapper', 'class', 'addonext-advanced-heading');
$this->add_render_attribute('title_wrapper', 'class', 'addonext-advanced-heading__title');
$this->add_render_attribute('title', 'class', 'addonext-advanced-heading__text');
$this->add_render_attribute('subtitle', 'class', 'addonext-advanced-heading__subtitle');

$addonext_show_subtitle = ! empty($addonext_settings['show_subtitle']) && $addonext_settings['show_subtitle'] === 'yes' && ! empty($addonext_settings['subtitle']);

$this->add_render_attribute('title_gradient', 'class', 'addonext-advanced-heading__gradient');
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('title_gradient', 'none');
$this->add_inline_editing_attributes('subtitle', 'none');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
	<<?php echo esc_attr($addonext_tag); ?> <?php $this->print_render_attribute_string('title_wrapper'); ?>>
		<span <?php $this->print_render_attribute_string('title'); ?>><?php echo esc_html($addonext_settings['title']); ?></span>
		<?php if (! empty($addonext_settings['title_gradient'])) : ?>
			<span <?php $this->print_render_attribute_string('title_gradient'); ?>><?php echo esc_html($addonext_settings['title_gradient']); ?></span>
		<?php endif; ?>
	</<?php echo esc_attr($addonext_tag); ?>>
	<?php if ($addonext_show_subtitle) : ?>
		<p <?php $this->print_render_attribute_string('subtitle'); ?>><?php echo wp_kses_post($addonext_settings['subtitle']); ?></p>
	<?php endif; ?>
</div>
