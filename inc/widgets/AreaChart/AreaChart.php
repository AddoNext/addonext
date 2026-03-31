<?php
namespace Elementrify\Widgets\AreaChart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

/**
 * SVG area chart with gradient fill, grid, and optional smooth curve.
 */
class AreaChart extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('elementrify-area-chart');
        wp_enqueue_script('elementrify-area-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-chart-area elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-area-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Area Chart', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Trend area chart with gradient fill.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#5b9bd5';
    }

    public function get_keywords(): array
    {
        return ['elementrify', 'chart', 'area', 'graph', 'revenue'];
    }

    public function get_style_depends(): array
    {
        return ['elementrify-area-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'elementrify-area-chart'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-area-chart',
            ELEMENTRIFY_URL . 'assets/css/area-chart.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-area-chart',
            ELEMENTRIFY_URL . 'assets/js/area-chart.js',
            ['elementor-frontend'],
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

        $this->add_control('title', [
            'label'   => __('Chart title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Monthly Revenue Growth', 'elementrify'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('label', [
            'label'   => __('Label', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);
        $repeater->add_control('value', [
            'label'   => __('Value', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 0,
        ]);

        $default_data = [
            ['label' => 'Jan', 'value' => 25],
            ['label' => 'Feb', 'value' => 28],
            ['label' => 'Mar', 'value' => 32],
            ['label' => 'Apr', 'value' => 35],
            ['label' => 'May', 'value' => 38],
            ['label' => 'Jun', 'value' => 42],
            ['label' => 'Jul', 'value' => 45],
            ['label' => 'Aug', 'value' => 48],
            ['label' => 'Sep', 'value' => 52],
            ['label' => 'Oct', 'value' => 55],
            ['label' => 'Nov', 'value' => 58],
            ['label' => 'Dec', 'value' => 62],
        ];

        $this->add_control('data', [
            'label'       => __('Data', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => $default_data,
            'title_field' => '{{{ label }}}',
        ]);

        $this->add_control('y_axis_suffix', [
            'label'   => __('Y-axis suffix', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => 'k',
        ]);

        $this->add_control('y_min', [
            'label'       => __('Y-axis min', 'elementrify'),
            'type'        => Controls_Manager::NUMBER,
            'description' => __('Leave empty to auto-calculate from data.', 'elementrify'),
        ]);

        $this->add_control('y_max', [
            'label'       => __('Y-axis max', 'elementrify'),
            'type'        => Controls_Manager::NUMBER,
            'description' => __('Leave empty to auto-calculate from data.', 'elementrify'),
        ]);

        $this->add_control('smooth_line', [
            'label'        => __('Smooth curve', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_chart', [
            'label' => __('Chart', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('chart_width', [
            'label'      => __('Width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range'      => [
                '%'  => ['min' => 10, 'max' => 100],
                'px' => ['min' => 200, 'max' => 1400],
            ],
            'default'    => ['unit' => '%', 'size' => 100],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-area-chart' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('chart_height', [
            'label'      => __('Height', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => [
                'px' => ['min' => 180, 'max' => 800],
            ],
            'default'    => ['unit' => 'px', 'size' => 320],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-area-chart__canvas' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('line_color', [
            'label'     => __('Line color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#5b9bd5',
            'selectors' => [
                '{{WRAPPER}} .elementrify-area-chart__line' => 'stroke: {{VALUE}};',
            ],
        ]);

        $this->add_control('area_opacity_top', [
            'label'   => __('Fill opacity (top)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 1,
            'step'    => 0.05,
            'default' => 0.4,
        ]);

        $this->add_control('area_opacity_bottom', [
            'label'   => __('Fill opacity (bottom)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 1,
            'step'    => 0.05,
            'default' => 0.03,
        ]);

        $this->add_control('grid_color', [
            'label'     => __('Grid color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e2e8f0',
            'selectors' => [
                '{{WRAPPER}} .elementrify-area-chart__grid line' => 'stroke: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .elementrify-area-chart__title',
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#334155',
            'selectors' => [
                '{{WRAPPER}} .elementrify-area-chart__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_align', [
            'label'   => __('Alignment', 'elementrify'),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'left'   => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-text-align-center'],
                'right'  => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .elementrify-area-chart__title' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_axes', [
            'label' => __('Axis labels', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'axis_typography',
                'selector' => '{{WRAPPER}} .elementrify-area-chart__axis-label',
            ]
        );

        $this->add_control('axis_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#94a3b8',
            'selectors' => [
                '{{WRAPPER}} .elementrify-area-chart__axis-label' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/area-chart/area-chart.php';
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
        _.each(data, function(row) {
            labels.push(row.label || '');
            values.push(row.value !== undefined ? row.value : 0);
        });
        var ySuffix = settings.y_axis_suffix || 'k';
        var yMin = settings.y_min !== '' && settings.y_min !== undefined ? parseFloat(settings.y_min) : '';
        var yMax = settings.y_max !== '' && settings.y_max !== undefined ? parseFloat(settings.y_max) : '';
        var smooth = settings.smooth_line !== 'no' ? 'yes' : 'no';
        var lineColor = settings.line_color || '#5b9bd5';
        var opTop = settings.area_opacity_top !== undefined && settings.area_opacity_top !== '' ? parseFloat(settings.area_opacity_top) : 0.4;
        var opBot = settings.area_opacity_bottom !== undefined && settings.area_opacity_bottom !== '' ? parseFloat(settings.area_opacity_bottom) : 0.03;
        #>
        <div class="elementrify-area-chart"
            data-labels="{{ labels.join('|') }}"
            data-values="{{ values.join('|') }}"
            data-y-suffix="{{ ySuffix }}"
            data-y-min="{{ yMin }}"
            data-y-max="{{ yMax }}"
            data-smooth="{{ smooth }}"
            data-line-color="{{ lineColor }}"
            data-area-opacity-top="{{ opTop }}"
            data-area-opacity-bottom="{{ opBot }}">
            <# if (title) { #>
                <h3 class="elementrify-area-chart__title">{{{ title }}}</h3>
            <# } #>
            <div class="elementrify-area-chart__canvas" role="img" aria-label="{{ title }}"></div>
        </div>
        <?php
    }
}
