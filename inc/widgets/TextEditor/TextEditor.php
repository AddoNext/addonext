<?php
namespace Elementrify\Widgets\TextEditor;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class TextEditor extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-text elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-text-editor';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Text Editor', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Rich text content editor', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#1f2937';
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
            'editor',
            [
                'label'   => __('Content', 'elementrify'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => __('Add your text here.', 'elementrify'),
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
                'selector' => '{{WRAPPER}} .elementrify-text-editor',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-text-editor' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/text-editor/text-editor.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('editor', 'class', 'elementrify-text-editor');
        view.addInlineEditingAttributes('editor', 'advanced');
        #>
        <div {{{ view.getRenderAttributeString('editor') }}}>{{{ settings.editor }}}</div>
        <?php
    }
}
