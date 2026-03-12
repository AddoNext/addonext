<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$slides   = isset($settings['slides']) && is_array($settings['slides']) ? $settings['slides'] : [];
$nav      = isset($settings['navigation']) ? $settings['navigation'] : 'both';
$show_arrows = ($nav === 'both' || $nav === 'arrows') && count($slides) > 1;
$show_dots   = ($nav === 'both' || $nav === 'dots') && count($slides) > 1;
$autoplay = !empty($settings['autoplay']) && $settings['autoplay'] === 'yes';
$autoplay_delay = ($autoplay && isset($settings['autoplay_delay']) && (int) $settings['autoplay_delay'] > 0) ? (int) $settings['autoplay_delay'] : 5;
$loop     = empty($settings['loop']) || $settings['loop'] === 'yes';
$pagination_type = isset($settings['pagination_type']) ? $settings['pagination_type'] : 'bullets';
$dots_gap = isset($settings['dots_gap']['size']) ? (int) $settings['dots_gap']['size'] : 8;
$bar_width = ($pagination_type === 'progressbar' && isset($settings['bar_width']['size'])) ? (int) $settings['bar_width']['size'] : 28;

if (empty($slides)) {
    return;
}

$image_size_name = isset($settings['image_size_size']) ? $settings['image_size_size'] : 'large';
$image_size_custom = isset($settings['image_size_custom_dimension']) ? $settings['image_size_custom_dimension'] : ['width' => '', 'height' => ''];
$use_custom_size = ($image_size_name === 'custom' && (!empty($image_size_custom['width']) || !empty($image_size_custom['height'])));

$placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$autoplay_attr = $autoplay ? '1' : '0';
$loop_attr     = $loop ? '1' : '0';
$pagination_class = '';
if ($pagination_type === 'square') {
    $pagination_class = ' elementrify-pagination-square';
} elseif ($pagination_type === 'progressbar') {
    $pagination_class = ' elementrify-pagination-bar';
}
$inline_style = '--elementrify-dots-gap: ' . $dots_gap . 'px;';
if ($pagination_type === 'progressbar') {
    $inline_style .= ' --elementrify-bar-width: ' . $bar_width . 'px;';
}
$carousel_height = isset($settings['carousel_height']['size']) && $settings['carousel_height']['size'] !== '' ? (float) $settings['carousel_height']['size'] : 0;
$carousel_height_unit = isset($settings['carousel_height']['unit']) ? $settings['carousel_height']['unit'] : 'px';
if ($carousel_height > 0) {
    $inline_style .= ' --elementrify-carousel-height: ' . $carousel_height . $carousel_height_unit . ';';
}
?>
<div class="elementrify-image-carousel swiper swiper-autoheight<?php echo esc_attr($pagination_class); ?>" data-autoplay="<?php echo esc_attr($autoplay_attr); ?>" data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>" data-loop="<?php echo esc_attr($loop_attr); ?>" data-pagination-type="<?php echo esc_attr($pagination_type); ?>" style="<?php echo esc_attr($inline_style); ?>">
    <div class="elementrify-image-carousel__wrapper swiper-wrapper">
        <?php foreach ($slides as $slide) :
            $img = isset($slide['image']) && is_array($slide['image']) ? $slide['image'] : [];
            $id  = isset($img['id']) ? (int) $img['id'] : 0;
            $caption = isset($slide['caption']) ? $slide['caption'] : '';
            $url = isset($img['url']) ? trim((string) $img['url']) : '';
            if ($id) {
                if ($use_custom_size) {
                    $img_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($id, 'image_size', $settings);
                    if (!empty($img_src)) {
                        $url = $img_src;
                    }
                } else {
                    $img_src = wp_get_attachment_image_src($id, $image_size_name);
                    if (!empty($img_src[0])) {
                        $url = $img_src[0];
                    }
                }
                if ($url === '' && $id) {
                    $full = wp_get_attachment_image_src($id, 'full');
                    if (!empty($full[0])) {
                        $url = $full[0];
                    }
                }
            }
            if ($url === '') {
                $url = $placeholder_src;
            }
        ?>
            <div class="elementrify-image-carousel__slide swiper-slide">
                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($caption); ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($show_arrows) : ?>
        <div class="elementrify-image-carousel__arrow elementrify-image-carousel__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'elementrify'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="elementrify-image-carousel__arrow elementrify-image-carousel__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'elementrify'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
