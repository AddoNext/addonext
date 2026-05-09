<?php
namespace Elementrify\Widgets\ModalPopup;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ModalPopup extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-lightbox elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-modal-popup';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Modal Popup', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Button-triggered popup with title, image, content, countdown and overlay.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-modal-popup'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-modal-popup'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-modal-popup',
            ELEMENTRIFY_URL . 'assets/css/modal-popup.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-modal-popup',
            ELEMENTRIFY_URL . 'assets/js/modal-popup.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_button',
            [
                'label' => __('Button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'trigger_text',
            [
                'label'   => __('Button text', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Open popup', 'elementrify'),
            ]
        );

        $this->add_control(
            'button_variant',
            [
                'label'   => __('Button variant', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => [
                    'primary'   => __('Primary', 'elementrify'),
                    'secondary' => __('Secondary', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label'   => __('Button size', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'md',
                'options' => [
                    'xs' => __('Extra Small', 'elementrify'),
                    'sm' => __('Small', 'elementrify'),
                    'md' => __('Medium', 'elementrify'),
                    'lg' => __('Large', 'elementrify'),
                    'xl' => __('Extra Large', 'elementrify'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_popup_content',
            [
                'label' => __('Popup content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'popup_title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Popup title', 'elementrify'),
            ]
        );

        $this->add_control(
            'popup_image',
            [
                'label' => __('Image', 'elementrify'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'popup_content',
            [
                'label'   => __('Content', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Add your paragraph or text content here.', 'elementrify'),
                'rows'    => 5,
            ]
        );

        $this->add_control(
            'countdown_seconds',
            [
                'label'   => __('Auto-close countdown (seconds)', 'elementrify'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'max'     => 300,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'close_on_overlay_click',
            [
                'label'        => __('Close on overlay click', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .elementrify-modal .elementrify-button',
                'fields_options' => [
                    'font_weight' => ['default' => '400'],
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .elementrify-modal .elementrify-button' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .elementrify-modal .elementrify-button',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#7B4397'],
                    'color_b'    => ['default' => '#B054BD'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit' => 'px',
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                ],
                'selectors'  => ['{{WRAPPER}} .elementrify-modal .elementrify-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors'  => ['{{WRAPPER}} .elementrify-modal .elementrify-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => __('Overlay', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_background',
            [
                'label'     => __('Background color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.5)',
                'selectors' => ['{{WRAPPER}} .elementrify-modal__overlay' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'overlay_blur',
            [
                'label'        => __('Blur background', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'overlay_blur_amount',
            [
                'label'     => __('Blur amount (px)', 'elementrify'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => ['px' => ['min' => 0, 'max' => 30]],
                'default'   => ['size' => 8],
                'condition' => ['overlay_blur' => 'yes'],
                'selectors' => ['{{WRAPPER}} .elementrify-modal__overlay.is-blur' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_popup',
            [
                'label' => __('Popup box', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'popup_background',
            [
                'label'     => __('Background color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => ['{{WRAPPER}} .elementrify-modal__content' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'popup_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => ['{{WRAPPER}} .elementrify-modal__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_control(
            'popup_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => ['top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24],
                'selectors'  => ['{{WRAPPER}} .elementrify-modal__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'popup_max_width',
            [
                'label'      => __('Max width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range'      => ['px' => ['min' => 200, 'max' => 1200]],
                'default'    => ['size' => 480, 'unit' => 'px'],
                'selectors'  => ['{{WRAPPER}} .elementrify-modal__content' => 'max-width: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_close',
            [
                'label' => __('Close button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'close_button_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => ['{{WRAPPER}} .elementrify-modal__close' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'close_button_size',
            [
                'label'     => __('Size (px)', 'elementrify'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => ['px' => ['min' => 16, 'max' => 56]],
                'default'   => ['size' => 32],
                'selectors' => ['{{WRAPPER}} .elementrify-modal__close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Popup title', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .elementrify-modal__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .elementrify-modal__title' => 'color: {{VALUE}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_body',
            [
                'label' => __('Popup content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'body_typography',
                'selector' => '{{WRAPPER}} .elementrify-modal__body',
            ]
        );

        $this->add_control(
            'body_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .elementrify-modal__body' => 'color: {{VALUE}};'],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/modal-popup/modal-popup.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var overlayClass = 'elementrify-modal__overlay';
        if (settings.overlay_blur === 'yes') overlayClass += ' is-blur';
        view.addRenderAttribute('overlay', 'class', overlayClass);
        view.addRenderAttribute('overlay', 'data-countdown', settings.countdown_seconds || 0);
        view.addRenderAttribute('overlay', 'data-close-on-overlay', settings.close_on_overlay_click === 'yes' ? '1' : '0');
        #>
        <#
        var variant = settings.button_variant || 'primary';
        var size = settings.button_size || 'md';
        var triggerClass = 'elementrify-button elementrify-button--' + variant + ' elementrify-button--' + size + ' elementrify-modal__trigger';
        #>
        <div class="elementrify-modal">
            <div class="elementrify-button-wrapper">
                <button type="button" class="{{ triggerClass }}" role="button">
                    <span class="elementrify-button__content">
                        <span class="elementrify-button__text">{{{ settings.trigger_text }}}</span>
                    </span>
                </button>
            </div>
            <div {{{ view.getRenderAttributeString('overlay') }}}>
                <div class="elementrify-modal__content" role="dialog" aria-modal="true" aria-labelledby="elementrify-modal-title-{{ view.getID() }}">
                    <button type="button" class="elementrify-modal__close" aria-label="<?php echo esc_attr__('Close', 'elementrify'); ?>"><span class="elementrify-modal__close-icon" aria-hidden="true">&times;</span></button>
                    <# if (settings.popup_title) { #><h3 class="elementrify-modal__title" id="elementrify-modal-title-{{ view.getID() }}">{{{ settings.popup_title }}}</h3><# } #>
                    <# if (settings.popup_image && settings.popup_image.url) { #><div class="elementrify-modal__image"><img src="{{ settings.popup_image.url }}" alt=""></div><# } #>
                    <# if (settings.popup_content) { #><div class="elementrify-modal__body">{{{ settings.popup_content }}}</div><# } #>
                </div>
            </div>
        </div>
        <?php
    }
}
