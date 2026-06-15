<?php
namespace Veloria\Widgets\PriceList;

use Veloria\Base\WidgetBase;
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
        return 'eicon-price-list veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-price-list';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Price List', 'veloria');
    }

    public function get_description(): string
    {
        return __('Menu or price list with title, items, and footer.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#d4a84b';
    }

    public function get_keywords(): array
    {
        return ['veloria', 'price', 'menu', 'restaurant', 'list'];
    }

    public function get_style_depends(): array
    {
        return ['veloria-price-list'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-price-list',
            VELORIA_URL . 'assets/css/price-list.css',
            [],
            VELORIA_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('heading_title', [
            'label'   => __('Title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Restaurant Menu', 'veloria'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('heading_subtitle', [
            'label'   => __('Subtitle', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 2,
            'default' => __('Freshly prepared daily with the finest ingredients', 'veloria'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_subtitle', [
            'label'        => __('Show subtitle', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'render_type'  => 'template',
        ]);

        $this->add_control('show_header_rule', [
            'label'        => __('Show header line', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'render_type'  => 'template',
        ]);

        $repeater = new Repeater();
        $repeater->add_control('title', [
            'label'   => __('Title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Menu item', 'veloria'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('description', [
            'label'   => __('Description', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 2,
            'default' => __('Short description of the dish', 'veloria'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('price', [
            'label'   => __('Price', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '$0',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('items', [
            'label'       => __('Items', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'title'       => __('Classic Caesar Salad', 'veloria'),
                    'description' => __('Crisp romaine, shaved parmesan, croutons, classic dressing', 'veloria'),
                    'price'       => '$12',
                ],
                [
                    'title'       => __('Grilled Salmon', 'veloria'),
                    'description' => __('Atlantic salmon, lemon butter, seasonal vegetables', 'veloria'),
                    'price'       => '$24',
                ],
                [
                    'title'       => __('House Pasta', 'veloria'),
                    'description' => __('Fresh pasta, slow-cooked tomato sauce, basil', 'veloria'),
                    'price'       => '$16',
                ],
                [
                    'title'       => __('Chocolate Torte', 'veloria'),
                    'description' => __('Rich dark chocolate, berry coulis', 'veloria'),
                    'price'       => '$9',
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->add_control('footer_note', [
            'label'   => __('Footer note', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 2,
            'default' => __('All prices include tax. Gratuity not included.', 'veloria'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_footer', [
            'label'        => __('Show footer', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'render_type'  => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_box', [
            'label' => __('Box', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('box_max_width', [
            'label'      => __('Max width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 280, 'max' => 1200],
                '%'  => ['min' => 50, 'max' => 100],
            ],
            'default'    => ['unit' => 'px', 'size' => 560],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__box' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('box_background', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#faf6ef',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__box' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('box_border_heading', [
            'label' => __('Border', 'veloria'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_control('box_border_color', [
            'label'     => __('Border color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d4a84b',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__box' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('box_border_width', [
            'label'      => __('Border width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 8]],
            'default'    => ['unit' => 'px', 'size' => 1],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__box' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
            ],
        ]);

        $this->add_responsive_control('box_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 12],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__box' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('box_padding', [
            'label'      => __('Padding', 'veloria'),
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
                '{{WRAPPER}} .veloria-price-list__box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_header', [
            'label' => __('Header', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'main_title_typography',
                'label'    => __('Title typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-price-list__main-title',
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
            'label'     => __('Title color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1f1f1f',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__main-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'label'    => __('Subtitle typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-price-list__subtitle',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 15, 'unit' => 'px']],
                    'font_style'  => ['default' => 'italic'],
                ],
            ]
        );

        $this->add_control('subtitle_color', [
            'label'     => __('Subtitle color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__subtitle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('header_spacing_bottom', [
            'label'      => __('Space below header', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('header_rule_color', [
            'label'     => __('Header line color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d4a84b',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__header-rule' => 'border-top-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('header_rule_height', [
            'label'      => __('Header line height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 1, 'max' => 8]],
            'default'    => ['unit' => 'px', 'size' => 1],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__header-rule' => 'border-top-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_items', [
            'label' => __('Items', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_title_typography',
                'label'    => __('Item title typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-price-list__item-title',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 16, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('item_title_color', [
            'label'     => __('Item title color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1f2937',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__item-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_price_typography',
                'label'    => __('Price typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-price-list__item-price',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 16, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('item_price_color', [
            'label'     => __('Price color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#b45309',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__item-price' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_desc_typography',
                'label'    => __('Description typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-price-list__item-desc',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 14, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('item_desc_color', [
            'label'     => __('Description color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__item-desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('item_desc_spacing', [
            'label'      => __('Space below title row', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['unit' => 'px', 'size' => 6],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__item-desc' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('item_vertical_padding', [
            'label'      => __('Item vertical padding', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('separator_color', [
            'label'     => __('Separator color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e8d5a3',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__separator' => 'border-top-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('separator_height', [
            'label'      => __('Separator height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 1, 'max' => 6]],
            'default'    => ['unit' => 'px', 'size' => 1],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('separator_spacing', [
            'label'      => __('Separator top offset', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__separator' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_footer', [
            'label' => __('Footer', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'footer_typography',
                'label'    => __('Footer typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-price-list__footer-note',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 13, 'unit' => 'px']],
                    'font_style'  => ['default' => 'italic'],
                ],
            ]
        );

        $this->add_control('footer_color', [
            'label'     => __('Footer color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .veloria-price-list__footer-note' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('footer_spacing_top', [
            'label'      => __('Space above footer', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 4],
            'selectors'  => [
                '{{WRAPPER}} .veloria-price-list__footer' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/price-list/price-list.php';
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
        view.addRenderAttribute('pl_heading', 'class', 'veloria-price-list__main-title elementor-inline-editing');
        view.addRenderAttribute('pl_heading', 'data-elementor-setting-key', 'heading_title');
        view.addRenderAttribute('pl_sub', 'class', 'veloria-price-list__subtitle elementor-inline-editing');
        view.addRenderAttribute('pl_sub', 'data-elementor-setting-key', 'heading_subtitle');
        view.addRenderAttribute('pl_sub', 'data-elementor-inline-editing-toolbar', 'advanced');
        view.addRenderAttribute('pl_footer', 'class', 'veloria-price-list__footer-note elementor-inline-editing');
        view.addRenderAttribute('pl_footer', 'data-elementor-setting-key', 'footer_note');
        view.addRenderAttribute('pl_footer', 'data-elementor-inline-editing-toolbar', 'advanced');
        #>
        <div class="veloria-price-list">
            <div class="veloria-price-list__box">
                <header class="veloria-price-list__header">
                    <h2 {{{ view.getRenderAttributeString('pl_heading') }}}>{{{ settings.heading_title || '' }}}</h2>
                    <# if (showSubtitle) { #>
                        <p {{{ view.getRenderAttributeString('pl_sub') }}}>{{{ settings.heading_subtitle || '' }}}</p>
                    <# } #>
                    <# if (showRule) { #>
                        <span class="veloria-price-list__header-rule" aria-hidden="true"></span>
                    <# } #>
                </header>

                <# if (items.length) { #>
                    <ul class="veloria-price-list__items">
                        <# _.each(items, function(item, index) { #>
                            <#
                            var tk = 'items.' + index + '.title';
                            var dk = 'items.' + index + '.description';
                            var pk = 'items.' + index + '.price';
                            var tAttr = 'pl_it_' + index + '_t';
                            var dAttr = 'pl_it_' + index + '_d';
                            var pAttr = 'pl_it_' + index + '_p';
                            view.addRenderAttribute(tAttr, 'class', 'veloria-price-list__item-title elementor-inline-editing');
                            view.addRenderAttribute(tAttr, 'data-elementor-setting-key', tk);
                            view.addRenderAttribute(dAttr, 'class', 'veloria-price-list__item-desc elementor-inline-editing');
                            view.addRenderAttribute(dAttr, 'data-elementor-setting-key', dk);
                            view.addRenderAttribute(dAttr, 'data-elementor-inline-editing-toolbar', 'advanced');
                            view.addRenderAttribute(pAttr, 'class', 'veloria-price-list__item-price elementor-inline-editing');
                            view.addRenderAttribute(pAttr, 'data-elementor-setting-key', pk);
                            #>
                            <li class="veloria-price-list__item">
                                <div class="veloria-price-list__line">
                                    <span {{{ view.getRenderAttributeString(tAttr) }}}>{{{ item.title || '' }}}</span>
                                    <span {{{ view.getRenderAttributeString(pAttr) }}}>{{{ item.price || '' }}}</span>
                                </div>
                                <p {{{ view.getRenderAttributeString(dAttr) }}}>{{{ item.description || '' }}}</p>
                                <span class="veloria-price-list__separator" aria-hidden="true"></span>
                            </li>
                        <# }); #>
                    </ul>
                <# } #>

                <# if (showFooter) { #>
                    <footer class="veloria-price-list__footer">
                        <p {{{ view.getRenderAttributeString('pl_footer') }}}>{{{ settings.footer_note || '' }}}</p>
                    </footer>
                <# } #>
            </div>
        </div>
        <?php
    }
}
