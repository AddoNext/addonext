<?php
namespace Addonext\Widgets\AdvancedHeading;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined('ABSPATH') or exit;

class AdvancedHeading extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-heading addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-advanced-heading';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Advanced Heading', 'addonext');
    }

    public function get_description(): string
    {
        return __('Create stunning headings with animations', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#1d4ed8';
    }

    public function get_style_depends(): array
    {
        return ['addonext-advanced-heading'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-advanced-heading',
            ADDONEXT_URL . 'assets/css/advanced-heading.css',
            [],
            ADDONEXT_VERSION
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

        $this->add_control(
            'title',
            [
                'label'   => __('Regular text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Frequently Asked', 'addonext'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'title_gradient',
            [
                'label'   => __('Gradient text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Questions', 'addonext'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'show_subtitle',
            [
                'label'        => __('Show subtitle', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'     => __('Subtitle', 'addonext'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => __('Everything you need to know about AddoNext', 'addonext'),
                'condition' => ['show_subtitle' => 'yes'],
                'dynamic'   => ['active' => true],
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label'   => __('HTML tag', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'p'  => 'p',
                ],
            ]
        );

        $this->add_control(
            'align',
            [
                'label'   => __('Alignment', 'addonext'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'addonext'), 'icon' => 'eicon-h-align-left'],
                    'center' => ['title' => __('Center', 'addonext'), 'icon' => 'eicon-h-align-center'],
                    'right'  => ['title' => __('Right', 'addonext'), 'icon' => 'eicon-h-align-right'],
                ],
                'default'      => 'center',
                'prefix_class' => 'addonext-advanced-heading--align-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .addonext-advanced-heading__title',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .addonext-advanced-heading__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_gradient',
            [
                'label' => __('Gradient text', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gradient_color_start',
            [
                'label'     => __('Gradient start', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .addonext-advanced-heading__gradient' => '--addonext-gradient-start: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gradient_color_end',
            [
                'label'     => __('Gradient end', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7c3aed',
                'selectors' => [
                    '{{WRAPPER}} .addonext-advanced-heading__gradient' => '--addonext-gradient-end: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label'     => __('Subtitle', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['show_subtitle' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_subtitle',
                'selector' => '{{WRAPPER}} .addonext-advanced-heading__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .addonext-advanced-heading__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_spacing',
            [
                'label'     => __('Spacing', 'addonext'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'     => [
                    'px' => ['min' => 0, 'max' => 60],
                    'em' => ['min' => 0, 'max' => 3],
                ],
                'default'   => ['unit' => 'px', 'size' => 12],
                'selectors' => [
                    '{{WRAPPER}} .addonext-advanced-heading__subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ADDONEXT_DIR . 'templates/advanced-heading/advanced-heading.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var tag = elementor.helpers.validateHTMLTag(settings.html_tag) || 'h2';
        view.addRenderAttribute('wrapper', 'class', 'addonext-advanced-heading');
        view.addRenderAttribute('title_wrapper', 'class', 'addonext-advanced-heading__title');
        view.addRenderAttribute('title', 'class', 'addonext-advanced-heading__text');
        view.addRenderAttribute('title_gradient', 'class', 'addonext-advanced-heading__gradient');
        view.addInlineEditingAttributes('title', 'none');
        view.addInlineEditingAttributes('title_gradient', 'none');
        view.addInlineEditingAttributes('subtitle', 'none');
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <{{{ tag }}} {{{ view.getRenderAttributeString('title_wrapper') }}}>
                <span {{{ view.getRenderAttributeString('title') }}}>{{{ settings.title }}}</span><# if (settings.title_gradient) { #> <span {{{ view.getRenderAttributeString('title_gradient') }}}>{{{ settings.title_gradient }}}</span><# } #>
            </{{{ tag }}}>
            <# if (settings.show_subtitle && settings.subtitle) { #>
            <p class="addonext-advanced-heading__subtitle">{{{ settings.subtitle }}}</p>
            <# } #>
        </div>
        <?php
    }
}
