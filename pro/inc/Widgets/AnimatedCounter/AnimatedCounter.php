<?php
namespace Addonext\Widgets\AnimatedCounter;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class AnimatedCounter extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_script_in_preview']);
    }

    public function enqueue_script_in_preview(): void
    {
        wp_enqueue_script('addonext-animated-counter');
    }

    public function get_icon(): string
    {
        return 'eicon-counter addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-animated-counter';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Animated Counter', 'addonext');
    }

    public function get_description(): string
    {
        return __('Count up number animations in a grid', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#059669';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['addonext-animated-counter'];
    }

    public function get_script_depends(): array
    {
        return ['addonext-animated-counter'];
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'number',
            [
                'label'   => __('Number', 'addonext'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 100,
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'prefix',
            [
                'label'   => __('Prefix', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'suffix',
            [
                'label'   => __('Suffix', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Counter Title', 'addonext'),
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'subtitle',
            [
                'label'   => __('Subtitle / Description', 'addonext'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Short description for this counter.', 'addonext'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['number' => 250, 'prefix' => '', 'suffix' => '+', 'title' => __('Projects', 'addonext'), 'subtitle' => ''],
                    ['number' => 50, 'prefix' => '', 'suffix' => 'K+', 'title' => __('Users', 'addonext'), 'subtitle' => ''],
                    ['number' => 99, 'prefix' => '', 'suffix' => '%', 'title' => __('Satisfaction', 'addonext'), 'subtitle' => ''],
                    ['number' => 24, 'prefix' => '', 'suffix' => '/7', 'title' => __('Support', 'addonext'), 'subtitle' => ''],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => __('Columns', 'addonext'),
                'type'           => Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-animated-counter__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'rows',
            [
                'label'          => __('Rows', 'addonext'),
                'type'           => Controls_Manager::SELECT,
                'default'        => '1',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-animated-counter__grid' => 'grid-template-rows: repeat({{VALUE}}, auto);',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => __('Gap', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => ['px' => ['min' => 0, 'max' => 80]],
                'default'    => ['size' => 24, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-animated-counter__grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_container',
            [
                'label' => __('Container', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'container_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .addonext-animated-counter__inner',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#3466e6'],
                    'color_b'    => ['default' => '#6c3ccc'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-animated-counter__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => [
                    'top' => '32', 'right' => '24', 'bottom' => '32', 'left' => '24',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-animated-counter__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_number',
            [
                'label' => __('Number', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-animated-counter__number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'number_typography',
                'selector' => '{{WRAPPER}} .addonext-animated-counter__number',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 42, 'unit' => 'px']],
                    'font_weight' => ['default' => '700'],
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

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-animated-counter__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .addonext-animated-counter__title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => __('Subtitle', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-animated-counter__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .addonext-animated-counter__subtitle',
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

        $path = ADDONEXT_DIR . 'pro/templates/animated-counter/animated-counter.php';
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
        var columns = settings.columns ? Math.max(1, parseInt(settings.columns, 10)) : 4;
        var gridStyle = 'display:grid;grid-template-columns:repeat(' + columns + ',1fr);gap:24px;width:100%;';
        var itemStyle = 'display:flex;flex-direction:column;align-items:center;text-align:center;';
        view.addRenderAttribute('inner', 'class', 'addonext-animated-counter__inner');
        view.addRenderAttribute('grid', 'class', 'addonext-animated-counter__grid');
        view.addRenderAttribute('grid', 'style', gridStyle);
        #>
        <div class="addonext-animated-counter">
            <div {{{ view.getRenderAttributeString('inner') }}}>
                <div {{{ view.getRenderAttributeString('grid') }}}>
                <# _.each(settings.items, function(item) { #>
                    <div class="addonext-animated-counter__item elementor-repeater-item-{{ item._id }}" style="display:flex;flex-direction:column;align-items:center;text-align:center;">
                        <span class="addonext-animated-counter__number" style="display:block;" data-to="{{ item.number || 0 }}" data-prefix="{{ item.prefix || '' }}" data-suffix="{{ item.suffix || '' }}">{{{ item.prefix || '' }}}0{{{ item.suffix || '' }}}</span>
                        <# if (item.title) { #><span class="addonext-animated-counter__title" style="display:block;width:100%;">{{{ item.title }}}</span><# } #>
                        <# if (item.subtitle) { #><p class="addonext-animated-counter__subtitle" style="display:block;width:100%;margin:0;">{{{ item.subtitle }}}</p><# } #>
                    </div>
                <# }); #>
                </div>
            </div>
        </div>
        <?php
                }
        }
}
}
