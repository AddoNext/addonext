<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$logos = isset($settings['logos']) && is_array($settings['logos']) ? $settings['logos'] : [];
$view = isset($settings['view']) ? $settings['view'] : 'grid';
$show_title = !empty($settings['show_title']) && $settings['show_title'] === 'yes';
$title = $show_title && isset($settings['title']) ? $settings['title'] : '';

if (empty($logos)) {
    return;
}

$placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$is_slider = ($view === 'slider' && count($logos) > 1);
$columns_mode = ($view === 'grid' && isset($settings['columns_mode'])) ? $settings['columns_mode'] : 'auto';
$columns = ($view === 'grid' && $columns_mode === 'custom' && isset($settings['columns'])) ? max(1, min(6, (int) $settings['columns'])) : 4;
$column_gap = ($view === 'grid' && isset($settings['column_gap']['size'])) ? (int) $settings['column_gap']['size'] : 24;
$grid_auto_class = ($view === 'grid' && $columns_mode === 'auto') ? ' elementrify-logo-showcase__grid--auto' : '';
$nav = isset($settings['navigation']) ? $settings['navigation'] : 'none';
$show_arrows = $is_slider && ($nav === 'both' || $nav === 'arrows');
$show_dots = $is_slider && ($nav === 'both' || $nav === 'dots');
$autoplay = $is_slider && !empty($settings['autoplay']) && $settings['autoplay'] === 'yes';
$autoplay_delay = ($autoplay && isset($settings['autoplay_delay']) && (int) $settings['autoplay_delay'] > 0) ? (int) $settings['autoplay_delay'] : 3;
$loop = $is_slider && (empty($settings['loop']) || $settings['loop'] === 'yes');
$pagination_type = isset($settings['pagination_type']) ? $settings['pagination_type'] : 'bullets';
$dots_gap = isset($settings['dots_gap']['size']) ? (int) $settings['dots_gap']['size'] : 8;
$slides_per_view = $is_slider && isset($settings['slides_per_view']) ? max(1, min(6, (int) $settings['slides_per_view'])) : 4;
$slide_gap = $is_slider && isset($settings['slide_gap']['size']) ? (int) $settings['slide_gap']['size'] : 24;

$pagination_class = '';
if ($pagination_type === 'square') {
    $pagination_class = ' elementrify-pagination-square';
} elseif ($pagination_type === 'progressbar') {
    $pagination_class = ' elementrify-pagination-bar';
}

$inline_style = '';
if ($view === 'grid') {
    $inline_style = '--elementrify-logo-showcase-gap: ' . $column_gap . 'px;';
    if ($columns_mode === 'custom') {
        $inline_style .= ' --elementrify-logo-showcase-columns: ' . $columns . ';';
    }
} else {
    $inline_style = '--elementrify-dots-gap: ' . $dots_gap . 'px;';
}

function elementrify_logo_showcase_img_url($item, $placeholder_src) {
    $url = isset($item['logo']['url']) ? trim((string) $item['logo']['url']) : '';
    if ($url === '' && !empty($item['logo']['id'])) {
        $src = wp_get_attachment_image_src((int) $item['logo']['id'], 'medium');
        if (!empty($src[0])) {
            $url = $src[0];
        }
    }
    return $url !== '' ? $url : $placeholder_src;
}
?>
<div class="elementrify-logo-showcase">
    <?php if ($show_title && $title !== '') : ?>
        <h3 class="elementrify-logo-showcase__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    <?php if ($view === 'grid') : ?>
        <div class="elementrify-logo-showcase__grid<?php echo esc_attr($grid_auto_class); ?>" style="<?php echo esc_attr($inline_style); ?>">
            <?php foreach ($logos as $item) :
                $img_url = elementrify_logo_showcase_img_url($item, $placeholder_src);
                $link_url = !empty($item['link']['url']) ? esc_url($item['link']['url']) : '';
                $alt = isset($item['alt']) ? $item['alt'] : '';
            ?>
                <div class="elementrify-logo-showcase__card">
                    <?php if ($link_url !== '') : ?><a href="<?php echo esc_url($link_url); ?>" class="elementrify-logo-showcase__link"><?php endif; ?>
                        <img class="elementrify-logo-showcase__img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($alt); ?>">
                    <?php if ($link_url !== '') : ?></a><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="elementrify-logo-showcase--slider swiper<?php echo esc_attr($pagination_class); ?>"
             data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>"
             data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>"
             data-loop="<?php echo $loop ? '1' : '0'; ?>"
             data-pagination-type="<?php echo esc_attr($pagination_type); ?>"
             data-slides-per-view="<?php echo esc_attr($slides_per_view); ?>"
             data-space-between="<?php echo esc_attr($slide_gap); ?>"
             style="<?php echo esc_attr($inline_style); ?>">
            <div class="swiper-wrapper">
                <?php foreach ($logos as $item) :
                    $img_url = elementrify_logo_showcase_img_url($item, $placeholder_src);
                    $link_url = !empty($item['link']['url']) ? esc_url($item['link']['url']) : '';
                    $alt = isset($item['alt']) ? $item['alt'] : '';
                ?>
                    <div class="swiper-slide">
                        <div class="elementrify-logo-showcase__card">
                            <?php if ($link_url !== '') : ?><a href="<?php echo esc_url($link_url); ?>" class="elementrify-logo-showcase__link"><?php endif; ?>
                                <img class="elementrify-logo-showcase__img" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($alt); ?>">
                            <?php if ($link_url !== '') : ?></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($show_arrows) : ?>
                <div class="elementrify-logo-showcase__arrow elementrify-logo-showcase__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'elementrify'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                </div>
                <div class="elementrify-logo-showcase__arrow elementrify-logo-showcase__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'elementrify'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                </div>
            <?php endif; ?>
            <?php if ($show_dots) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
