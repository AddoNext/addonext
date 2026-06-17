<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_slides   = isset($veloria_settings['slides']) && is_array($veloria_settings['slides']) ? $veloria_settings['slides'] : [];
$veloria_nav      = isset($veloria_settings['navigation']) ? $veloria_settings['navigation'] : 'both';
$veloria_show_arrows = ($veloria_nav === 'both' || $veloria_nav === 'arrows') && count($veloria_slides) > 1;
$veloria_show_dots   = ($veloria_nav === 'both' || $veloria_nav === 'dots') && count($veloria_slides) > 1;
$veloria_autoplay = !empty($veloria_settings['autoplay']) && $veloria_settings['autoplay'] === 'yes';
$veloria_autoplay_delay = ($veloria_autoplay && isset($veloria_settings['autoplay_delay']) && (int) $veloria_settings['autoplay_delay'] > 0) ? (int) $veloria_settings['autoplay_delay'] : 5;
$veloria_loop     = empty($veloria_settings['loop']) || $veloria_settings['loop'] === 'yes';
$veloria_pagination_type = isset($veloria_settings['pagination_type']) ? $veloria_settings['pagination_type'] : 'bullets';
$veloria_dots_gap = isset($veloria_settings['dots_gap']['size']) ? (int) $veloria_settings['dots_gap']['size'] : 8;
$veloria_bar_width = ($veloria_pagination_type === 'progressbar' && isset($veloria_settings['bar_width']['size'])) ? (int) $veloria_settings['bar_width']['size'] : 28;

if (empty($veloria_slides)) {
    return;
}

$veloria_image_size_name = isset($veloria_settings['image_size_size']) ? $veloria_settings['image_size_size'] : 'large';
$veloria_image_size_custom = isset($veloria_settings['image_size_custom_dimension']) ? $veloria_settings['image_size_custom_dimension'] : ['width' => '', 'height' => ''];
$veloria_use_custom_size = ($veloria_image_size_name === 'custom' && (!empty($veloria_image_size_custom['width']) || !empty($veloria_image_size_custom['height'])));

$veloria_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$veloria_autoplay_attr = $veloria_autoplay ? '1' : '0';
$veloria_loop_attr     = $veloria_loop ? '1' : '0';
$veloria_pagination_class = '';
if ($veloria_pagination_type === 'square') {
    $veloria_pagination_class = ' veloria-pagination-square';
} elseif ($veloria_pagination_type === 'progressbar') {
    $veloria_pagination_class = ' veloria-pagination-bar';
}
$veloria_inline_style = '--veloria-dots-gap: ' . $veloria_dots_gap . 'px;';
if ($veloria_pagination_type === 'progressbar') {
    $veloria_inline_style .= ' --veloria-bar-width: ' . $veloria_bar_width . 'px;';
}
$veloria_carousel_height = isset($veloria_settings['carousel_height']['size']) && $veloria_settings['carousel_height']['size'] !== '' ? (float) $veloria_settings['carousel_height']['size'] : 0;
$veloria_carousel_height_unit = isset($veloria_settings['carousel_height']['unit']) ? $veloria_settings['carousel_height']['unit'] : 'px';
if ($veloria_carousel_height > 0) {
    $veloria_inline_style .= ' --veloria-carousel-height: ' . $veloria_carousel_height . $veloria_carousel_height_unit . ';';
}
?>
<div class="veloria-image-carousel swiper swiper-autoheight<?php echo esc_attr($veloria_pagination_class); ?>" data-autoplay="<?php echo esc_attr($veloria_autoplay_attr); ?>" data-autoplay-delay="<?php echo esc_attr($veloria_autoplay_delay); ?>" data-loop="<?php echo esc_attr($veloria_loop_attr); ?>" data-pagination-type="<?php echo esc_attr($veloria_pagination_type); ?>" style="<?php echo esc_attr($veloria_inline_style); ?>">
    <div class="veloria-image-carousel__wrapper swiper-wrapper">
        <?php foreach ($veloria_slides as $veloria_slide) :
            $veloria_img = isset($veloria_slide['image']) && is_array($veloria_slide['image']) ? $veloria_slide['image'] : [];
            $veloria_id  = isset($veloria_img['id']) ? (int) $veloria_img['id'] : 0;
            $veloria_caption = isset($veloria_slide['caption']) ? $veloria_slide['caption'] : '';
            $veloria_url = isset($veloria_img['url']) ? trim((string) $veloria_img['url']) : '';
            if ($veloria_id) {
                if ($veloria_use_custom_size) {
                    $veloria_img_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($veloria_id, 'image_size', $veloria_settings);
                    if (!empty($veloria_img_src)) {
                        $veloria_url = $veloria_img_src;
                    }
                } else {
                    $veloria_img_src = wp_get_attachment_image_src($veloria_id, $veloria_image_size_name);
                    if (!empty($veloria_img_src[0])) {
                        $veloria_url = $veloria_img_src[0];
                    }
                }
                if ($veloria_url === '' && $veloria_id) {
                    $veloria_full = wp_get_attachment_image_src($veloria_id, 'full');
                    if (!empty($veloria_full[0])) {
                        $veloria_url = $veloria_full[0];
                    }
                }
            }
            if ($veloria_url === '') {
                $veloria_url = $veloria_placeholder_src;
            }
        ?>
            <div class="veloria-image-carousel__slide swiper-slide">
                <img src="<?php echo esc_url($veloria_url); ?>" alt="<?php echo esc_attr($veloria_caption); ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($veloria_show_arrows) : ?>
        <div class="veloria-image-carousel__arrow veloria-image-carousel__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="veloria-image-carousel__arrow veloria-image-carousel__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($veloria_show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
