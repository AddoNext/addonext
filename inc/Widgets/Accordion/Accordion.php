<?php
namespace Addonext\Widgets\Accordion;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;

defined('ABSPATH') or exit;

class Accordion extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_script_in_preview']);
    }

    public function enqueue_script_in_preview(): void
    {
        $this->enqueue_assets();
        wp_enqueue_script('addonext-accordion');
    }

    public function get_icon(): string
    {
        return 'eicon-accordion addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-accordion';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Accordion', 'addonext');
    }

    public function get_description(): string
    {
        return __('Advanced accordion with multiple items', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['addonext-accordion'];
    }

    public function get_script_depends(): array
    {
        return ['addonext-accordion'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-accordion',
            ADDONEXT_URL . 'assets/css/accordion.css',
            [],
            ADDONEXT_VERSION
        );
        wp_register_script(
            'addonext-accordion',
            ADDONEXT_URL . 'assets/js/accordion.js',
            [],
            ADDONEXT_VERSION,
            true
        );
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

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label'       => __('Title', 'addonext'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Accordion Title', 'addonext'),
                'dynamic'     => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label'   => __('Content', 'addonext'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => __('Accordion content goes here.', 'addonext'),
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label'       => __('Items', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_title'   => __('Accordion #1', 'addonext'),
                        'tab_content'  => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'addonext'),
                    ],
                    [
                        'tab_title'   => __('Accordion #2', 'addonext'),
                        'tab_content'  => __('Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'addonext'),
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->add_control(
            'first_expanded',
            [
                'label'   => __('First item expanded', 'addonext'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'allow_multiple',
            [
                'label'   => __('Allow multiple open', 'addonext'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'   => __('Icon', 'addonext'),
                'type'    => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value'   => 'fas fa-chevron-down',
                    'library' => 'fa-solid',
                ],
                'label_block' => false,
            ]
        );

        $this->add_control(
            'selected_active_icon',
            [
                'label'   => __('Active icon', 'addonext'),
                'type'    => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon_active',
                'default' => [
                    'value'   => 'fas fa-chevron-up',
                    'library' => 'fa-solid',
                ],
                'label_block' => false,
                'condition'   => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'title_html_tag',
            [
                'label'   => __('Title HTML tag', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                ],
                'default' => 'div',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_accordion',
            [
                'label' => __('Accordion', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label'      => __('Border width', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => ['max' => 20],
                    'em' => ['max' => 2],
                ],
                'default'    => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__item' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .addonext-accordion__content' => 'border-top-width: 0;',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => __('Border color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__item' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => __('Header', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_background',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__header' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-accordion__icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_active_color',
            [
                'label'     => __('Active text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__item.is-open .addonext-accordion__header' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-accordion__item.is-open .addonext-accordion__icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_typography',
                'selector' => '{{WRAPPER}} .addonext-accordion__title',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'header_text_shadow',
                'selector' => '{{WRAPPER}} .addonext-accordion__title',
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label'     => __('Icon', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $start = is_rtl() ? 'right' : 'left';
        $end   = is_rtl() ? 'left' : 'right';

        $this->add_control(
            'icon_align',
            [
                'label'        => __('Position', 'addonext'),
                'type'         => Controls_Manager::CHOOSE,
                'default'      => 'left',
                'options'      => [
                    'left'  => [
                        'title' => __('Start', 'addonext'),
                        'icon'  => "eicon-h-align-{$start}",
                    ],
                    'right' => [
                        'title' => __('End', 'addonext'),
                        'icon'  => "eicon-h-align-{$end}",
                    ],
                ],
                'prefix_class' => 'addonext-accordion--icon-',
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label'      => __('Spacing', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px'  => ['max' => 60],
                    'em'  => ['max' => 4],
                    'rem' => ['max' => 4],
                ],
                'selectors' => [
                    '{{WRAPPER}}.addonext-accordion--icon-right .addonext-accordion__icon' => 'margin-' . $start . ': {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.addonext-accordion--icon-left .addonext-accordion__icon'  => 'margin-' . $end . ': {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_background',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__content' => 'color: {{VALUE}};',
                ],
                'global' => ['default' => Global_Colors::COLOR_TEXT],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .addonext-accordion__content',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_TEXT],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top' => '0', 'right' => '24', 'bottom' => '24', 'left' => '24',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-accordion__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $template_path = ADDONEXT_DIR . 'templates/accordion/accordion.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var hasIcon = settings.selected_icon && settings.selected_icon.value;
        var firstExpanded = settings.first_expanded === 'yes';
        var allowMultiple = settings.allow_multiple === 'yes';
        var titleTag = elementor.helpers.validateHTMLTag(settings.title_html_tag) || 'div';
        #>
        <div class="addonext-accordion" data-allow-multiple="{{ allowMultiple ? '1' : '0' }}">
            <# _.each(settings.tabs, function(item, index) {
                var isOpen = firstExpanded && index === 0;
                var openClass = isOpen ? ' is-open' : '';
                var itemKey = view.getRepeaterSettingKey('tab_title', 'tabs', index);
                var contentKey = view.getRepeaterSettingKey('tab_content', 'tabs', index);
                view.addRenderAttribute(itemKey, 'class', 'addonext-accordion__item' + openClass);
                view.addRenderAttribute(contentKey, 'class', 'addonext-accordion__content');
                view.addInlineEditingAttributes(contentKey, 'advanced');
            #>
            <div {{{ view.getRenderAttributeString(itemKey) }}}>
                <{{{ titleTag }}} class="addonext-accordion__header" role="button" tabindex="0">
                    <# if (hasIcon) { #>
                        <span class="addonext-accordion__icon addonext-accordion__icon--closed" aria-hidden="true">
                            {{{ elementor.helpers.renderIcon(view, settings.selected_icon, {}, 'i', 'object').value }}}
                        </span>
                        <span class="addonext-accordion__icon addonext-accordion__icon--open" aria-hidden="true">
                            {{{ elementor.helpers.renderIcon(view, settings.selected_active_icon, {}, 'i', 'object').value }}}
                        </span>
                    <# } #>
                    <span class="addonext-accordion__title">{{{ item.tab_title }}}</span>
                </{{{ titleTag }}}>
                <div {{{ view.getRenderAttributeString(contentKey) }}}>{{{ item.tab_content }}}</div>
            </div>
            <# }); #>
        </div>
        <?php
    }
}
