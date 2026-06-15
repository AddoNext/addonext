<?php
namespace Veloria\Widgets\Timeline;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Timeline extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-time-line veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-timeline';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Timeline', 'veloria');
    }

    public function get_description(): string
    {
        return __('Vertical timeline layouts', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function get_style_depends(): array
    {
        return ['veloria-timeline'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'year',
            [
                'label'   => __('Year', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '2024',
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Event title', 'veloria'),
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'subtitle',
            [
                'label'   => __('Subtitle', 'veloria'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => '',
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'dot_color',
            [
                'label'   => __('Marker color', 'veloria'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#4A90E2',
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'year'      => '2024',
                        'title'     => __('Version 3.0 Released', 'veloria'),
                        'subtitle'  => __('Major update with 50+ new widgets', 'veloria'),
                        'dot_color' => '#4A90E2',
                    ],
                    [
                        'year'      => '2023',
                        'title'     => __('100K+ Users Milestone', 'veloria'),
                        'subtitle'  => __('Reached 100,000 active users', 'veloria'),
                        'dot_color' => '#9B59B6',
                    ],
                    [
                        'year'      => '2022',
                        'title'     => __('AddoNext Launch', 'veloria'),
                        'subtitle'  => __('First version released to public', 'veloria'),
                        'dot_color' => '#4CAF50',
                    ],
                ],
                'title_field' => '{{{ year }}} - {{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_year',
            [
                'label' => __('Year', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'year_typography',
                'selector'       => '{{WRAPPER}} .veloria-timeline__year',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 14, 'unit' => 'px']],
                    'font_weight' => ['default' => '500'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3C3C3C',
                'selectors' => [
                    '{{WRAPPER}} .veloria-timeline__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .veloria-timeline__title',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 16, 'unit' => 'px']],
                    'font_weight' => ['default' => '700'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => __('Subtitle', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7D7D7D',
                'selectors' => [
                    '{{WRAPPER}} .veloria-timeline__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'subtitle_typography',
                'selector'       => '{{WRAPPER}} .veloria-timeline__subtitle',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 14, 'unit' => 'px']],
                    'font_weight' => ['default' => '400'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_marker',
            [
                'label' => __('Marker', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'marker_size',
            [
                'label'      => __('Dot size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 6, 'max' => 32, 'step' => 1],
                ],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-timeline__dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_control(
            'line_width',
            [
                'label'      => __('Line width', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 1, 'max' => 6, 'step' => 1],
                ],
                'default'    => ['size' => 2],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-timeline__line' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 0.5);',
                ],
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label'      => __('Item spacing', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 80, 'step' => 4],
                ],
                'default'    => ['size' => 24],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-timeline__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-timeline__item:last-child' => 'margin-bottom: 0;',
                ],
            ]
        );

        $this->add_control(
            'content_gap',
            [
                'label'      => __('Gap (line to content)', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 48, 'step' => 2],
                ],
                'default'    => ['size' => 20],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-timeline__track' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/timeline/timeline.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <div class="veloria-timeline">
            <# _.each(settings.items, function(item, index) { #>
                <div class="veloria-timeline__item elementor-repeater-item-{{ item._id }}">
                    <div class="veloria-timeline__track" style="--timeline-dot: {{ item.dot_color || '#4A90E2' }};">
                        <span class="veloria-timeline__dot"></span>
                        <# if (index < settings.items.length - 1) { #>
                            <span class="veloria-timeline__line"></span>
                        <# } #>
                    </div>
                    <div class="veloria-timeline__content">
                        <# if (item.year) { #>
                            <span class="veloria-timeline__year" style="color: {{ item.dot_color || '#4A90E2' }};">{{{ item.year }}}</span>
                        <# } #>
                        <# if (item.title) { #>
                            <h4 class="veloria-timeline__title">{{{ item.title }}}</h4>
                        <# } #>
                        <# if (item.subtitle) { #>
                            <p class="veloria-timeline__subtitle">{{{ item.subtitle }}}</p>
                        <# } #>
                    </div>
                </div>
            <# }); #>
        </div>
        <?php
    }
}
