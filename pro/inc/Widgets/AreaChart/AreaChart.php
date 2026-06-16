<?php
namespace Veloria\Widgets\AreaChart;

use Veloria\Base\WidgetBase;
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
        wp_enqueue_style('veloria-area-chart');
        wp_enqueue_script('veloria-area-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-chart-area veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-area-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Area Chart', 'veloria');
    }

    public function get_description(): string
    {
        return __('Trend area chart with gradient fill.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#5b9bd5';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_keywords(): array
    {
        return ['veloria', 'chart', 'area', 'graph', 'revenue'];
    }

    public function get_style_depends(): array
    {
        return ['veloria-area-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'veloria-area-chart'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-area-chart',
            VELORIA_URL . 'assets/css/area-chart.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-area-chart',
            VELORIA_URL . 'assets/js/area-chart.js',
            ['elementor-frontend'],
            VELORIA_VERSION,
            true
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

        $this->add_control('title', [
            'label'   => __('Chart title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Monthly Revenue Growth', 'veloria'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('label', [
            'label'   => __('Label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);
        $repeater->add_control('value', [
            'label'   => __('Value', 'veloria'),
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
            'label'       => __('Data', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => $default_data,
            'title_field' => '{{{ label }}}',
        ]);

        $this->add_control('y_axis_suffix', [
            'label'   => __('Y-axis suffix', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => 'k',
        ]);

        $this->add_control('y_min', [
            'label'       => __('Y-axis min', 'veloria'),
            'type'        => Controls_Manager::NUMBER,
            'description' => __('Leave empty to auto-calculate from data.', 'veloria'),
        ]);

        $this->add_control('y_max', [
            'label'       => __('Y-axis max', 'veloria'),
            'type'        => Controls_Manager::NUMBER,
            'description' => __('Leave empty to auto-calculate from data.', 'veloria'),
        ]);

        $this->add_control('smooth_line', [
            'label'        => __('Smooth curve', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_chart', [
            'label' => __('Chart', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('chart_width', [
            'label'      => __('Width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range'      => [
                '%'  => ['min' => 10, 'max' => 100],
                'px' => ['min' => 200, 'max' => 1400],
            ],
            'default'    => ['unit' => '%', 'size' => 100],
            'selectors'  => [
                '{{WRAPPER}} .veloria-area-chart' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('chart_height', [
            'label'      => __('Height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => [
                'px' => ['min' => 180, 'max' => 800],
            ],
            'default'    => ['unit' => 'px', 'size' => 320],
            'selectors'  => [
                '{{WRAPPER}} .veloria-area-chart__canvas' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('line_color', [
            'label'     => __('Line color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#5b9bd5',
            'selectors' => [
                '{{WRAPPER}} .veloria-area-chart__line' => 'stroke: {{VALUE}};',
            ],
        ]);

        $this->add_control('area_opacity_top', [
            'label'   => __('Fill opacity (top)', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 1,
            'step'    => 0.05,
            'default' => 0.4,
        ]);

        $this->add_control('area_opacity_bottom', [
            'label'   => __('Fill opacity (bottom)', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 1,
            'step'    => 0.05,
            'default' => 0.03,
        ]);

        $this->add_control('grid_color', [
            'label'     => __('Grid color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e2e8f0',
            'selectors' => [
                '{{WRAPPER}} .veloria-area-chart__grid line' => 'stroke: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .veloria-area-chart__title',
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#334155',
            'selectors' => [
                '{{WRAPPER}} .veloria-area-chart__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_align', [
            'label'   => __('Alignment', 'veloria'),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'left'   => ['title' => __('Left', 'veloria'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'veloria'), 'icon' => 'eicon-text-align-center'],
                'right'  => ['title' => __('Right', 'veloria'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .veloria-area-chart__title' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_axes', [
            'label' => __('Axis labels', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'axis_typography',
                'selector' => '{{WRAPPER}} .veloria-area-chart__axis-label',
            ]
        );

        $this->add_control('axis_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#94a3b8',
            'selectors' => [
                '{{WRAPPER}} .veloria-area-chart__axis-label' => 'fill: {{VALUE}};',
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

        $path = VELORIA_DIR . 'pro/templates/area-chart/area-chart.php';
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
        <div class="veloria-area-chart"
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
                <h3 class="veloria-area-chart__title">{{{ title }}}</h3>
            <# } #>
            <div class="veloria-area-chart__canvas" role="img" aria-label="{{ title }}"></div>
        </div>
        <?php
                }
        }
}
}
