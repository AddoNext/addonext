<?php
namespace Addonext\Widgets\TextEditor;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

defined('ABSPATH') || exit;

class TextEditor extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-text addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-text-editor';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Text Editor', 'addonext');
    }

    public function get_description(): string
    {
        return __('Rich text with gradient, 3D, shadows and animations', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#1f2937';
    }

    public function get_style_depends(): array
    {
        return ['addonext-text-editor'];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_preview_styles']);
    }

    public function enqueue_preview_styles(): void
    {
        wp_enqueue_style('addonext-text-editor');
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
            'editor',
            [
                'label'   => __('Content', 'addonext'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => __('Welcome to WordPress. This is your first post. Edit or delete it, then start writing!', 'addonext'),
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
                'default'      => 'left',
                'prefix_class' => 'addonext-text-editor--align-',
            ]
        );

        $this->add_control(
            'drop_cap',
            [
                'label'        => __('Drop cap', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => '',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Typography', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .addonext-text-editor',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 16]],
                    'font_weight' => ['default' => '400'],
                    'line_height' => ['default' => ['unit' => 'em', 'size' => 1.6]],
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .addonext-text-editor' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label'     => __('Link color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .addonext-text-editor a' => 'color: {{VALUE}};',
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
            'use_gradient',
            [
                'label'        => __('Use gradient', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => '',
            ]
        );

        $this->add_control(
            'gradient_color_start',
            [
                'label'     => __('Gradient start', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'condition' => ['use_gradient' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-text-editor--gradient' => '--addonext-te-gradient-start: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gradient_color_end',
            [
                'label'     => __('Gradient end', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7c3aed',
                'condition' => ['use_gradient' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-text-editor--gradient' => '--addonext-te-gradient-end: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gradient_angle',
            [
                'label'     => __('Angle', 'addonext'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range'     => ['deg' => ['min' => 0, 'max' => 360]],
                'default'   => ['unit' => 'deg', 'size' => 90],
                'condition' => ['use_gradient' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-text-editor--gradient' => '--addonext-te-gradient-angle: {{SIZE}}deg;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_text_shadow',
            [
                'label' => __('Text shadow', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text_shadow',
                'selector' => '{{WRAPPER}} .addonext-text-editor',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_3d',
            [
                'label' => __('3D text', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_3d',
            [
                'label'        => __('Enable 3D', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => '',
            ]
        );

        $this->add_control(
            'text_3d_depth',
            [
                'label'     => __('Depth', 'addonext'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => ['px' => ['min' => 1, 'max' => 15]],
                'default'   => ['unit' => 'px', 'size' => 1],
                'condition' => ['text_3d' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-text-editor--3d' => '--addonext-te-3d-depth: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'text_3d_color',
            [
                'label'     => __('Shadow color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.3)',
                'condition' => ['text_3d' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-text-editor--3d' => '--addonext-te-3d-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_animation',
            [
                'label' => __('Animation', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'entrance_animation',
            [
                'label'   => __('Entrance animation', 'addonext'),
                'type'    => Controls_Manager::ANIMATION,
                'default' => '',
            ]
        );

        $this->add_control(
            'animation_duration',
            [
                'label'     => __('Duration', 'addonext'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'normal',
                'options'   => [
                    'slow'   => __('Slow', 'addonext'),
                    'normal' => __('Normal', 'addonext'),
                    'fast'   => __('Fast', 'addonext'),
                ],
                'condition' => ['entrance_animation!' => ''],
            ]
        );

        $this->add_control(
            'animation_delay',
            [
                'label'     => __('Delay (ms)', 'addonext'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 3000,
                'step'     => 100,
                'condition' => ['entrance_animation!' => ''],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label'   => __('Hover animation', 'addonext'),
                'type'    => Controls_Manager::HOVER_ANIMATION,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['entrance_animation'])) {
            $this->add_render_attribute('_wrapper', 'class', 'elementor-invisible elementor-animation-' . esc_attr($settings['entrance_animation']));
            if (!empty($settings['animation_duration'])) {
                $this->add_render_attribute('_wrapper', 'data-animation-duration', esc_attr($settings['animation_duration']));
            }
            if (!empty($settings['animation_delay'])) {
                $delay = (int) $settings['animation_delay'];
                $this->add_render_attribute('_wrapper', 'style', 'animation-delay: ' . $delay . 'ms');
            }
        }
        if (!empty($settings['hover_animation'])) {
            $this->add_render_attribute('_wrapper', 'class', 'elementor-animation-' . esc_attr($settings['hover_animation']));
        }
        $template_path = ADDONEXT_DIR . 'templates/text-editor/text-editor.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        if (settings.entrance_animation) {
            view.addRenderAttribute('_wrapper', 'class', 'elementor-invisible elementor-animation-' + settings.entrance_animation);
            if (settings.animation_duration) view.addRenderAttribute('_wrapper', 'data-animation-duration', settings.animation_duration);
            if (settings.animation_delay) view.addRenderAttribute('_wrapper', 'data-animation-delay', settings.animation_delay);
        }
        if (settings.hover_animation) view.addRenderAttribute('_wrapper', 'class', 'elementor-animation-' + settings.hover_animation);
        var innerClass = 'addonext-text-editor';
        if (settings.drop_cap === 'yes') innerClass += ' addonext-text-editor--drop-cap';
        if (settings.use_gradient === 'yes') innerClass += ' addonext-text-editor--gradient';
        if (settings.text_3d === 'yes') innerClass += ' addonext-text-editor--3d';
        view.addRenderAttribute('editor', 'class', innerClass);
        view.addInlineEditingAttributes('editor', 'advanced');
        #>
        <div {{{ view.getRenderAttributeString('editor') }}}>{{{ settings.editor }}}</div>
        <?php
    }
}
