<?php
namespace Veloria\Widgets\Dropcap;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Dropcap extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-paragraph veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-dropcap';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Dropcap', 'veloria');
    }

    public function get_description(): string
    {
        return __('Large first letter styled as a dropcap.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['veloria-dropcap'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-dropcap',
            VELORIA_URL . 'assets/css/dropcap.css',
            [],
            VELORIA_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('text', [
            'label'   => __('Paragraph', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'veloria'),
            'rows'    => 4,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_paragraph', [
            'label' => __('Paragraph', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('paragraph_font_size', [
            'label'      => __('Font size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 10, 'max' => 72]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .veloria-dropcap' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'paragraph_typography',
                'selector' => '{{WRAPPER}} .veloria-dropcap',
                'exclude'  => ['font_size'],
            ]
        );

        $this->add_control('paragraph_color', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .veloria-dropcap' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_dropcap', [
            'label' => __('Dropcap', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('dropcap_fill', [
            'label'   => __('Fill type', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'solid'    => __('Solid color', 'veloria'),
                'gradient' => __('Gradient', 'veloria'),
            ],
        ]);

        $this->add_control('dropcap_color', [
            'label'     => __('Solid color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'condition' => ['dropcap_fill' => 'solid'],
            'selectors' => [
                '{{WRAPPER}} .veloria-dropcap__letter' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('dropcap_gradient_start', [
            'label'     => __('Gradient start', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'condition' => ['dropcap_fill' => 'gradient'],
            'selectors' => [
                '{{WRAPPER}} .veloria-dropcap__letter' => '--veloria-dropcap-gradient-start: {{VALUE}};',
            ],
        ]);

        $this->add_control('dropcap_gradient_end', [
            'label'     => __('Gradient end', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#7c3aed',
            'condition' => ['dropcap_fill' => 'gradient'],
            'selectors' => [
                '{{WRAPPER}} .veloria-dropcap__letter' => '--veloria-dropcap-gradient-end: {{VALUE}};',
            ],
        ]);

        $this->add_control('dropcap_gradient_angle', [
            'label'      => __('Gradient angle', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range'      => ['deg' => ['min' => 0, 'max' => 360]],
            'default'    => ['unit' => 'deg', 'size' => 90],
            'condition'  => ['dropcap_fill' => 'gradient'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-dropcap__letter' => '--veloria-dropcap-gradient-angle: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('dropcap_size', [
            'label'      => __('Size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 24, 'max' => 160]],
            'default'    => ['unit' => 'px', 'size' => 52],
            'selectors'  => [
                '{{WRAPPER}} .veloria-dropcap__letter' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('dropcap_offset_top', [
            'label'      => __('Top offset', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => -40, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 4],
            'selectors'  => [
                '{{WRAPPER}} .veloria-dropcap__letter' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('dropcap_gap', [
            'label'      => __('Gap to text', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 0, 'max' => 60]],
            'default'    => ['unit' => 'px', 'size' => 10],
            'selectors'  => [
                '{{WRAPPER}} .veloria-dropcap__letter' => 'margin-right: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/dropcap/dropcap.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?>
        <#
        var t = settings.text || '';
        var first = t.charAt(0);
        var rest = t.slice(1);
        var fill = settings.dropcap_fill || 'solid';
        var cls = 'veloria-dropcap';
        if (fill === 'gradient') {
            cls += ' veloria-dropcap--gradient';
        }
        #>
        <p class="{{ cls }}"><span class="veloria-dropcap__letter">{{{ first }}}</span><span class="veloria-dropcap__text">{{{ rest }}}</span></p>
        <?php
    }
}
