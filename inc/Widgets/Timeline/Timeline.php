<?php
namespace Elementrify\Widgets\Timeline;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Timeline extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-time-line elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-timeline';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Timeline', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Vertical timeline layouts', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-timeline'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'year',
            [
                'label'   => __('Year', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '2024',
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Event title', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'subtitle',
            [
                'label'   => __('Subtitle', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => '',
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'dot_color',
            [
                'label'   => __('Marker color', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#4A90E2',
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'year'      => '2024',
                        'title'     => __('Version 3.0 Released', 'elementrify'),
                        'subtitle'  => __('Major update with 50+ new widgets', 'elementrify'),
                        'dot_color' => '#4A90E2',
                    ],
                    [
                        'year'      => '2023',
                        'title'     => __('100K+ Users Milestone', 'elementrify'),
                        'subtitle'  => __('Reached 100,000 active users', 'elementrify'),
                        'dot_color' => '#9B59B6',
                    ],
                    [
                        'year'      => '2022',
                        'title'     => __('AddoNext Launch', 'elementrify'),
                        'subtitle'  => __('First version released to public', 'elementrify'),
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
                'label' => __('Year', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'year_typography',
                'selector'       => '{{WRAPPER}} .elementrify-timeline__year',
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
                'label' => __('Title', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3C3C3C',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-timeline__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .elementrify-timeline__title',
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
                'label' => __('Subtitle', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7D7D7D',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-timeline__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'subtitle_typography',
                'selector'       => '{{WRAPPER}} .elementrify-timeline__subtitle',
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
                'label' => __('Marker', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'marker_size',
            [
                'label'      => __('Dot size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 6, 'max' => 32, 'step' => 1],
                ],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-timeline__dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_control(
            'line_width',
            [
                'label'      => __('Line width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 1, 'max' => 6, 'step' => 1],
                ],
                'default'    => ['size' => 2],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-timeline__line' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 0.5);',
                ],
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label'      => __('Item spacing', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 80, 'step' => 4],
                ],
                'default'    => ['size' => 24],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-timeline__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-timeline__item:last-child' => 'margin-bottom: 0;',
                ],
            ]
        );

        $this->add_control(
            'content_gap',
            [
                'label'      => __('Gap (line to content)', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 48, 'step' => 2],
                ],
                'default'    => ['size' => 20],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-timeline__track' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/timeline/timeline.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <div class="elementrify-timeline">
            <# _.each(settings.items, function(item, index) { #>
                <div class="elementrify-timeline__item elementor-repeater-item-{{ item._id }}">
                    <div class="elementrify-timeline__track" style="--timeline-dot: {{ item.dot_color || '#4A90E2' }};">
                        <span class="elementrify-timeline__dot"></span>
                        <# if (index < settings.items.length - 1) { #>
                            <span class="elementrify-timeline__line"></span>
                        <# } #>
                    </div>
                    <div class="elementrify-timeline__content">
                        <# if (item.year) { #>
                            <span class="elementrify-timeline__year" style="color: {{ item.dot_color || '#4A90E2' }};">{{{ item.year }}}</span>
                        <# } #>
                        <# if (item.title) { #>
                            <h4 class="elementrify-timeline__title">{{{ item.title }}}</h4>
                        <# } #>
                        <# if (item.subtitle) { #>
                            <p class="elementrify-timeline__subtitle">{{{ item.subtitle }}}</p>
                        <# } #>
                    </div>
                </div>
            <# }); #>
        </div>
        <?php
    }
}
