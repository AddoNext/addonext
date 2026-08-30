<?php
defined('ABSPATH') || exit;

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_s        = $this->get_settings_for_display();
$addonext_split    = isset($addonext_s['initial_position']['size']) ? (float) $addonext_s['initial_position']['size'] : 50.0;
$addonext_split    = max(0.0, min(100.0, $addonext_split));
$addonext_vertical = isset($addonext_s['slide_direction']) && $addonext_s['slide_direction'] === 'vertical';

$addonext_before = $addonext_s['image_before'] ?? [];
$addonext_after  = $addonext_s['image_after'] ?? [];

$addonext_imgBefore = '';
if (!empty($addonext_before['id'])) {
    $addonext_src = Group_Control_Image_Size::get_attachment_image_src((int) $addonext_before['id'], 'image_size', $addonext_s);
    if ($addonext_src) {
        $addonext_imgBefore = '<img class="addonext-before-after__img" src="' . esc_url($addonext_src) . '" alt="' . esc_attr($addonext_before['alt'] ?? '') . '" draggable="false" loading="lazy">';
    }
}
if ($addonext_imgBefore === '' && !empty($addonext_before['url'])) {
    $addonext_imgBefore = '<img class="addonext-before-after__img" src="' . esc_url($addonext_before['url']) . '" alt="' . esc_attr($addonext_before['alt'] ?? '') . '" draggable="false" loading="lazy">';
}

$addonext_imgAfter = '';
if (!empty($addonext_after['id'])) {
    $addonext_src = Group_Control_Image_Size::get_attachment_image_src((int) $addonext_after['id'], 'image_size', $addonext_s);
    if ($addonext_src) {
        $addonext_imgAfter = '<img class="addonext-before-after__img" src="' . esc_url($addonext_src) . '" alt="' . esc_attr($addonext_after['alt'] ?? '') . '" draggable="false" loading="lazy">';
    }
}
if ($addonext_imgAfter === '' && !empty($addonext_after['url'])) {
    $addonext_imgAfter = '<img class="addonext-before-after__img" src="' . esc_url($addonext_after['url']) . '" alt="' . esc_attr($addonext_after['alt'] ?? '') . '" draggable="false" loading="lazy">';
}

$addonext_iconHtml = '';
if (!empty($addonext_s['handle_icon'])) {
    ob_start();
    Icons_Manager::render_icon($addonext_s['handle_icon'], ['aria-hidden' => 'true']);
    $addonext_iconHtml = trim((string) ob_get_clean());
}
if ($addonext_iconHtml === '') {
    $addonext_iconHtml = $addonext_vertical
        ? '<i class="fas fa-arrows-alt-v" aria-hidden="true"></i>'
        : '<i class="fas fa-arrows-alt-h" aria-hidden="true"></i>';
}

$addonext_showLabels = !isset($addonext_s['show_labels']) || $addonext_s['show_labels'] === 'yes';
$addonext_lb         = isset($addonext_s['label_before']) ? (string) $addonext_s['label_before'] : __('Before', 'addonext');
$addonext_la         = isset($addonext_s['label_after']) ? (string) $addonext_s['label_after'] : __('After', 'addonext');

$addonext_rootClass    = 'addonext-before-after' . ($addonext_vertical ? ' addonext-before-after--vertical' : '');
$addonext_dataOrient   = $addonext_vertical ? 'vertical' : 'horizontal';
$addonext_clipAfter    = $addonext_vertical
    ? 'inset(' . $addonext_split . '% 0 0 0)'
    : 'inset(0 0 0 ' . $addonext_split . '%)';
$addonext_dividerStyle = $addonext_vertical
    ? 'top:' . $addonext_split . '%;'
    : 'left:' . $addonext_split . '%;';
?>
<div
    class="<?php echo esc_attr($addonext_rootClass); ?>"
    style="--ba-split: <?php echo esc_attr((string) $addonext_split); ?>%; --ba-split-num: <?php echo esc_attr((string) $addonext_split); ?>;"
    data-initial-split="<?php echo esc_attr((string) $addonext_split); ?>"
    data-ba-orientation="<?php echo esc_attr($addonext_dataOrient); ?>"
>
    <div class="addonext-before-after__frame">
        <div class="addonext-before-after__before">
            <?php echo $addonext_imgBefore ? wp_kses_post($addonext_imgBefore) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <div class="addonext-before-after__after" style="clip-path: <?php echo esc_attr($addonext_clipAfter); ?>;">
            <?php echo $addonext_imgAfter ? wp_kses_post($addonext_imgAfter) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <?php if ($addonext_showLabels && $addonext_lb !== '') : ?>
            <span class="addonext-before-after__label addonext-before-after__label--before"><?php echo esc_html($addonext_lb); ?></span>
        <?php endif; ?>
        <?php if ($addonext_showLabels && $addonext_la !== '') : ?>
            <span class="addonext-before-after__label addonext-before-after__label--after"><?php echo esc_html($addonext_la); ?></span>
        <?php endif; ?>
        <div class="addonext-before-after__divider" style="<?php echo esc_attr($addonext_dividerStyle); ?>">
            <span class="addonext-before-after__line" aria-hidden="true"></span>
            <button
                type="button"
                class="addonext-before-after__handle"
                aria-label="<?php echo esc_attr__('Drag to compare images', 'addonext'); ?>"
                aria-orientation="<?php echo esc_attr($addonext_dataOrient); ?>"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-valuenow="<?php echo esc_attr((string) (int) round($addonext_split)); ?>"
                role="slider"
            >
                <span class="addonext-before-after__handle-icon"><?php echo $addonext_iconHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            </button>
        </div>
    </div>
</div>

<?php
    }
}
