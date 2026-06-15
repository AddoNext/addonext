<?php
namespace Veloria\Widgets\RadarChart;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class RadarChart extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('veloria-radar-chart');
        wp_enqueue_script('veloria-radar-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-chart-radar veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-radar-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Radar Chart', 'veloria');
    }

    public function get_description(): string
    {
        return __('Radar/spider chart', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    
    public function get_required_plan(): string
    {
        return 'pro';
    }

    public function get_style_depends(): array
    {
        return ['veloria-radar-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'veloria-radar-chart'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-radar-chart',
            VELORIA_URL . 'assets/css/radar-chart.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-radar-chart',
            VELORIA_URL . 'assets/js/radar-chart.js',
            ['elementor-frontend'],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            ['label' => __('Content', 'veloria'), 'tab' => Controls_Manager::TAB_CONTENT]
        );

        $this->add_control('title', [
            'label'   => __('Chart title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('System Performance Analysis', 'veloria'),
        ]);

        $this->add_control('series_a_name', [
            'label'   => __('Series A name', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Current Metrics', 'veloria'),
        ]);
        $this->add_control('series_b_name', [
            'label'   => __('Series B name', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Target Metrics', 'veloria'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('label', [
            'label'   => __('Category', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);
        $repeater->add_control('value_a', [
            'label'   => __('Series A value', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 0,
            'min'     => 0,
        ]);
        $repeater->add_control('value_b', [
            'label'   => __('Series B value', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 0,
            'min'     => 0,
        ]);

        $default_data = [
            ['label' => 'Performance', 'value_a' => 85, 'value_b' => 78],
            ['label' => 'Quality', 'value_a' => 92, 'value_b' => 80],
            ['label' => 'Speed', 'value_a' => 78, 'value_b' => 85],
            ['label' => 'Reliability', 'value_a' => 88, 'value_b' => 83],
            ['label' => 'Security', 'value_a' => 90, 'value_b' => 87],
            ['label' => 'Usability', 'value_a' => 84, 'value_b' => 80],
        ];

        $this->add_control('data', [
            'label'       => __('Data', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => $default_data,
            'title_field' => '{{{ label }}}',
        ]);

        $this->add_control('max_value', [
            'label'       => __('Max value', 'veloria'),
            'type'        => Controls_Manager::NUMBER,
            'default'     => 100,
            'min'         => 1,
            'description' => __('Used for radar scale labels and outer ring.', 'veloria'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_chart',
            ['label' => __('Chart', 'veloria'), 'tab' => Controls_Manager::TAB_STYLE]
        );

        $this->add_control('series_a_color', [
            'label'   => __('Series A color', 'veloria'),
            'type'    => Controls_Manager::COLOR,
            'default' => '#4285F4',
        ]);
        $this->add_control('series_b_color', [
            'label'   => __('Series B color', 'veloria'),
            'type'    => Controls_Manager::COLOR,
            'default' => '#24B47E',
        ]);
        $this->add_control('grid_color', [
            'label'     => __('Grid color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d1d5db',
            'selectors' => [
                '{{WRAPPER}} .veloria-radar-chart__grid polygon' => 'stroke: {{VALUE}};',
                '{{WRAPPER}} .veloria-radar-chart__axes line' => 'stroke: {{VALUE}};',
            ],
        ]);
        $this->add_responsive_control('chart_height', [
            'label'      => __('Chart height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 260, 'max' => 700]],
            'default'    => ['size' => 360, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-radar-chart__canvas' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            ['label' => __('Title', 'veloria'), 'tab' => Controls_Manager::TAB_STYLE]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            ['name' => 'title_typography', 'selector' => '{{WRAPPER}} .veloria-radar-chart__title']
        );
        $this->add_control('title_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1f2937',
            'selectors' => ['{{WRAPPER}} .veloria-radar-chart__title' => 'color: {{VALUE}};'],
        ]);
        $this->add_control('title_align', [
            'label'   => __('Alignment', 'veloria'),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'veloria'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'veloria'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'veloria'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .veloria-radar-chart__title' => 'text-align: {{VALUE}};'],
        ]);
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_labels',
            ['label' => __('Axis labels', 'veloria'), 'tab' => Controls_Manager::TAB_STYLE]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            ['name' => 'axis_label_typography', 'selector' => '{{WRAPPER}} .veloria-radar-chart__axis-label']
        );
        $this->add_control('axis_label_color', [
            'label'     => __('Axis label color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4b5563',
            'selectors' => ['{{WRAPPER}} .veloria-radar-chart__axis-label' => 'fill: {{VALUE}};'],
        ]);
        $this->add_control('tick_label_color', [
            'label'     => __('Scale label color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6b7280',
            'selectors' => ['{{WRAPPER}} .veloria-radar-chart__tick-label' => 'fill: {{VALUE}};'],
        ]);
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_legend',
            ['label' => __('Legend', 'veloria'), 'tab' => Controls_Manager::TAB_STYLE]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            ['name' => 'legend_typography', 'selector' => '{{WRAPPER}} .veloria-radar-chart__legend-item']
        );
        $this->add_control('legend_color', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4b5563',
            'selectors' => ['{{WRAPPER}} .veloria-radar-chart__legend-item' => 'color: {{VALUE}};'],
        ]);
        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = VELORIA_DIR . 'templates/radar-chart/radar-chart.php';
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
        var title = settings.title || '';
        var rows = settings.data || [];
        var labels = [];
        var valuesA = [];
        var valuesB = [];
        _.each(rows, function(row) {
            labels.push(row.label || '');
            valuesA.push(row.value_a !== undefined ? row.value_a : 0);
            valuesB.push(row.value_b !== undefined ? row.value_b : 0);
        });
        var seriesA = settings.series_a_name || 'Series A';
        var seriesB = settings.series_b_name || 'Series B';
        var colorA = settings.series_a_color || '#4285F4';
        var colorB = settings.series_b_color || '#24B47E';
        var maxValue = settings.max_value !== '' && settings.max_value !== undefined ? settings.max_value : 100;
        #>
        <div class="veloria-radar-chart"
            data-labels="{{ labels.join('|') }}"
            data-values-a="{{ valuesA.join('|') }}"
            data-values-b="{{ valuesB.join('|') }}"
            data-series-a-name="{{ seriesA }}"
            data-series-b-name="{{ seriesB }}"
            data-color-a="{{ colorA }}"
            data-color-b="{{ colorB }}"
            data-max-value="{{ maxValue }}">
            <# if (title) { #><h3 class="veloria-radar-chart__title">{{{ title }}}</h3><# } #>
            <div class="veloria-radar-chart__wrap">
                <div class="veloria-radar-chart__canvas" role="img" aria-label="{{ title }}"></div>
                <div class="veloria-radar-chart__legend"></div>
            </div>
        </div>
        <?php
                }
        }
}
}
