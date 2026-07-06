<?php
defined('ABSPATH') || exit;

use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

$addonext_settings     = $this->get_settings_for_display();
$addonext_placeholder_src = Utils::get_placeholder_image_src();
$addonext_image           = $addonext_settings['image'] ?? [];
$addonext_url             = isset($addonext_image['url']) ? (string) $addonext_image['url'] : '';
$addonext_has_attachment  = !empty($addonext_image['id']);
$addonext_is_placeholder_only = $addonext_has_attachment === false && $addonext_url !== '' && $addonext_placeholder_src !== '' && $addonext_url === $addonext_placeholder_src;

$addonext_in_elementor_preview = false;
if (class_exists('\Elementor\Plugin')) {
    $addonext_preview = \Elementor\Plugin::$instance->preview ?? null;
    if (is_object($addonext_preview) && method_exists($addonext_preview, 'is_preview_mode')) {
        $addonext_in_elementor_preview = $addonext_preview->is_preview_mode();
    }
}

$addonext_has_visible_frontend_image = $addonext_has_attachment || ($addonext_url !== '' && !$addonext_is_placeholder_only);
$addonext_show_image_wrapper         = $addonext_has_visible_frontend_image || $addonext_in_elementor_preview;

$addonext_wrapper_class = 'addonext-image-box';
if (!empty($addonext_settings['image_bw_mode'])) {
    $addonext_wrapper_class .= ' addonext-image-box--bw';
}
if (!empty($addonext_settings['hover_effect']) && $addonext_settings['hover_effect'] !== 'none') {
    $addonext_wrapper_class .= ' addonext-image-box--hover-' . esc_attr($addonext_settings['hover_effect']);
}
if (!empty($addonext_settings['hover_grayscale_off'])) {
    $addonext_wrapper_class .= ' addonext-image-box--hover-grayscale-off';
}
if (!empty($addonext_settings['hover_overlay_color'])) {
    $addonext_wrapper_class .= ' addonext-image-box--hover-overlay';
}
$this->add_render_attribute('wrapper', 'class', $addonext_wrapper_class);
$this->add_inline_editing_attributes('title', 'none');
$this->add_inline_editing_attributes('description', 'none');
$addonext_has_link = !empty($addonext_settings['link']['url']);
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_show_image_wrapper) : ?>
        <div class="addonext-image-box__image">
            <?php if ($addonext_has_link) : ?><a href="<?php echo esc_url($addonext_settings['link']['url']); ?>"><?php endif; ?>
            <?php
            if ($addonext_has_attachment) {
                echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($addonext_settings, 'image_size', 'image'));
            } else {
                $addonext_img_url = '';
                if ($addonext_has_visible_frontend_image && $addonext_url !== '') {
                    $addonext_img_url = $addonext_url;
                } elseif ($addonext_in_elementor_preview && $addonext_placeholder_src !== '') {
                    $addonext_img_url = ($addonext_url !== '') ? $addonext_url : $addonext_placeholder_src;
                }
                if ($addonext_img_url !== '') {
                    echo '<img src="' . esc_url($addonext_img_url) . '" alt="' . esc_attr($addonext_image['alt'] ?? $addonext_settings['title'] ?? '') . '">';
                }
            }
            ?>
            <?php if ($addonext_has_link) : ?></a><?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="addonext-image-box__content">
        <?php if (!empty($addonext_settings['show_title']) && $addonext_settings['show_title'] !== 'no') : ?>
            <?php if ($addonext_has_link) : ?><a href="<?php echo esc_url($addonext_settings['link']['url']); ?>"><?php endif; ?>
            <h3 class="addonext-image-box__title" <?php $this->print_render_attribute_string('title'); ?>><?php echo wp_kses_post($addonext_settings['title'] ?? ''); ?></h3>
            <?php if ($addonext_has_link) : ?></a><?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($addonext_settings['show_description']) && $addonext_settings['show_description'] !== 'no') : ?>
            <p class="addonext-image-box__description" <?php $this->print_render_attribute_string('description'); ?>><?php echo wp_kses_post($addonext_settings['description'] ?? ''); ?></p>
        <?php endif; ?>
    </div>
</div>
