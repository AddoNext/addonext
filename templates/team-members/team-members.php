<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$items = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];
$view = isset($settings['view']) ? $settings['view'] : 'grid';

if (empty($items)) {
    return;
}

$placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$is_slider = ($view === 'slider' && count($items) > 1);
$columns = ($view === 'grid' && isset($settings['columns'])) ? max(1, min(6, (int) $settings['columns'])) : 3;
$column_gap = ($view === 'grid' && isset($settings['column_gap']['size'])) ? (int) $settings['column_gap']['size'] : 24;

$nav = isset($settings['navigation']) ? $settings['navigation'] : 'both';
$show_arrows = $is_slider && ($nav === 'both' || $nav === 'arrows');
$show_dots = $is_slider && ($nav === 'both' || $nav === 'dots');
$autoplay = $is_slider && !empty($settings['autoplay']) && $settings['autoplay'] === 'yes';
$autoplay_delay = ($autoplay && isset($settings['autoplay_delay']) && (int) $settings['autoplay_delay'] > 0) ? (int) $settings['autoplay_delay'] : 5;
$loop = $is_slider && (empty($settings['loop']) || $settings['loop'] === 'yes');
$pagination_type = isset($settings['pagination_type']) ? $settings['pagination_type'] : 'bullets';
$dots_gap = isset($settings['dots_gap']['size']) ? (int) $settings['dots_gap']['size'] : 8;
$slides_per_view = $is_slider && isset($settings['slides_per_view']) ? max(1, min(5, (int) $settings['slides_per_view'])) : 3;
$slide_gap = $is_slider && isset($settings['slide_gap']['size']) ? (int) $settings['slide_gap']['size'] : 24;

$pagination_class = '';
if ($pagination_type === 'square') {
    $pagination_class = ' veloria-pagination-square';
} elseif ($pagination_type === 'progressbar') {
    $pagination_class = ' veloria-pagination-bar';
}

$inline_style = '';
if ($view === 'grid') {
    $inline_style = '--veloria-team-members-columns: ' . $columns . '; --veloria-team-members-gap: ' . $column_gap . 'px;';
} else {
    $inline_style = '--veloria-dots-gap: ' . $dots_gap . 'px;';
}

function veloria_team_members_card_social($item) {
    $linkedin_url = !empty($item['link_linkedin']['url']) ? esc_url($item['link_linkedin']['url']) : '#';
    $twitter_url = !empty($item['link_twitter']['url']) ? esc_url($item['link_twitter']['url']) : '#';
    $linkedin_attr = $linkedin_url !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
    $twitter_attr = $twitter_url !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
    return '<a href="' . $linkedin_url . '" aria-label="LinkedIn"' . $linkedin_attr . '><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H4.5v8.37h3.77z"/></svg></a>'
        . '<a href="' . $twitter_url . '" aria-label="Twitter"' . $twitter_attr . '><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>';
}

if ($view === 'grid') :
?>
<div class="veloria-team-members veloria-team-members--grid" style="<?php echo esc_attr($inline_style); ?>">
    <?php foreach ($items as $item) :
        $img_url = isset($item['image']['url']) ? trim((string) $item['image']['url']) : '';
        if ($img_url === '' && !empty($item['image']['id'])) {
            $src = wp_get_attachment_image_src((int) $item['image']['id'], 'medium');
            if (!empty($src[0])) {
                $img_url = $src[0];
            }
        }
        if ($img_url === '') {
            $img_url = $placeholder_src;
        }
        $name = isset($item['name']) ? $item['name'] : '';
        $role = isset($item['role']) ? $item['role'] : '';
    ?>
        <div class="veloria-team-members-card">
            <img class="veloria-team-members-card__image" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($name); ?>">
            <h3 class="veloria-team-members-card__name"><?php echo esc_html($name); ?></h3>
            <p class="veloria-team-members-card__role"><?php echo esc_html($role); ?></p>
            <div class="veloria-team-members-card__social"><?php
            echo wp_kses(
                veloria_team_members_card_social($item),
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
<div class="veloria-team-members veloria-team-members--slider swiper<?php echo esc_attr($pagination_class); ?>"
     data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>"
     data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>"
     data-loop="<?php echo $loop ? '1' : '0'; ?>"
     data-pagination-type="<?php echo esc_attr($pagination_type); ?>"
     data-slides-per-view="<?php echo esc_attr($slides_per_view); ?>"
     data-space-between="<?php echo esc_attr($slide_gap); ?>"
     style="<?php echo esc_attr($inline_style); ?>">
    <div class="swiper-wrapper">
        <?php foreach ($items as $item) :
            $img_url = isset($item['image']['url']) ? trim((string) $item['image']['url']) : '';
            if ($img_url === '' && !empty($item['image']['id'])) {
                $src = wp_get_attachment_image_src((int) $item['image']['id'], 'medium');
                if (!empty($src[0])) {
                    $img_url = $src[0];
                }
            }
            if ($img_url === '') {
                $img_url = $placeholder_src;
            }
            $name = isset($item['name']) ? $item['name'] : '';
            $role = isset($item['role']) ? $item['role'] : '';
        ?>
            <div class="swiper-slide">
                <div class="veloria-team-members-card">
                    <img class="veloria-team-members-card__image" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($name); ?>">
                    <h3 class="veloria-team-members-card__name"><?php echo esc_html($name); ?></h3>
                    <p class="veloria-team-members-card__role"><?php echo esc_html($role); ?></p>
                    <div class="veloria-team-members-card__social"><?php
            echo wp_kses(
                veloria_team_members_card_social($item),
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
    <?php if ($show_arrows) : ?>
        <div class="veloria-team-members__arrow veloria-team-members__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="veloria-team-members__arrow veloria-team-members__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
<?php
endif;
