<?php
namespace Veloria\Extensions\CustomStyles;

use Veloria\Base\ExtensionBase;

defined('ABSPATH') or exit;

/**
 * Registers per-element Custom CSS controls in Elementor (widgets, sections, columns, containers).
 *
 * @package Veloria
 * @since 1.0.0
 */
class CustomStyles extends ExtensionBase
{
    public function get_id(): string
    {
        return 'custom-styles';
    }

    public function get_title(): string
    {
        return __('Custom Styles', 'veloria');
    }

    public function get_description(): string
    {
        return __('Add a Custom CSS section under Advanced for widgets, sections, columns, and containers.', 'veloria');
    }

    public function get_icon(): string
    {
        return 'eicon-code veloria-icon';
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

    public function is_popular(): bool
    {
        return true;
    }

    public function should_enqueue_frontend_assets(): bool
    {
        return false;
    }

    public function register_hooks(): void
    {
        add_action('elementor/element/after_section_end', [$this, 'register_widget_section'], 25, 2);
        add_action('elementor/element/parse_css', [$this, 'inject_parsed_css'], 10, 2);
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
            'section_veloria_custom_styles',
            [
                'label' => __('Custom CSS - Veloria', 'veloria'),
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'veloria_custom_css',
            [
                'label'               => __('Custom CSS', 'veloria'),
                'type'                => \Elementor\Controls_Manager::CODE,
                'language'            => 'css',
                'render_type'         => 'ui',
                'frontend_available'  => true,
            ]
        );

        $element->add_control(
            'veloria_custom_css_hint',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => '<p class="elementor-descriptor"><em>' . esc_html__(
                    'Use the "selector" keyword to target this element’s wrapper.',
                    'veloria'
                ) . '</em></p>',
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $element->end_controls_section();
    }

    /**
     * @param mixed $post_css
     * @param mixed $element
     * @return void
     */
    public function inject_parsed_css($post_css, $element): void
    {
        if (!$this->is_supported_element($element)) {
            return;
        }

        if (!is_object($post_css) || !method_exists($post_css, 'get_stylesheet')) {
            return;
        }

        $settings = $element->get_settings();

        if (!is_array($settings) || empty($settings['veloria_custom_css'])) {
            return;
        }

        $css = trim((string) $settings['veloria_custom_css']);

        if ($css === '') {
            return;
        }

        $selector = method_exists($post_css, 'get_element_unique_selector')
            ? $post_css->get_element_unique_selector($element)
            : $element->get_unique_selector();

        $css = str_replace('selector', $selector, $css);

        $post_css->get_stylesheet()->add_raw_css(
            sprintf('/* Veloria custom CSS: %s */', $element->get_name()) . $css
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
