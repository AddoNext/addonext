<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$this->add_render_attribute('wrapper', 'class', 'elementrify-image-box');
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('description', 'none');
$has_link = !empty($settings['link']['url']);
$image    = $settings['image'] ?? [];
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if (!empty($image['url'])) : ?>
        <div class="elementrify-image-box__image">
            <?php if ($has_link) : ?><a href="<?php echo esc_url($settings['link']['url']); ?>"><?php endif; ?>
            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?? $settings['title'] ?? ''); ?>">
            <?php if ($has_link) : ?></a><?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="elementrify-image-box__content">
        <?php if ($has_link) : ?><a href="<?php echo esc_url($settings['link']['url']); ?>"><?php endif; ?>
        <h3 class="elementrify-image-box__title" <?php $this->print_render_attribute_string('title'); ?>><?php echo wp_kses_post($settings['title'] ?? ''); ?></h3>
        <?php if ($has_link) : ?></a><?php endif; ?>
        <p class="elementrify-image-box__description" <?php $this->print_render_attribute_string('description'); ?>><?php echo wp_kses_post($settings['description'] ?? ''); ?></p>
    </div>
</div>
