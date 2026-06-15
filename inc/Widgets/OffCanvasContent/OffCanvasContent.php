<?php
namespace Veloria\Widgets\OffCanvasContent;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined('ABSPATH') or exit;

class OffCanvasContent extends WidgetBase
{
    private function get_elementor_templates(): array
    {
        $options = ['' => __('Select a template', 'veloria')];
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
                    __('Template #%d', 'veloria'),
                    $post->ID
                );
            }
        }
        return $options;
    }

    public function get_icon(): string
    {
        return 'eicon-sidebar veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-off-canvas-content';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Off-canvas Content', 'veloria');
    }

    public function get_description(): string
    {
        return __('Slide-out sidebar panels', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function get_style_depends(): array
    {
        return ['veloria-off-canvas-content'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-off-canvas-content'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-off-canvas-content',
            VELORIA_URL . 'assets/css/off-canvas-content.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-off-canvas-content',
            VELORIA_URL . 'assets/js/off-canvas-content.js',
            [],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('trigger_text', [
            'label'   => __('Trigger label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Open panel', 'veloria'),
        ]);

        $this->add_control('trigger_icon', [
            'label'   => __('Trigger icon', 'veloria'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'eicon-menu-bar',
                'library' => 'elementor',
            ],
        ]);

        $this->add_control('panel_title', [
            'label'   => __('Panel title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Off-canvas content', 'veloria'),
        ]);

        $this->add_control('content_source', [
            'label'   => __('Content source', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [
                'text'     => __('Text editor', 'veloria'),
                'template' => __('Elementor template', 'veloria'),
            ],
        ]);

        $this->add_control('panel_content', [
            'label'   => __('Panel body', 'veloria'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => __('Add content for the slide-out panel.', 'veloria'),
            'condition' => [
                'content_source' => 'text',
            ],
        ]);

        $this->add_control('panel_template_id', [
            'label'       => __('Choose template', 'veloria'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $this->get_elementor_templates(),
            'label_block' => true,
            'condition'   => [
                'content_source' => 'template',
            ],
        ]);

        $this->add_control('panel_position', [
            'label'   => __('Panel position', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'right',
            'options' => [
                'right'  => __('Right', 'veloria'),
                'left'   => __('Left', 'veloria'),
                'top'    => __('Top', 'veloria'),
                'bottom' => __('Bottom', 'veloria'),
                'center' => __('Center (modal)', 'veloria'),
            ],
        ]);

        $this->add_responsive_control('panel_width', [
            'label'      => __('Side panel width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 240, 'max' => 800],
                '%'  => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['size' => 360, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-off-canvas--left .veloria-off-canvas__panel, {{WRAPPER}} .veloria-off-canvas--right .veloria-off-canvas__panel' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('panel_top_bottom_max_height', [
            'label'      => __('Top / bottom max height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', '%'],
            'range'      => [
                'px' => ['min' => 120, 'max' => 900],
                'vh' => ['min' => 20, 'max' => 100],
                '%'  => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['size' => 60, 'unit' => 'vh'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-off-canvas--top .veloria-off-canvas__panel, {{WRAPPER}} .veloria-off-canvas--bottom .veloria-off-canvas__panel' => 'max-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('modal_max_width', [
            'label'      => __('Modal max width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 280, 'max' => 1200],
                '%'  => ['min' => 50, 'max' => 100],
            ],
            'default'    => ['size' => 560, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-off-canvas--center .veloria-off-canvas__panel' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('close_on_overlay', [
            'label'        => __('Close when overlay is clicked', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('panel_animation', [
            'label'   => __('Animation', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => [
                'slide'      => __('Slide', 'veloria'),
                'slide_fade' => __('Slide + fade', 'veloria'),
                'fade'       => __('Fade', 'veloria'),
                'zoom'       => __('Zoom', 'veloria'),
            ],
        ]);

        $this->add_control('panel_header_layout', [
            'label'   => __('Panel header', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'title_and_close',
            'options' => [
                'title_and_close' => __('Title and close button', 'veloria'),
                'close_only'      => __('Close button only', 'veloria'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_trigger', [
            'label' => __('Trigger', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'trigger_typography',
                'selector' => '{{WRAPPER}} .veloria-off-canvas__trigger-label',
            ]
        );

        $this->add_control('trigger_text_color', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .veloria-off-canvas__trigger' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('trigger_background', [
            'label'     => __('Background color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f3f4f6',
            'selectors' => [
                '{{WRAPPER}} .veloria-off-canvas__trigger' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_panel', [
            'label' => __('Panel', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'panel_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .veloria-off-canvas__panel-inner',
                'fields_options' => [
                    'color' => ['default' => '#ffffff'],
                ],
            ]
        );

        $this->add_control('panel_text_color', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .veloria-off-canvas__panel-inner' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/off-canvas-content/off-canvas-content.php';
        if (is_readable($path)) include $path;
    }
}
