<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$items    = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];
$view     = isset($settings['view']) ? $settings['view'] : 'grid';

if (empty($items)) {
    return;
}

$placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$star_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';

function elementrify_testimonial_card_style($item) {
    $accent = isset($item['card_accent_color']) ? trim((string) $item['card_accent_color']) : '';
    if ($accent === '') {
        return '';
    }
    $parts = ['border-color: ' . esc_attr($accent) . ';'];
    $use_gradient = !empty($item['card_use_gradient']) && $item['card_use_gradient'] === 'yes';
    if ($use_gradient) {
        $hex = ltrim($accent, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $light = "rgba($r,$g,$b,0.14)";
        $fade = "rgba($r,$g,$b,0.04)";
        $parts[] = 'background: linear-gradient(135deg, ' . $light . ' 0%, ' . $fade . ' 45%, #ffffff 100%);';
    } else {
        $parts[] = 'background-color: #ffffff;';
    }
    return implode(' ', $parts);
}

$is_slider = ($view === 'slider' && count($items) > 1);
$columns   = ($view === 'grid' && isset($settings['columns'])) ? (int) $settings['columns'] : 3;
$column_gap = ($view === 'grid' && isset($settings['column_gap']['size'])) ? (int) $settings['column_gap']['size'] : 24;

$nav = isset($settings['navigation']) ? $settings['navigation'] : 'both';
$show_arrows = $is_slider && ($nav === 'both' || $nav === 'arrows');
$show_dots   = $is_slider && ($nav === 'both' || $nav === 'dots');
$autoplay    = $is_slider && !empty($settings['autoplay']) && $settings['autoplay'] === 'yes';
$autoplay_delay = ($autoplay && isset($settings['autoplay_delay']) && (int) $settings['autoplay_delay'] > 0) ? (int) $settings['autoplay_delay'] : 5;
$loop        = $is_slider && (empty($settings['loop']) || $settings['loop'] === 'yes');
$pagination_type = isset($settings['pagination_type']) ? $settings['pagination_type'] : 'bullets';
$dots_gap    = isset($settings['dots_gap']['size']) ? (int) $settings['dots_gap']['size'] : 8;
$bar_width   = ($pagination_type === 'progressbar' && isset($settings['bar_width']['size'])) ? (int) $settings['bar_width']['size'] : 28;
$slides_per_view = $is_slider && isset($settings['slides_per_view']) ? max(1, min(5, (int) $settings['slides_per_view'])) : 3;
$slide_gap   = $is_slider && isset($settings['slide_gap']['size']) ? (int) $settings['slide_gap']['size'] : 24;

$pagination_class = '';
if ($pagination_type === 'square') {
    $pagination_class = ' elementrify-pagination-square';
} elseif ($pagination_type === 'progressbar') {
    $pagination_class = ' elementrify-pagination-bar';
}

$inline_style = '';
if ($view === 'grid') {
    $inline_style = '--elementrify-testimonials-columns: ' . $columns . '; --elementrify-testimonials-gap: ' . $column_gap . 'px;';
} else {
    $inline_style = '--elementrify-dots-gap: ' . $dots_gap . 'px;';
    if ($pagination_type === 'progressbar') {
        $inline_style .= ' --elementrify-bar-width: ' . $bar_width . 'px;';
    }
}

if ($view === 'grid') :
?>
<div class="elementrify-testimonials elementrify-testimonials--grid" style="<?php echo esc_attr($inline_style); ?>">
    <?php foreach ($items as $item) :
        $avatar_url = isset($item['image']['url']) ? trim((string) $item['image']['url']) : '';
        if ($avatar_url === '' && !empty($item['image']['id'])) {
            $src = wp_get_attachment_image_src((int) $item['image']['id'], 'thumbnail');
            if (!empty($src[0])) {
                $avatar_url = $src[0];
            }
        }
        if ($avatar_url === '') {
            $avatar_url = $placeholder_src;
        }
        $rating = isset($item['rating']) ? min(5.0, max(0.0, (float) $item['rating'])) : 5.0;
        $card_style = elementrify_testimonial_card_style($item);
    ?>
        <div class="elementrify-testimonial-card"<?php echo $card_style !== '' ? ' style="' . esc_attr($card_style) . '"' : ''; ?>>
            <div class="elementrify-testimonial__stars">
                <?php for ($i = 0; $i < 5; $i++) :
                    $remainder = $rating - $i;
                    $fill = min(1.0, max(0.0, $remainder));
                    $fill_pct = (int) round($fill * 100);
                ?>
                    <span class="elementrify-testimonial__star">
                        <span class="elementrify-testimonial__star-empty"><?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                        echo $star_svg;
                        ?></span>
                        <span class="elementrify-testimonial__star-fill" style="width:<?php echo esc_attr($fill_pct); ?>%">
                            <span class="elementrify-testimonial__star-filled"><?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                            echo $star_svg;
                            ?></span>
                        </span>
                    </span>
                <?php endfor; ?>
            </div>
            <blockquote class="elementrify-testimonial-card__quote">"<?php echo wp_kses_post($item['content'] ?? ''); ?>"</blockquote>
            <div class="elementrify-testimonial-card__footer">
                <img class="elementrify-testimonial-card__avatar" src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($item['name'] ?? ''); ?>">
                <div class="elementrify-testimonial-card__meta">
                    <span class="elementrify-testimonial-card__name"><?php echo esc_html($item['name'] ?? ''); ?></span>
                    <?php if (!empty($item['role'])) : ?>
                        <span class="elementrify-testimonial-card__role"><?php echo esc_html($item['role']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
else :
?>
<div class="elementrify-testimonials elementrify-testimonials--slider swiper<?php echo esc_attr($pagination_class); ?>" data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>" data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>" data-loop="<?php echo $loop ? '1' : '0'; ?>" data-pagination-type="<?php echo esc_attr($pagination_type); ?>" data-slides-per-view="<?php echo esc_attr($slides_per_view); ?>" data-space-between="<?php echo esc_attr($slide_gap); ?>" style="<?php echo esc_attr($inline_style); ?>">
    <div class="swiper-wrapper">
        <?php foreach ($items as $item) :
            $avatar_url = isset($item['image']['url']) ? trim((string) $item['image']['url']) : '';
            if ($avatar_url === '' && !empty($item['image']['id'])) {
                $src = wp_get_attachment_image_src((int) $item['image']['id'], 'thumbnail');
                if (!empty($src[0])) {
                    $avatar_url = $src[0];
                }
            }
            if ($avatar_url === '') {
                $avatar_url = $placeholder_src;
            }
            $rating = isset($item['rating']) ? min(5.0, max(0.0, (float) $item['rating'])) : 5.0;
            $card_style = elementrify_testimonial_card_style($item);
        ?>
            <div class="swiper-slide">
                <div class="elementrify-testimonial-card"<?php echo $card_style !== '' ? ' style="' . esc_attr($card_style) . '"' : ''; ?>>
                    <div class="elementrify-testimonial__stars">
                        <?php for ($i = 0; $i < 5; $i++) :
                            $remainder = $rating - $i;
                            $fill = min(1.0, max(0.0, $remainder));
                            $fill_pct = (int) round($fill * 100);
                        ?>
                            <span class="elementrify-testimonial__star">
                                <span class="elementrify-testimonial__star-empty"><?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                                echo $star_svg;
                                ?></span>
                                <span class="elementrify-testimonial__star-fill" style="width:<?php echo esc_attr($fill_pct); ?>%">
                                    <span class="elementrify-testimonial__star-filled"><?php
                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                                    echo $star_svg;
                                    ?></span>
                                </span>
                            </span>
                        <?php endfor; ?>
                    </div>
                    <blockquote class="elementrify-testimonial-card__quote">"<?php echo wp_kses_post($item['content'] ?? ''); ?>"</blockquote>
                    <div class="elementrify-testimonial-card__footer">
                        <img class="elementrify-testimonial-card__avatar" src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($item['name'] ?? ''); ?>">
                        <div class="elementrify-testimonial-card__meta">
                            <span class="elementrify-testimonial-card__name"><?php echo esc_html($item['name'] ?? ''); ?></span>
                            <?php if (!empty($item['role'])) : ?>
                                <span class="elementrify-testimonial-card__role"><?php echo esc_html($item['role']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($show_arrows) : ?>
        <div class="elementrify-testimonials__arrow elementrify-testimonials__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'elementrify'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="elementrify-testimonials__arrow elementrify-testimonials__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'elementrify'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
<?php
endif;
