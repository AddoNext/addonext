<?php
namespace Addonext\Widgets\BentoGrid;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class BentoGrid extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-gallery-grid addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-bento-grid';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Bento Grid', 'addonext');
    }

    public function get_description(): string
    {
        return __('Configurable bento-style feature grid.', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#6366f1';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['addonext-bento-grid'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-bento-grid',
            ADDONEXT_URL . 'assets/css/bento-grid.css',
            [],
            ADDONEXT_VERSION
        );
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $spanOptions = [];
        for ($i = 1; $i <= 12; $i++) {
            $spanOptions[(string) $i] = (string) $i;
        }

        $columnRepeater = new Repeater();
        $columnRepeater->add_control('icon', [
            'label'   => __('Icon', 'addonext'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fas fa-rocket',
                'library' => 'fa-solid',
            ],
        ]);
        $columnRepeater->add_control('icon_color', [
            'label'   => __('Icon color', 'addonext'),
            'type'    => Controls_Manager::COLOR,
            'default' => '#2563eb',
        ]);
        $columnRepeater->add_control('title', [
            'label'   => __('Title', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Lightning Fast', 'addonext'),
        ]);
        $columnRepeater->add_control('subtitle', [
            'label'   => __('Subtitle', 'addonext'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 2,
            'default' => __('Optimized performance for the best user experience', 'addonext'),
        ]);
        $columnRepeater->add_control('background_type', [
            'label'   => __('Background type', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'solid'    => __('Solid color', 'addonext'),
                'gradient' => __('Gradient', 'addonext'),
            ],
        ]);
        $columnRepeater->add_control('background_color', [
            'label'     => __('Background color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#eef6ff',
            'condition' => [
                'background_type' => 'solid',
            ],
        ]);
        $columnRepeater->add_control('gradient_start', [
            'label'     => __('Gradient start', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#eef6ff',
            'condition' => [
                'background_type' => 'gradient',
            ],
        ]);
        $columnRepeater->add_control('gradient_end', [
            'label'     => __('Gradient end', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e8f7ff',
            'condition' => [
                'background_type' => 'gradient',
            ],
        ]);
        $columnRepeater->add_control('gradient_angle', [
            'label'      => __('Gradient angle', 'addonext'),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 135,
            'min'        => 0,
            'max'        => 360,
            'step'       => 1,
            'condition'  => [
                'background_type' => 'gradient',
            ],
        ]);
        $columnRepeater->add_control('span_desktop', [
            'label'   => __('Desktop span (12 cols)', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => '6',
            'options' => $spanOptions,
        ]);
        $columnRepeater->add_control('span_tablet', [
            'label'   => __('Tablet span (12 cols)', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => '6',
            'options' => $spanOptions,
        ]);
        $columnRepeater->add_control('span_mobile', [
            'label'   => __('Mobile span (12 cols)', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => '12',
            'options' => $spanOptions,
        ]);

        $rowRepeater = new Repeater();
        $rowRepeater->add_control('row_label', [
            'label'   => __('Row label', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Row', 'addonext'),
        ]);
        $rowRepeater->add_control('columns', [
            'label'       => __('Tiles', 'addonext'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $columnRepeater->get_controls(),
            'default'     => [],
            'title_field' => '{{{ title }}}',
        ]);

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('rows', [
            'label'       => __('Rows', 'addonext'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $rowRepeater->get_controls(),
            'title_field' => '{{{ row_label }}}',
            'default'     => [
                [
                    'row_label' => __('Top row', 'addonext'),
                    'columns'   => [
                        [
                            'title'            => __('Lightning Fast', 'addonext'),
                            'subtitle'         => __('Optimized performance for the best user experience', 'addonext'),
                            'icon'             => ['value' => 'fas fa-rocket', 'library' => 'fa-solid'],
                            'icon_color'       => '#2563eb',
                            'background_type'  => 'solid',
                            'background_color' => '#eef6ff',
                            'span_desktop'     => '6',
                            'span_tablet'      => '6',
                            'span_mobile'      => '12',
                        ],
                        [
                            'title'            => __('Secure', 'addonext'),
                            'subtitle'         => __('Enterprise-grade security for your peace of mind', 'addonext'),
                            'icon'             => ['value' => 'fas fa-shield-alt', 'library' => 'fa-solid'],
                            'icon_color'       => '#9333ea',
                            'background_type'  => 'solid',
                            'background_color' => '#fcf4ff',
                            'span_desktop'     => '6',
                            'span_tablet'      => '6',
                            'span_mobile'      => '12',
                        ],
                    ],
                ],
                [
                    'row_label' => __('Bottom row', 'addonext'),
                    'columns'   => [
                        [
                            'title'            => __('Mobile Ready', 'addonext'),
                            'subtitle'         => '',
                            'icon'             => ['value' => 'fas fa-mobile-alt', 'library' => 'fa-solid'],
                            'icon_color'       => '#16a34a',
                            'background_type'  => 'solid',
                            'background_color' => '#effff3',
                            'span_desktop'     => '4',
                            'span_tablet'      => '6',
                            'span_mobile'      => '12',
                        ],
                        [
                            'title'            => __('Customizable', 'addonext'),
                            'subtitle'         => '',
                            'icon'             => ['value' => 'fas fa-palette', 'library' => 'fa-solid'],
                            'icon_color'       => '#ea580c',
                            'background_type'  => 'solid',
                            'background_color' => '#fff8ea',
                            'span_desktop'     => '4',
                            'span_tablet'      => '6',
                            'span_mobile'      => '12',
                        ],
                        [
                            'title'            => __('24/7 Support', 'addonext'),
                            'subtitle'         => __('Always here to help', 'addonext'),
                            'icon'             => ['value' => 'fas fa-headset', 'library' => 'fa-solid'],
                            'icon_color'       => '#dc2626',
                            'background_type'  => 'solid',
                            'background_color' => '#fff2f3',
                            'span_desktop'     => '4',
                            'span_tablet'      => '12',
                            'span_mobile'      => '12',
                        ],
                    ],
                ],
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', [
            'label' => __('Style', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('row_gap', [
            'label'      => __('Row gap', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 14],
            'selectors'  => [
                '{{WRAPPER}} .addonext-bento-grid' => '--addonext-bento-row-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('column_gap', [
            'label'      => __('Column gap', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 14],
            'selectors'  => [
                '{{WRAPPER}} .addonext-bento-grid' => '--addonext-bento-column-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => __('Tile padding', 'addonext'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'      => '60',
                'right'    => '60',
                'bottom'   => '60',
                'left'     => '60',
                'unit'     => 'px',
                'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .addonext-bento-grid__tile' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label'      => __('Tile border radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 60]],
            'default'    => ['unit' => 'px', 'size' => 12],
            'selectors'  => [
                '{{WRAPPER}} .addonext-bento-grid__tile' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('tile_min_height', [
            'label'      => __('Tile min height', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 80, 'max' => 420]],
            'default'    => ['unit' => 'px', 'size' => 160],
            'selectors'  => [
                '{{WRAPPER}} .addonext-bento-grid__tile' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => __('Title typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-bento-grid__title',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 18,
                            'unit' => 'px',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Title color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .addonext-bento-grid__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'label'    => __('Subtitle typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-bento-grid__subtitle',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => ['default' => ['size' => 14, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('subtitle_color', [
            'label'     => __('Subtitle color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4b5563',
            'selectors' => [
                '{{WRAPPER}} .addonext-bento-grid__subtitle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('icon_size', [
            'label'      => __('Icon size', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 12, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 28],
            'selectors'  => [
                '{{WRAPPER}} .addonext-bento-grid__icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .addonext-bento-grid__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = ADDONEXT_DIR . 'pro/templates/bento-grid/bento-grid.php';
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
        var rows = settings.rows || [];
        #>
        <div class="addonext-bento-grid">
            <# _.each(rows, function(row) { #>
                <div class="addonext-bento-grid__row">
                    <#
                    var columns = row.columns || [];
                    _.each(columns, function(col, colIndex) {
                        var dSpan = col.span_desktop || '6';
                        var tSpan = col.span_tablet || '6';
                        var mSpan = col.span_mobile || '12';
                        var angle = col.gradient_angle || 135;
                        var bg = col.background_type === 'gradient'
                            ? 'linear-gradient(' + angle + 'deg, ' + (col.gradient_start || '#eef6ff') + ', ' + (col.gradient_end || '#e8f7ff') + ')'
                            : (col.background_color || '#eef6ff');
                        var iconColor = col.icon_color || '#2563eb';
                        var tileStyle = '--er-bento-span-d:' + dSpan + ';--er-bento-span-t:' + tSpan + ';--er-bento-span-m:' + mSpan + ';--er-bento-icon-color:' + iconColor + ';background:' + bg + ';';
                        var tileKey = 'tile_' + colIndex;
                        view.addRenderAttribute(tileKey, 'class', 'addonext-bento-grid__tile');
                        view.addRenderAttribute(tileKey, 'style', tileStyle);
                    #>
                        <div {{{ view.getRenderAttributeString(tileKey) }}}>
                            <# if (col.icon && col.icon.value) { #>
                                <div class="addonext-bento-grid__icon"><i class="<# print(col.icon.value); #>"></i></div>
                            <# } #>
                            <h4 class="addonext-bento-grid__title"><# print(col.title || ''); #></h4>
                            <# if (col.subtitle) { #>
                                <p class="addonext-bento-grid__subtitle"><# print(col.subtitle); #></p>
                            <# } #>
                        </div>
                    <# }); #>
                </div>
            <# }); #>
        </div>
        <?php
                }
        }
}
}
