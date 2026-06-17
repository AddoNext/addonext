<?php
defined('ABSPATH') || exit;

if (!function_exists('veloria_pc_hex_to_rgb')) {
    /**
     * @return array{0: int, 1: int, 2: int}|null
     */
    function veloria_pc_hex_to_rgb(string $veloria_hex): ?array
    {
        $veloria_hex = strtolower(trim($veloria_hex));
        $veloria_hex = ltrim($veloria_hex, '#');
        if ($veloria_hex === '') {
            return null;
        }
        if (strlen($veloria_hex) === 3) {
            $veloria_hex = $veloria_hex[0] . $veloria_hex[0] . $veloria_hex[1] . $veloria_hex[1] . $veloria_hex[2] . $veloria_hex[2];
        }
        if (strlen($veloria_hex) !== 6 || !ctype_xdigit($veloria_hex)) {
            return null;
        }

        return [
            hexdec(substr($veloria_hex, 0, 2)),
            hexdec(substr($veloria_hex, 2, 2)),
            hexdec(substr($veloria_hex, 4, 2)),
        ];
    }

    function veloria_pc_rgba(string $veloria_hex, float $veloria_alpha): string
    {
        $veloria_rgb = veloria_pc_hex_to_rgb($veloria_hex);
        if ($veloria_rgb === null) {
            return 'rgba(37, 99, 235, ' . max(0.0, min(1.0, $veloria_alpha)) . ')';
        }
        $veloria_a = max(0.0, min(1.0, $veloria_alpha));

        return 'rgba(' . $veloria_rgb[0] . ',' . $veloria_rgb[1] . ',' . $veloria_rgb[2] . ',' . $veloria_a . ')';
    }

    function veloria_pc_render_category_card(array $veloria_item, bool $veloria_show_count, string $veloria_hover_effect): void
    {
        $veloria_allowed_hover = ['none', 'zoom-in', 'zoom-out', 'jump', 'tilt'];
        if (!in_array($veloria_hover_effect, $veloria_allowed_hover, true)) {
            $veloria_hover_effect = 'zoom-in';
        }

        $veloria_term_id = isset($veloria_item['category_id']) ? (int) $veloria_item['category_id'] : 0;
        $veloria_term = $veloria_term_id > 0 ? get_term($veloria_term_id, 'product_cat') : null;
        if (!$veloria_term instanceof \WP_Term || is_wp_error($veloria_term)) {
            return;
        }

        $veloria_icon_color = isset($veloria_item['icon_color']) && (string) $veloria_item['icon_color'] !== ''
            ? (string) $veloria_item['icon_color']
            : '#2563eb';

        $veloria_hide_image = isset($veloria_item['hide_image']) && $veloria_item['hide_image'] === 'yes';
        $veloria_override = isset($veloria_item['override_image']) && $veloria_item['override_image'] === 'yes';
        $veloria_custom = isset($veloria_item['custom_image']) && is_array($veloria_item['custom_image']) ? $veloria_item['custom_image'] : [];

        $veloria_custom_url = '';
        if ($veloria_override) {
            $veloria_custom_url = isset($veloria_custom['url']) ? trim((string) $veloria_custom['url']) : '';
            if ($veloria_custom_url === '' && !empty($veloria_custom['id'])) {
                $veloria_src = wp_get_attachment_image_src((int) $veloria_custom['id'], 'large');
                if (!empty($veloria_src[0])) {
                    $veloria_custom_url = $veloria_src[0];
                }
            }
        }

        $veloria_thumb_id = (int) get_term_meta($veloria_term->term_id, 'thumbnail_id', true);
        $veloria_cat_url = $veloria_thumb_id ? wp_get_attachment_image_url($veloria_thumb_id, 'large') : '';

        $veloria_bg_url = '';
        if (!$veloria_hide_image) {
            if ($veloria_override && $veloria_custom_url !== '') {
                $veloria_bg_url = $veloria_custom_url;
            } elseif (!$veloria_override && $veloria_cat_url) {
                $veloria_bg_url = $veloria_cat_url;
            }
        }

        $veloria_use_image = $veloria_bg_url !== '';
        $veloria_tint_soft = veloria_pc_rgba($veloria_icon_color, 0.38);
        $veloria_tint_deep = veloria_pc_rgba($veloria_icon_color, 0.82);

        if ($veloria_use_image) {
            $veloria_bg_layer_style = 'background-image: url(' . esc_url($veloria_bg_url) . ');';
        } else {
            $veloria_bg_layer_style = 'background-image: linear-gradient(180deg, ' . $veloria_tint_soft . ' 0%, ' . $veloria_tint_deep . ' 100%);';
        }

        $veloria_effect_class = 'veloria-pc-card__media--effect-' . $veloria_hover_effect;

        $veloria_link = get_term_link($veloria_term);
        if (is_wp_error($veloria_link)) {
            $veloria_link = '#';
        }

        $veloria_count_html = '';
        if ($veloria_show_count) {
            $veloria_count_html = sprintf(
                /* translators: %d: product count */
                _n('%d product', '%d products', (int) $veloria_term->count, 'veloria'),
                (int) $veloria_term->count
            );
        }

        $veloria_icon_html = '';
        if (!empty($veloria_item['item_icon']) && is_array($veloria_item['item_icon'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($veloria_item['item_icon'], ['aria-hidden' => 'true']);
            $veloria_icon_html = (string) ob_get_clean();
        }

        $veloria_icon_wrap_style = '--veloria-pc-icon:' . esc_attr($veloria_icon_color) . ';';
        ?>
        <article class="veloria-pc-card">
            <a class="veloria-pc-card__link" href="<?php echo esc_url($veloria_link); ?>">
                <div class="veloria-pc-card__media <?php echo esc_attr($veloria_effect_class); ?>">
                    <div class="veloria-pc-card__bg" style="<?php echo esc_attr($veloria_bg_layer_style); ?>"></div>
                    <?php if ($veloria_use_image) : ?>
                        <span class="veloria-pc-card__scrim" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?php if ($veloria_icon_html !== '') : ?>
                        <span class="veloria-pc-card__icon-wrap" style="<?php echo esc_attr($veloria_icon_wrap_style); ?>">
                            <span class="veloria-pc-card__icon"><?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                            echo $veloria_icon_html;
                            ?></span>
                        </span>
                    <?php endif; ?>
                    <div class="veloria-pc-card__body">
                        <h3 class="veloria-pc-card__title"><?php echo esc_html($veloria_term->name); ?></h3>
                        <?php if ($veloria_count_html !== '') : ?>
                            <p class="veloria-pc-card__count"><?php echo esc_html($veloria_count_html); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </article>
        <?php
    }
}

if (!class_exists('WooCommerce') || !taxonomy_exists('product_cat')) {
    echo '<div class="veloria-pc veloria-pc--empty"><p class="veloria-pc__empty-note">' . esc_html__('WooCommerce and product categories are required.', 'veloria') . '</p></div>';
    return;
}

$veloria_settings = $this->get_settings_for_display();
$veloria_items = isset($veloria_settings['category_items']) && is_array($veloria_settings['category_items']) ? $veloria_settings['category_items'] : [];

if ($veloria_items === []) {
    echo '<div class="veloria-pc veloria-pc--empty"><p class="veloria-pc__empty-note">' . esc_html__('Add categories in the widget settings.', 'veloria') . '</p></div>';
    return;
}

$veloria_layout_mode = isset($veloria_settings['layout_mode']) && $veloria_settings['layout_mode'] === 'slider' ? 'slider' : 'grid';
$veloria_show_count = !isset($veloria_settings['show_count']) || $veloria_settings['show_count'] === 'yes';

$veloria_grid_columns = isset($veloria_settings['grid_columns']) ? max(1, min(6, (int) $veloria_settings['grid_columns'])) : 4;
$veloria_grid_gap = isset($veloria_settings['grid_gap']['size']) ? max(0, (int) $veloria_settings['grid_gap']['size']) : 20;

$veloria_slides_desktop = isset($veloria_settings['slides_desktop']) ? max(1, min(6, (int) $veloria_settings['slides_desktop'])) : 4;
$veloria_slides_tablet = isset($veloria_settings['slides_tablet']) ? max(1, min(4, (int) $veloria_settings['slides_tablet'])) : 2;
$veloria_slides_mobile = isset($veloria_settings['slides_mobile']) ? max(1, min(3, (int) $veloria_settings['slides_mobile'])) : 1;
$veloria_slide_gap = isset($veloria_settings['slide_gap']['size']) ? max(0, (int) $veloria_settings['slide_gap']['size']) : 20;
$veloria_slider_nav = isset($veloria_settings['slider_nav']) ? (string) $veloria_settings['slider_nav'] : 'both';
$veloria_slider_loop = !isset($veloria_settings['slider_loop']) || $veloria_settings['slider_loop'] === 'yes';

$veloria_show_arrows = $veloria_layout_mode === 'slider' && ($veloria_slider_nav === 'both' || $veloria_slider_nav === 'arrows');
$veloria_show_dots = $veloria_layout_mode === 'slider' && ($veloria_slider_nav === 'both' || $veloria_slider_nav === 'dots');

$veloria_is_slider = $veloria_layout_mode === 'slider';

$veloria_grid_style = $veloria_layout_mode === 'grid'
    ? sprintf('--veloria-pc-cols:%d;--veloria-pc-gap:%dpx;', $veloria_grid_columns, $veloria_grid_gap)
    : '';

$veloria_allowed_hover = ['none', 'zoom-in', 'zoom-out', 'jump', 'tilt'];
$veloria_raw_hover = isset($veloria_settings['image_hover_effect']) ? (string) $veloria_settings['image_hover_effect'] : 'zoom-in';
$veloria_hover_effect = in_array($veloria_raw_hover, $veloria_allowed_hover, true) ? $veloria_raw_hover : 'zoom-in';

$veloria_valid_slider_items = 0;
foreach ($veloria_items as $veloria_row) {
    if (!is_array($veloria_row)) {
        continue;
    }
    $veloria_tid = isset($veloria_row['category_id']) ? (int) $veloria_row['category_id'] : 0;
    $veloria_t = $veloria_tid > 0 ? get_term($veloria_tid, 'product_cat') : null;
    if ($veloria_t instanceof \WP_Term && !is_wp_error($veloria_t)) {
        $veloria_valid_slider_items++;
    }
}
?>
<div class="veloria-pc<?php echo $veloria_is_slider ? ' veloria-pc--is-slider' : ''; ?>" data-layout="<?php echo esc_attr($veloria_layout_mode); ?>">
    <?php if ($veloria_layout_mode === 'grid') : ?>
        <div class="veloria-pc__grid" style="<?php echo esc_attr($veloria_grid_style); ?>">
            <?php foreach ($veloria_items as $veloria_item) : ?>
                <?php if (is_array($veloria_item)) {
                    veloria_pc_render_category_card($veloria_item, $veloria_show_count, $veloria_hover_effect);
                } ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="veloria-pc__slider-shell">
            <div
                class="veloria-pc__swiper swiper"
                data-slides-desktop="<?php echo esc_attr((string) $veloria_slides_desktop); ?>"
                data-slides-tablet="<?php echo esc_attr((string) $veloria_slides_tablet); ?>"
                data-slides-mobile="<?php echo esc_attr((string) $veloria_slides_mobile); ?>"
                data-space-between="<?php echo esc_attr((string) $veloria_slide_gap); ?>"
                data-show-nav="<?php echo $veloria_show_arrows ? 'yes' : 'no'; ?>"
                data-show-dots="<?php echo $veloria_show_dots ? 'yes' : 'no'; ?>"
                data-loop="<?php echo $veloria_slider_loop ? 'yes' : 'no'; ?>"
            >
                <div class="swiper-wrapper">
                    <?php foreach ($veloria_items as $veloria_item) : ?>
                        <?php if (!is_array($veloria_item)) {
                            continue;
                        } ?>
                        <?php
                        $veloria_tid = isset($veloria_item['category_id']) ? (int) $veloria_item['category_id'] : 0;
                        $veloria_tcheck = $veloria_tid > 0 ? get_term($veloria_tid, 'product_cat') : null;
                        if (!$veloria_tcheck instanceof \WP_Term || is_wp_error($veloria_tcheck)) {
                            continue;
                        }
                        ?>
                        <div class="swiper-slide">
                            <?php veloria_pc_render_category_card($veloria_item, $veloria_show_count, $veloria_hover_effect); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($veloria_show_dots && $veloria_valid_slider_items > 0) : ?>
                    <div class="swiper-pagination veloria-pc__pagination"></div>
                <?php endif; ?>
            </div>
            <?php if ($veloria_show_arrows && $veloria_valid_slider_items > 0) : ?>
                <button type="button" class="veloria-pc__nav veloria-pc__nav--prev" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
                    <span aria-hidden="true">‹</span>
                </button>
                <button type="button" class="veloria-pc__nav veloria-pc__nav--next" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
                    <span aria-hidden="true">›</span>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
