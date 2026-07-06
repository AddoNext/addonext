<?php
/**
 * Product Image widget template.
 *
 * @var array<int, array{thumb:string,main:string,full:string,alt:string}> $addonext_gallery_images
 * @var \WC_Product $addonext_product
 * @var bool $addonext_show_thumbnails
 * @var bool $addonext_show_thumb_row
 * @var string $addonext_zoom_mode lightbox|pan
 * @var string $addonext_images_json
 * @var float $addonext_pan_scale
 */

defined('ABSPATH') || exit;

$addonext_uid = 'addonext-pi-' . $this->get_id();
$addonext_first = $addonext_gallery_images[0];
$addonext_main_alt = $addonext_first['alt'] !== '' ? $addonext_first['alt'] : $addonext_product->get_name();
?>
<div
    id="<?php echo esc_attr($addonext_uid); ?>"
    class="addonext-product-image elementor-clickable"
    data-zoom="<?php echo esc_attr($addonext_zoom_mode); ?>"
    data-pan-scale="<?php echo esc_attr((string) $addonext_pan_scale); ?>"
    data-images="<?php echo esc_attr($addonext_images_json); ?>"
>
    <?php if ($addonext_zoom_mode === 'lightbox') : ?>
        <button
            type="button"
            class="addonext-pi__main addonext-pi__main--clickable elementor-clickable"
            aria-label="<?php echo esc_attr__('Open image in lightbox', 'addonext'); ?>"
        >
            <span class="addonext-pi__visual">
                <span class="addonext-pi__stage">
                    <img
                        class="addonext-pi__main-img"
                        src="<?php echo esc_url($addonext_first['main']); ?>"
                        alt="<?php echo esc_attr($addonext_main_alt); ?>"
                        width="600"
                        height="600"
                        decoding="async"
                        fetchpriority="high"
                    />
                </span>
            </span>
        </button>
    <?php else : ?>
        <div class="addonext-pi__main addonext-pi__main--pan-zoom">
            <div class="addonext-pi__visual">
                <div
                    class="addonext-pi__stage elementor-clickable"
                    role="button"
                    tabindex="0"
                    aria-label="<?php echo esc_attr__('Click to zoom. When zoomed, drag to move.', 'addonext'); ?>"
                >
                    <img
                        class="addonext-pi__main-img"
                        src="<?php echo esc_url($addonext_first['main']); ?>"
                        alt="<?php echo esc_attr($addonext_main_alt); ?>"
                        width="600"
                        height="600"
                        decoding="async"
                        fetchpriority="high"
                    />
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($addonext_show_thumb_row) : ?>
        <div class="addonext-pi__thumbs" aria-label="<?php echo esc_attr__('Product gallery', 'addonext'); ?>">
            <?php foreach ($addonext_gallery_images as $addonext_index => $addonext_row) : ?>
                <?php
                $addonext_thumb_alt = $addonext_row['alt'] !== '' ? $addonext_row['alt'] : sprintf(
                    /* translators: %d: image position */
                    __('Product image %d', 'addonext'),
                    $addonext_index + 1
                );
                ?>
                <button
                    type="button"
                    class="addonext-pi__thumb elementor-clickable<?php echo $addonext_index === 0 ? ' is-active' : ''; ?>"
                    aria-pressed="<?php echo $addonext_index === 0 ? 'true' : 'false'; ?>"
                    aria-label="<?php echo esc_attr($addonext_thumb_alt); ?>"
                    data-index="<?php echo esc_attr((string) $addonext_index); ?>"
                >
                    <img
                        src="<?php echo esc_url($addonext_row['thumb']); ?>"
                        alt=""
                        width="100"
                        height="100"
                        loading="lazy"
                        decoding="async"
                    />
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
