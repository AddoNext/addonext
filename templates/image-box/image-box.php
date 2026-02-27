<?php
defined('ABSPATH') || exit;

use Elementor\Group_Control_Image_Size;

$settings     = $this->get_settings_for_display();
$wrapper_class = 'elementrify-image-box';
if (!empty($settings['image_bw_mode'])) {
    $wrapper_class .= ' elementrify-image-box--bw';
}
if (!empty($settings['hover_effect']) && $settings['hover_effect'] !== 'none') {
    $wrapper_class .= ' elementrify-image-box--hover-' . esc_attr($settings['hover_effect']);
}
if (!empty($settings['hover_grayscale_off'])) {
    $wrapper_class .= ' elementrify-image-box--hover-grayscale-off';
}
if (!empty($settings['hover_overlay_color'])) {
    $wrapper_class .= ' elementrify-image-box--hover-overlay';
}
$this->add_render_attribute('wrapper', 'class', $wrapper_class);
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('description', 'none');
$has_link = !empty($settings['link']['url']);
$image    = $settings['image'] ?? [];
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if (!empty($image['url']) || !empty($image['id'])) : ?>
        <div class="elementrify-image-box__image">
            <?php if ($has_link) : ?><a href="<?php echo esc_url($settings['link']['url']); ?>"><?php endif; ?>
            <?php
            if (!empty($image['id'])) {
                echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($settings, 'image_size', 'image'));
            } else {
                $img_url = !empty($image['url']) ? $image['url'] : '';
                if ($img_url) {
                    echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($image['alt'] ?? $settings['title'] ?? '') . '">';
                }
            }
            ?>
            <?php if ($has_link) : ?></a><?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="elementrify-image-box__content">
        <?php if (!empty($settings['show_title']) && $settings['show_title'] !== 'no') : ?>
            <?php if ($has_link) : ?><a href="<?php echo esc_url($settings['link']['url']); ?>"><?php endif; ?>
            <h3 class="elementrify-image-box__title" <?php $this->print_render_attribute_string('title'); ?>><?php echo wp_kses_post($settings['title'] ?? ''); ?></h3>
            <?php if ($has_link) : ?></a><?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($settings['show_description']) && $settings['show_description'] !== 'no') : ?>
            <p class="elementrify-image-box__description" <?php $this->print_render_attribute_string('description'); ?>><?php echo wp_kses_post($settings['description'] ?? ''); ?></p>
        <?php endif; ?>
    </div>
</div>
