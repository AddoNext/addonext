<?php
namespace Elementrify\Widgets\PieChart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class PieChart extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('elementrify-pie-chart');
        wp_enqueue_script('elementrify-pie-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-chart-pie elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-pie-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Pie Chart', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Data distribution chart', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    
    public function get_required_plan(): string
    {
        return 'pro';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-pie-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'elementrify-pie-chart'];
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

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
                'default' => __('Device Usage Distribution', 'elementrify'),
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
                'label'   => __('Value (%)', 'elementrify'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'max'     => 100,
            ]
        );
        $repeater->add_control(
            'color',
            [
                'label'   => __('Color', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#2563eb',
            ]
        );

        $default_data = [
            ['label' => 'Desktop', 'value' => 45, 'color' => '#4285F4'],
            ['label' => 'Mobile', 'value' => 35, 'color' => '#61BD8F'],
            ['label' => 'Tablet', 'value' => 15, 'color' => '#F4A000'],
            ['label' => 'Other', 'value' => 5, 'color' => '#EA4335'],
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
                'selector' => '{{WRAPPER}} .elementrify-pie-chart__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-pie-chart__title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementrify-pie-chart__title' => 'text-align: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .elementrify-pie-chart__legend-item',
            ]
        );

        $this->add_control(
            'legend_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-pie-chart__legend-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = ELEMENTRIFY_DIR . 'templates/pie-chart/pie-chart.php';
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
        var colors = [];
        _.each(data, function(row) {
            labels.push(row.label || '');
            values.push(row.value !== undefined ? row.value : 0);
            colors.push(row.color || '#2563eb');
        });
        #>
        <div class="elementrify-pie-chart" data-labels="{{ labels.join('|') }}" data-values="{{ values.join('|') }}" data-colors="{{ colors.join('|') }}">
            <# if (title) { #><h3 class="elementrify-pie-chart__title">{{{ title }}}</h3><# } #>
            <div class="elementrify-pie-chart__wrap">
                <div class="elementrify-pie-chart__canvas" role="img" aria-label="{{ title }}"></div>
                <div class="elementrify-pie-chart__legend"></div>
            </div>
        </div>
        <?php
                }
        }
}
}
