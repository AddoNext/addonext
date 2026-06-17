<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_items = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];
$veloria_view = isset($veloria_settings['view']) ? $veloria_settings['view'] : 'grid';

if (empty($veloria_items)) {
    return;
}

$veloria_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$veloria_is_slider = ($veloria_view === 'slider' && count($veloria_items) > 1);
$veloria_columns = ($veloria_view === 'grid' && isset($veloria_settings['columns'])) ? max(1, min(6, (int) $veloria_settings['columns'])) : 3;
$veloria_column_gap = ($veloria_view === 'grid' && isset($veloria_settings['column_gap']['size'])) ? (int) $veloria_settings['column_gap']['size'] : 24;

$veloria_nav = isset($veloria_settings['navigation']) ? $veloria_settings['navigation'] : 'both';
$veloria_show_arrows = $veloria_is_slider && ($veloria_nav === 'both' || $veloria_nav === 'arrows');
$veloria_show_dots = $veloria_is_slider && ($veloria_nav === 'both' || $veloria_nav === 'dots');
$veloria_autoplay = $veloria_is_slider && !empty($veloria_settings['autoplay']) && $veloria_settings['autoplay'] === 'yes';
$veloria_autoplay_delay = ($veloria_autoplay && isset($veloria_settings['autoplay_delay']) && (int) $veloria_settings['autoplay_delay'] > 0) ? (int) $veloria_settings['autoplay_delay'] : 5;
$veloria_loop = $veloria_is_slider && (empty($veloria_settings['loop']) || $veloria_settings['loop'] === 'yes');
$veloria_pagination_type = isset($veloria_settings['pagination_type']) ? $veloria_settings['pagination_type'] : 'bullets';
$veloria_dots_gap = isset($veloria_settings['dots_gap']['size']) ? (int) $veloria_settings['dots_gap']['size'] : 8;
$veloria_slides_per_view = $veloria_is_slider && isset($veloria_settings['slides_per_view']) ? max(1, min(5, (int) $veloria_settings['slides_per_view'])) : 3;
$veloria_slide_gap = $veloria_is_slider && isset($veloria_settings['slide_gap']['size']) ? (int) $veloria_settings['slide_gap']['size'] : 24;

$veloria_pagination_class = '';
if ($veloria_pagination_type === 'square') {
    $veloria_pagination_class = ' veloria-pagination-square';
} elseif ($veloria_pagination_type === 'progressbar') {
    $veloria_pagination_class = ' veloria-pagination-bar';
}

$veloria_inline_style = '';
if ($veloria_view === 'grid') {
    $veloria_inline_style = '--veloria-team-members-columns: ' . $veloria_columns . '; --veloria-team-members-gap: ' . $veloria_column_gap . 'px;';
} else {
    $veloria_inline_style = '--veloria-dots-gap: ' . $veloria_dots_gap . 'px;';
}

function veloria_team_members_card_social($veloria_item) {
    $veloria_linkedin_url = !empty($veloria_item['link_linkedin']['url']) ? esc_url($veloria_item['link_linkedin']['url']) : '#';
    $veloria_twitter_url = !empty($veloria_item['link_twitter']['url']) ? esc_url($veloria_item['link_twitter']['url']) : '#';
    $veloria_linkedin_attr = $veloria_linkedin_url !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
    $veloria_twitter_attr = $veloria_twitter_url !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
    return '<a href="' . $veloria_linkedin_url . '" aria-label="LinkedIn"' . $veloria_linkedin_attr . '><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H4.5v8.37h3.77z"/></svg></a>'
        . '<a href="' . $veloria_twitter_url . '" aria-label="Twitter"' . $veloria_twitter_attr . '><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>';
}

if ($veloria_view === 'grid') :
?>
<div class="veloria-team-members veloria-team-members--grid" style="<?php echo esc_attr($veloria_inline_style); ?>">
    <?php foreach ($veloria_items as $veloria_item) :
        $veloria_img_url = isset($veloria_item['image']['url']) ? trim((string) $veloria_item['image']['url']) : '';
        if ($veloria_img_url === '' && !empty($veloria_item['image']['id'])) {
            $veloria_src = wp_get_attachment_image_src((int) $veloria_item['image']['id'], 'medium');
            if (!empty($veloria_src[0])) {
                $veloria_img_url = $veloria_src[0];
            }
        }
        if ($veloria_img_url === '') {
            $veloria_img_url = $veloria_placeholder_src;
        }
        $veloria_name = isset($veloria_item['name']) ? $veloria_item['name'] : '';
        $veloria_role = isset($veloria_item['role']) ? $veloria_item['role'] : '';
    ?>
        <div class="veloria-team-members-card">
            <img class="veloria-team-members-card__image" src="<?php echo esc_url($veloria_img_url); ?>" alt="<?php echo esc_attr($veloria_name); ?>">
            <h3 class="veloria-team-members-card__name"><?php echo esc_html($veloria_name); ?></h3>
            <p class="veloria-team-members-card__role"><?php echo esc_html($veloria_role); ?></p>
            <div class="veloria-team-members-card__social"><?php
            echo wp_kses(
                veloria_team_members_card_social($veloria_item),
                [
                    'a'    => [
                        'href'       => true,
                        'aria-label' => true,
                        'target'     => true,
                        'rel'        => true,
                    ],
                    'svg'  => [
                        'xmlns'       => true,
                        'viewBox'     => true,
                        'fill'        => true,
                        'aria-hidden' => true,
                    ],
                    'path' => ['d' => true],
                ]
            );
            ?></div>
        </div>
    <?php endforeach; ?>
</div>
<?php
else :
?>
<div class="veloria-team-members veloria-team-members--slider swiper<?php echo esc_attr($veloria_pagination_class); ?>"
     data-autoplay="<?php echo $veloria_autoplay ? '1' : '0'; ?>"
     data-autoplay-delay="<?php echo esc_attr($veloria_autoplay_delay); ?>"
     data-loop="<?php echo $veloria_loop ? '1' : '0'; ?>"
     data-pagination-type="<?php echo esc_attr($veloria_pagination_type); ?>"
     data-slides-per-view="<?php echo esc_attr($veloria_slides_per_view); ?>"
     data-space-between="<?php echo esc_attr($veloria_slide_gap); ?>"
     style="<?php echo esc_attr($veloria_inline_style); ?>">
    <div class="swiper-wrapper">
        <?php foreach ($veloria_items as $veloria_item) :
            $veloria_img_url = isset($veloria_item['image']['url']) ? trim((string) $veloria_item['image']['url']) : '';
            if ($veloria_img_url === '' && !empty($veloria_item['image']['id'])) {
                $veloria_src = wp_get_attachment_image_src((int) $veloria_item['image']['id'], 'medium');
                if (!empty($veloria_src[0])) {
                    $veloria_img_url = $veloria_src[0];
                }
            }
            if ($veloria_img_url === '') {
                $veloria_img_url = $veloria_placeholder_src;
            }
            $veloria_name = isset($veloria_item['name']) ? $veloria_item['name'] : '';
            $veloria_role = isset($veloria_item['role']) ? $veloria_item['role'] : '';
        ?>
            <div class="swiper-slide">
                <div class="veloria-team-members-card">
                    <img class="veloria-team-members-card__image" src="<?php echo esc_url($veloria_img_url); ?>" alt="<?php echo esc_attr($veloria_name); ?>">
                    <h3 class="veloria-team-members-card__name"><?php echo esc_html($veloria_name); ?></h3>
                    <p class="veloria-team-members-card__role"><?php echo esc_html($veloria_role); ?></p>
                    <div class="veloria-team-members-card__social"><?php
            echo wp_kses(
                veloria_team_members_card_social($veloria_item),
                [
                    'a'    => [
                        'href'       => true,
                        'aria-label' => true,
                        'target'     => true,
                        'rel'        => true,
                    ],
                    'svg'  => [
                        'xmlns'       => true,
                        'viewBox'     => true,
                        'fill'        => true,
                        'aria-hidden' => true,
                    ],
                    'path' => ['d' => true],
                ]
            );
            ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($veloria_show_arrows) : ?>
        <div class="veloria-team-members__arrow veloria-team-members__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="veloria-team-members__arrow veloria-team-members__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($veloria_show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
<?php
endif;
