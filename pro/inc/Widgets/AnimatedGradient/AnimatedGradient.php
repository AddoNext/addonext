<?php
namespace Veloria\Widgets\AnimatedGradient;

use Veloria\Base\WidgetBase;
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
        return 'eicon-paint-tool veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-animated-gradient';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Animated Gradient', 'veloria');
    }

    public function get_description(): string
    {
        return __('Moving gradient as background or gradient text.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#ec4899';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_keywords(): array
    {
        return ['veloria', 'gradient', 'animated', 'text', 'background', 'color'];
    }

    public function get_style_depends(): array
    {
        return ['veloria-animated-gradient'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-animated-gradient',
            VELORIA_URL . 'assets/css/animated-gradient.css',
            [],
            VELORIA_VERSION
        );
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('text', [
            'label'   => __('Text', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Gradient in motion', 'veloria'),
            'rows'    => 3,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('html_tag', [
            'label'   => __('HTML tag', 'veloria'),
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
            'label' => __('Gradient', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gradient_mode', [
            'label'        => __('Apply gradient to', 'veloria'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'bg',
            'options'      => [
                'bg'   => __('Background', 'veloria'),
                'text' => __('Text', 'veloria'),
            ],
            'prefix_class' => 'veloria-animated-gradient--',
        ]);

        $this->add_control('text_mode_inner_clear', [
            'type'      => Controls_Manager::HIDDEN,
            'default'   => '1',
            'condition' => [
                'gradient_mode' => 'text',
            ],
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-gradient__inner' => 'background-image: none !important; background-color: transparent !important; background-size: auto !important; animation: none !important;',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_text',
                'label'    => __('Typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-animated-gradient__text',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_control('text_color_on_bg', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}}.veloria-animated-gradient--bg .veloria-animated-gradient__text' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'gradient_mode' => 'bg',
            ],
        ]);

        $this->add_control('gradient_color_start', [
            'label'     => __('Gradient start', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d65db1',
            'selectors' => [
                '{{WRAPPER}}' => '--veloria-ag-start: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_color_end', [
            'label'     => __('Gradient end', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#845ef7',
            'selectors' => [
                '{{WRAPPER}}' => '--veloria-ag-end: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_angle', [
            'label'     => __('Gradient angle', 'veloria'),
            'type'      => Controls_Manager::NUMBER,
            'min'       => 0,
            'max'       => 360,
            'step'      => 1,
            'default'   => 90,
            'selectors' => [
                '{{WRAPPER}}.veloria-animated-gradient--bg .veloria-animated-gradient__inner' => 'background-image: linear-gradient({{VALUE}}deg, var(--veloria-ag-start, #d65db1), var(--veloria-ag-end, #845ef7), var(--veloria-ag-start, #d65db1));',
                '{{WRAPPER}}.veloria-animated-gradient--text .veloria-animated-gradient__text' => 'background-image: linear-gradient({{VALUE}}deg, var(--veloria-ag-start, #d65db1), var(--veloria-ag-end, #845ef7), var(--veloria-ag-start, #d65db1));',
            ],
        ]);

        $this->add_responsive_control('gradient_scale', [
            'label'      => __('Gradient stretch', 'veloria'),
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
                '{{WRAPPER}}.veloria-animated-gradient--bg .veloria-animated-gradient__inner' => 'background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.veloria-animated-gradient--text .veloria-animated-gradient__text' => 'background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('animation_direction', [
            'label'        => __('Motion direction', 'veloria'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'h',
            'options'      => [
                'h' => __('Horizontal', 'veloria'),
                'v' => __('Vertical', 'veloria'),
                'd' => __('Diagonal', 'veloria'),
            ],
            'prefix_class' => 'veloria-animated-gradient--dir-',
        ]);

        $this->add_control('animation_duration', [
            'label'       => __('Animation speed (seconds)', 'veloria'),
            'description' => __('Lower is faster. One loop duration in seconds.', 'veloria'),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 0.5,
            'max'         => 120,
            'step'        => 0.1,
            'default'     => 8,
            'selectors'   => [
                '{{WRAPPER}}' => '--veloria-ag-duration: {{VALUE}}s;',
            ],
        ]);

        $this->add_control('animation_easing', [
            'label'     => __('Easing', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'ease-in-out',
            'options'   => [
                'linear'      => __('Linear', 'veloria'),
                'ease'        => __('Ease', 'veloria'),
                'ease-in-out' => __('Ease in out', 'veloria'),
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--veloria-ag-ease: {{VALUE}};',
            ],
        ]);

        $this->add_control('align', [
            'label'        => __('Alignment', 'veloria'),
            'type'         => Controls_Manager::CHOOSE,
            'options'      => [
                'left'   => [
                    'title' => __('Left', 'veloria'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'veloria'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'  => [
                    'title' => __('Right', 'veloria'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'      => 'left',
            'prefix_class' => 'veloria-animated-gradient--align-',
            'selectors'    => [
                '{{WRAPPER}} .veloria-animated-gradient__inner' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .veloria-animated-gradient__text' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('inner_padding', [
            'label'      => __('Padding', 'veloria'),
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
                '{{WRAPPER}} .veloria-animated-gradient__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('inner_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-gradient__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $path = VELORIA_DIR . 'pro/templates/animated-gradient/animated-gradient.php';
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
        var innerClass = 'veloria-animated-gradient veloria-animated-gradient__inner veloria-animated-gradient--' + mode + ' veloria-animated-gradient--dir-' + dir + ' veloria-animated-gradient--align-' + align;
        #>
        <div class="<# print( innerClass ); #>">
            <{{ tag }} class="veloria-animated-gradient__text">{{{ raw }}}</{{ tag }}>
        </div>
        <?php
                }
        }
}
}
