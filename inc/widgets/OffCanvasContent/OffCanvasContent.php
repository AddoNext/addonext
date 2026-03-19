<?php
namespace Elementrify\Widgets\OffCanvasContent;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined('ABSPATH') or exit;

class OffCanvasContent extends WidgetBase
{
    private function get_elementor_templates(): array
    {
        $options = ['' => __('Select a template', 'elementrify')];
        $posts = get_posts([
            'post_type'      => 'elementor_library',
            'post_status'    => 'publish',
            'posts_per_page' => 200,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $options[(string) $post->ID] = $post->post_title !== '' ? $post->post_title : sprintf(__('Template #%d', 'elementrify'), $post->ID);
            }
        }
        return $options;
    }

    public function get_icon(): string
    {
        return 'eicon-sidebar elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-off-canvas-content';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Off-canvas Content', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Slide-out sidebar panels', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-off-canvas-content'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-off-canvas-content'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-off-canvas-content',
            ELEMENTRIFY_URL . 'assets/css/off-canvas-content.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-off-canvas-content',
            ELEMENTRIFY_URL . 'assets/js/off-canvas-content.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('trigger_text', [
            'label'   => __('Trigger label', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Open panel', 'elementrify'),
        ]);

        $this->add_control('trigger_icon', [
            'label'   => __('Trigger icon', 'elementrify'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'eicon-menu-bar',
                'library' => 'elementor',
            ],
        ]);

        $this->add_control('panel_title', [
            'label'   => __('Panel title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Off-canvas content', 'elementrify'),
        ]);

        $this->add_control('content_source', [
            'label'   => __('Content source', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [
                'text'     => __('Text editor', 'elementrify'),
                'template' => __('Elementor template', 'elementrify'),
            ],
        ]);

        $this->add_control('panel_content', [
            'label'   => __('Panel body', 'elementrify'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => __('Add content for the slide-out panel.', 'elementrify'),
            'condition' => [
                'content_source' => 'text',
            ],
        ]);

        $this->add_control('panel_template_id', [
            'label'       => __('Choose template', 'elementrify'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $this->get_elementor_templates(),
            'label_block' => true,
            'condition'   => [
                'content_source' => 'template',
            ],
        ]);

        $this->add_control('panel_position', [
            'label'   => __('Panel position', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'right',
            'options' => [
                'right'  => __('Right', 'elementrify'),
                'left'   => __('Left', 'elementrify'),
                'top'    => __('Top', 'elementrify'),
                'bottom' => __('Bottom', 'elementrify'),
                'center' => __('Center (modal)', 'elementrify'),
            ],
        ]);

        $this->add_responsive_control('panel_width', [
            'label'      => __('Side panel width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 240, 'max' => 800],
                '%'  => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['size' => 360, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-off-canvas--left .elementrify-off-canvas__panel, {{WRAPPER}} .elementrify-off-canvas--right .elementrify-off-canvas__panel' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('panel_top_bottom_max_height', [
            'label'      => __('Top / bottom max height', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', '%'],
            'range'      => [
                'px' => ['min' => 120, 'max' => 900],
                'vh' => ['min' => 20, 'max' => 100],
                '%'  => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['size' => 60, 'unit' => 'vh'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-off-canvas--top .elementrify-off-canvas__panel, {{WRAPPER}} .elementrify-off-canvas--bottom .elementrify-off-canvas__panel' => 'max-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('modal_max_width', [
            'label'      => __('Modal max width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 280, 'max' => 1200],
                '%'  => ['min' => 50, 'max' => 100],
            ],
            'default'    => ['size' => 560, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-off-canvas--center .elementrify-off-canvas__panel' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('close_on_overlay', [
            'label'        => __('Close when overlay is clicked', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementrify'),
            'label_off'    => __('No', 'elementrify'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('panel_animation', [
            'label'   => __('Animation', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => [
                'slide'      => __('Slide', 'elementrify'),
                'slide_fade' => __('Slide + fade', 'elementrify'),
                'fade'       => __('Fade', 'elementrify'),
                'zoom'       => __('Zoom', 'elementrify'),
            ],
        ]);

        $this->add_control('panel_header_layout', [
            'label'   => __('Panel header', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'title_and_close',
            'options' => [
                'title_and_close' => __('Title and close button', 'elementrify'),
                'close_only'      => __('Close button only', 'elementrify'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_trigger', [
            'label' => __('Trigger', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'trigger_typography',
                'selector' => '{{WRAPPER}} .elementrify-off-canvas__trigger-label',
            ]
        );

        $this->add_control('trigger_text_color', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .elementrify-off-canvas__trigger' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('trigger_background', [
            'label'     => __('Background color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f3f4f6',
            'selectors' => [
                '{{WRAPPER}} .elementrify-off-canvas__trigger' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_panel', [
            'label' => __('Panel', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'panel_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementrify-off-canvas__panel-inner',
                'fields_options' => [
                    'color' => ['default' => '#ffffff'],
                ],
            ]
        );

        $this->add_control('panel_text_color', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .elementrify-off-canvas__panel-inner' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/off-canvas-content/off-canvas-content.php';
        if (is_readable($path)) include $path;
    }
}
