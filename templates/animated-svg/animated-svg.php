<?php
/**
 * Animated SVG widget template.
 *
 * @var \Veloria\Widgets\AnimatedSvg\AnimatedSvg $this
 * @var array{mode: string, markup: string, url: string} $veloria_payload
 * @var array<string, mixed> $veloria_settings
 */

defined('ABSPATH') || exit;

use Elementor\Plugin;

$veloria_payload  = $veloria_payload ?? ['mode' => 'inline', 'markup' => '', 'url' => ''];
$veloria_settings = is_array($veloria_settings ?? null) ? $veloria_settings : [];
$veloria_has_link = !empty($veloria_settings['link']['url']);
$veloria_is_edit  = class_exists('\Elementor\Plugin')
    && Plugin::$instance->editor
    && Plugin::$instance->editor->is_edit_mode();

?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_has_link) : ?>
        <a <?php $this->print_render_attribute_string('svg_link'); ?> class="veloria-asvg__link">
    <?php endif; ?>
    <span class="veloria-asvg__inner">
        <?php
        if ($veloria_payload['mode'] === 'img' && $veloria_payload['url'] !== '') {
            echo '<img class="veloria-asvg__img" src="' . esc_url($veloria_payload['url']) . '" alt="" loading="lazy" decoding="async" />';
        } elseif ($veloria_payload['mode'] === 'inline' && $veloria_payload['markup'] !== '') {
            echo $veloria_payload['markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized in widget
        } elseif ($veloria_is_edit) {
            echo '<p class="veloria-asvg__placeholder">' . esc_html__('Choose a built-in icon, upload an SVG, or set a file URL.', 'veloria') . '</p>';
        }
        ?>
    </span>
    <?php if ($veloria_has_link) : ?>
        </a>
    <?php endif; ?>
</div>
