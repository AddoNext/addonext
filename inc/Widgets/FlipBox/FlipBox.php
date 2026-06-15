<?php
namespace Veloria\Widgets\FlipBox;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

defined('ABSPATH') || exit;

class FlipBox extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-flip-box veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-flip-box';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('3D Flip Box', 'veloria');
    }

    public function get_description(): string
    {
        return __('Interactive flip animations', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    public function get_style_depends(): array
    {
        return ['veloria-flip-box'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-flip-box'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-flip-box',
            VELORIA_URL . 'assets/css/flip-box.css',
            [],
            VELORIA_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_front',
            [
                'label' => __('Front', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'front_icon',
            [
                'label'   => __('Icon', 'veloria'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'eicon-cube',
                    'library' => 'eicons',
                ],
            ]
        );

        $this->add_control(
            'front_title',
            [
                'label'   => __('Title', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('3D Design', 'veloria'),
            ]
        );

        $this->add_control(
            'front_description',
            [
                'label'   => __('Description', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Hover to explore', 'veloria'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_back',
            [
                'label' => __('Back', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'back_title',
            [
                'label'   => __('Title', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Back', 'veloria'),
            ]
        );

        $this->add_control(
            'back_description',
            [
                'label'   => __('Description', 'veloria'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Add your back content here.', 'veloria'),
            ]
        );

        $this->add_control(
            'back_button_text',
            [
                'label'   => __('Button text', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'back_button_url',
            [
                'label'       => __('Button URL', 'veloria'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://example.com', 'veloria'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_effect',
            [
                'label' => __('Hover effect', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label'   => __('Effect', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'flip-horizontal',
                'options' => [
                    'flip-horizontal' => __('Flip horizontal', 'veloria'),
                    'flip-vertical'   => __('Flip vertical', 'veloria'),
                    'flip-3d'         => __('Flip 3D (rotate Y)', 'veloria'),
                    'tilt-3d'         => __('Tilt 3D', 'veloria'),
                    'lift'            => __('Lift up', 'veloria'),
                    'zoom'            => __('Zoom in', 'veloria'),
                    'zoom-out'        => __('Zoom out', 'veloria'),
                    'fade-in'         => __('Fade in', 'veloria'),
                    'fade-out'        => __('Fade out', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'trigger',
            [
                'label'   => __('Animate on', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __('Hover', 'veloria'),
                    'click' => __('Click', 'veloria'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_box',
            [
                'label' => __('Box', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label'      => __('Height', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 120, 'max' => 500]],
                'default'    => ['size' => 220, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-flip-box' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 10],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-flip-box__inner'           => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-flip-box__front'           => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-flip-box__back'            => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_front',
            [
                'label' => __('Front', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'front_background',
                'types'          => ['classic', 'gradient'],
                'selector'       => '{{WRAPPER}} .veloria-flip-box__front',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#4a8efb'],
                    'color_b'    => ['default' => '#8e52f7'],
                ],
            ]
        );

        $this->add_control(
            'front_icon_size',
            [
                'label'      => __('Icon size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 24, 'max' => 80]],
                'default'    => ['size' => 48],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-flip-box__front-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-flip-box__front-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'front_icon_color',
            [
                'label'     => __('Icon color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-flip-box__front-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-flip-box__front-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'front_title_typography',
                'selector' => '{{WRAPPER}} .veloria-flip-box__front-title',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 22]],
                ],
            ]
        );

        $this->add_control(
            'front_title_color',
            [
                'label'     => __('Title color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-flip-box__front-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'front_desc_typography',
                'selector' => '{{WRAPPER}} .veloria-flip-box__front-desc',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '400'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control(
            'front_desc_color',
            [
                'label'     => __('Description color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-flip-box__front-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_back',
            [
                'label' => __('Back', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'back_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .veloria-flip-box__back',
                'fields_options' => [
                    'color' => ['default' => '#1e293b'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'back_title_typography',
                'selector' => '{{WRAPPER}} .veloria-flip-box__back-title',
            ]
        );

        $this->add_control(
            'back_title_color',
            [
                'label'     => __('Title color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-flip-box__back-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'back_desc_typography',
                'selector' => '{{WRAPPER}} .veloria-flip-box__back-desc',
            ]
        );

        $this->add_control(
            'back_desc_color',
            [
                'label'     => __('Description color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e2e8f0',
                'selectors' => [
                    '{{WRAPPER}} .veloria-flip-box__back-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'back_button_heading',
            [
                'label'     => __('Button', 'veloria'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'back_button_typography',
                'selector' => '{{WRAPPER}} .veloria-flip-box__back-button',
            ]
        );

        $this->add_control(
            'back_button_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .veloria-flip-box__back-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'back_button_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .veloria-flip-box__back-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'back_button_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-flip-box__back-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public static function get_cube_icon_svg(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48" aria-hidden="true"><path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.18l6.9 3.45L12 11.09 5.1 7.63 12 4.18zM4 8.82l7 3.5v7.36l-7-3.5V8.82zm9 10.86v-7.36l7-3.5v7.36l-7 3.5z"/></svg>';
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/flip-box/flip-box.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var effect = settings.hover_effect || 'flip-horizontal';
        var trigger = settings.trigger || 'hover';
        var frontIconHtml = <?php echo wp_json_encode(self::get_cube_icon_svg()); ?>;
        var isDefaultCube = settings.front_icon && settings.front_icon.value === 'eicon-cube' && settings.front_icon.library === 'eicons';
        if (settings.front_icon && settings.front_icon.value && !isDefaultCube) {
            var iconResult = elementor.helpers.renderIcon(view, settings.front_icon, { 'aria-hidden': 'true' }, 'i', 'object');
            if (iconResult && iconResult.value && iconResult.value.trim()) {
                frontIconHtml = iconResult.value;
            }
        }
        var backUrl = settings.back_button_url && settings.back_button_url.url ? settings.back_button_url.url : '#';
        var backBtnAttrs = '';
        if (settings.back_button_url && settings.back_button_url.is_external) backBtnAttrs += ' target="_blank"';
        if (settings.back_button_url && settings.back_button_url.nofollow) backBtnAttrs += ' rel="nofollow"';
        #>
        <div class="veloria-flip-box veloria-flip-box--{{ effect }} veloria-flip-box--trigger-{{ trigger }}">
            <div class="veloria-flip-box__inner">
                <div class="veloria-flip-box__front">
                    <div class="veloria-flip-box__front-content">
                        <div class="veloria-flip-box__front-icon">{{{ frontIconHtml }}}</div>
                        <# if (settings.front_title) { #><h3 class="veloria-flip-box__front-title">{{{ settings.front_title }}}</h3><# } #>
                        <# if (settings.front_description) { #><p class="veloria-flip-box__front-desc">{{{ settings.front_description }}}</p><# } #>
                    </div>
                </div>
                <div class="veloria-flip-box__back">
                    <div class="veloria-flip-box__back-content">
                        <# if (settings.back_title) { #><h3 class="veloria-flip-box__back-title">{{{ settings.back_title }}}</h3><# } #>
                        <# if (settings.back_description) { #><p class="veloria-flip-box__back-desc">{{{ settings.back_description }}}</p><# } #>
                        <# if (settings.back_button_text) { #><a href="{{ backUrl }}"{{ backBtnAttrs }} class="veloria-flip-box__back-button">{{{ settings.back_button_text }}}</a><# } #>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
