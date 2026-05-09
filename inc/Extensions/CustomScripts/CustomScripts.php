<?php
namespace Elementrify\Extensions\CustomScripts;

use Elementrify\Base\ExtensionBase;

defined('ABSPATH') or exit;

/**
 * Registers per-element custom JavaScript in Elementor and extension settings for global script placement.
 *
 * @package Elementrify
 * @since 1.0.0
 */
class CustomScripts extends ExtensionBase
{
    public function get_id(): string
    {
        return 'custom-scripts';
    }

    public function get_title(): string
    {
        return __('Custom Scripts', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Add a Custom JavaScript section under Advanced for widgets, sections, columns, and containers.', 'elementrify');
    }

    public function get_icon(): string
    {
        return 'eicon-editor-code elementrify-icon';
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

    public function should_enqueue_frontend_assets(): bool
    {
        return false;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_fields(): array
    {
        return [
            [
                'id'          => 'load_position',
                'type'        => 'select',
                'label'       => __('Load position', 'elementrify'),
                'description' => __('Choose where custom JavaScript should be printed.', 'elementrify'),
                'default'     => 'footer',
                'options'     => [
                    ['value' => 'header', 'label' => __('Header', 'elementrify')],
                    ['value' => 'footer', 'label' => __('Footer', 'elementrify')],
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
        $merged = $this->merge_settings_defaults($input);
        $value  = isset($merged['load_position']) ? (string) $merged['load_position'] : 'footer';

        if (!in_array($value, ['header', 'footer'], true)) {
            $value = 'footer';
        }

        return ['load_position' => $value];
    }

    public function register_hooks(): void
    {
        add_action('elementor/element/after_section_end', [$this, 'register_widget_section'], 26, 2);
        foreach (['widget', 'section', 'column', 'container'] as $type) {
            add_action('elementor/frontend/' . $type . '/after_render', [$this, 'print_element_custom_js'], 10, 1);
        }
    }

    /**
     * @param \Elementor\Controls_Stack $element
     * @param string $section_id
     * @return void
     */
    public function register_widget_section($element, string $section_id): void
    {
        if (!$this->is_supported_element($element)) {
            return;
        }

        if (!in_array($section_id, ['section_custom_css_pro', 'section_custom_css'], true)) {
            return;
        }

        static $registered = [];

        $stack_name = $element->get_name();

        if (isset($registered[$stack_name])) {
            return;
        }

        $registered[$stack_name] = true;

        $element->start_controls_section(
            'section_elementrify_custom_scripts',
            [
                'label' => __('Custom JavaScript - Elementrify', 'elementrify'),
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'elementrify_custom_js',
            [
                'label'               => __('Custom JavaScript', 'elementrify'),
                'type'                => \Elementor\Controls_Manager::CODE,
                'language'            => 'javascript',
                'render_type'         => 'ui',
                'frontend_available'  => true,
            ]
        );

        $element->add_control(
            'elementrify_custom_js_hint',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => '<p class="elementor-descriptor"><em>' . esc_html__(
                    'Runs on the front end after this element is rendered. Avoid conflicting with other scripts.',
                    'elementrify'
                ) . '</em></p>',
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $element->end_controls_section();
    }

    /**
     * @param mixed $element
     * @return void
     */
    public function print_element_custom_js($element): void
    {
        if (!$this->is_supported_element($element)) {
            return;
        }

        $settings = $element->get_settings_for_display();
        $custom_js = isset($settings['elementrify_custom_js']) ? trim((string) $settings['elementrify_custom_js']) : '';

        if ($custom_js === '') {
            return;
        }

        printf(
            '<script id="elementrify-element-js-%s">' . "\n%s\n</script>\n",
            esc_attr((string) $element->get_id()),
            $custom_js // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted builder input
        );
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
