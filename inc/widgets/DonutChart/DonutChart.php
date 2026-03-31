<?php
namespace Elementrify\Widgets\DonutChart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class DonutChart extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('elementrify-donut-chart');
        wp_enqueue_script('elementrify-donut-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-circle-o elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-donut-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Donut Chart', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Donut chart visualization', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#8b5cf6';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-donut-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'elementrify-donut-chart'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-donut-chart',
            ELEMENTRIFY_URL . 'assets/css/donut-chart.css',
            [],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            'elementrify-donut-chart',
            ELEMENTRIFY_URL . 'assets/js/donut-chart.js',
            ['elementor-frontend'],
            ELEMENTRIFY_VERSION,
            true
        );
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

        $this->add_control(
            'title',
            [
                'label'   => __('Chart title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Department Budget Distribution', 'elementrify'),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'label',
            [
                'label'   => __('Label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'value',
            [
                'label'   => __('Value', 'elementrify'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
            ]
        );
        $repeater->add_control(
            'color',
            [
                'label'   => __('Color', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#4285F4',
            ]
        );

        $default_data = [
            ['label' => 'Marketing', 'value' => 30, 'color' => '#4285F4'],
            ['label' => 'Development', 'value' => 25, 'color' => '#24B47E'],
            ['label' => 'Operations', 'value' => 20, 'color' => '#F9A602'],
            ['label' => 'Sales', 'value' => 15, 'color' => '#EF4444'],
            ['label' => 'Support', 'value' => 10, 'color' => '#8B5CF6'],
        ];

        $this->add_control(
            'data',
            [
                'label'       => __('Data', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $default_data,
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->add_control(
            'center_prefix',
            [
                'label'   => __('Center value prefix', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '$',
            ]
        );
        $this->add_control(
            'center_suffix',
            [
                'label'   => __('Center value suffix', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'K',
            ]
        );
        $this->add_control(
            'center_label',
            [
                'label'   => __('Center label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Total Budget', 'elementrify'),
            ]
        );
        $this->add_control(
            'show_slice_labels',
            [
                'label'        => __('Show slice labels', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'show_legend',
            [
                'label'        => __('Show legend', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_chart',
            [
                'label' => __('Chart', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'chart_size',
            [
                'label'      => __('Chart size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 180, 'max' => 520]],
                'default'    => ['size' => 360, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-donut-chart__canvas' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'inner_cutout',
            [
                'label' => __('Inner cutout (%)', 'elementrify'),
                'type'  => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 35, 'max' => 80]],
                'default' => ['size' => 50],
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
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .elementrify-donut-chart__title',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-donut-chart__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_align',
            [
                'label'   => __('Alignment', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-donut-chart__title' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_center',
            [
                'label' => __('Center text', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'center_value_typography',
                'selector' => '{{WRAPPER}} .elementrify-donut-chart__center-value',
            ]
        );
        $this->add_control(
            'center_value_color',
            [
                'label'     => __('Value color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-donut-chart__center-value' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'center_label_typography',
                'selector' => '{{WRAPPER}} .elementrify-donut-chart__center-label',
            ]
        );
        $this->add_control(
            'center_label_color',
            [
                'label'     => __('Label color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-donut-chart__center-label' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_legend',
            [
                'label' => __('Legend', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'legend_typography',
                'selector' => '{{WRAPPER}} .elementrify-donut-chart__legend-item',
            ]
        );
        $this->add_control(
            'legend_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-donut-chart__legend-item' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/donut-chart/donut-chart.php';
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
            colors.push(row.color || '#4285F4');
        });
        var centerPrefix = settings.center_prefix || '';
        var centerSuffix = settings.center_suffix || '';
        var centerLabel = settings.center_label || 'Total Budget';
        var showLabels = settings.show_slice_labels === 'yes' ? 'yes' : 'no';
        var showLegend = settings.show_legend === 'yes' ? 'yes' : 'no';
        var innerCutout = settings.inner_cutout && settings.inner_cutout.size ? settings.inner_cutout.size : 50;
        #>
        <div class="elementrify-donut-chart"
            data-labels="{{ labels.join('|') }}"
            data-values="{{ values.join('|') }}"
            data-colors="{{ colors.join('|') }}"
            data-center-prefix="{{ centerPrefix }}"
            data-center-suffix="{{ centerSuffix }}"
            data-center-label="{{ centerLabel }}"
            data-show-labels="{{ showLabels }}"
            data-show-legend="{{ showLegend }}"
            data-inner-cutout="{{ innerCutout }}">
            <# if (title) { #><h3 class="elementrify-donut-chart__title">{{{ title }}}</h3><# } #>
            <div class="elementrify-donut-chart__wrap">
                <div class="elementrify-donut-chart__canvas" role="img" aria-label="{{ title }}"></div>
                <div class="elementrify-donut-chart__legend"></div>
            </div>
        </div>
        <?php
    }
}
