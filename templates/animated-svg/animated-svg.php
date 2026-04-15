<?php
/**
 * Animated SVG widget template.
 *
 * @var \Elementrify\Widgets\AnimatedSvg\AnimatedSvg $this
 * @var array{mode: string, markup: string, url: string} $payload
 * @var array<string, mixed> $settings
 */

defined('ABSPATH') || exit;

use Elementor\Plugin;

$payload  = $payload ?? ['mode' => 'inline', 'markup' => '', 'url' => ''];
$settings = is_array($settings ?? null) ? $settings : [];
$has_link = !empty($settings['link']['url']);
$is_edit  = class_exists('\Elementor\Plugin')
    && Plugin::$instance->editor
    && Plugin::$instance->editor->is_edit_mode();

?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($has_link) : ?>
        <a <?php $this->print_render_attribute_string('svg_link'); ?> class="elementrify-asvg__link">
    <?php endif; ?>
    <span class="elementrify-asvg__inner">
        <?php
        if ($payload['mode'] === 'img' && $payload['url'] !== '') {
            echo '<img class="elementrify-asvg__img" src="' . esc_url($payload['url']) . '" alt="" loading="lazy" decoding="async" />';
        } elseif ($payload['mode'] === 'inline' && $payload['markup'] !== '') {
            echo $payload['markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized in widget
        } elseif ($is_edit) {
            echo '<p class="elementrify-asvg__placeholder">' . esc_html__('Choose a built-in icon, upload an SVG, or set a file URL.', 'elementrify') . '</p>';
        }
        ?>
    </span>
    <?php if ($has_link) : ?>
        </a>
    <?php endif; ?>
</div>
