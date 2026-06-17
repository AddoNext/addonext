<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_tag      = ! empty($veloria_settings['html_tag']) ? $veloria_settings['html_tag'] : 'h2';

$this->add_render_attribute('wrapper', 'class', 'veloria-advanced-heading');
$this->add_render_attribute('title_wrapper', 'class', 'veloria-advanced-heading__title');
$this->add_render_attribute('title', 'class', 'veloria-advanced-heading__text');
$this->add_render_attribute('subtitle', 'class', 'veloria-advanced-heading__subtitle');

$veloria_show_subtitle = ! empty($veloria_settings['show_subtitle']) && $veloria_settings['show_subtitle'] === 'yes' && ! empty($veloria_settings['subtitle']);

$this->add_render_attribute('title_gradient', 'class', 'veloria-advanced-heading__gradient');
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('title_gradient', 'none');
$this->add_inline_editing_attributes('subtitle', 'none');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
	<<?php echo esc_attr($veloria_tag); ?> <?php $this->print_render_attribute_string('title_wrapper'); ?>>
		<span <?php $this->print_render_attribute_string('title'); ?>><?php echo esc_html($veloria_settings['title']); ?></span>
		<?php if (! empty($veloria_settings['title_gradient'])) : ?>
			<span <?php $this->print_render_attribute_string('title_gradient'); ?>><?php echo esc_html($veloria_settings['title_gradient']); ?></span>
		<?php endif; ?>
	</<?php echo esc_attr($veloria_tag); ?>>
	<?php if ($veloria_show_subtitle) : ?>
		<p <?php $this->print_render_attribute_string('subtitle'); ?>><?php echo wp_kses_post($veloria_settings['subtitle']); ?></p>
	<?php endif; ?>
</div>
