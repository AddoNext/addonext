<?php
namespace Elementrify\Widgets\AdvancedHeading;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class AdvancedHeading extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-heading elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-advanced-heading';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Advanced Heading', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Create stunning headings with animations', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#1d4ed8';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-advanced-heading'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-advanced-heading',
            ELEMENTRIFY_URL . 'assets/css/advanced-heading.css',
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
            'title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Add your heading here', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label'   => __('HTML tag', 'elementrify'),
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
                'label'   => __('Alignment', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-h-align-left'],
                    'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-h-align-center'],
                    'right'  => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-h-align-right'],
                ],
                'default'      => 'left',
                'prefix_class' => 'elementrify-advanced-heading--align-',
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
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .elementrify-advanced-heading__title',
            ]
        );

        $this->add_control(
            'color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-heading__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/advanced-heading/advanced-heading.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var tag = elementor.helpers.validateHTMLTag(settings.html_tag) || 'h2';
        view.addRenderAttribute('title', 'class', 'elementrify-advanced-heading__title');
        view.addInlineEditingAttributes('title', 'none');
        #>
        <div class="elementrify-advanced-heading">
            <{{{ tag }}} {{{ view.getRenderAttributeString('title') }}}>{{{ settings.title }}}</{{{ tag }}}>
        </div>
        <?php
    }
}
