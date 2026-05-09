<?php
namespace Elementrify\Extensions\MovingCircles;

use Elementrify\Base\ExtensionBase;

defined('ABSPATH') or exit;

/**
 * Soft floating circles behind sections, columns, containers, and widgets (Style tab).
 *
 * @package Elementrify
 * @since 1.0.0
 */
class MovingCircles extends ExtensionBase
{
    /**
     * @var bool
     */
    private static $handles_registered = false;

    /**
     * @return string
     */
    public function get_id(): string
    {
        return 'moving-circles';
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return __('Moving Circles', 'elementrify');
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return __('Add a calm floating-circle backdrop from the Style tab on widgets, sections, columns, and containers.', 'elementrify');
    }

    /**
     * @return string
     */
    public function get_icon(): string
    {
        return 'eicon-circle-o elementrify-icon';
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
     * @return void
     */
    public function register_hooks(): void
    {
        add_action('elementor/element/after_section_end', [$this, 'register_style_tab_section'], 15, 3);
        add_action('elementor/frontend/before_render', [$this, 'prepare_moving_circles_wrapper'], 10, 1);
    }

    /**
     * @return void
     */
    public function enqueue_assets(): void
    {
        $this->register_moving_circles_handles();
        wp_enqueue_style('moving-circles');
        wp_enqueue_script('moving-circles');
    }

    /**
     * Loads assets in the Elementor preview iframe.
     *
     * @return void
     */
    public function enqueue_for_elementor_preview(): void
    {
        $this->register_moving_circles_handles();
        wp_enqueue_style('moving-circles');
        wp_enqueue_script('moving-circles');
    }

    /**
     * @return void
     */
    private function register_moving_circles_handles(): void
    {
        if (self::$handles_registered) {
            return;
        }

        self::$handles_registered = true;

        wp_register_style(
            'moving-circles',
            ELEMENTRIFY_URL . 'assets/css/extensions/moving-circles.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'moving-circles',
            ELEMENTRIFY_URL . 'assets/js/extensions/moving-circles.js',
            ['jquery', 'elementor-frontend'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    /**
     * @param \Elementor\Controls_Stack $element
     * @param string $section_id
     * @param array<string, mixed> $args
     * @return void
     */
    public function register_style_tab_section($element, string $section_id, array $args = []): void
    {
        if (!$this->is_supported_element($element)) {
            return;
        }

        $tab = isset($args['tab']) ? $args['tab'] : '';

        if ($tab !== \Elementor\Controls_Manager::TAB_STYLE) {
            return;
        }

        static $registered = [];

        $stack_name = $element->get_name();

        if (isset($registered[$stack_name])) {
            return;
        }

        $registered[$stack_name] = true;

        $element->start_controls_section(
            'section_elementrify_moving_circles',
            [
                'label' => __('Moving circles', 'elementrify'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $element->add_control(
            'elementrify_moving_circles_enable',
            [
                'label'              => __('Circle backdrop', 'elementrify'),
                'type'               => \Elementor\Controls_Manager::SWITCHER,
                'label_on'           => __('Yes', 'elementrify'),
                'label_off'          => __('No', 'elementrify'),
                'return_value'       => 'yes',
                'default'            => '',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'elementrify_moving_circles_count',
            [
                'label'              => __('Number of circles', 'elementrify'),
                'type'               => \Elementor\Controls_Manager::SLIDER,
                'size_units'         => ['px'],
                'range'              => [
                    'px' => [
                        'min' => 3,
                        'max' => 28,
                    ],
                ],
                'default'            => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'frontend_available' => true,
                'condition'          => [
                    'elementrify_moving_circles_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'elementrify_moving_circles_speed',
            [
                'label'              => __('Motion speed', 'elementrify'),
                'type'               => \Elementor\Controls_Manager::SLIDER,
                'size_units'         => ['px'],
                'range'              => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'default'            => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'frontend_available' => true,
                'description'        => __('100% is the default pace. Higher moves faster.', 'elementrify'),
                'condition'          => [
                    'elementrify_moving_circles_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'elementrify_moving_circles_size',
            [
                'label'              => __('Circle size', 'elementrify'),
                'type'               => \Elementor\Controls_Manager::SLIDER,
                'size_units'         => ['px'],
                'range'              => [
                    'px' => [
                        'min' => 12,
                        'max' => 140,
                    ],
                ],
                'default'            => [
                    'unit' => 'px',
                    'size' => 48,
                ],
                'frontend_available' => true,
                'condition'          => [
                    'elementrify_moving_circles_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'elementrify_moving_circles_color',
            [
                'label'              => __('Circle color', 'elementrify'),
                'type'               => \Elementor\Controls_Manager::COLOR,
                'default'            => '#ffffff',
                'frontend_available' => true,
                'condition'          => [
                    'elementrify_moving_circles_enable' => 'yes',
                ],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * @param mixed $element
     * @return void
     */
    public function prepare_moving_circles_wrapper($element): void
    {
        if (!$this->is_supported_element($element)) {
            return;
        }

        $settings = $element->get_settings_for_display();

        if (empty($settings['elementrify_moving_circles_enable']) || $settings['elementrify_moving_circles_enable'] !== 'yes') {
            return;
        }

        $count = 10;

        if (isset($settings['elementrify_moving_circles_count']) && is_array($settings['elementrify_moving_circles_count'])) {
            $count = isset($settings['elementrify_moving_circles_count']['size'])
                ? (int) $settings['elementrify_moving_circles_count']['size']
                : 10;
        }

        $count = max(3, min(28, $count));

        $speedPercent = 100;

        if (isset($settings['elementrify_moving_circles_speed']) && is_array($settings['elementrify_moving_circles_speed'])) {
            $speedPercent = isset($settings['elementrify_moving_circles_speed']['size'])
                ? (int) $settings['elementrify_moving_circles_speed']['size']
                : 100;
        }

        $speedPercent = max(20, min(200, $speedPercent));

        $sizePx = 48;

        if (isset($settings['elementrify_moving_circles_size']) && is_array($settings['elementrify_moving_circles_size'])) {
            $sizePx = isset($settings['elementrify_moving_circles_size']['size'])
                ? (int) $settings['elementrify_moving_circles_size']['size']
                : 48;
        }

        $sizePx = max(12, min(140, $sizePx));

        $colorRaw = $this->extract_color_input($settings, 'elementrify_moving_circles_color');
        $color    = $this->sanitize_color_value($colorRaw, '#ffffff');

        $element->add_render_attribute('_wrapper', 'class', 'elementrify-moving-circles');
        $element->add_render_attribute('_wrapper', 'data-elementrify-moving-circles', 'yes');
        $element->add_render_attribute('_wrapper', 'data-elementrify-moving-circles-host-id', esc_attr($element->get_id()));
        $element->add_render_attribute('_wrapper', 'data-elementrify-moving-circles-count', (string) $count);
        $element->add_render_attribute('_wrapper', 'data-elementrify-moving-circles-speed', (string) $speedPercent);
        $element->add_render_attribute('_wrapper', 'data-elementrify-moving-circles-size', (string) $sizePx);
        $element->add_render_attribute('_wrapper', 'data-elementrify-moving-circles-color', esc_attr($color));
    }

    /**
     * @param array<string, mixed> $settings
     */
    private function extract_color_input(array $settings, string $key): string
    {
        $raw = $settings[$key] ?? null;

        if (is_string($raw)) {
            return trim($raw);
        }

        if (is_array($raw)) {
            if (isset($raw['color']) && $raw['color'] !== '') {
                return trim((string) $raw['color']);
            }
            if (isset($raw['value']) && is_string($raw['value']) && trim($raw['value']) !== '') {
                return trim($raw['value']);
            }
        }

        return '';
    }

    /**
     * @param string $value
     * @param string $fallback
     * @return string
     */
    private function sanitize_color_value(string $value, string $fallback): string
    {
        $value = trim($value);

        if ($value === '') {
            return $fallback;
        }

        if (stripos($value, 'rgb') === 0) {
            return preg_replace('/[\r\n\t]+/', '', $value);
        }

        $hex = sanitize_hex_color($value);

        return $hex !== '' ? $hex : $fallback;
    }

    /**
     * @param mixed $element
     */
    private function is_supported_element($element): bool
    {
        if (!$element instanceof \Elementor\Element_Base) {
            return false;
        }

        return in_array($element->get_type(), ['widget', 'section', 'column', 'container'], true);
    }
}
