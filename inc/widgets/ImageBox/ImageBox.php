<?php
namespace Elementrify\Widgets\ImageBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ImageBox extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-image-box elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-image-box';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Image Box', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Advanced image blocks', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#059669';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-image-box'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-image-box',
            ELEMENTRIFY_URL . 'assets/css/image-box.css',
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
            'image',
            [
                'label'   => __('Image', 'elementrify'),
                'type'    => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Image Box Title', 'elementrify'),
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

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'elementrify'),
                'type'  => Controls_Manager::URL,
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .elementrify-image-box__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/image-box/image-box.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-image-box');
        view.addInlineEditingAttributes('title', 'none');
        view.addInlineEditingAttributes('description', 'none');
        var hasLink = settings.link && settings.link.url;
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <# if (settings.image && settings.image.url) { #>
                <div class="elementrify-image-box__image">
                    <# if (hasLink) { #><a href="{{ settings.link.url }}"><# } #>
                    <img src="{{ settings.image.url }}" alt="{{ settings.image.alt || settings.title }}">
                    <# if (hasLink) { #></a><# } #>
                </div>
            <# } #>
            <div class="elementrify-image-box__content">
                <# if (hasLink) { #><a href="{{ settings.link.url }}"><# } #>
                <h3 class="elementrify-image-box__title" {{{ view.getRenderAttributeString('title') }}}>{{{ settings.title }}}</h3>
                <# if (hasLink) { #></a><# } #>
                <p class="elementrify-image-box__description" {{{ view.getRenderAttributeString('description') }}}>{{{ settings.description }}}</p>
            </div>
        </div>
        <?php
    }
}
