<?php
namespace Elementrify\Widgets\IconBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class IconBox extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-icon-box elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-icon-box';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Icon Box', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Box with text content', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-icon-box'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-icon-box',
            ELEMENTRIFY_URL . 'assets/css/icon-box.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'   => __('Icon', 'elementrify'),
                'type'    => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Icon Box Title', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'description',
            [
                'label'   => __('Description', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Description text here.', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7c3aed',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-icon-box__icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-icon-box__icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .elementrify-icon-box__title',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/icon-box/icon-box.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-icon-box');
        view.addInlineEditingAttributes('title', 'none');
        view.addInlineEditingAttributes('description', 'none');
        var iconHtml = settings.selected_icon && settings.selected_icon.value ? elementor.helpers.renderIcon(view, settings.selected_icon, {}, 'i', 'object').value : '';
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <# if (iconHtml) { #><div class="elementrify-icon-box__icon">{{{ iconHtml }}}</div><# } #>
            <h3 class="elementrify-icon-box__title" {{{ view.getRenderAttributeString('title') }}}>{{{ settings.title }}}</h3>
            <p class="elementrify-icon-box__description" {{{ view.getRenderAttributeString('description') }}}>{{{ settings.description }}}</p>
        </div>
        <?php
    }
}
