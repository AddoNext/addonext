<?php
namespace Addonext\Widgets\IconList;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class IconList extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-bullet-list addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-icon-list';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Icon List', 'addonext');
    }

    public function get_description(): string
    {
        return __('List with custom icons', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-icon-list',
            ADDONEXT_URL . 'assets/css/icon-list.css',
            [],
            ADDONEXT_VERSION
        );
    }

    public function get_style_depends(): array
    {
        return ['addonext-icon-list'];
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
        $repeater->add_control('text', [
            'label'   => __('Text', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('List item', 'addonext'),
        ]);
        $repeater->add_control('selected_icon', [
            'label'   => __('Icon', 'addonext'),
            'type'    => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('link', [
            'label'   => __('Link', 'addonext'),
            'type'    => Controls_Manager::URL,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['text' => __('Complete access to all premium widgets and templates', 'addonext'), 'selected_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid']],
                    ['text' => __('Priority customer support with dedicated account manager', 'addonext'), 'selected_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid']],
                    ['text' => __('Free updates and new features for lifetime', 'addonext'), 'selected_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid']],
                    ['text' => __('Advanced analytics and performance monitoring tools', 'addonext'), 'selected_icon' => ['value' => 'fas fa-check', 'library' => 'fa-solid']],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_list_style',
            [
                'label' => __('List', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label'   => __('Space between items', 'addonext'),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'   => ['px' => ['min' => 0, 'max' => 60]],
                'default' => ['size' => 18, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-icon-list__item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'   => __('Alignment', 'addonext'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left'   => ['title' => __('Left', 'addonext'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => __('Center', 'addonext'), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => __('Right', 'addonext'), 'icon' => 'eicon-text-align-right'],
                ],
                'prefix_class' => 'addonext-icon-list%s-align-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => __('Icon', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'   => __('Size', 'addonext'),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'   => ['px' => ['min' => 8, 'max' => 48]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-icon-list__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_background_color',
            [
                'label'  => __('Background color', 'addonext'),
                'type'   => Controls_Manager::COLOR,
                'default' => '#3B82F6',
                'selectors' => [
                    '{{WRAPPER}} .addonext-icon-list__icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'  => __('Icon color', 'addonext'),
                'type'   => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .addonext-icon-list__icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-icon-list__icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label'   => __('Border radius', 'addonext'),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'   => [
                    'px' => ['min' => 0, 'max' => 200],
                    '%' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 50, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-icon-list__icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label'   => __('Gap from text', 'addonext'),
                'type'    => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'   => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 10, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-icon-list__item' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_text_style',
            [
                'label' => __('Text', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'  => __('Color', 'addonext'),
                'type'   => Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .addonext-icon-list__text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-icon-list__link .addonext-icon-list__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'selector' => '{{WRAPPER}} .addonext-icon-list__text',
                'fields_options' => [
                    'font_size' => ['default' => ['size' => 14, 'unit' => 'px']],
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ADDONEXT_DIR . 'templates/icon-list/icon-list.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'addonext-icon-list');
        #>
        <ul {{{ view.getRenderAttributeString('wrapper') }}}>
            <# _.each(settings.items, function(item) {
                var iconData = item.selected_icon || item.icon;
                var iconHtml = iconData && iconData.value ? elementor.helpers.renderIcon(view, iconData, {}, 'i', 'object').value : '';
                var hasLink = item.link && item.link.url;
            #>
            <li class="addonext-icon-list__item">
                <# if (iconHtml) { #><span class="addonext-icon-list__icon">{{{ iconHtml }}}</span><# } #>
                <# if (hasLink) { #>
                    <a href="{{ item.link.url }}" class="addonext-icon-list__link" <# if (item.link.is_external) { #>target="_blank"<# } #><# if (item.link.nofollow) { #>rel="nofollow"<# } #>>
                        <span class="addonext-icon-list__text" data-elementor-setting-key="text" data-elementor-inline-editing-toolbar="basic">{{{ item.text }}}</span>
                    </a>
                <# } else { #>
                    <span class="addonext-icon-list__text" data-elementor-setting-key="text" data-elementor-inline-editing-toolbar="basic">{{{ item.text }}}</span>
                <# } #>
            </li>
            <# }); #>
        </ul>
        <?php
    }
}
