<?php
namespace Elementrify\Widgets\PromoBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class PromoBox extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_script_in_preview']);
    }

    public function enqueue_script_in_preview(): void
    {
        wp_enqueue_script('elementrify-promo-box');
    }

    public function get_icon(): string
    {
        return 'eicon-info-box elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-promo-box';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Promo Box', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Promotional banner with timer and CTA.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#ea580c';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-promo-box'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-promo-box'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-promo-box',
            ELEMENTRIFY_URL . 'assets/css/promo-box.css',
            [],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            'elementrify-promo-box',
            ELEMENTRIFY_URL . 'assets/js/promo-box.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('badge_text', [
            'label'   => __('Badge label', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Limited Time', 'elementrify'),
        ]);

        $this->add_control('badge_secondary', [
            'label'   => __('Badge secondary text', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('50% OFF', 'elementrify'),
        ]);

        $this->add_control('title', [
            'label'   => __('Title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Black Friday Sale', 'elementrify'),
        ]);

        $this->add_control('content', [
            'label'   => __('Subtitle', 'elementrify'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 3,
            'default' => __('Get premium access to all features with our biggest discount of the year. Don\'t miss out!', 'elementrify'),
        ]);

        $this->add_control('button_text', [
            'label'   => __('Button text', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Claim Offer', 'elementrify'),
        ]);

        $this->add_control('button_url', [
            'label'       => __('Button URL', 'elementrify'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://example.com',
            'default'     => ['url' => '#'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_timer', [
            'label' => __('Time Counter', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_counter', [
            'label'        => __('Show counter', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementrify'),
            'label_off'    => __('No', 'elementrify'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('due_date_time', [
            'label'       => __('Due date/time', 'elementrify'),
            'type'        => 'date_time',
            'default'     => wp_date('Y-m-d H:i', time() + DAY_IN_SECONDS),
            'picker_options' => [
                'enableTime' => true,
                'time_24hr'  => true,
                'dateFormat' => 'Y-m-d H:i',
            ],
            'condition'   => ['show_counter' => 'yes'],
        ]);

        $this->add_control('expired_text', [
            'label'     => __('Expired text', 'elementrify'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Offer ended', 'elementrify'),
            'condition' => ['show_counter' => 'yes'],
        ]);

        $this->add_control('label_days', [
            'label'     => __('Days label', 'elementrify'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Days', 'elementrify'),
            'condition' => ['show_counter' => 'yes'],
        ]);

        $this->add_control('label_hours', [
            'label'     => __('Hours label', 'elementrify'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Hours', 'elementrify'),
            'condition' => ['show_counter' => 'yes'],
        ]);

        $this->add_control('label_minutes', [
            'label'     => __('Minutes label', 'elementrify'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Minutes', 'elementrify'),
            'condition' => ['show_counter' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_box', [
            'label' => __('Box', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('background_type', [
            'label'        => __('Background type', 'elementrify'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'gradient',
            'options'      => [
                'gradient' => __('Gradient', 'elementrify'),
                'solid'    => __('Solid', 'elementrify'),
            ],
            'prefix_class' => 'elementrify-promo-box--bg-',
        ]);

        $this->add_control('background_solid', [
            'label'      => __('Background color', 'elementrify'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#3157f8',
            'condition'  => ['background_type' => 'solid'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => 'background-color: {{VALUE}}; background-image: none;',
            ],
        ]);

        $this->add_control('gradient_start', [
            'label'      => __('Gradient start', 'elementrify'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#2f6ef8',
            'condition'  => ['background_type' => 'gradient'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => '--elementrify-promo-start: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_end', [
            'label'      => __('Gradient end', 'elementrify'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#6546f8',
            'condition'  => ['background_type' => 'gradient'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => '--elementrify-promo-end: {{VALUE}};',
            ],
        ]);

        $this->add_control('gradient_angle', [
            'label'      => __('Gradient angle', 'elementrify'),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 112,
            'min'        => 0,
            'max'        => 360,
            'step'       => 1,
            'condition'  => ['background_type' => 'gradient'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => '--elementrify-promo-angle: {{VALUE}}deg;',
            ],
        ]);

        $this->add_responsive_control('box_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top'      => '28',
                'right'    => '30',
                'bottom'   => '28',
                'left'     => '30',
                'unit'     => 'px',
                'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('box_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top'      => '12',
                'right'    => '12',
                'bottom'   => '12',
                'left'     => '12',
                'unit'     => 'px',
                'isLinked' => true,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('content_max_width', [
            'label'      => __('Content max width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 200, 'max' => 1200],
                '%'  => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['unit' => '%', 'size' => 76],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box__content' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_text', [
            'label' => __('Text', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('text_color', [
            'label'     => __('Main text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__title' => 'color: {{VALUE}};',
                '{{WRAPPER}} .elementrify-promo-box__description' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_color', [
            'label'     => __('Badge text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f9fafb',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__badge-secondary' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_pill_bg', [
            'label'     => __('Badge pill background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f6d34b',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__badge-pill' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_pill_text_color', [
            'label'     => __('Badge pill text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1f2937',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__badge-pill' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .elementrify-promo-box__title',
                'global'         => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'fields_options' => [
                    'font_size'   => ['default' => ['size' => 24, 'unit' => 'px']],
                    'font_weight' => ['default' => '700'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'subtitle_typography',
                'selector'       => '{{WRAPPER}} .elementrify-promo-box__description',
                'fields_options' => [
                    'font_size'   => ['default' => ['size' => 16, 'unit' => 'px']],
                    'font_weight' => ['default' => '500'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_counter', [
            'label'     => __('Counter Style', 'elementrify'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_counter' => 'yes'],
        ]);

        $this->add_control('counter_number_color', [
            'label'     => __('Number color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__counter-value' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('counter_label_color', [
            'label'     => __('Label color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#dbeafe',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__counter-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'counter_number_typography',
                'selector'       => '{{WRAPPER}} .elementrify-promo-box__counter-value',
                'fields_options' => [
                    'font_size'   => ['default' => ['size' => 20, 'unit' => 'px']],
                    'font_weight' => ['default' => '700'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'counter_label_typography',
                'selector'       => '{{WRAPPER}} .elementrify-promo-box__counter-label',
                'fields_options' => [
                    'font_size'   => ['default' => ['size' => 12, 'unit' => 'px']],
                    'font_weight' => ['default' => '500'],
                ],
            ]
        );

        $this->add_control('counter_separator_color', [
            'label'     => __('Separator color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#89a2ff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__counter-item--separator::after' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_button', [
            'label' => __('Button Styler', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'button_typography',
                'selector'       => '{{WRAPPER}} .elementrify-promo-box__button',
                'fields_options' => [
                    'font_size'   => ['default' => ['size' => 14, 'unit' => 'px']],
                    'font_weight' => ['default' => '600'],
                ],
            ]
        );

        $this->start_controls_tabs('button_tabs');

        $this->start_controls_tab('button_tab_normal', [
            'label' => __('Normal', 'elementrify'),
        ]);

        $this->add_control('button_text_color', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2f6ef8',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__button' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_background', [
            'label'     => __('Background color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__button' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('button_tab_hover', [
            'label' => __('Hover', 'elementrify'),
        ]);

        $this->add_control('button_text_color_hover', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__button:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_background_hover', [
            'label'     => __('Background color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1d4ed8',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box__button:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .elementrify-promo-box__button',
            ]
        );

        $this->add_responsive_control('button_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'      => '14',
                'right'    => '34',
                'bottom'   => '14',
                'left'     => '34',
                'unit'     => 'px',
                'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('button_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top'      => '14',
                'right'    => '14',
                'bottom'   => '14',
                'left'     => '14',
                'unit'     => 'px',
                'isLinked' => true,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_animation', [
            'label' => __('Animation', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('enable_animation', [
            'label'        => __('Enable animation', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementrify'),
            'label_off'    => __('No', 'elementrify'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'prefix_class' => 'elementrify-promo-box--anim-',
        ]);

        $this->add_control('animation_speed', [
            'label'      => __('Gradient speed (seconds)', 'elementrify'),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 9,
            'min'        => 1,
            'max'        => 60,
            'step'       => 0.1,
            'condition'  => ['enable_animation' => 'yes'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => '--elementrify-promo-gradient-duration: {{VALUE}}s;',
            ],
        ]);

        $this->add_control('circles_speed', [
            'label'      => __('Circles speed (seconds)', 'elementrify'),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 7,
            'min'        => 1,
            'max'        => 60,
            'step'       => 0.1,
            'condition'  => ['enable_animation' => 'yes'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-promo-box' => '--elementrify-promo-circles-duration: {{VALUE}}s;',
            ],
        ]);

        $this->add_control('circle_one_color', [
            'label'     => __('Circle 1 color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(175, 120, 255, 0.35)',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box' => '--elementrify-promo-circle-one: {{VALUE}};',
            ],
        ]);

        $this->add_control('circle_two_color', [
            'label'     => __('Circle 2 color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(130, 90, 255, 0.35)',
            'selectors' => [
                '{{WRAPPER}} .elementrify-promo-box' => '--elementrify-promo-circle-two: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/promo-box/promo-box.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var showCounter = settings.show_counter === 'yes';
        var bgType = settings.background_type === 'solid' ? 'solid' : 'gradient';
        var isAnimated = settings.enable_animation === 'yes';
        var btnHref = settings.button_url && settings.button_url.url ? settings.button_url.url : '#';
        view.addRenderAttribute('promo_badge_text', 'class', 'elementrify-promo-box__badge-pill elementor-inline-editing');
        view.addRenderAttribute('promo_badge_text', 'data-elementor-setting-key', 'badge_text');
        view.addRenderAttribute('promo_badge_secondary', 'class', 'elementrify-promo-box__badge-secondary elementor-inline-editing');
        view.addRenderAttribute('promo_badge_secondary', 'data-elementor-setting-key', 'badge_secondary');
        view.addRenderAttribute('promo_title', 'class', 'elementrify-promo-box__title elementor-inline-editing');
        view.addRenderAttribute('promo_title', 'data-elementor-setting-key', 'title');
        view.addRenderAttribute('promo_content', 'class', 'elementrify-promo-box__description elementor-inline-editing');
        view.addRenderAttribute('promo_content', 'data-elementor-setting-key', 'content');
        view.addRenderAttribute('promo_content', 'data-elementor-inline-editing-toolbar', 'advanced');
        view.addRenderAttribute('promo_button_text', 'class', 'elementrify-promo-box__button elementor-inline-editing');
        view.addRenderAttribute('promo_button_text', 'href', btnHref);
        view.addRenderAttribute('promo_button_text', 'data-elementor-setting-key', 'button_text');
        if (settings.button_url && settings.button_url.is_external) {
            view.addRenderAttribute('promo_button_text', 'target', '_blank');
        }
        if (settings.button_url && settings.button_url.nofollow) {
            view.addRenderAttribute('promo_button_text', 'rel', 'nofollow');
        }
        view.addRenderAttribute('promo_label_days', 'class', 'elementrify-promo-box__counter-label elementor-inline-editing');
        view.addRenderAttribute('promo_label_days', 'data-elementor-setting-key', 'label_days');
        view.addRenderAttribute('promo_label_hours', 'class', 'elementrify-promo-box__counter-label elementor-inline-editing');
        view.addRenderAttribute('promo_label_hours', 'data-elementor-setting-key', 'label_hours');
        view.addRenderAttribute('promo_label_minutes', 'class', 'elementrify-promo-box__counter-label elementor-inline-editing');
        view.addRenderAttribute('promo_label_minutes', 'data-elementor-setting-key', 'label_minutes');
        #>
        <div class="elementrify-promo-box elementrify-promo-box--bg-{{ bgType }} <# if (isAnimated) { #>elementrify-promo-box--anim-yes<# } #>">
            <div class="elementrify-promo-box__content">
                <div class="elementrify-promo-box__badge-row">
                    <span {{{ view.getRenderAttributeString('promo_badge_text') }}}>{{{ settings.badge_text || '' }}}</span>
                    <span {{{ view.getRenderAttributeString('promo_badge_secondary') }}}>{{{ settings.badge_secondary || '' }}}</span>
                </div>

                <h4 {{{ view.getRenderAttributeString('promo_title') }}}>{{{ settings.title || '' }}}</h4>
                <p {{{ view.getRenderAttributeString('promo_content') }}}>{{{ settings.content || '' }}}</p>

                <div class="elementrify-promo-box__footer">
                    <# if (settings.button_text || elementorFrontend.isEditMode()) { #>
                        <a {{{ view.getRenderAttributeString('promo_button_text') }}}>{{{ settings.button_text || '' }}}</a>
                    <# } #>

                    <# if (showCounter) { #>
                        <div class="elementrify-promo-box__counter" aria-label="Countdown timer" data-due="{{ settings.due_date_time || '' }}" data-expired-text="{{ settings.expired_text || '' }}">
                            <div class="elementrify-promo-box__counter-item elementrify-promo-box__counter-item--separator">
                                <span class="elementrify-promo-box__counter-value" data-unit="days"><# print('00'); #></span>
                                <span {{{ view.getRenderAttributeString('promo_label_days') }}}>{{{ settings.label_days || 'Days' }}}</span>
                            </div>
                            <div class="elementrify-promo-box__counter-item elementrify-promo-box__counter-item--separator">
                                <span class="elementrify-promo-box__counter-value" data-unit="hours"><# print('00'); #></span>
                                <span {{{ view.getRenderAttributeString('promo_label_hours') }}}>{{{ settings.label_hours || 'Hours' }}}</span>
                            </div>
                            <div class="elementrify-promo-box__counter-item">
                                <span class="elementrify-promo-box__counter-value" data-unit="minutes"><# print('00'); #></span>
                                <span {{{ view.getRenderAttributeString('promo_label_minutes') }}}>{{{ settings.label_minutes || 'Minutes' }}}</span>
                            </div>
                        </div>
                    <# } #>
                </div>
            </div>

            <span class="elementrify-promo-box__circle elementrify-promo-box__circle--one" aria-hidden="true"></span>
            <span class="elementrify-promo-box__circle elementrify-promo-box__circle--two" aria-hidden="true"></span>
        </div>
        <?php
    }
}
