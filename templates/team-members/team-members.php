<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_items = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];
$addonext_view = isset($addonext_settings['view']) ? $addonext_settings['view'] : 'grid';

if (empty($addonext_items)) {
    return;
}

$addonext_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$addonext_is_slider = ($addonext_view === 'slider' && count($addonext_items) > 1);
$addonext_columns = ($addonext_view === 'grid' && isset($addonext_settings['columns'])) ? max(1, min(6, (int) $addonext_settings['columns'])) : 3;
$addonext_column_gap = ($addonext_view === 'grid' && isset($addonext_settings['column_gap']['size'])) ? (int) $addonext_settings['column_gap']['size'] : 24;

$addonext_nav = isset($addonext_settings['navigation']) ? $addonext_settings['navigation'] : 'both';
$addonext_show_arrows = $addonext_is_slider && ($addonext_nav === 'both' || $addonext_nav === 'arrows');
$addonext_show_dots = $addonext_is_slider && ($addonext_nav === 'both' || $addonext_nav === 'dots');
$addonext_autoplay = $addonext_is_slider && !empty($addonext_settings['autoplay']) && $addonext_settings['autoplay'] === 'yes';
$addonext_autoplay_delay = ($addonext_autoplay && isset($addonext_settings['autoplay_delay']) && (int) $addonext_settings['autoplay_delay'] > 0) ? (int) $addonext_settings['autoplay_delay'] : 5;
$addonext_loop = $addonext_is_slider && (empty($addonext_settings['loop']) || $addonext_settings['loop'] === 'yes');
$addonext_pagination_type = isset($addonext_settings['pagination_type']) ? $addonext_settings['pagination_type'] : 'bullets';
$addonext_dots_gap = isset($addonext_settings['dots_gap']['size']) ? (int) $addonext_settings['dots_gap']['size'] : 8;
$addonext_slides_per_view = $addonext_is_slider && isset($addonext_settings['slides_per_view']) ? max(1, min(5, (int) $addonext_settings['slides_per_view'])) : 3;
$addonext_slide_gap = $addonext_is_slider && isset($addonext_settings['slide_gap']['size']) ? (int) $addonext_settings['slide_gap']['size'] : 24;

$addonext_pagination_class = '';
if ($addonext_pagination_type === 'square') {
    $addonext_pagination_class = ' addonext-pagination-square';
} elseif ($addonext_pagination_type === 'progressbar') {
    $addonext_pagination_class = ' addonext-pagination-bar';
}

$addonext_inline_style = '';
if ($addonext_view === 'grid') {
    $addonext_inline_style = '--addonext-team-members-columns: ' . $addonext_columns . '; --addonext-team-members-gap: ' . $addonext_column_gap . 'px;';
} else {
    $addonext_inline_style = '--addonext-dots-gap: ' . $addonext_dots_gap . 'px;';
}

function addonext_team_members_card_social($addonext_item) {
    $addonext_linkedin_url = !empty($addonext_item['link_linkedin']['url']) ? esc_url($addonext_item['link_linkedin']['url']) : '#';
    $addonext_twitter_url = !empty($addonext_item['link_twitter']['url']) ? esc_url($addonext_item['link_twitter']['url']) : '#';
    $addonext_linkedin_attr = $addonext_linkedin_url !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
    $addonext_twitter_attr = $addonext_twitter_url !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
    return '<a href="' . $addonext_linkedin_url . '" aria-label="LinkedIn"' . $addonext_linkedin_attr . '><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H4.5v8.37h3.77z"/></svg></a>'
        . '<a href="' . $addonext_twitter_url . '" aria-label="Twitter"' . $addonext_twitter_attr . '><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>';
}

if ($addonext_view === 'grid') :
?>
<div class="addonext-team-members addonext-team-members--grid" style="<?php echo esc_attr($addonext_inline_style); ?>">
    <?php foreach ($addonext_items as $addonext_item) :
        $addonext_img_url = isset($addonext_item['image']['url']) ? trim((string) $addonext_item['image']['url']) : '';
        if ($addonext_img_url === '' && !empty($addonext_item['image']['id'])) {
            $addonext_src = wp_get_attachment_image_src((int) $addonext_item['image']['id'], 'medium');
            if (!empty($addonext_src[0])) {
                $addonext_img_url = $addonext_src[0];
            }
        }
        if ($addonext_img_url === '') {
            $addonext_img_url = $addonext_placeholder_src;
        }
        $addonext_name = isset($addonext_item['name']) ? $addonext_item['name'] : '';
        $addonext_role = isset($addonext_item['role']) ? $addonext_item['role'] : '';
    ?>
        <div class="addonext-team-members-card">
            <img class="addonext-team-members-card__image" src="<?php echo esc_url($addonext_img_url); ?>" alt="<?php echo esc_attr($addonext_name); ?>">
            <h3 class="addonext-team-members-card__name"><?php echo esc_html($addonext_name); ?></h3>
            <p class="addonext-team-members-card__role"><?php echo esc_html($addonext_role); ?></p>
            <div class="addonext-team-members-card__social"><?php
            echo wp_kses(
                addonext_team_members_card_social($addonext_item),
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
<div class="addonext-team-members addonext-team-members--slider swiper<?php echo esc_attr($addonext_pagination_class); ?>"
     data-autoplay="<?php echo $addonext_autoplay ? '1' : '0'; ?>"
     data-autoplay-delay="<?php echo esc_attr($addonext_autoplay_delay); ?>"
     data-loop="<?php echo $addonext_loop ? '1' : '0'; ?>"
     data-pagination-type="<?php echo esc_attr($addonext_pagination_type); ?>"
     data-slides-per-view="<?php echo esc_attr($addonext_slides_per_view); ?>"
     data-space-between="<?php echo esc_attr($addonext_slide_gap); ?>"
     style="<?php echo esc_attr($addonext_inline_style); ?>">
    <div class="swiper-wrapper">
        <?php foreach ($addonext_items as $addonext_item) :
            $addonext_img_url = isset($addonext_item['image']['url']) ? trim((string) $addonext_item['image']['url']) : '';
            if ($addonext_img_url === '' && !empty($addonext_item['image']['id'])) {
                $addonext_src = wp_get_attachment_image_src((int) $addonext_item['image']['id'], 'medium');
                if (!empty($addonext_src[0])) {
                    $addonext_img_url = $addonext_src[0];
                }
            }
            if ($addonext_img_url === '') {
                $addonext_img_url = $addonext_placeholder_src;
            }
            $addonext_name = isset($addonext_item['name']) ? $addonext_item['name'] : '';
            $addonext_role = isset($addonext_item['role']) ? $addonext_item['role'] : '';
        ?>
            <div class="swiper-slide">
                <div class="addonext-team-members-card">
                    <img class="addonext-team-members-card__image" src="<?php echo esc_url($addonext_img_url); ?>" alt="<?php echo esc_attr($addonext_name); ?>">
                    <h3 class="addonext-team-members-card__name"><?php echo esc_html($addonext_name); ?></h3>
                    <p class="addonext-team-members-card__role"><?php echo esc_html($addonext_role); ?></p>
                    <div class="addonext-team-members-card__social"><?php
            echo wp_kses(
                addonext_team_members_card_social($addonext_item),
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
    <?php if ($addonext_show_arrows) : ?>
        <div class="addonext-team-members__arrow addonext-team-members__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'addonext'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="addonext-team-members__arrow addonext-team-members__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'addonext'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($addonext_show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
<?php
endif;
