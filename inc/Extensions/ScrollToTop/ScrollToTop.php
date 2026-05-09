<?php
namespace Elementrify\Extensions\ScrollToTop;

use Elementrify\Base\ExtensionBase;
use Elementrify\Common\ExtensionManager;
use Elementrify\Utils\OptionsHelper;

defined('ABSPATH') or exit;

/**
 * Floating button that scrolls the viewport to the top.
 *
 * @package Elementrify
 * @since 1.0.0
 */
class ScrollToTop extends ExtensionBase
{
    /**
     * @return string
     */
    public function get_id(): string
    {
        return 'scroll-to-top';
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return __('Scroll to Top', 'elementrify');
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return __('Add a floating button that smoothly scrolls back to the top of the page.', 'elementrify');
    }

    /**
     * @return string
     */
    public function get_icon(): string
    {
        return 'eicon-arrow-up elementrify-icon';
    }

    /**
     * @return string
     */
    public function get_category(): string
    {
        return 'content';
    }

    /**
     * @return string
     */
    public function get_date_launched(): string
    {
        return '2026-04-18';
    }

    /**
     * @return string
     */
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
                'id'          => 'position',
                'type'        => 'select',
                'label'       => __('Position', 'elementrify'),
                'description' => __('Corner of the screen for the button.', 'elementrify'),
                'default'     => 'bottom-right',
                'options'     => [
                    ['value' => 'bottom-right', 'label' => __('Bottom right', 'elementrify')],
                    ['value' => 'bottom-left', 'label' => __('Bottom left', 'elementrify')],
                    ['value' => 'top-right', 'label' => __('Top right', 'elementrify')],
                    ['value' => 'top-left', 'label' => __('Top left', 'elementrify')],
                ],
            ],
            [
                'id'          => 'button_color',
                'type'        => 'color',
                'label'       => __('Button color', 'elementrify'),
                'description' => __('CSS color (hex recommended), e.g. #4f46e5', 'elementrify'),
                'default'     => '#4f46e5',
                'placeholder' => '#4f46e5',
            ],
            [
                'id'          => 'button_size_px',
                'type'        => 'range',
                'label'       => __('Button size (px)', 'elementrify'),
                'description' => __('Outer width and height of the square button.', 'elementrify'),
                'default'     => 48,
                'min'         => 32,
                'max'         => 96,
                'step'        => 1,
                'unit'        => 'px',
            ],
            [
                'id'          => 'padding_px',
                'type'        => 'range',
                'label'       => __('Inner padding (px)', 'elementrify'),
                'description' => __('Space between the icon and the button edge.', 'elementrify'),
                'default'     => 12,
                'min'         => 0,
                'max'         => 36,
                'step'        => 1,
                'unit'        => 'px',
            ],
            [
                'id'          => 'visibility',
                'type'        => 'select',
                'label'       => __('Where to show', 'elementrify'),
                'description' => __('Control on which singular posts or pages the button appears.', 'elementrify'),
                'default'     => 'all',
                'options'     => [
                    ['value' => 'all', 'label' => __('All pages', 'elementrify')],
                    ['value' => 'exclude', 'label' => __('All except selected', 'elementrify')],
                    ['value' => 'include_only', 'label' => __('Only selected', 'elementrify')],
                ],
            ],
            [
                'id'            => 'page_ids',
                'type'          => 'checkbox_group',
                'label'         => __('Posts & pages', 'elementrify'),
                'description'   => __('Pick posts or pages for the visibility modes above.', 'elementrify'),
                'default'       => [],
                'options'       => $this->get_page_and_post_options(),
                'show_when_in'  => [
                    'field'  => 'visibility',
                    'values' => ['exclude', 'include_only'],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function sanitize_settings(array $input): array
    {
        $merged = $this->merge_settings_defaults(is_array($input) ? $input : []);

        $allowed_positions = ['bottom-right', 'bottom-left', 'top-right', 'top-left'];
        $pos               = isset($merged['position']) ? (string) $merged['position'] : 'bottom-right';

        if (!in_array($pos, $allowed_positions, true)) {
            $pos = 'bottom-right';
        }

        $merged['position'] = $pos;

        $color = isset($merged['button_color']) ? (string) $merged['button_color'] : '#4f46e5';
        $hex   = sanitize_hex_color($color);

        if ($hex === '') {
            $hex = '#4f46e5';
        }

        $merged['button_color'] = $hex;

        $size = isset($merged['button_size_px']) ? (int) $merged['button_size_px'] : 48;
        $size = max(32, min(96, $size));
        $merged['button_size_px'] = (string) $size;

        $pad = isset($merged['padding_px']) ? (int) $merged['padding_px'] : 12;
        $pad = max(0, min(36, $pad));
        $merged['padding_px'] = (string) $pad;

        $vis = isset($merged['visibility']) ? (string) $merged['visibility'] : 'all';

        if (!in_array($vis, ['all', 'exclude', 'include_only'], true)) {
            $vis = 'all';
        }

        $merged['visibility'] = $vis;

        $allowed_ids = [];

        foreach ($this->get_page_and_post_options() as $opt) {
            $allowed_ids[] = $opt['value'];
        }

        $ids = isset($merged['page_ids']) && is_array($merged['page_ids']) ? $merged['page_ids'] : [];
        $ids = array_values(array_unique(array_map('strval', $ids)));
        $ids = array_values(array_intersect($ids, $allowed_ids));
        $merged['page_ids'] = $ids;

        return $merged;
    }

    /**
     * @return void
     */
    public function enqueue_assets(): void
    {
        if (is_admin()) {
            return;
        }

        $active = OptionsHelper::get_option('active_extensions', []);

        if (!is_array($active) || !in_array($this->get_id(), $active, true)) {
            return;
        }

        $manager  = emty_container(ExtensionManager::class);
        $settings = $manager->get_extension_settings_payload($this->get_id());

        $base = ELEMENTRIFY_URL . 'assets/';

        wp_register_style(
            $this->get_id(),
            $base . 'css/extensions/' . $this->get_id() . '.css',
            [],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            $this->get_id(),
            $base . 'js/extensions/' . $this->get_id() . '.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );

        $size = (int) $settings['button_size_px'];
        $pad   = (int) $settings['padding_px'];
        $color = esc_attr((string) $settings['button_color']);

        wp_add_inline_style(
            $this->get_id(),
            sprintf(
                '#elementrify-scroll-top{width:%dpx;height:%dpx;padding:%dpx;background-color:%s;}',
                max(32, min(96, $size)),
                max(32, min(96, $size)),
                max(0, min(36, $pad)),
                $color
            )
        );

        $current_id = is_singular() ? (int) get_queried_object_id() : 0;
        $front_id   = (int) get_option('page_on_front');
        $show_on_front = (string) get_option('show_on_front') === 'page' && $front_id > 0;

        wp_localize_script(
            $this->get_id(),
            'elementrifyScrollToTop',
            [
                'position'       => (string) $settings['position'],
                'visibility'     => (string) $settings['visibility'],
                'pageIds'        => array_map('strval', (array) ($settings['page_ids'] ?? [])),
                'currentPostId'  => $current_id,
                'isFront'        => is_front_page(),
                'frontPageId'    => $show_on_front ? $front_id : 0,
            ]
        );

        wp_enqueue_style($this->get_id());
        wp_enqueue_script($this->get_id());
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private function get_page_and_post_options(): array
    {
        $out   = [];
        $pages = get_posts(
            [
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'posts_per_page' => 80,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'no_found_rows'  => true,
            ]
        );

        foreach ($pages as $page) {
            $out[] = [
                'value' => (string) $page->ID,
                'label' => sprintf('(Page) %s', $page->post_title),
            ];
        }

        $posts = get_posts(
            [
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 80,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'no_found_rows'  => true,
            ]
        );

        foreach ($posts as $post) {
            $out[] = [
                'value' => (string) $post->ID,
                'label' => sprintf('(Post) %s', $post->post_title),
            ];
        }

        return $out;
    }
}
