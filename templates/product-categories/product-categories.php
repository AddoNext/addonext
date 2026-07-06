<?php
defined('ABSPATH') || exit;

if (!function_exists('addonext_pc_hex_to_rgb')) {
    /**
     * @return array{0: int, 1: int, 2: int}|null
     */
    function addonext_pc_hex_to_rgb(string $addonext_hex): ?array
    {
        $addonext_hex = strtolower(trim($addonext_hex));
        $addonext_hex = ltrim($addonext_hex, '#');
        if ($addonext_hex === '') {
            return null;
        }
        if (strlen($addonext_hex) === 3) {
            $addonext_hex = $addonext_hex[0] . $addonext_hex[0] . $addonext_hex[1] . $addonext_hex[1] . $addonext_hex[2] . $addonext_hex[2];
        }
        if (strlen($addonext_hex) !== 6 || !ctype_xdigit($addonext_hex)) {
            return null;
        }

        return [
            hexdec(substr($addonext_hex, 0, 2)),
            hexdec(substr($addonext_hex, 2, 2)),
            hexdec(substr($addonext_hex, 4, 2)),
        ];
    }

    function addonext_pc_rgba(string $addonext_hex, float $addonext_alpha): string
    {
        $addonext_rgb = addonext_pc_hex_to_rgb($addonext_hex);
        if ($addonext_rgb === null) {
            return 'rgba(37, 99, 235, ' . max(0.0, min(1.0, $addonext_alpha)) . ')';
        }
        $addonext_a = max(0.0, min(1.0, $addonext_alpha));

        return 'rgba(' . $addonext_rgb[0] . ',' . $addonext_rgb[1] . ',' . $addonext_rgb[2] . ',' . $addonext_a . ')';
    }

    function addonext_pc_render_category_card(array $addonext_item, bool $addonext_show_count, string $addonext_hover_effect): void
    {
        $addonext_allowed_hover = ['none', 'zoom-in', 'zoom-out', 'jump', 'tilt'];
        if (!in_array($addonext_hover_effect, $addonext_allowed_hover, true)) {
            $addonext_hover_effect = 'zoom-in';
        }

        $addonext_term_id = isset($addonext_item['category_id']) ? (int) $addonext_item['category_id'] : 0;
        $addonext_term = $addonext_term_id > 0 ? get_term($addonext_term_id, 'product_cat') : null;
        if (!$addonext_term instanceof \WP_Term || is_wp_error($addonext_term)) {
            return;
        }

        $addonext_icon_color = isset($addonext_item['icon_color']) && (string) $addonext_item['icon_color'] !== ''
            ? (string) $addonext_item['icon_color']
            : '#2563eb';

        $addonext_hide_image = isset($addonext_item['hide_image']) && $addonext_item['hide_image'] === 'yes';
        $addonext_override = isset($addonext_item['override_image']) && $addonext_item['override_image'] === 'yes';
        $addonext_custom = isset($addonext_item['custom_image']) && is_array($addonext_item['custom_image']) ? $addonext_item['custom_image'] : [];

        $addonext_custom_url = '';
        if ($addonext_override) {
            $addonext_custom_url = isset($addonext_custom['url']) ? trim((string) $addonext_custom['url']) : '';
            if ($addonext_custom_url === '' && !empty($addonext_custom['id'])) {
                $addonext_src = wp_get_attachment_image_src((int) $addonext_custom['id'], 'large');
                if (!empty($addonext_src[0])) {
                    $addonext_custom_url = $addonext_src[0];
                }
            }
        }

        $addonext_thumb_id = (int) get_term_meta($addonext_term->term_id, 'thumbnail_id', true);
        $addonext_cat_url = $addonext_thumb_id ? wp_get_attachment_image_url($addonext_thumb_id, 'large') : '';

        $addonext_bg_url = '';
        if (!$addonext_hide_image) {
            if ($addonext_override && $addonext_custom_url !== '') {
                $addonext_bg_url = $addonext_custom_url;
            } elseif (!$addonext_override && $addonext_cat_url) {
                $addonext_bg_url = $addonext_cat_url;
            }
        }

        $addonext_use_image = $addonext_bg_url !== '';
        $addonext_tint_soft = addonext_pc_rgba($addonext_icon_color, 0.38);
        $addonext_tint_deep = addonext_pc_rgba($addonext_icon_color, 0.82);

        if ($addonext_use_image) {
            $addonext_bg_layer_style = 'background-image: url(' . esc_url($addonext_bg_url) . ');';
        } else {
            $addonext_bg_layer_style = 'background-image: linear-gradient(180deg, ' . $addonext_tint_soft . ' 0%, ' . $addonext_tint_deep . ' 100%);';
        }

        $addonext_effect_class = 'addonext-pc-card__media--effect-' . $addonext_hover_effect;

        $addonext_link = get_term_link($addonext_term);
        if (is_wp_error($addonext_link)) {
            $addonext_link = '#';
        }

        $addonext_count_html = '';
        if ($addonext_show_count) {
            $addonext_count_html = sprintf(
                /* translators: %d: product count */
                _n('%d product', '%d products', (int) $addonext_term->count, 'addonext'),
                (int) $addonext_term->count
            );
        }

        $addonext_icon_html = '';
        if (!empty($addonext_item['item_icon']) && is_array($addonext_item['item_icon'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($addonext_item['item_icon'], ['aria-hidden' => 'true']);
            $addonext_icon_html = (string) ob_get_clean();
        }

        $addonext_icon_wrap_style = '--addonext-pc-icon:' . esc_attr($addonext_icon_color) . ';';
        ?>
        <article class="addonext-pc-card">
            <a class="addonext-pc-card__link" href="<?php echo esc_url($addonext_link); ?>">
                <div class="addonext-pc-card__media <?php echo esc_attr($addonext_effect_class); ?>">
                    <div class="addonext-pc-card__bg" style="<?php echo esc_attr($addonext_bg_layer_style); ?>"></div>
                    <?php if ($addonext_use_image) : ?>
                        <span class="addonext-pc-card__scrim" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?php if ($addonext_icon_html !== '') : ?>
                        <span class="addonext-pc-card__icon-wrap" style="<?php echo esc_attr($addonext_icon_wrap_style); ?>">
                            <span class="addonext-pc-card__icon"><?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                            echo $addonext_icon_html;
                            ?></span>
                        </span>
                    <?php endif; ?>
                    <div class="addonext-pc-card__body">
                        <h3 class="addonext-pc-card__title"><?php echo esc_html($addonext_term->name); ?></h3>
                        <?php if ($addonext_count_html !== '') : ?>
                            <p class="addonext-pc-card__count"><?php echo esc_html($addonext_count_html); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </article>
        <?php
    }
}

if (!class_exists('WooCommerce') || !taxonomy_exists('product_cat')) {
    echo '<div class="addonext-pc addonext-pc--empty"><p class="addonext-pc__empty-note">' . esc_html__('WooCommerce and product categories are required.', 'addonext') . '</p></div>';
    return;
}

$addonext_settings = $this->get_settings_for_display();
$addonext_items = isset($addonext_settings['category_items']) && is_array($addonext_settings['category_items']) ? $addonext_settings['category_items'] : [];

if ($addonext_items === []) {
    echo '<div class="addonext-pc addonext-pc--empty"><p class="addonext-pc__empty-note">' . esc_html__('Add categories in the widget settings.', 'addonext') . '</p></div>';
    return;
}

$addonext_layout_mode = isset($addonext_settings['layout_mode']) && $addonext_settings['layout_mode'] === 'slider' ? 'slider' : 'grid';
$addonext_show_count = !isset($addonext_settings['show_count']) || $addonext_settings['show_count'] === 'yes';

$addonext_grid_columns = isset($addonext_settings['grid_columns']) ? max(1, min(6, (int) $addonext_settings['grid_columns'])) : 4;
$addonext_grid_gap = isset($addonext_settings['grid_gap']['size']) ? max(0, (int) $addonext_settings['grid_gap']['size']) : 20;

$addonext_slides_desktop = isset($addonext_settings['slides_desktop']) ? max(1, min(6, (int) $addonext_settings['slides_desktop'])) : 4;
$addonext_slides_tablet = isset($addonext_settings['slides_tablet']) ? max(1, min(4, (int) $addonext_settings['slides_tablet'])) : 2;
$addonext_slides_mobile = isset($addonext_settings['slides_mobile']) ? max(1, min(3, (int) $addonext_settings['slides_mobile'])) : 1;
$addonext_slide_gap = isset($addonext_settings['slide_gap']['size']) ? max(0, (int) $addonext_settings['slide_gap']['size']) : 20;
$addonext_slider_nav = isset($addonext_settings['slider_nav']) ? (string) $addonext_settings['slider_nav'] : 'both';
$addonext_slider_loop = !isset($addonext_settings['slider_loop']) || $addonext_settings['slider_loop'] === 'yes';

$addonext_show_arrows = $addonext_layout_mode === 'slider' && ($addonext_slider_nav === 'both' || $addonext_slider_nav === 'arrows');
$addonext_show_dots = $addonext_layout_mode === 'slider' && ($addonext_slider_nav === 'both' || $addonext_slider_nav === 'dots');

$addonext_is_slider = $addonext_layout_mode === 'slider';

$addonext_grid_style = $addonext_layout_mode === 'grid'
    ? sprintf('--addonext-pc-cols:%d;--addonext-pc-gap:%dpx;', $addonext_grid_columns, $addonext_grid_gap)
    : '';

$addonext_allowed_hover = ['none', 'zoom-in', 'zoom-out', 'jump', 'tilt'];
$addonext_raw_hover = isset($addonext_settings['image_hover_effect']) ? (string) $addonext_settings['image_hover_effect'] : 'zoom-in';
$addonext_hover_effect = in_array($addonext_raw_hover, $addonext_allowed_hover, true) ? $addonext_raw_hover : 'zoom-in';

$addonext_valid_slider_items = 0;
foreach ($addonext_items as $addonext_row) {
    if (!is_array($addonext_row)) {
        continue;
    }
    $addonext_tid = isset($addonext_row['category_id']) ? (int) $addonext_row['category_id'] : 0;
    $addonext_t = $addonext_tid > 0 ? get_term($addonext_tid, 'product_cat') : null;
    if ($addonext_t instanceof \WP_Term && !is_wp_error($addonext_t)) {
        $addonext_valid_slider_items++;
    }
}
?>
<div class="addonext-pc<?php echo $addonext_is_slider ? ' addonext-pc--is-slider' : ''; ?>" data-layout="<?php echo esc_attr($addonext_layout_mode); ?>">
    <?php if ($addonext_layout_mode === 'grid') : ?>
        <div class="addonext-pc__grid" style="<?php echo esc_attr($addonext_grid_style); ?>">
            <?php foreach ($addonext_items as $addonext_item) : ?>
                <?php if (is_array($addonext_item)) {
                    addonext_pc_render_category_card($addonext_item, $addonext_show_count, $addonext_hover_effect);
                } ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="addonext-pc__slider-shell">
            <div
                class="addonext-pc__swiper swiper"
                data-slides-desktop="<?php echo esc_attr((string) $addonext_slides_desktop); ?>"
                data-slides-tablet="<?php echo esc_attr((string) $addonext_slides_tablet); ?>"
                data-slides-mobile="<?php echo esc_attr((string) $addonext_slides_mobile); ?>"
                data-space-between="<?php echo esc_attr((string) $addonext_slide_gap); ?>"
                data-show-nav="<?php echo $addonext_show_arrows ? 'yes' : 'no'; ?>"
                data-show-dots="<?php echo $addonext_show_dots ? 'yes' : 'no'; ?>"
                data-loop="<?php echo $addonext_slider_loop ? 'yes' : 'no'; ?>"
            >
                <div class="swiper-wrapper">
                    <?php foreach ($addonext_items as $addonext_item) : ?>
                        <?php if (!is_array($addonext_item)) {
                            continue;
                        } ?>
                        <?php
                        $addonext_tid = isset($addonext_item['category_id']) ? (int) $addonext_item['category_id'] : 0;
                        $addonext_tcheck = $addonext_tid > 0 ? get_term($addonext_tid, 'product_cat') : null;
                        if (!$addonext_tcheck instanceof \WP_Term || is_wp_error($addonext_tcheck)) {
                            continue;
                        }
                        ?>
                        <div class="swiper-slide">
                            <?php addonext_pc_render_category_card($addonext_item, $addonext_show_count, $addonext_hover_effect); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($addonext_show_dots && $addonext_valid_slider_items > 0) : ?>
                    <div class="swiper-pagination addonext-pc__pagination"></div>
                <?php endif; ?>
            </div>
            <?php if ($addonext_show_arrows && $addonext_valid_slider_items > 0) : ?>
                <button type="button" class="addonext-pc__nav addonext-pc__nav--prev" aria-label="<?php esc_attr_e('Previous', 'addonext'); ?>">
                    <span aria-hidden="true">‹</span>
                </button>
                <button type="button" class="addonext-pc__nav addonext-pc__nav--next" aria-label="<?php esc_attr_e('Next', 'addonext'); ?>">
                    <span aria-hidden="true">›</span>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
