<?php
namespace Elementrify\Widgets\FlipBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

defined('ABSPATH') || exit;

class FlipBox extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-flip-box elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-flip-box';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('3D Flip Box', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Interactive flip animations', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-flip-box'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-flip-box'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-flip-box',
            ELEMENTRIFY_URL . 'assets/css/flip-box.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_front',
            [
                'label' => __('Front', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'front_icon',
            [
                'label'   => __('Icon', 'elementrify'),
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
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('3D Design', 'elementrify'),
            ]
        );

        $this->add_control(
            'front_description',
            [
                'label'   => __('Description', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Hover to explore', 'elementrify'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_back',
            [
                'label' => __('Back', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'back_title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Back', 'elementrify'),
            ]
        );

        $this->add_control(
            'back_description',
            [
                'label'   => __('Description', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Add your back content here.', 'elementrify'),
            ]
        );

        $this->add_control(
            'back_button_text',
            [
                'label'   => __('Button text', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'back_button_url',
            [
                'label'       => __('Button URL', 'elementrify'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://example.com', 'elementrify'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_effect',
            [
                'label' => __('Hover effect', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label'   => __('Effect', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'flip-horizontal',
                'options' => [
                    'flip-horizontal' => __('Flip horizontal', 'elementrify'),
                    'flip-vertical'   => __('Flip vertical', 'elementrify'),
                    'flip-3d'         => __('Flip 3D (rotate Y)', 'elementrify'),
                    'tilt-3d'         => __('Tilt 3D', 'elementrify'),
                    'lift'            => __('Lift up', 'elementrify'),
                    'zoom'            => __('Zoom in', 'elementrify'),
                    'zoom-out'        => __('Zoom out', 'elementrify'),
                    'fade-in'         => __('Fade in', 'elementrify'),
                    'fade-out'        => __('Fade out', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'trigger',
            [
                'label'   => __('Animate on', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __('Hover', 'elementrify'),
                    'click' => __('Click', 'elementrify'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_box',
            [
                'label' => __('Box', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label'      => __('Height', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 120, 'max' => 500]],
                'default'    => ['size' => 220, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-flip-box' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 10],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-flip-box__inner'           => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-flip-box__front'           => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-flip-box__back'            => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_front',
            [
                'label' => __('Front', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'front_background',
                'types'          => ['classic', 'gradient'],
                'selector'       => '{{WRAPPER}} .elementrify-flip-box__front',
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
                'label'      => __('Icon size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 24, 'max' => 80]],
                'default'    => ['size' => 48],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-flip-box__front-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-flip-box__front-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'front_icon_color',
            [
                'label'     => __('Icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-flip-box__front-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-flip-box__front-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'front_title_typography',
                'selector' => '{{WRAPPER}} .elementrify-flip-box__front-title',
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
                'label'     => __('Title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-flip-box__front-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'front_desc_typography',
                'selector' => '{{WRAPPER}} .elementrify-flip-box__front-desc',
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
                'label'     => __('Description color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-flip-box__front-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_back',
            [
                'label' => __('Back', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'back_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementrify-flip-box__back',
                'fields_options' => [
                    'color' => ['default' => '#1e293b'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'back_title_typography',
                'selector' => '{{WRAPPER}} .elementrify-flip-box__back-title',
            ]
        );

        $this->add_control(
            'back_title_color',
            [
                'label'     => __('Title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-flip-box__back-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'back_desc_typography',
                'selector' => '{{WRAPPER}} .elementrify-flip-box__back-desc',
            ]
        );

        $this->add_control(
            'back_desc_color',
            [
                'label'     => __('Description color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e2e8f0',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-flip-box__back-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'back_button_heading',
            [
                'label'     => __('Button', 'elementrify'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'back_button_typography',
                'selector' => '{{WRAPPER}} .elementrify-flip-box__back-button',
            ]
        );

        $this->add_control(
            'back_button_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-flip-box__back-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'back_button_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-flip-box__back-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'back_button_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-flip-box__back-button' => 'border-radius: {{SIZE}}{{UNIT}};',
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
        $path = ELEMENTRIFY_DIR . 'templates/flip-box/flip-box.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        $svg_fallback = str_replace("'", "\\'", self::get_cube_icon_svg());
        ?>
        <#
        var effect = settings.hover_effect || 'flip-horizontal';
        var trigger = settings.trigger || 'hover';
        var frontIconHtml = '<?php echo $svg_fallback; ?>';
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
        <div class="elementrify-flip-box elementrify-flip-box--{{ effect }} elementrify-flip-box--trigger-{{ trigger }}">
            <div class="elementrify-flip-box__inner">
                <div class="elementrify-flip-box__front">
                    <div class="elementrify-flip-box__front-content">
                        <div class="elementrify-flip-box__front-icon">{{{ frontIconHtml }}}</div>
                        <# if (settings.front_title) { #><h3 class="elementrify-flip-box__front-title">{{{ settings.front_title }}}</h3><# } #>
                        <# if (settings.front_description) { #><p class="elementrify-flip-box__front-desc">{{{ settings.front_description }}}</p><# } #>
                    </div>
                </div>
                <div class="elementrify-flip-box__back">
                    <div class="elementrify-flip-box__back-content">
                        <# if (settings.back_title) { #><h3 class="elementrify-flip-box__back-title">{{{ settings.back_title }}}</h3><# } #>
                        <# if (settings.back_description) { #><p class="elementrify-flip-box__back-desc">{{{ settings.back_description }}}</p><# } #>
                        <# if (settings.back_button_text) { #><a href="{{ backUrl }}"{{ backBtnAttrs }} class="elementrify-flip-box__back-button">{{{ settings.back_button_text }}}</a><# } #>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
