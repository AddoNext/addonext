<?php
defined('ABSPATH') || exit;

use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

$veloria_settings     = $this->get_settings_for_display();
$veloria_placeholder_src = Utils::get_placeholder_image_src();
$veloria_image           = $veloria_settings['image'] ?? [];
$veloria_url             = isset($veloria_image['url']) ? (string) $veloria_image['url'] : '';
$veloria_has_attachment  = !empty($veloria_image['id']);
$veloria_is_placeholder_only = $veloria_has_attachment === false && $veloria_url !== '' && $veloria_placeholder_src !== '' && $veloria_url === $veloria_placeholder_src;

$veloria_in_elementor_preview = false;
if (class_exists('\Elementor\Plugin')) {
    $veloria_preview = \Elementor\Plugin::$instance->preview ?? null;
    if (is_object($veloria_preview) && method_exists($veloria_preview, 'is_preview_mode')) {
        $veloria_in_elementor_preview = $veloria_preview->is_preview_mode();
    }
}

$veloria_has_visible_frontend_image = $veloria_has_attachment || ($veloria_url !== '' && !$veloria_is_placeholder_only);
$veloria_show_image_wrapper         = $veloria_has_visible_frontend_image || $veloria_in_elementor_preview;

$veloria_wrapper_class = 'veloria-image-box';
if (!empty($veloria_settings['image_bw_mode'])) {
    $veloria_wrapper_class .= ' veloria-image-box--bw';
}
if (!empty($veloria_settings['hover_effect']) && $veloria_settings['hover_effect'] !== 'none') {
    $veloria_wrapper_class .= ' veloria-image-box--hover-' . esc_attr($veloria_settings['hover_effect']);
}
if (!empty($veloria_settings['hover_grayscale_off'])) {
    $veloria_wrapper_class .= ' veloria-image-box--hover-grayscale-off';
}
if (!empty($veloria_settings['hover_overlay_color'])) {
    $veloria_wrapper_class .= ' veloria-image-box--hover-overlay';
}
$this->add_render_attribute('wrapper', 'class', $veloria_wrapper_class);
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('description', 'none');
$veloria_has_link = !empty($veloria_settings['link']['url']);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_show_image_wrapper) : ?>
        <div class="veloria-image-box__image">
            <?php if ($veloria_has_link) : ?><a href="<?php echo esc_url($veloria_settings['link']['url']); ?>"><?php endif; ?>
            <?php
            if ($veloria_has_attachment) {
                echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($veloria_settings, 'image_size', 'image'));
            } else {
                $veloria_img_url = '';
                if ($veloria_has_visible_frontend_image && $veloria_url !== '') {
                    $veloria_img_url = $veloria_url;
                } elseif ($veloria_in_elementor_preview && $veloria_placeholder_src !== '') {
                    $veloria_img_url = ($veloria_url !== '') ? $veloria_url : $veloria_placeholder_src;
                }
                if ($veloria_img_url !== '') {
                    echo '<img src="' . esc_url($veloria_img_url) . '" alt="' . esc_attr($veloria_image['alt'] ?? $veloria_settings['title'] ?? '') . '">';
                }
            }
            ?>
            <?php if ($veloria_has_link) : ?></a><?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="veloria-image-box__content">
        <?php if (!empty($veloria_settings['show_title']) && $veloria_settings['show_title'] !== 'no') : ?>
            <?php if ($veloria_has_link) : ?><a href="<?php echo esc_url($veloria_settings['link']['url']); ?>"><?php endif; ?>
            <h3 class="veloria-image-box__title" <?php $this->print_render_attribute_string('title'); ?>><?php echo wp_kses_post($veloria_settings['title'] ?? ''); ?></h3>
            <?php if ($veloria_has_link) : ?></a><?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($veloria_settings['show_description']) && $veloria_settings['show_description'] !== 'no') : ?>
            <p class="veloria-image-box__description" <?php $this->print_render_attribute_string('description'); ?>><?php echo wp_kses_post($veloria_settings['description'] ?? ''); ?></p>
        <?php endif; ?>
    </div>
</div>
