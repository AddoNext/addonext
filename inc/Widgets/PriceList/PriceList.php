<?php
namespace Addonext\Widgets\PriceList;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined('ABSPATH') or exit;

/**
 * Restaurant-style price list with header, repeater rows, and footer note.
 */
class PriceList extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-price-list addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-price-list';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Price List', 'addonext');
    }

    public function get_description(): string
    {
        return __('Menu or price list with title, items, and footer.', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#d4a84b';
    }

    public function get_keywords(): array
    {
        return ['addonext', 'price', 'menu', 'restaurant', 'list'];
    }

    public function get_style_depends(): array
    {
        return ['addonext-price-list'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-price-list',
            ADDONEXT_URL . 'assets/css/price-list.css',
            [],
            ADDONEXT_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('heading_title', [
            'label'   => __('Title', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Restaurant Menu', 'addonext'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('heading_subtitle', [
            'label'   => __('Subtitle', 'addonext'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 2,
            'default' => __('Freshly prepared daily with the finest ingredients', 'addonext'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_subtitle', [
            'label'        => __('Show subtitle', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'render_type'  => 'template',
        ]);

        $this->add_control('show_header_rule', [
            'label'        => __('Show header line', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'render_type'  => 'template',
        ]);

        $repeater = new Repeater();
        $repeater->add_control('title', [
            'label'   => __('Title', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Menu item', 'addonext'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('description', [
            'label'   => __('Description', 'addonext'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 2,
            'default' => __('Short description of the dish', 'addonext'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('price', [
            'label'   => __('Price', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => '$0',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('items', [
            'label'       => __('Items', 'addonext'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'title'       => __('Classic Caesar Salad', 'addonext'),
                    'description' => __('Crisp romaine, shaved parmesan, croutons, classic dressing', 'addonext'),
                    'price'       => '$12',
                ],
                [
                    'title'       => __('Grilled Salmon', 'addonext'),
                    'description' => __('Atlantic salmon, lemon butter, seasonal vegetables', 'addonext'),
                    'price'       => '$24',
                ],
                [
                    'title'       => __('House Pasta', 'addonext'),
                    'description' => __('Fresh pasta, slow-cooked tomato sauce, basil', 'addonext'),
                    'price'       => '$16',
                ],
                [
                    'title'       => __('Chocolate Torte', 'addonext'),
                    'description' => __('Rich dark chocolate, berry coulis', 'addonext'),
                    'price'       => '$9',
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->add_control('footer_note', [
            'label'   => __('Footer note', 'addonext'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 2,
            'default' => __('All prices include tax. Gratuity not included.', 'addonext'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_footer', [
            'label'        => __('Show footer', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'render_type'  => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_box', [
            'label' => __('Box', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('box_max_width', [
            'label'      => __('Max width', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 280, 'max' => 1200],
                '%'  => ['min' => 50, 'max' => 100],
            ],
            'default'    => ['unit' => 'px', 'size' => 560],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__box' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('box_background', [
            'label'     => __('Background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#faf6ef',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__box' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('box_border_heading', [
            'label' => __('Border', 'addonext'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_control('box_border_color', [
            'label'     => __('Border color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d4a84b',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__box' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('box_border_width', [
            'label'      => __('Border width', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 8]],
            'default'    => ['unit' => 'px', 'size' => 1],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__box' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
            ],
        ]);

        $this->add_responsive_control('box_radius', [
            'label'      => __('Border radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 12],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__box' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('box_padding', [
            'label'      => __('Padding', 'addonext'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'      => '32',
                'right'    => '36',
                'bottom'   => '28',
                'left'     => '36',
                'unit'     => 'px',
                'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_header', [
            'label' => __('Header', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'main_title_typography',
                'label'    => __('Title typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-price-list__main-title',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 28, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('main_title_color', [
            'label'     => __('Title color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1f1f1f',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__main-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'label'    => __('Subtitle typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-price-list__subtitle',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 15, 'unit' => 'px']],
                    'font_style'  => ['default' => 'italic'],
                ],
            ]
        );

        $this->add_control('subtitle_color', [
            'label'     => __('Subtitle color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__subtitle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('header_spacing_bottom', [
            'label'      => __('Space below header', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('header_rule_color', [
            'label'     => __('Header line color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d4a84b',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__header-rule' => 'border-top-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('header_rule_height', [
            'label'      => __('Header line height', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 1, 'max' => 8]],
            'default'    => ['unit' => 'px', 'size' => 1],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__header-rule' => 'border-top-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_items', [
            'label' => __('Items', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_title_typography',
                'label'    => __('Item title typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-price-list__item-title',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 16, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('item_title_color', [
            'label'     => __('Item title color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1f2937',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__item-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_price_typography',
                'label'    => __('Price typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-price-list__item-price',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 16, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('item_price_color', [
            'label'     => __('Price color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#b45309',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__item-price' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_desc_typography',
                'label'    => __('Description typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-price-list__item-desc',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 14, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('item_desc_color', [
            'label'     => __('Description color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__item-desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('item_desc_spacing', [
            'label'      => __('Space below title row', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['unit' => 'px', 'size' => 6],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__item-desc' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('item_vertical_padding', [
            'label'      => __('Item vertical padding', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('separator_color', [
            'label'     => __('Separator color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e8d5a3',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__separator' => 'border-top-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('separator_height', [
            'label'      => __('Separator height', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 1, 'max' => 6]],
            'default'    => ['unit' => 'px', 'size' => 1],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('separator_spacing', [
            'label'      => __('Separator top offset', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__separator' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_footer', [
            'label' => __('Footer', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'footer_typography',
                'label'    => __('Footer typography', 'addonext'),
                'selector' => '{{WRAPPER}} .addonext-price-list__footer-note',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 13, 'unit' => 'px']],
                    'font_style'  => ['default' => 'italic'],
                ],
            ]
        );

        $this->add_control('footer_color', [
            'label'     => __('Footer color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .addonext-price-list__footer-note' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('footer_spacing_top', [
            'label'      => __('Space above footer', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 4],
            'selectors'  => [
                '{{WRAPPER}} .addonext-price-list__footer' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/price-list/price-list.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var showSubtitle = settings.show_subtitle === 'yes';
        var showRule = settings.show_header_rule === 'yes';
        var showFooter = settings.show_footer === 'yes';
        var items = settings.items || [];
        view.addRenderAttribute('pl_heading', 'class', 'addonext-price-list__main-title elementor-inline-editing');
        view.addRenderAttribute('pl_heading', 'data-elementor-setting-key', 'heading_title');
        view.addRenderAttribute('pl_sub', 'class', 'addonext-price-list__subtitle elementor-inline-editing');
        view.addRenderAttribute('pl_sub', 'data-elementor-setting-key', 'heading_subtitle');
        view.addRenderAttribute('pl_sub', 'data-elementor-inline-editing-toolbar', 'advanced');
        view.addRenderAttribute('pl_footer', 'class', 'addonext-price-list__footer-note elementor-inline-editing');
        view.addRenderAttribute('pl_footer', 'data-elementor-setting-key', 'footer_note');
        view.addRenderAttribute('pl_footer', 'data-elementor-inline-editing-toolbar', 'advanced');
        #>
        <div class="addonext-price-list">
            <div class="addonext-price-list__box">
                <header class="addonext-price-list__header">
                    <h2 {{{ view.getRenderAttributeString('pl_heading') }}}>{{{ settings.heading_title || '' }}}</h2>
                    <# if (showSubtitle) { #>
                        <p {{{ view.getRenderAttributeString('pl_sub') }}}>{{{ settings.heading_subtitle || '' }}}</p>
                    <# } #>
                    <# if (showRule) { #>
                        <span class="addonext-price-list__header-rule" aria-hidden="true"></span>
                    <# } #>
                </header>

                <# if (items.length) { #>
                    <ul class="addonext-price-list__items">
                        <# _.each(items, function(item, index) { #>
                            <#
                            var tk = 'items.' + index + '.title';
                            var dk = 'items.' + index + '.description';
                            var pk = 'items.' + index + '.price';
                            var tAttr = 'pl_it_' + index + '_t';
                            var dAttr = 'pl_it_' + index + '_d';
                            var pAttr = 'pl_it_' + index + '_p';
                            view.addRenderAttribute(tAttr, 'class', 'addonext-price-list__item-title elementor-inline-editing');
                            view.addRenderAttribute(tAttr, 'data-elementor-setting-key', tk);
                            view.addRenderAttribute(dAttr, 'class', 'addonext-price-list__item-desc elementor-inline-editing');
                            view.addRenderAttribute(dAttr, 'data-elementor-setting-key', dk);
                            view.addRenderAttribute(dAttr, 'data-elementor-inline-editing-toolbar', 'advanced');
                            view.addRenderAttribute(pAttr, 'class', 'addonext-price-list__item-price elementor-inline-editing');
                            view.addRenderAttribute(pAttr, 'data-elementor-setting-key', pk);
                            #>
                            <li class="addonext-price-list__item">
                                <div class="addonext-price-list__line">
                                    <span {{{ view.getRenderAttributeString(tAttr) }}}>{{{ item.title || '' }}}</span>
                                    <span {{{ view.getRenderAttributeString(pAttr) }}}>{{{ item.price || '' }}}</span>
                                </div>
                                <p {{{ view.getRenderAttributeString(dAttr) }}}>{{{ item.description || '' }}}</p>
                                <span class="addonext-price-list__separator" aria-hidden="true"></span>
                            </li>
                        <# }); #>
                    </ul>
                <# } #>

                <# if (showFooter) { #>
                    <footer class="addonext-price-list__footer">
                        <p {{{ view.getRenderAttributeString('pl_footer') }}}>{{{ settings.footer_note || '' }}}</p>
                    </footer>
                <# } #>
            </div>
        </div>
        <?php
    }
}
