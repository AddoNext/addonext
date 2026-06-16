<?php
namespace Veloria\Widgets\DonutChart;

use Veloria\Base\WidgetBase;
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
        wp_enqueue_style('veloria-donut-chart');
        wp_enqueue_script('veloria-donut-chart');
    }

    public function get_icon(): string
    {
        return 'eicon-circle-o veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-donut-chart';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Donut Chart', 'veloria');
    }

    public function get_description(): string
    {
        return __('Donut chart visualization', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#8b5cf6';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['veloria-donut-chart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'veloria-donut-chart'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-donut-chart',
            VELORIA_URL . 'assets/css/donut-chart.css',
            [],
            VELORIA_VERSION
        );

        wp_register_script(
            'veloria-donut-chart',
            VELORIA_URL . 'assets/js/donut-chart.js',
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
                'default' => __('Department Budget Distribution', 'veloria'),
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
                'label'   => __('Value', 'veloria'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
            ]
        );
        $repeater->add_control(
            'color',
            [
                'label'   => __('Color', 'veloria'),
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
                'label'       => __('Data', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $default_data,
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->add_control(
            'center_prefix',
            [
                'label'   => __('Center value prefix', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '$',
            ]
        );
        $this->add_control(
            'center_suffix',
            [
                'label'   => __('Center value suffix', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'K',
            ]
        );
        $this->add_control(
            'center_label',
            [
                'label'   => __('Center label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Total Budget', 'veloria'),
            ]
        );
        $this->add_control(
            'show_slice_labels',
            [
                'label'        => __('Show slice labels', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'show_legend',
            [
                'label'        => __('Show legend', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_chart',
            [
                'label' => __('Chart', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'chart_size',
            [
                'label'      => __('Chart size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 180, 'max' => 520]],
                'default'    => ['size' => 360, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-donut-chart__canvas' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'inner_cutout',
            [
                'label' => __('Inner cutout (%)', 'veloria'),
                'type'  => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 35, 'max' => 80]],
                'default' => ['size' => 50],
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
                'selector' => '{{WRAPPER}} .veloria-donut-chart__title',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .veloria-donut-chart__title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .veloria-donut-chart__title' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_center',
            [
                'label' => __('Center text', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'center_value_typography',
                'selector' => '{{WRAPPER}} .veloria-donut-chart__center-value',
            ]
        );
        $this->add_control(
            'center_value_color',
            [
                'label'     => __('Value color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .veloria-donut-chart__center-value' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'center_label_typography',
                'selector' => '{{WRAPPER}} .veloria-donut-chart__center-label',
            ]
        );
        $this->add_control(
            'center_label_color',
            [
                'label'     => __('Label color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .veloria-donut-chart__center-label' => 'fill: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .veloria-donut-chart__legend-item',
            ]
        );
        $this->add_control(
            'legend_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .veloria-donut-chart__legend-item' => 'color: {{VALUE}};',
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

        $path = VELORIA_DIR . 'pro/templates/donut-chart/donut-chart.php';
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
            colors.push(row.color || '#4285F4');
        });
        var centerPrefix = settings.center_prefix || '';
        var centerSuffix = settings.center_suffix || '';
        var centerLabel = settings.center_label || 'Total Budget';
        var showLabels = settings.show_slice_labels === 'yes' ? 'yes' : 'no';
        var showLegend = settings.show_legend === 'yes' ? 'yes' : 'no';
        var innerCutout = settings.inner_cutout && settings.inner_cutout.size ? settings.inner_cutout.size : 50;
        #>
        <div class="veloria-donut-chart"
            data-labels="{{ labels.join('|') }}"
            data-values="{{ values.join('|') }}"
            data-colors="{{ colors.join('|') }}"
            data-center-prefix="{{ centerPrefix }}"
            data-center-suffix="{{ centerSuffix }}"
            data-center-label="{{ centerLabel }}"
            data-show-labels="{{ showLabels }}"
            data-show-legend="{{ showLegend }}"
            data-inner-cutout="{{ innerCutout }}">
            <# if (title) { #><h3 class="veloria-donut-chart__title">{{{ title }}}</h3><# } #>
            <div class="veloria-donut-chart__wrap">
                <div class="veloria-donut-chart__canvas" role="img" aria-label="{{ title }}"></div>
                <div class="veloria-donut-chart__legend"></div>
            </div>
        </div>
        <?php
                }
        }
}
}
