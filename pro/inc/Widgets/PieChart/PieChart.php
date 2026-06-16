<?php
namespace Veloria\Widgets\PieChart;

use Veloria\Base\WidgetBase;
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
        wp_enqueue_style('veloria-pie-chart');
        wp_enqueue_script('veloria-pie-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-chart-pie veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-pie-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Pie Chart', 'veloria');
    }

    public function get_description(): string
    {
        return __('Data distribution chart', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['veloria-pie-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'veloria-pie-chart'];
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __('Chart title', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Device Usage Distribution', 'veloria'),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'label',
            [
                'label'   => __('Label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'value',
            [
                'label'   => __('Value (%)', 'veloria'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'max'     => 100,
            ]
        );
        $repeater->add_control(
            'color',
            [
                'label'   => __('Color', 'veloria'),
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
                'label'       => __('Data', 'veloria'),
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
                'label' => __('Title', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .veloria-pie-chart__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .veloria-pie-chart__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_align',
            [
                'label'   => __('Alignment', 'veloria'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'veloria'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => __('Center', 'veloria'), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => __('Right', 'veloria'), 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .veloria-pie-chart__title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_legend',
            [
                'label' => __('Legend', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'legend_typography',
                'selector' => '{{WRAPPER}} .veloria-pie-chart__legend-item',
            ]
        );

        $this->add_control(
            'legend_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .veloria-pie-chart__legend-item' => 'color: {{VALUE}};',
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

        $path = VELORIA_DIR . 'pro/templates/pie-chart/pie-chart.php';
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
        <div class="veloria-pie-chart" data-labels="{{ labels.join('|') }}" data-values="{{ values.join('|') }}" data-colors="{{ colors.join('|') }}">
            <# if (title) { #><h3 class="veloria-pie-chart__title">{{{ title }}}</h3><# } #>
            <div class="veloria-pie-chart__wrap">
                <div class="veloria-pie-chart__canvas" role="img" aria-label="{{ title }}"></div>
                <div class="veloria-pie-chart__legend"></div>
            </div>
        </div>
        <?php
                }
        }
}
}
