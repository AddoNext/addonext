<?php
namespace Addonext\Widgets\BarChart;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class BarChart extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('addonext-bar-chart');
        wp_enqueue_script('addonext-bar-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-bar-chart addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-bar-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Bar Chart', 'addonext');
    }

    public function get_description(): string
    {
        return __('Interactive bar charts', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['addonext-bar-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'addonext-bar-chart'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __('Chart title', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Quarterly Sales Performance', 'addonext'),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'label',
            [
                'label'   => __('Label', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'value',
            [
                'label'   => __('Value (thousands)', 'addonext'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
            ]
        );
        $repeater->add_control(
            'bar_color',
            [
                'label'   => __('Bar color', 'addonext'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#2563eb',
            ]
        );

        $default_data = [
            ['label' => 'Q1', 'value' => 85, 'bar_color' => '#5285ed'],
            ['label' => 'Q2', 'value' => 90, 'bar_color' => '#52c289'],
            ['label' => 'Q3', 'value' => 78, 'bar_color' => '#f7af4d'],
            ['label' => 'Q4', 'value' => 105, 'bar_color' => '#ed4e4d'],
        ];

        $this->add_control(
            'data',
            [
                'label'       => __('Data', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $default_data,
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->add_control(
            'y_axis_suffix',
            [
                'label'   => __('Y-axis suffix', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'k',
            ]
        );

        $this->add_control(
            'y_min',
            [
                'label'       => __('Y-axis min', 'addonext'),
                'type'        => Controls_Manager::NUMBER,
                'description' => __('Leave empty for 0.', 'addonext'),
            ]
        );

        $this->add_control(
            'y_max',
            [
                'label'       => __('Y-axis max', 'addonext'),
                'type'        => Controls_Manager::NUMBER,
                'description' => __('Leave empty to auto-calculate from data.', 'addonext'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_chart',
            [
                'label' => __('Chart', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'grid_color',
            [
                'label'   => __('Grid color', 'addonext'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .addonext-bar-chart__grid line' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .addonext-bar-chart__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .addonext-bar-chart__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_align',
            [
                'label'   => __('Alignment', 'addonext'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'addonext'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => __('Center', 'addonext'), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => __('Right', 'addonext'), 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .addonext-bar-chart__title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_axes',
            [
                'label' => __('Axes labels', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'axis_typography',
                'selector' => '{{WRAPPER}} .addonext-bar-chart__axis-label',
            ]
        );

        $this->add_control(
            'axis_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .addonext-bar-chart__axis-label' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/bar-chart/bar-chart.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var title = settings.title || '';
        var data = settings.data || [];
        var labels = [];
        var values = [];
        var colors = [];
        _.each(data, function(row) {
            labels.push(row.label || '');
            values.push(row.value !== undefined ? row.value : 0);
            colors.push(row.bar_color || '#2563eb');
        });
        var ySuffix = settings.y_axis_suffix || 'k';
        var yMin = settings.y_min !== '' && settings.y_min !== undefined ? parseFloat(settings.y_min) : '';
        var yMax = settings.y_max !== '' && settings.y_max !== undefined ? parseFloat(settings.y_max) : '';
        #>
        <div class="addonext-bar-chart" data-labels="{{ labels.join('|') }}" data-values="{{ values.join('|') }}" data-colors="{{ colors.join('|') }}" data-y-suffix="{{ ySuffix }}" data-y-min="{{ yMin }}" data-y-max="{{ yMax }}">
            <# if (title) { #><h3 class="addonext-bar-chart__title">{{{ title }}}</h3><# } #>
            <div class="addonext-bar-chart__canvas" role="img" aria-label="{{ title }}"></div>
        </div>
        <?php
    }
}
