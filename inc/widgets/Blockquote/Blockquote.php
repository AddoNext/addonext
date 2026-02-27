<?php
namespace Elementrify\Widgets\Blockquote;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Blockquote extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-blockquote elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-blockquote';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Blockquote', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Styled quote blocks', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#4b5563';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-blockquote'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-blockquote',
            ELEMENTRIFY_URL . 'assets/css/blockquote.css',
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
            'quote',
            [
                'label'   => __('Quote', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Enter your quote here.', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'author',
            [
                'label'   => __('Author', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
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
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .elementrify-blockquote__text',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-blockquote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => __('Border color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-blockquote' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/blockquote/blockquote.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-blockquote');
        view.addInlineEditingAttributes('quote', 'none');
        #>
        <blockquote {{{ view.getRenderAttributeString('wrapper') }}}>
            <p class="elementrify-blockquote__text" {{{ view.getRenderAttributeString('quote') }}}>{{{ settings.quote }}}</p>
            <# if (settings.author) { #>
                <cite class="elementrify-blockquote__author">{{{ settings.author }}}</cite>
            <# } #>
        </blockquote>
        <?php
    }
}
