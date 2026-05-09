<?php
namespace Elementrify\Widgets\ImageBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

defined('ABSPATH') || exit;

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
        return __('Image with filters, effects, animations and hover', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#059669';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-image-box'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-image-box'];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('elementrify-image-box');
        wp_enqueue_script('elementrify-image-box');
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
                'label' => __('Image', 'elementrify'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image_size',
                'default'   => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => __('Width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 1200], '%' => ['min' => 0, 'max' => 100]],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-box__image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => __('Height', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 800]],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-box__image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_fit',
            [
                'label'   => __('Object fit', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover'   => __('Cover', 'elementrify'),
                    'contain' => __('Contain', 'elementrify'),
                    'fill'    => __('Fill', 'elementrify'),
                    'none'    => __('None', 'elementrify'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label'   => __('Image alignment', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [
                        'title' => __('Left', 'elementrify'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'elementrify'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'elementrify'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'      => 'left',
                'prefix_class' => 'elementrify-ib-image-align%s-',
                'selectors'    => [
                    '{{WRAPPER}} .elementrify-image-box__image' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => __('Show title', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'     => __('Title', 'elementrify'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Image Box Title', 'elementrify'),
                'dynamic'   => ['active' => true],
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label'        => __('Show description', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'description',
            [
                'label'     => __('Description', 'elementrify'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => __('Description text here.', 'elementrify'),
                'dynamic'   => ['active' => true],
                'condition' => ['show_description' => 'yes'],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'   => __('Link', 'elementrify'),
                'type'    => Controls_Manager::URL,
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Image', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_bw_mode',
            [
                'label'        => __('Black & white', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => '',
            ]
        );

        $this->add_control(
            'image_grayscale',
            [
                'label'     => __('Grayscale (%)', 'elementrify'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => ['px' => ['min' => 0, 'max' => 100]],
                'default'   => ['unit' => '%', 'size' => 0],
                'condition' => ['image_bw_mode' => ''],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image' => '--elementrify-ib-grayscale: {{SIZE}}%;',
                ],
            ]
        );

        $this->add_control(
            'image_sepia',
            [
                'label'   => __('Sepia (%)', 'elementrify'),
                'type'    => Controls_Manager::SLIDER,
                'range'   => ['px' => ['min' => 0, 'max' => 100]],
                'default' => ['unit' => '%', 'size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image' => '--elementrify-ib-sepia: {{SIZE}}%;',
                ],
            ]
        );

        $this->add_control(
            'image_blur',
            [
                'label'   => __('Blur (px)', 'elementrify'),
                'type'    => Controls_Manager::SLIDER,
                'range'   => ['px' => ['min' => 0, 'max' => 20]],
                'default' => ['unit' => 'px', 'size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image' => '--elementrify-ib-blur: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'image_brightness',
            [
                'label'   => __('Brightness (%)', 'elementrify'),
                'type'    => Controls_Manager::SLIDER,
                'range'   => ['px' => ['min' => 0, 'max' => 200]],
                'default' => ['unit' => '%', 'size' => 100],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image' => '--elementrify-ib-brightness: {{SIZE}}%;',
                ],
            ]
        );

        $this->add_control(
            'image_contrast',
            [
                'label'   => __('Contrast (%)', 'elementrify'),
                'type'    => Controls_Manager::SLIDER,
                'range'   => ['px' => ['min' => 0, 'max' => 200]],
                'default' => ['unit' => '%', 'size' => 100],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image' => '--elementrify-ib-contrast: {{SIZE}}%;',
                ],
            ]
        );

        $this->add_control(
            'image_saturate',
            [
                'label'   => __('Saturate (%)', 'elementrify'),
                'type'    => Controls_Manager::SLIDER,
                'range'   => ['px' => ['min' => 0, 'max' => 200]],
                'default' => ['unit' => '%', 'size' => 100],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image' => '--elementrify-ib-saturate: {{SIZE}}%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-box__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-image-box__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'image_border',
                'selector' => '{{WRAPPER}} .elementrify-image-box__image',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_shadow',
                'selector' => '{{WRAPPER}} .elementrify-image-box__image',
            ]
        );

        $this->add_control(
            'image_transition_duration',
            [
                'label'   => __('Transition (s)', 'elementrify'),
                'type'    => Controls_Manager::SLIDER,
                'range'   => ['px' => ['min' => 0.1, 'max' => 2, 'step' => 0.1]],
                'default' => ['unit' => 'px', 'size' => 0.35],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__image img' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image_hover',
            [
                'label' => __('Image hover', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label'   => __('Hover effect', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'     => __('None', 'elementrify'),
                    'zoom'     => __('Zoom in', 'elementrify'),
                    'grow'     => __('Grow', 'elementrify'),
                    'lift'     => __('Lift', 'elementrify'),
                    'tilt'     => __('Tilt', 'elementrify'),
                    'tilt-3d'  => __('3D tilt (follow mouse)', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'hover_grayscale_off',
            [
                'label'        => __('Remove grayscale on hover', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => '',
            ]
        );

        $this->add_control(
            'hover_overlay_color',
            [
                'label'   => __('Overlay color on hover', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementrify-image-box--hover-overlay .elementrify-image-box__image::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'image_shadow_hover',
                'selector'  => '{{WRAPPER}} .elementrify-image-box__image:hover',
                'condition' => ['hover_effect!' => 'none'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_animation',
            [
                'label' => __('Animation', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'entrance_animation',
            [
                'label'   => __('Entrance animation', 'elementrify'),
                'type'    => Controls_Manager::ANIMATION,
                'default' => '',
            ]
        );

        $this->add_control(
            'animation_duration',
            [
                'label'     => __('Duration', 'elementrify'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'normal',
                'options'   => [
                    'slow'   => __('Slow', 'elementrify'),
                    'normal' => __('Normal', 'elementrify'),
                    'fast'   => __('Fast', 'elementrify'),
                ],
                'condition' => ['entrance_animation!' => ''],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'elementrify'),
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
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => __('Description', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .elementrify-image-box__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-box__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $wrapper_class = 'elementrify-image-box';
        if (!empty($settings['image_bw_mode'])) {
            $wrapper_class .= ' elementrify-image-box--bw';
        }
        if (!empty($settings['hover_effect']) && $settings['hover_effect'] !== 'none') {
            $wrapper_class .= ' elementrify-image-box--hover-' . esc_attr($settings['hover_effect']);
        }
        if (!empty($settings['hover_grayscale_off'])) {
            $wrapper_class .= ' elementrify-image-box--hover-grayscale-off';
        }
        if (!empty($settings['hover_overlay_color'])) {
            $wrapper_class .= ' elementrify-image-box--hover-overlay';
        }
        if (!empty($settings['entrance_animation'])) {
            $wrapper_class .= ' elementor-invisible elementor-animation-' . esc_attr($settings['entrance_animation']);
            if (!empty($settings['animation_duration'])) {
                $this->add_render_attribute('_wrapper', 'data-animation-duration', esc_attr($settings['animation_duration']));
            }
        }
        $this->add_render_attribute('wrapper', 'class', $wrapper_class);
        $template_path = ELEMENTRIFY_DIR . 'templates/image-box/image-box.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var wrapperClass = 'elementrify-image-box';
        if (settings.image_bw_mode === 'yes') wrapperClass += ' elementrify-image-box--bw';
        if (settings.hover_effect && settings.hover_effect !== 'none') wrapperClass += ' elementrify-image-box--hover-' + settings.hover_effect;
        if (settings.hover_grayscale_off === 'yes') wrapperClass += ' elementrify-image-box--hover-grayscale-off';
        if (settings.hover_overlay_color) wrapperClass += ' elementrify-image-box--hover-overlay';
        if (settings.entrance_animation) {
            wrapperClass += ' elementor-invisible elementor-animation-' + settings.entrance_animation;
            if (settings.animation_duration) view.addRenderAttribute('_wrapper', 'data-animation-duration', settings.animation_duration);
        }
        view.addRenderAttribute('wrapper', 'class', wrapperClass);
        view.addInlineEditingAttributes('title', 'none');
        view.addInlineEditingAttributes('description', 'none');
        var hasLink = settings.link && settings.link.url;
        var showTitle = settings.show_title !== 'no';
        var showDescription = settings.show_description !== 'no';
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <# if (settings.image && (settings.image.url || settings.image.id)) { #>
                <div class="elementrify-image-box__image">
                    <# if (hasLink) { #><a href="{{ settings.link.url }}"><# } #>
                    <img src="{{ settings.image.url }}" alt="{{ settings.image.alt || settings.title }}">
                    <# if (hasLink) { #></a><# } #>
                </div>
            <# } #>
            <div class="elementrify-image-box__content">
                <# if (showTitle && settings.title) { #>
                    <# if (hasLink) { #><a href="{{ settings.link.url }}"><# } #>
                    <h3 class="elementrify-image-box__title" {{{ view.getRenderAttributeString('title') }}}>{{{ settings.title }}}</h3>
                    <# if (hasLink) { #></a><# } #>
                <# } #>
                <# if (showDescription && settings.description) { #>
                    <p class="elementrify-image-box__description" {{{ view.getRenderAttributeString('description') }}}>{{{ settings.description }}}</p>
                <# } #>
            </div>
        </div>
        <?php
    }
}
