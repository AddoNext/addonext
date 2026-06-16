<?php
namespace Veloria\Widgets\LottieAnimation;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Css_Filter;

defined('ABSPATH') or exit;

/**
 * Lottie JSON animation (lottie-web), modeled on Royal Addons Lottie widget behavior.
 */
class LottieAnimation extends WidgetBase
{
    public const DEFAULT_JSON_URL = VELORIA_URL . 'assets/lottie/default.json';

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('veloria-lottie-animation');
        wp_enqueue_script('veloria-lottie-animation');
    }

    public function get_icon(): string
    {
        return 'eicon-lottie veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-lottie-animation';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Lottie Animation', 'veloria');
    }

    public function get_description(): string
    {
        return __('Lottie JSON animations with triggers, speed, and SVG or canvas render.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#06b6d4';
    }

    public function get_keywords(): array
    {
        return ['veloria', 'lottie', 'animation', 'json', 'svg', 'canvas', 'motion'];
    }

    public function get_style_depends(): array
    {
        return ['veloria-lottie-animation'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-lottie-animation'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-lottie-animation',
            VELORIA_URL . 'assets/css/lottie-animation.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-lottie-web',
            VELORIA_URL . 'assets/js/lib/lottie/lottie.min.js',
            [],
            VELORIA_VERSION,
            true
        );
        wp_register_script(
            'veloria-lottie-animation',
            VELORIA_URL . 'assets/js/lottie-animation.js',
            ['veloria-lottie-web'],
            VELORIA_VERSION,
            true
        );
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function build_lottie_settings_json(array $settings): string
    {
        $animate = $settings['animate_view'] ?? [];
        $sizes   = is_array($animate) && isset($animate['sizes']) && is_array($animate['sizes']) ? $animate['sizes'] : [];

        $payload = [
            'loop'            => $settings['loop'] ?? 'yes',
            'autoplay'        => $settings['autoplay'] ?? 'yes',
            'speed'           => isset($settings['speed']) ? (float) $settings['speed'] : 1.0,
            'trigger'         => $settings['trigger'] ?? 'none',
            'reverse'         => $settings['reverse'] ?? '',
            'scroll_start'    => isset($sizes['start']) ? (float) $sizes['start'] : 0.0,
            'scroll_end'      => isset($sizes['end']) ? (float) $sizes['end'] : 100.0,
            'lottie_renderer' => $settings['lottie_renderer'] ?? 'svg',
        ];

        return wp_json_encode($payload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_settings', [
            'label' => __('Settings', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('source', [
            'label'   => __('File source', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'url'  => __('External URL', 'veloria'),
                'file' => __('Media file', 'veloria'),
            ],
            'default' => 'url',
        ]);

        $this->add_control('json_url', [
            'label'       => __('Animation JSON URL', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'dynamic'     => ['active' => true],
            'default'     => self::DEFAULT_JSON_URL,
            'description' => sprintf(
                wp_kses(
                    /* translators: %s: HTML link to LottieFiles. */
                    __('Paste a .json URL from %s.', 'veloria'),
                    ['a' => ['href' => [], 'target' => []]]
                ),
                '<a href="https://lottiefiles.com/" target="_blank" rel="noopener noreferrer">LottieFiles</a>'
            ),
            'label_block' => true,
            'condition'   => [
                'source' => 'url',
            ],
        ]);

        $this->add_control('json_file', [
            'label'              => __('Upload JSON file', 'veloria'),
            'type'               => Controls_Manager::MEDIA,
            'media_type'         => 'application/json',
            'frontend_available' => true,
            'condition'          => [
                'source' => 'file',
            ],
        ]);

        $this->add_control('autoplay', [
            'label'        => __('Autoplay', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
            'separator'    => 'before',
        ]);

        $this->add_control('loop', [
            'label'        => __('Loop', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('reverse', [
            'label'        => __('Reverse', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'condition'    => [
                'trigger!' => 'scroll',
            ],
        ]);

        $this->add_control('speed', [
            'label'   => __('Animation speed', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 1,
            'min'     => 0.1,
            'max'     => 3,
            'step'    => 0.1,
        ]);

        $this->add_control('trigger', [
            'label'              => __('Trigger', 'veloria'),
            'type'               => Controls_Manager::SELECT,
            'default'            => 'none',
            'options'            => [
                'none'     => __('None', 'veloria'),
                'viewport' => __('Viewport', 'veloria'),
                'hover'    => __('Hover', 'veloria'),
                'scroll'   => __('Scroll', 'veloria'),
            ],
            'frontend_available' => true,
        ]);

        $this->add_control('animate_view', [
            'label'     => __('Viewport range', 'veloria'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => [
                'sizes' => [
                    'start' => 0,
                    'end'   => 100,
                ],
                'unit'  => '%',
            ],
            'labels'    => [
                __('Bottom', 'veloria'),
                __('Top', 'veloria'),
            ],
            'scales'    => 1,
            'handles'   => 'range',
            'condition' => [
                'trigger' => ['scroll', 'viewport'],
            ],
        ]);

        $this->add_responsive_control('animation_size', [
            'label'       => __('Size', 'veloria'),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => ['px', '%'],
            'default'     => [
                'unit' => '%',
                'size' => 50,
            ],
            'range'       => [
                'px' => [
                    'min' => 1,
                    'max' => 800,
                ],
                '%'  => [
                    'min' => 1,
                    'max' => 100,
                ],
            ],
            'render_type' => 'template',
            'separator'   => 'before',
            'selectors'   => [
                '{{WRAPPER}} .veloria-lottie-animation svg' => 'width: 100% !important; height: 100% !important;',
                '{{WRAPPER}} .veloria-lottie-animation' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('rotate', [
            'label'       => __('Rotate (degrees)', 'veloria'),
            'type'        => Controls_Manager::SLIDER,
            'description' => __('Rotation in degrees.', 'veloria'),
            'range'       => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ],
            ],
            'default'     => [
                'size' => 0,
            ],
            'selectors'   => [
                '{{WRAPPER}} .veloria-lottie-animation' => 'transform: rotate({{SIZE}}deg);',
            ],
        ]);

        $this->add_responsive_control('animation_align', [
            'label'     => __('Alignment', 'veloria'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [
                    'title' => __('Left', 'veloria'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center'     => [
                    'title' => __('Center', 'veloria'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'flex-end'   => [
                    'title' => __('Right', 'veloria'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'center',
            'toggle'    => false,
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .veloria-lottie-animation__wrapper' => 'justify-content: {{VALUE}}; align-items: center;',
            ],
        ]);

        $this->add_control('lottie_renderer', [
            'label'        => __('Render as', 'veloria'),
            'type'         => Controls_Manager::SELECT,
            'options'      => [
                'svg'    => __('SVG', 'veloria'),
                'canvas' => __('Canvas', 'veloria'),
            ],
            'default'      => 'svg',
            'render_type'  => 'template',
            'separator'    => 'before',
        ]);

        $this->add_control('render_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __('Use canvas if the page feels slow with many Lottie instances.', 'veloria'),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        ]);

        $this->add_control('link_switcher', [
            'label' => __('Wrapper link', 'veloria'),
            'type'  => Controls_Manager::SWITCHER,
        ]);

        $this->add_control('link', [
            'label'       => __('Link', 'veloria'),
            'type'        => Controls_Manager::URL,
            'dynamic'     => ['active' => true],
            'default'     => [
                'url' => '',
            ],
            'placeholder' => 'https://',
            'label_block' => true,
            'condition'   => [
                'link_switcher' => 'yes',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', [
            'label' => __('Animation', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('tabs_lottie');

        $this->start_controls_tab('tab_lottie_normal', [
            'label' => __('Normal', 'veloria'),
        ]);

        $this->add_control('opacity', [
            'label'     => __('Opacity', 'veloria'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'max'  => 1,
                    'min'  => 0.1,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .veloria-lottie-animation' => 'opacity: {{SIZE}};',
            ],
        ]);

        $this->add_control('hover_transition', [
            'label'     => __('Transition duration', 'veloria'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 0.3,
            'min'       => 0,
            'max'       => 5,
            'step'      => 0.1,
            'selectors' => [
                '{{WRAPPER}} .veloria-lottie-animation' => 'transition-duration: {{VALUE}}s;',
            ],
        ]);

        $this->add_group_control(Group_Control_Css_Filter::get_type(), [
            'name'     => 'css_filters',
            'selector' => '{{WRAPPER}} .veloria-lottie-animation',
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('tab_lottie_hover', [
            'label' => __('Hover', 'veloria'),
        ]);

        $this->add_control('hover_opacity', [
            'label'     => __('Opacity', 'veloria'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'max'  => 1,
                    'min'  => 0.1,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .veloria-lottie-animation:hover' => 'opacity: {{SIZE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Css_Filter::get_type(), [
            'name'     => 'hover_css_filters',
            'selector' => '{{WRAPPER}} .veloria-lottie-animation:hover',
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/lottie-animation/lottie-animation.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
