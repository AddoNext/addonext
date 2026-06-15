<?php
namespace Veloria\Widgets\AdvancedAnalytics;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class AdvancedAnalytics extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_script('veloria-advanced-analytics');
    }

    public function get_icon(): string
    {
        return 'eicon-analytics veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-advanced-analytics';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Advanced Analytics', 'veloria');
    }

    public function get_description(): string
    {
        return __('Analytics KPI cards with trend indicators.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#6366f1';
    }

    public function get_style_depends(): array
    {
        return ['veloria-advanced-analytics'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-advanced-analytics'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-advanced-analytics',
            VELORIA_URL . 'assets/css/advanced-analytics.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-advanced-analytics',
            VELORIA_URL . 'assets/js/advanced-analytics.js',
            [],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label'   => __('Icon', 'veloria'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('icon_bg', [
            'label'   => __('Icon background', 'veloria'),
            'type'    => Controls_Manager::COLOR,
            'default' => '#2563eb',
        ]);
        $repeater->add_control('value', [
            'label'   => __('Value', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '24,583',
        ]);
        $repeater->add_control('label', [
            'label'   => __('Label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Total Visitors', 'veloria'),
        ]);
        $repeater->add_control('trend', [
            'label'   => __('Trend text', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '+12.5%',
        ]);
        $repeater->add_control('trend_type', [
            'label'   => __('Trend type', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'positive',
            'options' => [
                'positive' => __('Positive', 'veloria'),
                'negative' => __('Negative', 'veloria'),
            ],
        ]);
        $repeater->add_control('card_bg', [
            'label'   => __('Card background', 'veloria'),
            'type'    => Controls_Manager::COLOR,
            'default' => '#dbeafe',
        ]);

        $this->add_control('cards', [
            'label'       => __('Cards', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ label }}}',
            'default'     => [
                [
                    'icon'       => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
                    'icon_bg'    => '#2563eb',
                    'value'      => '24,583',
                    'label'      => __('Total Visitors', 'veloria'),
                    'trend'      => '+12.5%',
                    'trend_type' => 'positive',
                    'card_bg'    => '#dbeafe',
                ],
                [
                    'icon'       => ['value' => 'fas fa-shopping-cart', 'library' => 'fa-solid'],
                    'icon_bg'    => '#9333ea',
                    'value'      => '$45,892',
                    'label'      => __('Total Revenue', 'veloria'),
                    'trend'      => '+8.2%',
                    'trend_type' => 'positive',
                    'card_bg'    => '#f3e8ff',
                ],
                [
                    'icon'       => ['value' => 'fas fa-chart-line', 'library' => 'fa-solid'],
                    'icon_bg'    => '#16a34a',
                    'value'      => '3.8%',
                    'label'      => __('Conversion Rate', 'veloria'),
                    'trend'      => '+15.3%',
                    'trend_type' => 'positive',
                    'card_bg'    => '#dcfce7',
                ],
                [
                    'icon'       => ['value' => 'fas fa-clock', 'library' => 'fa-solid'],
                    'icon_bg'    => '#ea580c',
                    'value'      => '4m 32s',
                    'label'      => __('Avg. Session', 'veloria'),
                    'trend'      => '-2.1%',
                    'trend_type' => 'negative',
                    'card_bg'    => '#fef3c7',
                ],
            ],
        ]);

        $this->add_control('enable_counter_animation', [
            'label'        => __('Animate numbers', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => '',
        ]);
        $this->add_control('counter_animation_duration', [
            'label'     => __('Animation duration (ms)', 'veloria'),
            'type'      => Controls_Manager::NUMBER,
            'min'       => 300,
            'max'       => 10000,
            'step'      => 100,
            'default'   => 1400,
            'condition' => [
                'enable_counter_animation' => 'yes',
            ],
        ]);

        $this->add_responsive_control('columns', [
            'label'   => __('Columns', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => '4',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_cards', [
            'label' => __('Cards', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('cards_gap', [
            'label'      => __('Gap', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 60]],
            'default'    => ['size' => 14, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-advanced-analytics' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => [
                'top'      => '16',
                'right'    => '18',
                'bottom'   => '16',
                'left'     => '18',
                'unit'     => 'px',
                'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-advanced-analytics__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('card_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 30]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-advanced-analytics__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_icon', [
            'label' => __('Icon', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('icon_box_size', [
            'label'      => __('Icon box size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 26, 'max' => 80]],
            'default'    => ['size' => 40, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-advanced-analytics__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label'      => __('Icon size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 10, 'max' => 34]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-advanced-analytics__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .veloria-advanced-analytics__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('icon_color', [
            'label'     => __('Icon color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .veloria-advanced-analytics__icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_value', [
            'label' => __('Value', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'value_typography',
                'selector' => '{{WRAPPER}} .veloria-advanced-analytics__value',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => ['default' => ['size' => 28, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('value_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics__value' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_label', [
            'label' => __('Label', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .veloria-advanced-analytics__label',
            ]
        );

        $this->add_control('label_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4b5563',
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_trend', [
            'label' => __('Trend Badge', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'trend_typography',
                'selector' => '{{WRAPPER}} .veloria-advanced-analytics__trend',
            ]
        );

        $this->add_control('trend_positive_color', [
            'label'     => __('Positive text', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#16a34a',
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics__trend--positive' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('trend_positive_bg', [
            'label'     => __('Positive background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#dcfce7',
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics__trend--positive' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('trend_negative_color', [
            'label'     => __('Negative text', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#be123c',
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics__trend--negative' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('trend_negative_bg', [
            'label'     => __('Negative background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffe4e6',
            'selectors' => [
                '{{WRAPPER}} .veloria-advanced-analytics__trend--negative' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/advanced-analytics/advanced-analytics.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var cards = settings.cards || [];
        var animate = settings.enable_counter_animation === 'yes';
        var duration = settings.counter_animation_duration || 1400;
        #>
        <div class="veloria-advanced-analytics" data-aa-animate="{{ animate ? 'yes' : 'no' }}" data-aa-duration="{{ duration }}">
            <# _.each(cards, function(card, index) {
                var cBg = card.card_bg || '#dbeafe';
                var iBg = card.icon_bg || '#2563eb';
                var trendType = card.trend_type === 'negative' ? 'negative' : 'positive';
                var iconData = card.icon || card.selected_icon;
                var iconHtml = iconData && iconData.value ? elementor.helpers.renderIcon(view, iconData, {}, 'i', 'object').value : '';

                var cardAttr = 'aa_card_' + index;
                var valueAttr = 'aa_value_' + index;
                var labelAttr = 'aa_label_' + index;
                var trendAttr = 'aa_trend_' + index;

                view.addRenderAttribute(cardAttr, 'class', 'veloria-advanced-analytics__card');
                view.addRenderAttribute(cardAttr, 'style', '--aa-card-bg:' + cBg + ';--aa-icon-bg:' + iBg + ';');

                view.addRenderAttribute(valueAttr, 'class', 'veloria-advanced-analytics__value elementor-inline-editing');
                view.addRenderAttribute(valueAttr, 'data-elementor-setting-key', 'cards.' + index + '.value');

                view.addRenderAttribute(labelAttr, 'class', 'veloria-advanced-analytics__label elementor-inline-editing');
                view.addRenderAttribute(labelAttr, 'data-elementor-setting-key', 'cards.' + index + '.label');

                view.addRenderAttribute(trendAttr, 'class', 'veloria-advanced-analytics__trend veloria-advanced-analytics__trend--' + trendType + ' elementor-inline-editing');
                view.addRenderAttribute(trendAttr, 'data-elementor-setting-key', 'cards.' + index + '.trend');
            #>
                <div {{{ view.getRenderAttributeString(cardAttr) }}}>
                    <div class="veloria-advanced-analytics__top">
                        <div class="veloria-advanced-analytics__icon"><# if (iconHtml) { #>{{{ iconHtml }}}<# } #></div>
                        <span {{{ view.getRenderAttributeString(trendAttr) }}}>{{{ card.trend || '' }}}</span>
                    </div>
                    <div class="veloria-advanced-analytics__bottom">
                        <div {{{ view.getRenderAttributeString(valueAttr) }}} data-aa-value="{{ card.value || '' }}">{{{ card.value || '' }}}</div>
                        <div {{{ view.getRenderAttributeString(labelAttr) }}}>{{{ card.label || '' }}}</div>
                    </div>
                </div>
            <# }); #>
        </div>
        <?php
    }
}
