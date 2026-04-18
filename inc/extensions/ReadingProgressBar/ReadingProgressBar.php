<?php
namespace Elementrify\Extensions\ReadingProgressBar;

use Elementrify\Base\ExtensionBase;
use Elementrify\Common\ExtensionManager;

defined('ABSPATH') or exit;

/**
 * Displays a fixed reading progress bar based on scroll depth.
 *
 * @package Elementrify
 * @since 1.0.0
 */
class ReadingProgressBar extends ExtensionBase
{
    public function get_id(): string
    {
        return 'reading-progress-bar';
    }

    public function get_title(): string
    {
        return __('Reading Progress Bar', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Show a top progress indicator based on current scroll position.', 'elementrify');
    }

    public function get_icon(): string
    {
        return 'eicon-skill-bar elementrify-icon';
    }

    public function get_category(): string
    {
        return 'content';
    }

    public function get_date_launched(): string
    {
        return '2026-04-18';
    }

    public function get_date_updated(): string
    {
        return '2026-04-18';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_fields(): array
    {
        return [
            [
                'id'          => 'progress_color',
                'type'        => 'color',
                'label'       => __('Progress color', 'elementrify'),
                'description' => __('Bar fill color (hex), e.g. #4f46e5', 'elementrify'),
                'default'     => '#4f46e5',
                'placeholder' => '#4f46e5',
            ],
            [
                'id'          => 'track_color',
                'type'        => 'color',
                'label'       => __('Track color', 'elementrify'),
                'description' => __('Background color behind the progress bar.', 'elementrify'),
                'default'     => '#e5e7eb',
                'placeholder' => '#e5e7eb',
            ],
            [
                'id'          => 'height_px',
                'type'        => 'range',
                'label'       => __('Bar height (px)', 'elementrify'),
                'description' => __('Thickness of the progress bar.', 'elementrify'),
                'default'     => 4,
                'min'         => 2,
                'max'         => 16,
                'step'        => 1,
                'unit'        => 'px',
            ],
            [
                'id'          => 'offset_top_px',
                'type'        => 'range',
                'label'       => __('Top offset (px)', 'elementrify'),
                'description' => __('Distance from top edge, useful with sticky notices.', 'elementrify'),
                'default'     => 0,
                'min'         => 0,
                'max'         => 120,
                'step'        => 1,
                'unit'        => 'px',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function sanitize_settings(array $input): array
    {
        $merged = $this->merge_settings_defaults($input);

        $progress = sanitize_hex_color((string) ($merged['progress_color'] ?? '#4f46e5'));
        $track    = sanitize_hex_color((string) ($merged['track_color'] ?? '#e5e7eb'));
        $height   = (int) ($merged['height_px'] ?? 4);
        $offset   = (int) ($merged['offset_top_px'] ?? 0);

        if ($progress === null || $progress === '') {
            $progress = '#4f46e5';
        }

        if ($track === null || $track === '') {
            $track = '#e5e7eb';
        }

        $height = max(2, min(16, $height));
        $offset = max(0, min(120, $offset));

        return [
            'progress_color' => $progress,
            'track_color'    => $track,
            'height_px'      => (string) $height,
            'offset_top_px'  => (string) $offset,
        ];
    }

    public function enqueue_assets(): void
    {
        if (is_admin()) {
            return;
        }

        $settings = emty_container(ExtensionManager::class)->get_extension_settings_payload($this->get_id());

        $progress = sanitize_hex_color((string) ($settings['progress_color'] ?? '#4f46e5')) ?: '#4f46e5';
        $track    = sanitize_hex_color((string) ($settings['track_color'] ?? '#e5e7eb')) ?: '#e5e7eb';
        $height   = max(2, min(16, (int) ($settings['height_px'] ?? 4)));
        $offset   = max(0, min(120, (int) ($settings['offset_top_px'] ?? 0)));

        wp_register_style(
            $this->get_id(),
            ELEMENTRIFY_URL . 'assets/css/extensions/' . $this->get_id() . '.css',
            [],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            $this->get_id(),
            ELEMENTRIFY_URL . 'assets/js/extensions/' . $this->get_id() . '.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );

        wp_add_inline_style(
            $this->get_id(),
            sprintf(
                ':root{--elementrify-rpb-color:%1$s;--elementrify-rpb-track:%2$s;--elementrify-rpb-height:%3$dpx;--elementrify-rpb-top:%4$dpx;}',
                $progress,
                $track,
                $height,
                $offset
            )
        );

        wp_enqueue_style($this->get_id());
        wp_enqueue_script($this->get_id());
    }
}
