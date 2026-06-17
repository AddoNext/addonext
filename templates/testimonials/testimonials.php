<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_items    = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];
$veloria_view     = isset($veloria_settings['view']) ? $veloria_settings['view'] : 'grid';

if (empty($veloria_items)) {
    return;
}

$veloria_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$veloria_star_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';

function veloria_testimonial_card_style($veloria_item) {
    $veloria_accent = isset($veloria_item['card_accent_color']) ? trim((string) $veloria_item['card_accent_color']) : '';
    if ($veloria_accent === '') {
        return '';
    }
    $veloria_parts = ['border-color: ' . esc_attr($veloria_accent) . ';'];
    $veloria_use_gradient = !empty($veloria_item['card_use_gradient']) && $veloria_item['card_use_gradient'] === 'yes';
    if ($veloria_use_gradient) {
        $veloria_hex = ltrim($veloria_accent, '#');
        if (strlen($veloria_hex) === 3) {
            $veloria_hex = $veloria_hex[0] . $veloria_hex[0] . $veloria_hex[1] . $veloria_hex[1] . $veloria_hex[2] . $veloria_hex[2];
        }
        $veloria_r = hexdec(substr($veloria_hex, 0, 2));
        $veloria_g = hexdec(substr($veloria_hex, 2, 2));
        $veloria_b = hexdec(substr($veloria_hex, 4, 2));
        $veloria_light = "rgba($veloria_r,$veloria_g,$veloria_b,0.14)";
        $veloria_fade = "rgba($veloria_r,$veloria_g,$veloria_b,0.04)";
        $veloria_parts[] = 'background: linear-gradient(135deg, ' . $veloria_light . ' 0%, ' . $veloria_fade . ' 45%, #ffffff 100%);';
    }
    return implode(' ', $veloria_parts);
}

$veloria_is_slider = ($veloria_view === 'slider' && count($veloria_items) > 1);
$veloria_columns   = ($veloria_view === 'grid' && isset($veloria_settings['columns'])) ? (int) $veloria_settings['columns'] : 3;
$veloria_column_gap = ($veloria_view === 'grid' && isset($veloria_settings['column_gap']['size'])) ? (int) $veloria_settings['column_gap']['size'] : 24;

$veloria_nav = isset($veloria_settings['navigation']) ? $veloria_settings['navigation'] : 'both';
$veloria_show_arrows = $veloria_is_slider && ($veloria_nav === 'both' || $veloria_nav === 'arrows');
$veloria_show_dots   = $veloria_is_slider && ($veloria_nav === 'both' || $veloria_nav === 'dots');
$veloria_autoplay    = $veloria_is_slider && !empty($veloria_settings['autoplay']) && $veloria_settings['autoplay'] === 'yes';
$veloria_autoplay_delay = ($veloria_autoplay && isset($veloria_settings['autoplay_delay']) && (int) $veloria_settings['autoplay_delay'] > 0) ? (int) $veloria_settings['autoplay_delay'] : 5;
$veloria_loop        = $veloria_is_slider && (empty($veloria_settings['loop']) || $veloria_settings['loop'] === 'yes');
$veloria_pagination_type = isset($veloria_settings['pagination_type']) ? $veloria_settings['pagination_type'] : 'bullets';
$veloria_dots_gap    = isset($veloria_settings['dots_gap']['size']) ? (int) $veloria_settings['dots_gap']['size'] : 8;
$veloria_bar_width   = ($veloria_pagination_type === 'progressbar' && isset($veloria_settings['bar_width']['size'])) ? (int) $veloria_settings['bar_width']['size'] : 28;
$veloria_slides_per_view = $veloria_is_slider && isset($veloria_settings['slides_per_view']) ? max(1, min(5, (int) $veloria_settings['slides_per_view'])) : 3;
$veloria_slide_gap   = $veloria_is_slider && isset($veloria_settings['slide_gap']['size']) ? (int) $veloria_settings['slide_gap']['size'] : 24;

$veloria_pagination_class = '';
if ($veloria_pagination_type === 'square') {
    $veloria_pagination_class = ' veloria-pagination-square';
} elseif ($veloria_pagination_type === 'progressbar') {
    $veloria_pagination_class = ' veloria-pagination-bar';
}

$veloria_inline_style = '';
if ($veloria_view === 'grid') {
    $veloria_inline_style = '--veloria-testimonials-columns: ' . $veloria_columns . '; --veloria-testimonials-gap: ' . $veloria_column_gap . 'px;';
} else {
    $veloria_inline_style = '--veloria-dots-gap: ' . $veloria_dots_gap . 'px;';
    if ($veloria_pagination_type === 'progressbar') {
        $veloria_inline_style .= ' --veloria-bar-width: ' . $veloria_bar_width . 'px;';
    }
}

if ($veloria_view === 'grid') :
?>
<div class="veloria-testimonials veloria-testimonials--grid" style="<?php echo esc_attr($veloria_inline_style); ?>">
    <?php foreach ($veloria_items as $veloria_item) :
        $veloria_avatar_url = isset($veloria_item['image']['url']) ? trim((string) $veloria_item['image']['url']) : '';
        if ($veloria_avatar_url === '' && !empty($veloria_item['image']['id'])) {
            $veloria_src = wp_get_attachment_image_src((int) $veloria_item['image']['id'], 'thumbnail');
            if (!empty($veloria_src[0])) {
                $veloria_avatar_url = $veloria_src[0];
            }
        }
        if ($veloria_avatar_url === '') {
            $veloria_avatar_url = $veloria_placeholder_src;
        }
        $veloria_rating = isset($veloria_item['rating']) ? min(5.0, max(0.0, (float) $veloria_item['rating'])) : 5.0;
        $veloria_card_style = veloria_testimonial_card_style($veloria_item);
    ?>
        <div class="veloria-testimonial-card"<?php echo $veloria_card_style !== '' ? ' style="' . esc_attr($veloria_card_style) . '"' : ''; ?>>
            <div class="veloria-testimonial__stars">
                <?php for ($veloria_i = 0; $veloria_i < 5; $veloria_i++) :
                    $veloria_remainder = $veloria_rating - $veloria_i;
                    $veloria_fill = min(1.0, max(0.0, $veloria_remainder));
                    $veloria_fill_pct = (int) round($veloria_fill * 100);
                ?>
                    <span class="veloria-testimonial__star">
                        <span class="veloria-testimonial__star-empty"><?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                        echo $veloria_star_svg;
                        ?></span>
                        <span class="veloria-testimonial__star-fill" style="width:<?php echo esc_attr($veloria_fill_pct); ?>%">
                            <span class="veloria-testimonial__star-filled"><?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                            echo $veloria_star_svg;
                            ?></span>
                        </span>
                    </span>
                <?php endfor; ?>
            </div>
            <blockquote class="veloria-testimonial-card__quote">"<?php echo wp_kses_post($veloria_item['content'] ?? ''); ?>"</blockquote>
            <div class="veloria-testimonial-card__footer">
                <img class="veloria-testimonial-card__avatar" src="<?php echo esc_url($veloria_avatar_url); ?>" alt="<?php echo esc_attr($veloria_item['name'] ?? ''); ?>">
                <div class="veloria-testimonial-card__meta">
                    <span class="veloria-testimonial-card__name"><?php echo esc_html($veloria_item['name'] ?? ''); ?></span>
                    <?php if (!empty($veloria_item['role'])) : ?>
                        <span class="veloria-testimonial-card__role"><?php echo esc_html($veloria_item['role']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
else :
?>
<div class="veloria-testimonials veloria-testimonials--slider swiper<?php echo esc_attr($veloria_pagination_class); ?>" data-autoplay="<?php echo $veloria_autoplay ? '1' : '0'; ?>" data-autoplay-delay="<?php echo esc_attr($veloria_autoplay_delay); ?>" data-loop="<?php echo $veloria_loop ? '1' : '0'; ?>" data-pagination-type="<?php echo esc_attr($veloria_pagination_type); ?>" data-slides-per-view="<?php echo esc_attr($veloria_slides_per_view); ?>" data-space-between="<?php echo esc_attr($veloria_slide_gap); ?>" style="<?php echo esc_attr($veloria_inline_style); ?>">
    <div class="swiper-wrapper">
        <?php foreach ($veloria_items as $veloria_item) :
            $veloria_avatar_url = isset($veloria_item['image']['url']) ? trim((string) $veloria_item['image']['url']) : '';
            if ($veloria_avatar_url === '' && !empty($veloria_item['image']['id'])) {
                $veloria_src = wp_get_attachment_image_src((int) $veloria_item['image']['id'], 'thumbnail');
                if (!empty($veloria_src[0])) {
                    $veloria_avatar_url = $veloria_src[0];
                }
            }
            if ($veloria_avatar_url === '') {
                $veloria_avatar_url = $veloria_placeholder_src;
            }
            $veloria_rating = isset($veloria_item['rating']) ? min(5.0, max(0.0, (float) $veloria_item['rating'])) : 5.0;
            $veloria_card_style = veloria_testimonial_card_style($veloria_item);
        ?>
            <div class="swiper-slide">
                <div class="veloria-testimonial-card"<?php echo $veloria_card_style !== '' ? ' style="' . esc_attr($veloria_card_style) . '"' : ''; ?>>
                    <div class="veloria-testimonial__stars">
                        <?php for ($veloria_i = 0; $veloria_i < 5; $veloria_i++) :
                            $veloria_remainder = $veloria_rating - $veloria_i;
                            $veloria_fill = min(1.0, max(0.0, $veloria_remainder));
                            $veloria_fill_pct = (int) round($veloria_fill * 100);
                        ?>
                            <span class="veloria-testimonial__star">
                                <span class="veloria-testimonial__star-empty"><?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                                echo $veloria_star_svg;
                                ?></span>
                                <span class="veloria-testimonial__star-fill" style="width:<?php echo esc_attr($veloria_fill_pct); ?>%">
                                    <span class="veloria-testimonial__star-filled"><?php
                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                                    echo $veloria_star_svg;
                                    ?></span>
                                </span>
                            </span>
                        <?php endfor; ?>
                    </div>
                    <blockquote class="veloria-testimonial-card__quote">"<?php echo wp_kses_post($veloria_item['content'] ?? ''); ?>"</blockquote>
                    <div class="veloria-testimonial-card__footer">
                        <img class="veloria-testimonial-card__avatar" src="<?php echo esc_url($veloria_avatar_url); ?>" alt="<?php echo esc_attr($veloria_item['name'] ?? ''); ?>">
                        <div class="veloria-testimonial-card__meta">
                            <span class="veloria-testimonial-card__name"><?php echo esc_html($veloria_item['name'] ?? ''); ?></span>
                            <?php if (!empty($veloria_item['role'])) : ?>
                                <span class="veloria-testimonial-card__role"><?php echo esc_html($veloria_item['role']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($veloria_show_arrows) : ?>
        <div class="veloria-testimonials__arrow veloria-testimonials__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="veloria-testimonials__arrow veloria-testimonials__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($veloria_show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
<?php
endif;
