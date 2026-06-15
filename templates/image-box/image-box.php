<?php
defined('ABSPATH') || exit;

use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

$settings     = $this->get_settings_for_display();
$placeholder_src = Utils::get_placeholder_image_src();
$image           = $settings['image'] ?? [];
$url             = isset($image['url']) ? (string) $image['url'] : '';
$has_attachment  = !empty($image['id']);
$is_placeholder_only = $has_attachment === false && $url !== '' && $placeholder_src !== '' && $url === $placeholder_src;

$in_elementor_preview = false;
if (class_exists('\Elementor\Plugin')) {
    $preview = \Elementor\Plugin::$instance->preview ?? null;
    if (is_object($preview) && method_exists($preview, 'is_preview_mode')) {
        $in_elementor_preview = $preview->is_preview_mode();
    }
}

$has_visible_frontend_image = $has_attachment || ($url !== '' && !$is_placeholder_only);
$show_image_wrapper         = $has_visible_frontend_image || $in_elementor_preview;

$wrapper_class = 'veloria-image-box';
if (!empty($settings['image_bw_mode'])) {
    $wrapper_class .= ' veloria-image-box--bw';
}
if (!empty($settings['hover_effect']) && $settings['hover_effect'] !== 'none') {
    $wrapper_class .= ' veloria-image-box--hover-' . esc_attr($settings['hover_effect']);
}
if (!empty($settings['hover_grayscale_off'])) {
    $wrapper_class .= ' veloria-image-box--hover-grayscale-off';
}
if (!empty($settings['hover_overlay_color'])) {
    $wrapper_class .= ' veloria-image-box--hover-overlay';
}
$this->add_render_attribute('wrapper', 'class', $wrapper_class);
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('description', 'none');
$has_link = !empty($settings['link']['url']);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($show_image_wrapper) : ?>
        <div class="veloria-image-box__image">
            <?php if ($has_link) : ?><a href="<?php echo esc_url($settings['link']['url']); ?>"><?php endif; ?>
            <?php
            if ($has_attachment) {
                echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($settings, 'image_size', 'image'));
            } else {
                $img_url = '';
                if ($has_visible_frontend_image && $url !== '') {
                    $img_url = $url;
                } elseif ($in_elementor_preview && $placeholder_src !== '') {
                    $img_url = ($url !== '') ? $url : $placeholder_src;
                }
                if ($img_url !== '') {
                    echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($image['alt'] ?? $settings['title'] ?? '') . '">';
                }
            }
            ?>
            <?php if ($has_link) : ?></a><?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="veloria-image-box__content">
        <?php if (!empty($settings['show_title']) && $settings['show_title'] !== 'no') : ?>
            <?php if ($has_link) : ?><a href="<?php echo esc_url($settings['link']['url']); ?>"><?php endif; ?>
            <h3 class="veloria-image-box__title" <?php $this->print_render_attribute_string('title'); ?>><?php echo wp_kses_post($settings['title'] ?? ''); ?></h3>
            <?php if ($has_link) : ?></a><?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($settings['show_description']) && $settings['show_description'] !== 'no') : ?>
            <p class="veloria-image-box__description" <?php $this->print_render_attribute_string('description'); ?>><?php echo wp_kses_post($settings['description'] ?? ''); ?></p>
        <?php endif; ?>
    </div>
</div>
