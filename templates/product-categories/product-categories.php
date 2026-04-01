<?php
defined('ABSPATH') || exit;

if (!function_exists('elementrify_pc_hex_to_rgb')) {
    /**
     * @return array{0: int, 1: int, 2: int}|null
     */
    function elementrify_pc_hex_to_rgb(string $hex): ?array
    {
        $hex = strtolower(trim($hex));
        $hex = ltrim($hex, '#');
        if ($hex === '') {
            return null;
        }
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
            return null;
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    function elementrify_pc_rgba(string $hex, float $alpha): string
    {
        $rgb = elementrify_pc_hex_to_rgb($hex);
        if ($rgb === null) {
            return 'rgba(37, 99, 235, ' . max(0.0, min(1.0, $alpha)) . ')';
        }
        $a = max(0.0, min(1.0, $alpha));

        return 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $a . ')';
    }

    function elementrify_pc_render_category_card(array $item, bool $show_count, string $hover_effect): void
    {
        $allowed_hover = ['none', 'zoom-in', 'zoom-out', 'jump', 'tilt'];
        if (!in_array($hover_effect, $allowed_hover, true)) {
            $hover_effect = 'zoom-in';
        }

        $term_id = isset($item['category_id']) ? (int) $item['category_id'] : 0;
        $term = $term_id > 0 ? get_term($term_id, 'product_cat') : null;
        if (!$term instanceof \WP_Term || is_wp_error($term)) {
            return;
        }

        $icon_color = isset($item['icon_color']) && (string) $item['icon_color'] !== ''
            ? (string) $item['icon_color']
            : '#2563eb';

        $hide_image = isset($item['hide_image']) && $item['hide_image'] === 'yes';
        $override = isset($item['override_image']) && $item['override_image'] === 'yes';
        $custom = isset($item['custom_image']) && is_array($item['custom_image']) ? $item['custom_image'] : [];

        $custom_url = '';
        if ($override) {
            $custom_url = isset($custom['url']) ? trim((string) $custom['url']) : '';
            if ($custom_url === '' && !empty($custom['id'])) {
                $src = wp_get_attachment_image_src((int) $custom['id'], 'large');
                if (!empty($src[0])) {
                    $custom_url = $src[0];
                }
            }
        }

        $thumb_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
        $cat_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';

        $bg_url = '';
        if (!$hide_image) {
            if ($override && $custom_url !== '') {
                $bg_url = $custom_url;
            } elseif (!$override && $cat_url) {
                $bg_url = $cat_url;
            }
        }

        $use_image = $bg_url !== '';
        $tint_soft = elementrify_pc_rgba($icon_color, 0.38);
        $tint_deep = elementrify_pc_rgba($icon_color, 0.82);

        if ($use_image) {
            $bg_layer_style = 'background-image: url(' . esc_url($bg_url) . ');';
        } else {
            $bg_layer_style = 'background-image: linear-gradient(180deg, ' . $tint_soft . ' 0%, ' . $tint_deep . ' 100%);';
        }

        $effect_class = 'elementrify-pc-card__media--effect-' . $hover_effect;

        $link = get_term_link($term);
        if (is_wp_error($link)) {
            $link = '#';
        }

        $count_html = '';
        if ($show_count) {
            $count_html = sprintf(
                /* translators: %d: product count */
                _n('%d product', '%d products', (int) $term->count, 'elementrify'),
                (int) $term->count
            );
        }

        $icon_html = '';
        if (!empty($item['item_icon']) && is_array($item['item_icon'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true']);
            $icon_html = (string) ob_get_clean();
        }

        $icon_wrap_style = '--elementrify-pc-icon:' . esc_attr($icon_color) . ';';
        ?>
        <article class="elementrify-pc-card">
            <a class="elementrify-pc-card__link" href="<?php echo esc_url($link); ?>">
                <div class="elementrify-pc-card__media <?php echo esc_attr($effect_class); ?>">
                    <div class="elementrify-pc-card__bg" style="<?php echo esc_attr($bg_layer_style); ?>"></div>
                    <?php if ($use_image) : ?>
                        <span class="elementrify-pc-card__scrim" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?php if ($icon_html !== '') : ?>
                        <span class="elementrify-pc-card__icon-wrap" style="<?php echo esc_attr($icon_wrap_style); ?>">
                            <span class="elementrify-pc-card__icon"><?php echo $icon_html; ?></span>
                        </span>
                    <?php endif; ?>
                    <div class="elementrify-pc-card__body">
                        <h3 class="elementrify-pc-card__title"><?php echo esc_html($term->name); ?></h3>
                        <?php if ($count_html !== '') : ?>
                            <p class="elementrify-pc-card__count"><?php echo esc_html($count_html); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </article>
        <?php
    }
}

if (!class_exists('WooCommerce') || !taxonomy_exists('product_cat')) {
    echo '<div class="elementrify-pc elementrify-pc--empty"><p class="elementrify-pc__empty-note">' . esc_html__('WooCommerce and product categories are required.', 'elementrify') . '</p></div>';
    return;
}

$settings = $this->get_settings_for_display();
$items = isset($settings['category_items']) && is_array($settings['category_items']) ? $settings['category_items'] : [];

if ($items === []) {
    echo '<div class="elementrify-pc elementrify-pc--empty"><p class="elementrify-pc__empty-note">' . esc_html__('Add categories in the widget settings.', 'elementrify') . '</p></div>';
    return;
}

$layout_mode = isset($settings['layout_mode']) && $settings['layout_mode'] === 'slider' ? 'slider' : 'grid';
$show_count = !isset($settings['show_count']) || $settings['show_count'] === 'yes';

$grid_columns = isset($settings['grid_columns']) ? max(1, min(6, (int) $settings['grid_columns'])) : 4;
$grid_gap = isset($settings['grid_gap']['size']) ? max(0, (int) $settings['grid_gap']['size']) : 20;

$slides_desktop = isset($settings['slides_desktop']) ? max(1, min(6, (int) $settings['slides_desktop'])) : 4;
$slides_tablet = isset($settings['slides_tablet']) ? max(1, min(4, (int) $settings['slides_tablet'])) : 2;
$slides_mobile = isset($settings['slides_mobile']) ? max(1, min(3, (int) $settings['slides_mobile'])) : 1;
$slide_gap = isset($settings['slide_gap']['size']) ? max(0, (int) $settings['slide_gap']['size']) : 20;
$slider_nav = isset($settings['slider_nav']) ? (string) $settings['slider_nav'] : 'both';
$slider_loop = !isset($settings['slider_loop']) || $settings['slider_loop'] === 'yes';

$show_arrows = $layout_mode === 'slider' && ($slider_nav === 'both' || $slider_nav === 'arrows');
$show_dots = $layout_mode === 'slider' && ($slider_nav === 'both' || $slider_nav === 'dots');

$is_slider = $layout_mode === 'slider';

$grid_style = $layout_mode === 'grid'
    ? sprintf('--elementrify-pc-cols:%d;--elementrify-pc-gap:%dpx;', $grid_columns, $grid_gap)
    : '';

$allowed_hover = ['none', 'zoom-in', 'zoom-out', 'jump', 'tilt'];
$raw_hover = isset($settings['image_hover_effect']) ? (string) $settings['image_hover_effect'] : 'zoom-in';
$hover_effect = in_array($raw_hover, $allowed_hover, true) ? $raw_hover : 'zoom-in';

$valid_slider_items = 0;
foreach ($items as $row) {
    if (!is_array($row)) {
        continue;
    }
    $tid = isset($row['category_id']) ? (int) $row['category_id'] : 0;
    $t = $tid > 0 ? get_term($tid, 'product_cat') : null;
    if ($t instanceof \WP_Term && !is_wp_error($t)) {
        $valid_slider_items++;
    }
}
?>
<div class="elementrify-pc<?php echo $is_slider ? ' elementrify-pc--is-slider' : ''; ?>" data-layout="<?php echo esc_attr($layout_mode); ?>">
    <?php if ($layout_mode === 'grid') : ?>
        <div class="elementrify-pc__grid" style="<?php echo esc_attr($grid_style); ?>">
            <?php foreach ($items as $item) : ?>
                <?php if (is_array($item)) {
                    elementrify_pc_render_category_card($item, $show_count, $hover_effect);
                } ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="elementrify-pc__slider-shell">
            <div
                class="elementrify-pc__swiper swiper"
                data-slides-desktop="<?php echo esc_attr((string) $slides_desktop); ?>"
                data-slides-tablet="<?php echo esc_attr((string) $slides_tablet); ?>"
                data-slides-mobile="<?php echo esc_attr((string) $slides_mobile); ?>"
                data-space-between="<?php echo esc_attr((string) $slide_gap); ?>"
                data-show-nav="<?php echo $show_arrows ? 'yes' : 'no'; ?>"
                data-show-dots="<?php echo $show_dots ? 'yes' : 'no'; ?>"
                data-loop="<?php echo $slider_loop ? 'yes' : 'no'; ?>"
            >
                <div class="swiper-wrapper">
                    <?php foreach ($items as $item) : ?>
                        <?php if (!is_array($item)) {
                            continue;
                        } ?>
                        <?php
                        $tid = isset($item['category_id']) ? (int) $item['category_id'] : 0;
                        $tcheck = $tid > 0 ? get_term($tid, 'product_cat') : null;
                        if (!$tcheck instanceof \WP_Term || is_wp_error($tcheck)) {
                            continue;
                        }
                        ?>
                        <div class="swiper-slide">
                            <?php elementrify_pc_render_category_card($item, $show_count, $hover_effect); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($show_dots && $valid_slider_items > 0) : ?>
                    <div class="swiper-pagination elementrify-pc__pagination"></div>
                <?php endif; ?>
            </div>
            <?php if ($show_arrows && $valid_slider_items > 0) : ?>
                <button type="button" class="elementrify-pc__nav elementrify-pc__nav--prev" aria-label="<?php esc_attr_e('Previous', 'elementrify'); ?>">
                    <span aria-hidden="true">‹</span>
                </button>
                <button type="button" class="elementrify-pc__nav elementrify-pc__nav--next" aria-label="<?php esc_attr_e('Next', 'elementrify'); ?>">
                    <span aria-hidden="true">›</span>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
