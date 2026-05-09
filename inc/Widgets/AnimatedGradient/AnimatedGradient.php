<?php
namespace Elementrify\Widgets\AnimatedGradient;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined('ABSPATH') or exit;

/**
 * Animated linear gradient as background fill or clipped text fill.
 */
class AnimatedGradient extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-paint-tool elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-animated-gradient';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Animated Gradient', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Moving gradient as background or gradient text.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#ec4899';
    }

    
    public function get_required_plan(): string
    {
        return 'pro';
    }

    public function get_keywords(): array
    {
        return ['elementrify', 'gradient', 'animated', 'text', 'background', 'color'];
    }

    public function get_style_depends(): array
    {
        return ['elementrify-animated-gradient'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-animated-gradient',
            ELEMENTRIFY_URL . 'assets/css/animated-gradient.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('text', [
            'label'   => __('Text', 'elementrify'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Gradient in motion', 'elementrify'),
            'rows'    => 3,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('html_tag', [
            'label'   => __('HTML tag', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1'  => 'H1',
                'h2'  => 'H2',
                'h3'  => 'H3',
                'h4'  => 'H4',
                'h5'  => 'H5',
                'h6'  => 'H6',
                'div' => 'div',
                'p'   => 'p',
                'span' => 'span',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', [
            'label' => __('Gradient', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gradient_mode', [
            'label'        => __('Apply gradient to', 'elementrify'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'bg',
            'options'      => [
                'bg'   => __('Background', 'elementrify'),
                'text' => __('Text', 'elementrify'),
            ],
            'prefix_class' => 'elementrify-animated-gradient--',
        ]);

        $this->add_control('text_mode_inner_clear', [
            'type'      => Controls_Manager::HIDDEN,
            'default'   => '1',
            'condition' => [
                'gradient_mode' => 'text',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementrify-animated-gradient__inner' => 'background-image: none !important; background-color: transparent !important; background-size: auto !important; animation: none !important;',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_text',
                'label'    => __('Typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-animated-gradient__text',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_control('text_color_on_bg', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}}.elementrify-animated-gradient--bg .elementrify-animated-gradient__text' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'gradient_mode' => 'bg',
            ],
        ]);

        $this->add_control('gradient_color_start', [
            'label'     => __('Gradient start', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d65db1',
            'selectors' => [
                '{{WRAPPER}}' => '--elementrify-ag-start: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_color_end', [
            'label'     => __('Gradient end', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#845ef7',
            'selectors' => [
                '{{WRAPPER}}' => '--elementrify-ag-end: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_angle', [
            'label'     => __('Gradient angle', 'elementrify'),
            'type'      => Controls_Manager::NUMBER,
            'min'       => 0,
            'max'       => 360,
            'step'      => 1,
            'default'   => 90,
            'selectors' => [
                '{{WRAPPER}}.elementrify-animated-gradient--bg .elementrify-animated-gradient__inner' => 'background-image: linear-gradient({{VALUE}}deg, var(--elementrify-ag-start, #d65db1), var(--elementrify-ag-end, #845ef7), var(--elementrify-ag-start, #d65db1));',
                '{{WRAPPER}}.elementrify-animated-gradient--text .elementrify-animated-gradient__text' => 'background-image: linear-gradient({{VALUE}}deg, var(--elementrify-ag-start, #d65db1), var(--elementrify-ag-end, #845ef7), var(--elementrify-ag-start, #d65db1));',
            ],
        ]);

        $this->add_responsive_control('gradient_scale', [
            'label'      => __('Gradient stretch', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range'      => [
                '%' => ['min' => 150, 'max' => 500, 'step' => 1],
            ],
            'default'    => [
                'unit' => '%',
                'size' => 300,
            ],
            'selectors'  => [
                '{{WRAPPER}}.elementrify-animated-gradient--bg .elementrify-animated-gradient__inner' => 'background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.elementrify-animated-gradient--text .elementrify-animated-gradient__text' => 'background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('animation_direction', [
            'label'        => __('Motion direction', 'elementrify'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'h',
            'options'      => [
                'h' => __('Horizontal', 'elementrify'),
                'v' => __('Vertical', 'elementrify'),
                'd' => __('Diagonal', 'elementrify'),
            ],
            'prefix_class' => 'elementrify-animated-gradient--dir-',
        ]);

        $this->add_control('animation_duration', [
            'label'       => __('Animation speed (seconds)', 'elementrify'),
            'description' => __('Lower is faster. One loop duration in seconds.', 'elementrify'),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 0.5,
            'max'         => 120,
            'step'        => 0.1,
            'default'     => 8,
            'selectors'   => [
                '{{WRAPPER}}' => '--elementrify-ag-duration: {{VALUE}}s;',
            ],
        ]);

        $this->add_control('animation_easing', [
            'label'     => __('Easing', 'elementrify'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'ease-in-out',
            'options'   => [
                'linear'      => __('Linear', 'elementrify'),
                'ease'        => __('Ease', 'elementrify'),
                'ease-in-out' => __('Ease in out', 'elementrify'),
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--elementrify-ag-ease: {{VALUE}};',
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
            'prefix_class' => 'elementrify-animated-gradient--align-',
            'selectors'    => [
                '{{WRAPPER}} .elementrify-animated-gradient__inner' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .elementrify-animated-gradient__text' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('inner_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top' => '1',
                'right' => '1',
                'bottom' => '1',
                'left' => '1',
                'unit' => 'em',
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-animated-gradient__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('inner_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-animated-gradient__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = ELEMENTRIFY_DIR . 'templates/animated-gradient/animated-gradient.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    protected function content_template(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        ?>
        <#
        var raw = settings.text || settings.content || '';
        var tag = settings.html_tag || 'h2';
        var allowed = ['h1','h2','h3','h4','h5','h6','div','p','span'];
        if (allowed.indexOf(tag) === -1) { tag = 'h2'; }
        var mode = settings.gradient_mode === 'text' ? 'text' : 'bg';
        var dir = settings.animation_direction || 'h';
        if (['h','v','d'].indexOf(dir) === -1) { dir = 'h'; }
        var align = settings.align || 'left';
        if (['left','center','right'].indexOf(align) === -1) { align = 'left'; }
        var innerClass = 'elementrify-animated-gradient elementrify-animated-gradient__inner elementrify-animated-gradient--' + mode + ' elementrify-animated-gradient--dir-' + dir + ' elementrify-animated-gradient--align-' + align;
        #>
        <div class="<# print( innerClass ); #>">
            <{{ tag }} class="elementrify-animated-gradient__text">{{{ raw }}}</{{ tag }}>
        </div>
        <?php
                }
        }
}
}
