<?php
namespace Addonext\Widgets\PricingTable;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

defined('ABSPATH') or exit;

class PricingTable extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-price-table addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-pricing-table';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Pricing Table', 'addonext');
    }

    public function get_description(): string
    {
        return __('Pricing cards with features and CTA', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#6366f1';
    }

    public function get_style_depends(): array
    {
        return ['addonext-pricing-table'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style('addonext-pricing-table', ADDONEXT_URL . 'assets/css/pricing-table.css', [], ADDONEXT_VERSION);
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $features_repeater = new Repeater();
        $features_repeater->add_control(
            'text',
            [
                'label'   => __('Feature', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Feature item', 'addonext'),
            ]
        );
        $features_repeater->add_control(
            'included',
            [
                'label'        => __('Included', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $plans_repeater = new Repeater();
        $plans_repeater->add_control(
            'icon',
            [
                'label'   => __('Icon', 'addonext'),
                'type'    => Controls_Manager::ICONS,
                'default' => ['value' => 'eicon-star', 'library' => 'eicons'],
            ]
        );
        $plans_repeater->add_control(
            'title',
            [
                'label'   => __('Plan name', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Pro', 'addonext'),
            ]
        );
        $plans_repeater->add_control(
            'description',
            [
                'label'   => __('Description', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('For professional designers', 'addonext'),
            ]
        );
        $plans_repeater->add_control(
            'price',
            [
                'label'   => __('Price', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '$49',
            ]
        );
        $plans_repeater->add_control(
            'period',
            [
                'label'   => __('Period', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '/year',
            ]
        );
        $plans_repeater->add_control(
            'featured',
            [
                'label'        => __('Featured (recommended)', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );
        $plans_repeater->add_control(
            'button_text',
            [
                'label'   => __('Button text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Get Started', 'addonext'),
            ]
        );
        $plans_repeater->add_control(
            'button_url',
            [
                'label' => __('Button URL', 'addonext'),
                'type'  => Controls_Manager::URL,
            ]
        );
        $plans_repeater->add_control(
            'button_primary',
            [
                'label'        => __('Primary (gradient) button', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );
        $plans_repeater->add_control(
            'features',
            [
                'label'       => __('Features', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $features_repeater->get_controls(),
                'default'     => [
                    ['text' => __('50+ Premium Widgets', 'addonext'), 'included' => 'yes'],
                    ['text' => __('100+ Pro Templates', 'addonext'), 'included' => 'yes'],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->add_control(
            'plans',
            [
                'label'       => __('Plans', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $plans_repeater->get_controls(),
                'default'     => [
                    [
                        'icon' => ['value' => 'eicon-star', 'library' => 'eicons'],
                        'title' => __('Free', 'addonext'),
                        'description' => __('Perfect for getting started', 'addonext'),
                        'price' => '$0',
                        'period' => '/month',
                        'featured' => '',
                        'button_text' => __('Get Started Free', 'addonext'),
                        'features' => [
                            ['text' => __('20 Free Widgets', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Basic Templates', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Community Support', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Lifetime Updates', 'addonext'), 'included' => 'yes'],
                            ['text' => __('1 Website License', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Premium Widgets', 'addonext'), 'included' => ''],
                            ['text' => __('Priority Support', 'addonext'), 'included' => ''],
                            ['text' => __('Advanced Extensions', 'addonext'), 'included' => ''],
                        ],
                    ],
                    [
                        'icon' => ['value' => 'eicon-upgrade-crown', 'library' => 'eicons'],
                        'title' => __('Pro', 'addonext'),
                        'description' => __('For professional designers', 'addonext'),
                        'price' => '$49',
                        'period' => '/year',
                        'featured' => 'yes',
                        'button_text' => __('Start Pro Plan', 'addonext'),
                        'features' => [
                            ['text' => __('50+ Premium Widgets', 'addonext'), 'included' => 'yes'],
                            ['text' => __('100+ Pro Templates', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Priority Email Support', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Lifetime Updates', 'addonext'), 'included' => 'yes'],
                            ['text' => __('3 Website Licenses', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Advanced Extensions', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Custom Integrations', 'addonext'), 'included' => 'yes'],
                            ['text' => __('White Label Option', 'addonext'), 'included' => 'yes'],
                        ],
                    ],
                    [
                        'icon' => ['value' => 'eicon-theme-builder', 'library' => 'eicons'],
                        'title' => __('Agency', 'addonext'),
                        'description' => __('For teams and agencies', 'addonext'),
                        'price' => '$149',
                        'period' => '/year',
                        'featured' => '',
                        'button_text' => __('Start Agency Plan', 'addonext'),
                        'button_primary' => 'yes',
                        'features' => [
                            ['text' => __('All Pro Features', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Unlimited Websites', 'addonext'), 'included' => 'yes'],
                            ['text' => __('24/7 Priority Support', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Dedicated Account Manager', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Custom Development', 'addonext'), 'included' => 'yes'],
                            ['text' => __('API Access', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Team Collaboration Tools', 'addonext'), 'included' => 'yes'],
                            ['text' => __('Early Access to Features', 'addonext'), 'included' => 'yes'],
                        ],
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => __('Cards', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'   => __('Columns', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '3',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'gap',
            [
                'label'      => __('Gap between cards', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 60]],
                'default'    => ['size' => 24],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-pricing-table__list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'default'    => ['size' => 16],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-pricing-table__card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .addonext-pricing-table__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'     => __('Card background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label'     => __('Card border', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_featured',
            [
                'label' => __('Featured card', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'featured_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .addonext-pricing-table__card--featured',
                'fields_options' => [
                    'background' => [
                        'default' => 'gradient',
                    ],
                    'color' => ['default' => '#6366f1'],
                    'color_b' => ['default' => '#8b5cf6'],
                ],
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label'   => __('Badge text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('RECOMMENDED', 'addonext'),
            ]
        );

        $this->add_control(
            'badge_bg',
            [
                'label'     => __('Badge background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FBBF24',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label'     => __('Badge text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => __('Icon', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_box_size',
            [
                'label'      => __('Icon box size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 32, 'max' => 80]],
                'default'    => ['size' => 48],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-pricing-table__icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card:not(.addonext-pricing-table__card--featured):not(.addonext-pricing-table__card--primary-btn) .addonext-pricing-table__icon-wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label'     => __('Icon background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_featured_bg',
            [
                'label'     => __('Featured card icon background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255,255,255,0.25)',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card--featured .addonext-pricing-table__icon-wrap' => 'background-color: {{VALUE}}; color: #ffffff;',
                ],
            ]
        );

        $this->add_control(
            'icon_featured_color',
            [
                'label'     => __('Featured card icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card--featured .addonext-pricing-table__icon-wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_primary_color',
            [
                'label'     => __('Primary-button card icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7c3aed',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card--primary-btn .addonext-pricing-table__icon-wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title & description', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .addonext-pricing-table__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Title color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => __('Description color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .addonext-pricing-table__desc',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_price',
            [
                'label' => __('Price', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .addonext-pricing-table__price',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => __('Price color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'period_color',
            [
                'label'     => __('Period color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .addonext-pricing-table__button',
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_featured_bg',
            [
                'label'     => __('Featured card button background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card--featured .addonext-pricing-table__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_featured_color',
            [
                'label'     => __('Featured card button text', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6366f1',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__card--featured .addonext-pricing-table__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-pricing-table__button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_button_primary',
            [
                'label'     => __('Primary (gradient) button', 'addonext'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_primary_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .addonext-pricing-table__button--primary',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color' => ['default' => '#6366f1'],
                    'color_b' => ['default' => '#8b5cf6'],
                ],
            ]
        );

        $this->add_control(
            'button_primary_color',
            [
                'label'     => __('Primary button text', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__button--primary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_features',
            [
                'label' => __('Features list', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'features_typography',
                'selector' => '{{WRAPPER}} .addonext-pricing-table__feature-text',
            ]
        );

        $this->add_control(
            'feature_included_color',
            [
                'label'     => __('Included text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__feature--included .addonext-pricing-table__feature-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_excluded_color',
            [
                'label'     => __('Excluded text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#9ca3af',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__feature--excluded .addonext-pricing-table__feature-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'check_color',
            [
                'label'     => __('Checkmark color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#22c55e',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__feature--included .addonext-pricing-table__feature-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-pricing-table__card--featured .addonext-pricing-table__feature--included .addonext-pricing-table__feature-icon' => 'color: #ffffff;',
                ],
            ]
        );

        $this->add_control(
            'cross_color',
            [
                'label'     => __('Cross icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .addonext-pricing-table__feature--excluded .addonext-pricing-table__feature-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/pricing-table/pricing-table.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var plans = settings.plans || [];
        var badgeText = settings.badge_text || 'RECOMMENDED';
        #>
        <div class="addonext-pricing-table">
            <div class="addonext-pricing-table__list">
                <# var defaultIcons = ['eicon-star', 'eicon-upgrade-crown', 'eicon-theme-builder']; #>
                <# _.each(plans, function(plan, index) {
                    var featured = plan.featured === 'yes';
                    var primaryBtn = plan.button_primary === 'yes' && !featured;
                    var btnUrl = (plan.button_url && plan.button_url.url) ? plan.button_url.url : '#';
                    var iconSettings = (plan.icon && plan.icon.value) ? plan.icon : { value: defaultIcons[index % defaultIcons.length] || 'eicon-star', library: 'eicons' };
                    if (!iconSettings.library) { iconSettings.library = 'eicons'; }
                    var iconResult = elementor.helpers.renderIcon(view, iconSettings, { 'aria-hidden': 'true' }, 'i', 'object');
                    var iconHtml = (iconResult && iconResult.value) ? iconResult.value : (elementor.helpers.renderIcon(view, { value: 'eicon-star', library: 'eicons' }, { 'aria-hidden': 'true' }, 'i', 'object').value || '');
                    var iconValue = iconSettings.value || defaultIcons[index % defaultIcons.length] || 'eicon-star';
                    if (!iconHtml || (typeof iconHtml === 'string' && !iconHtml.trim())) {
                        iconHtml = '<i class="eicon ' + iconValue.replace(/"/g, '') + '" aria-hidden="true"></i>';
                    }
                #>
                    <div class="addonext-pricing-table__card<# if (featured) { #> addonext-pricing-table__card--featured<# } #><# if (primaryBtn) { #> addonext-pricing-table__card--primary-btn<# } #>">
                        <# if (featured && badgeText) { #>
                            <div class="addonext-pricing-table__badge">{{{ badgeText }}}</div>
                        <# } #>
                        <div class="addonext-pricing-table__inner">
                            <div class="addonext-pricing-table__icon-wrap">
                                {{{ iconHtml }}}
                            </div>
                            <h3 class="addonext-pricing-table__title">{{{ plan.title }}}</h3>
                            <# if (plan.description) { #><p class="addonext-pricing-table__desc">{{{ plan.description }}}</p><# } #>
                            <div class="addonext-pricing-table__price-wrap">
                                <span class="addonext-pricing-table__price">{{{ plan.price }}}</span><span class="addonext-pricing-table__period">{{{ plan.period }}}</span>
                            </div>
                            <a href="{{ btnUrl }}" class="addonext-pricing-table__button<# if (plan.button_primary === 'yes') { #> addonext-pricing-table__button--primary<# } #>">{{{ plan.button_text }}}</a>
                            <ul class="addonext-pricing-table__features">
                                <# _.each(plan.features || [], function(f) {
                                    var included = f.included === 'yes';
                                #>
                                    <li class="addonext-pricing-table__feature addonext-pricing-table__feature--<# print(included ? 'included' : 'excluded'); #>">
                                        <span class="addonext-pricing-table__feature-icon" aria-hidden="true">
                                            <# if (included) { #><svg class="addonext-pricing-table__check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><# } else { #><svg class="addonext-pricing-table__cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg><# } #>
                                        </span>
                                        <span class="addonext-pricing-table__feature-text">{{{ f.text }}}</span>
                                    </li>
                                <# }); #>
                            </ul>
                        </div>
                    </div>
                <# }); #>
            </div>
        </div>
        <?php
    }
}
