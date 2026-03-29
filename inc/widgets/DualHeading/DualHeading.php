<?php
namespace Elementrify\Widgets\DualHeading;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined('ABSPATH') or exit;

/**
 * Two-part heading: general text plus gradient text or a highlighted box (gradient or solid).
 */
class DualHeading extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_preview_styles']);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_styles(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('elementrify-dual-heading');
    }

    public function enqueue_preview_scripts(): void
    {
        $this->enqueue_assets();
        wp_enqueue_script('elementrify-dual-heading-editor');
    }

    public function get_icon(): string
    {
        return 'eicon-heading elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-dual-heading';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Dual Heading', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Heading with separate styles for general and highlighted text.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#1d4ed8';
    }

    public function get_keywords(): array
    {
        return ['elementrify', 'heading', 'dual', 'gradient', 'title', 'typography'];
    }

    public function get_style_depends(): array
    {
        return ['elementrify-dual-heading'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-dual-heading',
            ELEMENTRIFY_URL . 'assets/css/dual-heading.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-dual-heading-editor',
            ELEMENTRIFY_URL . 'assets/js/dual-heading-editor.js',
            ['jquery', 'elementor-frontend'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('first_line', [
            'label'   => __('General text', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('What a', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('second_line', [
            'label'   => __('Highlighted text', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Amazing experience', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('html_tag', [
            'label'   => __('HTML tag', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'p'  => 'p',
            ],
        ]);

        $this->add_control('align', [
            'label'        => __('Alignment', 'elementrify'),
            'type'         => Controls_Manager::CHOOSE,
            'options'      => [
                'left'   => [
                    'title' => __('Left', 'elementrify'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'elementrify'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'  => [
                    'title' => __('Right', 'elementrify'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'      => 'left',
            'prefix_class' => 'elementrify-dual-heading--align-',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_general', [
            'label' => __('General text', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_general',
                'label'    => __('Typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-dual-heading__general',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'fields_options' => [
                    'font_weight' => [
                        'default' => '800',
                    ],
                ],
            ]
        );

        $this->add_control('color_general', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#343a40',
            'selectors' => [
                '{{WRAPPER}} .elementrify-dual-heading__general' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('parts_gap', [
            'label'      => __('Space between parts', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => [
                'px' => ['min' => 0, 'max' => 80],
                'em' => ['min' => 0, 'max' => 3, 'step' => 0.05],
            ],
            'default'    => [
                'unit' => 'em',
                'size' => 0.35,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-dual-heading__title' => 'column-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_highlight', [
            'label' => __('Highlighted text', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('highlight_appearance', [
            'label'        => __('Highlight style', 'elementrify'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'text',
            'options'      => [
                'text' => __('Gradient text', 'elementrify'),
                'box'  => __('Box', 'elementrify'),
            ],
            'prefix_class' => 'elementrify-dual-heading--hl-',
        ]);

        $this->add_control('highlight_box_fill', [
            'label'     => __('Box fill', 'elementrify'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'gradient',
            'options'   => [
                'gradient' => __('Gradient', 'elementrify'),
                'solid'    => __('Solid color', 'elementrify'),
            ],
            'condition' => [
                'highlight_appearance' => 'box',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_highlight',
                'label'    => __('Typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-dual-heading__highlight-inner',
                'fields_options' => [
                    'font_weight' => [
                        'default' => '800',
                    ],
                ],
            ]
        );

        $this->add_control('highlight_text_on_box', [
            'label'     => __('Text on highlight', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}}' => '--elementrify-dual-highlight-text: {{VALUE}};',
                '{{WRAPPER}}.elementrify-dual-heading--hl-box .elementrify-dual-heading__highlight-inner' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'highlight_appearance' => 'box',
            ],
        ]);

        $this->add_control('highlight_box_solid_color', [
            'label'      => __('Background color', 'elementrify'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#845ef7',
            'conditions' => [
                'relation' => 'and',
                'terms'    => [
                    [
                        'name'     => 'highlight_appearance',
                        'operator' => '==',
                        'value'    => 'box',
                    ],
                    [
                        'name'     => 'highlight_box_fill',
                        'operator' => '==',
                        'value'    => 'solid',
                    ],
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}}.elementrify-dual-heading--hl-box .elementrify-dual-heading__highlight' => 'background-image: none; background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('highlight_padding', [
            'label'      => __('Highlight padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '0.45',
                'right' => '0.85',
                'bottom' => '0.45',
                'left' => '0.85',
                'unit' => 'em',
                'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-dual-heading__highlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition'  => [
                'highlight_appearance' => 'box',
            ],
        ]);

        $this->add_responsive_control('highlight_radius', [
            'label'      => __('Highlight radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top' => 6,
                'right' => 6,
                'bottom' => 6,
                'left' => 6,
                'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-dual-heading__highlight' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition'  => [
                'highlight_appearance' => 'box',
            ],
        ]);

        $gradientControlsConditions = [
            'relation' => 'or',
            'terms'    => [
                [
                    'name'     => 'highlight_appearance',
                    'operator' => '==',
                    'value'    => 'text',
                ],
                [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'highlight_appearance',
                            'operator' => '==',
                            'value'    => 'box',
                        ],
                        [
                            'name'     => 'highlight_box_fill',
                            'operator' => '!=',
                            'value'    => 'solid',
                        ],
                    ],
                ],
            ],
        ];

        $this->add_control('gradient_color_start', [
            'label'      => __('Gradient start', 'elementrify'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#d65db1',
            'conditions' => $gradientControlsConditions,
            'selectors'  => [
                '{{WRAPPER}} .elementrify-dual-heading__highlight' => '--elementrify-dual-gradient-start: {{VALUE}};',
                '{{WRAPPER}} .elementrify-dual-heading__highlight-inner' => '--elementrify-dual-gradient-start: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_color_end', [
            'label'      => __('Gradient end', 'elementrify'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#845ef7',
            'conditions' => $gradientControlsConditions,
            'selectors'  => [
                '{{WRAPPER}} .elementrify-dual-heading__highlight' => '--elementrify-dual-gradient-end: {{VALUE}};',
                '{{WRAPPER}} .elementrify-dual-heading__highlight-inner' => '--elementrify-dual-gradient-end: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_angle', [
            'label'      => __('Gradient angle', 'elementrify'),
            'type'       => Controls_Manager::NUMBER,
            'min'        => 0,
            'max'        => 360,
            'step'       => 1,
            'default'    => 90,
            'conditions' => $gradientControlsConditions,
            'selectors'  => [
                '{{WRAPPER}}.elementrify-dual-heading--hl-box .elementrify-dual-heading__highlight' => 'background-color: transparent; background-image: linear-gradient({{VALUE}}deg, var(--elementrify-dual-gradient-start, #d65db1), var(--elementrify-dual-gradient-end, #845ef7));',
                '{{WRAPPER}}.elementrify-dual-heading--hl-text .elementrify-dual-heading__highlight-inner' => 'background-color: transparent; background-image: linear-gradient({{VALUE}}deg, var(--elementrify-dual-gradient-start, #d65db1), var(--elementrify-dual-gradient-end, #845ef7));',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/dual-heading/dual-heading.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
