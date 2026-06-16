<?php
namespace Veloria\Widgets\CircularProgress;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class CircularProgress extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-circle-o veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-circular-progress';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Circular Progress', 'veloria');
    }

    public function get_description(): string
    {
        return __('Circular progress indicators with title and subtitle', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['veloria-circular-progress'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style('veloria-circular-progress', VELORIA_URL . 'assets/css/circular-progress.css', [], VELORIA_VERSION);
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

        $repeater = new Repeater();
        $repeater->add_control(
            'percent',
            [
                'label'   => __('Percentage', 'veloria'),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'   => ['%' => ['min' => 0, 'max' => 100]],
                'default' => ['unit' => '%', 'size' => 60],
            ]
        );
        $repeater->add_control(
            'bar_color',
            [
                'label'   => __('Progress color', 'veloria'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#4285F4',
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Design Phase', 'veloria'),
            ]
        );
        $repeater->add_control(
            'subtitle',
            [
                'label'   => __('Subtitle', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('UI/UX Design Complete', 'veloria'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['percent' => ['size' => 60], 'bar_color' => '#4285F4', 'title' => __('Design Phase', 'veloria'), 'subtitle' => __('UI/UX Design Complete', 'veloria')],
                    ['percent' => ['size' => 70], 'bar_color' => '#34A853', 'title' => __('Development', 'veloria'), 'subtitle' => __('Frontend Implementation', 'veloria')],
                    ['percent' => ['size' => 30], 'bar_color' => '#FBBC04', 'title' => __('Testing', 'veloria'), 'subtitle' => __('Quality Assurance', 'veloria')],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_circle',
            [
                'label' => __('Circle', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'circle_size',
            [
                'label'      => __('Size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 80, 'max' => 280]],
                'default'    => ['size' => 130],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-circular-progress__svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'stroke_width',
            [
                'label'      => __('Stroke width', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 2, 'max' => 24]],
                'default'    => ['size' => 3],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-circular-progress__bg' => 'stroke-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-circular-progress__fill' => 'stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'track_color',
            [
                'label'     => __('Track color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E0E0E0',
                'selectors' => [
                    '{{WRAPPER}} .veloria-circular-progress__bg' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_value',
            [
                'label' => __('Percentage value', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'value_typography',
                'selector' => '{{WRAPPER}} .veloria-circular-progress__value',
                'default'  => ['font_size' => ['unit' => 'px', 'size' => 18]],
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .veloria-circular-progress__value' => 'color: {{VALUE}};',
                ],
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
                'selector' => '{{WRAPPER}} .veloria-circular-progress__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .veloria-circular-progress__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => __('Subtitle', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .veloria-circular-progress__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .veloria-circular-progress__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __('Layout', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'items_gap',
            [
                'label'      => __('Gap between items', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 80]],
                'default'    => ['size' => 32],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-circular-progress__list' => 'gap: {{SIZE}}{{UNIT}};',
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

        $path = VELORIA_DIR . 'pro/templates/circular-progress/circular-progress.php';
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
        var items = settings.items || [];
        var radius = 16;
        var circumference = 2 * Math.PI * radius;
        #>
        <div class="veloria-circular-progress">
            <div class="veloria-circular-progress__list">
                <# _.each(items, function(item) {
                    var percent = (item.percent && item.percent.size != null) ? Math.max(0, Math.min(100, parseInt(item.percent.size, 10))) : 0;
                    var barColor = (item.bar_color) ? item.bar_color : '#4285F4';
                    var dash = (percent / 100) * circumference;
                #>
                    <div class="veloria-circular-progress__item">
                        <div class="veloria-circular-progress__circle-wrap">
                            <svg class="veloria-circular-progress__svg" viewBox="0 0 36 36">
                                <circle class="veloria-circular-progress__bg" cx="18" cy="18" r="{{ radius }}" fill="none"/>
                                <circle class="veloria-circular-progress__fill" cx="18" cy="18" r="{{ radius }}" fill="none" stroke-dasharray="{{ dash }}, {{ circumference }}" transform="rotate(-90 18 18)" style="stroke: {{ barColor }};"/>
                            </svg>
                            <span class="veloria-circular-progress__value">{{ percent }}%</span>
                        </div>
                        <# if (item.title) { #><span class="veloria-circular-progress__title">{{{ item.title }}}</span><# } #>
                        <# if (item.subtitle) { #><span class="veloria-circular-progress__subtitle">{{{ item.subtitle }}}</span><# } #>
                    </div>
                <# }); #>
            </div>
        </div>
        <?php
                }
        }
}
}
