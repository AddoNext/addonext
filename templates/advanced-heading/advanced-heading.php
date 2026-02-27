<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$tag      = ! empty($settings['html_tag']) ? $settings['html_tag'] : 'h2';

$this->add_render_attribute('wrapper', 'class', 'elementrify-advanced-heading');
$this->add_render_attribute('title_wrapper', 'class', 'elementrify-advanced-heading__title');
$this->add_render_attribute('title', 'class', 'elementrify-advanced-heading__text');
$this->add_render_attribute('subtitle', 'class', 'elementrify-advanced-heading__subtitle');

$show_subtitle = ! empty($settings['show_subtitle']) && $settings['show_subtitle'] === 'yes' && ! empty($settings['subtitle']);

$this->add_render_attribute('title_gradient', 'class', 'elementrify-advanced-heading__gradient');
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('title_gradient', 'none');
$this->add_inline_editing_attributes('subtitle', 'none');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
	<<?php echo esc_attr($tag); ?> <?php $this->print_render_attribute_string('title_wrapper'); ?>>
		<span <?php $this->print_render_attribute_string('title'); ?>><?php echo esc_html($settings['title']); ?></span>
		<?php if (! empty($settings['title_gradient'])) : ?>
			<span <?php $this->print_render_attribute_string('title_gradient'); ?>><?php echo esc_html($settings['title_gradient']); ?></span>
		<?php endif; ?>
	</<?php echo esc_attr($tag); ?>>
	<?php if ($show_subtitle) : ?>
		<p <?php $this->print_render_attribute_string('subtitle'); ?>><?php echo wp_kses_post($settings['subtitle']); ?></p>
	<?php endif; ?>
</div>
