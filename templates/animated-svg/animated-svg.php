<?php
/**
 * Animated SVG widget template.
 *
 * @var \Addonext\Widgets\AnimatedSvg\AnimatedSvg $this
 * @var array{mode: string, markup: string, url: string} $addonext_payload
 * @var array<string, mixed> $addonext_settings
 */

defined('ABSPATH') || exit;

use Elementor\Plugin;

$addonext_payload  = $addonext_payload ?? ['mode' => 'inline', 'markup' => '', 'url' => ''];
$addonext_settings = is_array($addonext_settings ?? null) ? $addonext_settings : [];
$addonext_has_link = !empty($addonext_settings['link']['url']);
$addonext_is_edit  = class_exists('\Elementor\Plugin')
    && Plugin::$instance->editor
    && Plugin::$instance->editor->is_edit_mode();

?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_has_link) : ?>
        <a <?php $this->print_render_attribute_string('svg_link'); ?> class="addonext-asvg__link">
    <?php endif; ?>
    <span class="addonext-asvg__inner">
        <?php
        if ($addonext_payload['mode'] === 'img' && $addonext_payload['url'] !== '') {
            echo '<img class="addonext-asvg__img" src="' . esc_url($addonext_payload['url']) . '" alt="" loading="lazy" decoding="async" />';
        } elseif ($addonext_payload['mode'] === 'inline' && $addonext_payload['markup'] !== '') {
            echo $addonext_payload['markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized in widget
        } elseif ($addonext_is_edit) {
            echo '<p class="addonext-asvg__placeholder">' . esc_html__('Choose a built-in icon, upload an SVG, or set a file URL.', 'addonext') . '</p>';
        }
        ?>
    </span>
    <?php if ($addonext_has_link) : ?>
        </a>
    <?php endif; ?>
</div>
