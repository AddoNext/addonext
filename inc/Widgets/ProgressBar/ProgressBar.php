<?php
namespace Veloria\Widgets\ProgressBar;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ProgressBar extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-bar-chart veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-progress-bar';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Progress Bar', 'veloria');
    }

    public function get_description(): string
    {
        return __('Animated progress indicators', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['veloria-progress-bar'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style('veloria-progress-bar', VELORIA_URL . 'assets/css/progress-bar.css', [], VELORIA_VERSION);
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
            'title',
            [
                'label'   => __('Label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Web Design', 'veloria'),
            ]
        );
        $repeater->add_control(
            'percent',
            [
                'label'   => __('Percentage', 'veloria'),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'   => ['%' => ['min' => 0, 'max' => 100]],
                'default' => ['unit' => '%', 'size' => 70],
            ]
        );
        $repeater->add_control(
            'bar_color',
            [
                'label'   => __('Bar color', 'veloria'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#4285F4',
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Bars', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['title' => __('Web Design', 'veloria'), 'percent' => ['size' => 90], 'bar_color' => '#4285F4'],
                    ['title' => __('Development', 'veloria'), 'percent' => ['size' => 85], 'bar_color' => '#8E24AA'],
                    ['title' => __('Marketing', 'veloria'), 'percent' => ['size' => 75], 'bar_color' => '#4CAF50'],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_bars',
            [
                'label' => __('Bars', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bar_height',
            [
                'label'      => __('Bar height', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 4, 'max' => 24]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-progress-bar__track' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'track_color',
            [
                'label'     => __('Track (unfilled) color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E0E0E0',
                'selectors' => [
                    '{{WRAPPER}} .veloria-progress-bar__track' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label'      => __('Gap between rows', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'default'    => ['size' => 16],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-progress-bar__list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_label',
            [
                'label' => __('Label', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .veloria-progress-bar__title',
                'default'  => ['font_size' => ['unit' => 'px', 'size' => 14]],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .veloria-progress-bar__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_percent',
            [
                'label' => __('Percentage', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'percent_typography',
                'selector' => '{{WRAPPER}} .veloria-progress-bar__percent',
                'default'  => ['font_size' => ['unit' => 'px', 'size' => 14]],
            ]
        );

        $this->add_control(
            'percent_color_override',
            [
                'label'        => __('Use bar color for percentage', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'percent_color',
            [
                'label'     => __('Color (when not bar color)', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .veloria-progress-bar__percent' => 'color: {{VALUE}};',
                ],
                'condition' => ['percent_color_override' => ''],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/progress-bar/progress-bar.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var items = settings.items || [];
        var percentUseBarColor = settings.percent_color_override === 'yes';
        #>
        <div class="veloria-progress-bar">
            <div class="veloria-progress-bar__list">
                <# _.each(items, function(item) {
                    var percentVal = (item.percent && item.percent.size != null) ? item.percent.size : (typeof item.percent === 'number' ? item.percent : 0);
                    var percent = Math.max(0, Math.min(100, parseInt(percentVal, 10) || 0));
                    var barColor = (item.bar_color) ? item.bar_color : '#4285F4';
                    var percentStyle = percentUseBarColor ? ('color: ' + barColor + ';') : '';
                    var fillStyle = 'width: ' + percent + '% !important; background-color: ' + barColor + ';';
                #>
                    <div class="veloria-progress-bar__row">
                        <div class="veloria-progress-bar__header">
                            <span class="veloria-progress-bar__title">{{{ item.title }}}</span>
                            <span class="veloria-progress-bar__percent" style="{{ percentStyle }}">{{ percent }}%</span>
                        </div>
                        <div class="veloria-progress-bar__track">
                            <div class="veloria-progress-bar__fill" style="{{ fillStyle }}"></div>
                        </div>
                    </div>
                <# }); #>
            </div>
        </div>
        <?php
    }
}
