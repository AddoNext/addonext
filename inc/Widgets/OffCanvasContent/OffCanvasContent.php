<?php
namespace Addonext\Widgets\OffCanvasContent;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined('ABSPATH') or exit;

class OffCanvasContent extends WidgetBase
{
    private function get_elementor_templates(): array
    {
        $options = ['' => __('Select a template', 'addonext')];
        $posts = get_posts([
            'post_type'      => 'elementor_library',
            'post_status'    => 'publish',
            'posts_per_page' => 200,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $options[(string) $post->ID] = $post->post_title !== '' ? $post->post_title : sprintf(
                    /* translators: %d: Elementor template post ID */
                    __('Template #%d', 'addonext'),
                    $post->ID
                );
            }
        }
        return $options;
    }

    public function get_icon(): string
    {
        return 'eicon-sidebar addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-off-canvas-content';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Off-canvas Content', 'addonext');
    }

    public function get_description(): string
    {
        return __('Slide-out sidebar panels', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function get_style_depends(): array
    {
        return ['addonext-off-canvas-content'];
    }

    public function get_script_depends(): array
    {
        return ['addonext-off-canvas-content'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-off-canvas-content',
            ADDONEXT_URL . 'assets/css/off-canvas-content.css',
            [],
            ADDONEXT_VERSION
        );
        wp_register_script(
            'addonext-off-canvas-content',
            ADDONEXT_URL . 'assets/js/off-canvas-content.js',
            [],
            ADDONEXT_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('trigger_text', [
            'label'   => __('Trigger label', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Open panel', 'addonext'),
        ]);

        $this->add_control('trigger_icon', [
            'label'   => __('Trigger icon', 'addonext'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'eicon-menu-bar',
                'library' => 'elementor',
            ],
        ]);

        $this->add_control('panel_title', [
            'label'   => __('Panel title', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Off-canvas content', 'addonext'),
        ]);

        $this->add_control('content_source', [
            'label'   => __('Content source', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [
                'text'     => __('Text editor', 'addonext'),
                'template' => __('Elementor template', 'addonext'),
            ],
        ]);

        $this->add_control('panel_content', [
            'label'   => __('Panel body', 'addonext'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => __('Add content for the slide-out panel.', 'addonext'),
            'condition' => [
                'content_source' => 'text',
            ],
        ]);

        $this->add_control('panel_template_id', [
            'label'       => __('Choose template', 'addonext'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $this->get_elementor_templates(),
            'label_block' => true,
            'condition'   => [
                'content_source' => 'template',
            ],
        ]);

        $this->add_control('panel_position', [
            'label'   => __('Panel position', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'right',
            'options' => [
                'right'  => __('Right', 'addonext'),
                'left'   => __('Left', 'addonext'),
                'top'    => __('Top', 'addonext'),
                'bottom' => __('Bottom', 'addonext'),
                'center' => __('Center (modal)', 'addonext'),
            ],
        ]);

        $this->add_responsive_control('panel_width', [
            'label'      => __('Side panel width', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 240, 'max' => 800],
                '%'  => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['size' => 360, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-off-canvas--left .addonext-off-canvas__panel, {{WRAPPER}} .addonext-off-canvas--right .addonext-off-canvas__panel' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('panel_top_bottom_max_height', [
            'label'      => __('Top / bottom max height', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', '%'],
            'range'      => [
                'px' => ['min' => 120, 'max' => 900],
                'vh' => ['min' => 20, 'max' => 100],
                '%'  => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['size' => 60, 'unit' => 'vh'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-off-canvas--top .addonext-off-canvas__panel, {{WRAPPER}} .addonext-off-canvas--bottom .addonext-off-canvas__panel' => 'max-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('modal_max_width', [
            'label'      => __('Modal max width', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 280, 'max' => 1200],
                '%'  => ['min' => 50, 'max' => 100],
            ],
            'default'    => ['size' => 560, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-off-canvas--center .addonext-off-canvas__panel' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('close_on_overlay', [
            'label'        => __('Close when overlay is clicked', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'addonext'),
            'label_off'    => __('No', 'addonext'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('panel_animation', [
            'label'   => __('Animation', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => [
                'slide'      => __('Slide', 'addonext'),
                'slide_fade' => __('Slide + fade', 'addonext'),
                'fade'       => __('Fade', 'addonext'),
                'zoom'       => __('Zoom', 'addonext'),
            ],
        ]);

        $this->add_control('panel_header_layout', [
            'label'   => __('Panel header', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'title_and_close',
            'options' => [
                'title_and_close' => __('Title and close button', 'addonext'),
                'close_only'      => __('Close button only', 'addonext'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_trigger', [
            'label' => __('Trigger', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'trigger_typography',
                'selector' => '{{WRAPPER}} .addonext-off-canvas__trigger-label',
            ]
        );

        $this->add_control('trigger_text_color', [
            'label'     => __('Text color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .addonext-off-canvas__trigger' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('trigger_background', [
            'label'     => __('Background color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f3f4f6',
            'selectors' => [
                '{{WRAPPER}} .addonext-off-canvas__trigger' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_panel', [
            'label' => __('Panel', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'panel_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .addonext-off-canvas__panel-inner',
                'fields_options' => [
                    'color' => ['default' => '#ffffff'],
                ],
            ]
        );

        $this->add_control('panel_text_color', [
            'label'     => __('Text color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .addonext-off-canvas__panel-inner' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/off-canvas-content/off-canvas-content.php';
        if (is_readable($path)) include $path;
    }
}
