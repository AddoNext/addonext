<?php
namespace Addonext\Widgets\ModalPopup;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ModalPopup extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-lightbox addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-modal-popup';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Modal Popup', 'addonext');
    }

    public function get_description(): string
    {
        return __('Button-triggered popup with title, image, content, countdown and overlay.', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    public function get_style_depends(): array
    {
        return ['addonext-modal-popup'];
    }

    public function get_script_depends(): array
    {
        return ['addonext-modal-popup'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-modal-popup',
            ADDONEXT_URL . 'assets/css/modal-popup.css',
            [],
            ADDONEXT_VERSION
        );
        wp_register_script(
            'addonext-modal-popup',
            ADDONEXT_URL . 'assets/js/modal-popup.js',
            [],
            ADDONEXT_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_button',
            [
                'label' => __('Button', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'trigger_text',
            [
                'label'   => __('Button text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Open popup', 'addonext'),
            ]
        );

        $this->add_control(
            'button_variant',
            [
                'label'   => __('Button variant', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => [
                    'primary'   => __('Primary', 'addonext'),
                    'secondary' => __('Secondary', 'addonext'),
                ],
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label'   => __('Button size', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'md',
                'options' => [
                    'xs' => __('Extra Small', 'addonext'),
                    'sm' => __('Small', 'addonext'),
                    'md' => __('Medium', 'addonext'),
                    'lg' => __('Large', 'addonext'),
                    'xl' => __('Extra Large', 'addonext'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_popup_content',
            [
                'label' => __('Popup content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'popup_title',
            [
                'label'   => __('Title', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Popup title', 'addonext'),
            ]
        );

        $this->add_control(
            'popup_image',
            [
                'label' => __('Image', 'addonext'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'popup_content',
            [
                'label'   => __('Content', 'addonext'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Add your paragraph or text content here.', 'addonext'),
                'rows'    => 5,
            ]
        );

        $this->add_control(
            'countdown_seconds',
            [
                'label'   => __('Auto-close countdown (seconds)', 'addonext'),
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
                'label'        => __('Close on overlay click', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .addonext-modal .addonext-button',
                'fields_options' => [
                    'font_weight' => ['default' => '400'],
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .addonext-modal .addonext-button' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .addonext-modal .addonext-button',
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
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit' => 'px',
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                ],
                'selectors'  => ['{{WRAPPER}} .addonext-modal .addonext-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors'  => ['{{WRAPPER}} .addonext-modal .addonext-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => __('Overlay', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_background',
            [
                'label'     => __('Background color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.5)',
                'selectors' => ['{{WRAPPER}} .addonext-modal__overlay' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'overlay_blur',
            [
                'label'        => __('Blur background', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'overlay_blur_amount',
            [
                'label'     => __('Blur amount (px)', 'addonext'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => ['px' => ['min' => 0, 'max' => 30]],
                'default'   => ['size' => 8],
                'condition' => ['overlay_blur' => 'yes'],
                'selectors' => ['{{WRAPPER}} .addonext-modal__overlay.is-blur' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_popup',
            [
                'label' => __('Popup box', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'popup_background',
            [
                'label'     => __('Background color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => ['{{WRAPPER}} .addonext-modal__content' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'popup_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => ['{{WRAPPER}} .addonext-modal__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_control(
            'popup_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => ['top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24],
                'selectors'  => ['{{WRAPPER}} .addonext-modal__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'popup_max_width',
            [
                'label'      => __('Max width', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range'      => ['px' => ['min' => 200, 'max' => 1200]],
                'default'    => ['size' => 480, 'unit' => 'px'],
                'selectors'  => ['{{WRAPPER}} .addonext-modal__content' => 'max-width: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_close',
            [
                'label' => __('Close button', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'close_button_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => ['{{WRAPPER}} .addonext-modal__close' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'close_button_size',
            [
                'label'     => __('Size (px)', 'addonext'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => ['px' => ['min' => 16, 'max' => 56]],
                'default'   => ['size' => 32],
                'selectors' => ['{{WRAPPER}} .addonext-modal__close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Popup title', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .addonext-modal__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .addonext-modal__title' => 'color: {{VALUE}};'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_body',
            [
                'label' => __('Popup content', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'body_typography',
                'selector' => '{{WRAPPER}} .addonext-modal__body',
            ]
        );

        $this->add_control(
            'body_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .addonext-modal__body' => 'color: {{VALUE}};'],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/modal-popup/modal-popup.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var overlayClass = 'addonext-modal__overlay';
        if (settings.overlay_blur === 'yes') overlayClass += ' is-blur';
        view.addRenderAttribute('overlay', 'class', overlayClass);
        view.addRenderAttribute('overlay', 'data-countdown', settings.countdown_seconds || 0);
        view.addRenderAttribute('overlay', 'data-close-on-overlay', settings.close_on_overlay_click === 'yes' ? '1' : '0');
        #>
        <#
        var variant = settings.button_variant || 'primary';
        var size = settings.button_size || 'md';
        var triggerClass = 'addonext-button addonext-button--' + variant + ' addonext-button--' + size + ' addonext-modal__trigger';
        #>
        <div class="addonext-modal">
            <div class="addonext-button-wrapper">
                <button type="button" class="{{ triggerClass }}" role="button">
                    <span class="addonext-button__content">
                        <span class="addonext-button__text">{{{ settings.trigger_text }}}</span>
                    </span>
                </button>
            </div>
            <div {{{ view.getRenderAttributeString('overlay') }}}>
                <div class="addonext-modal__content" role="dialog" aria-modal="true" aria-labelledby="addonext-modal-title-{{ view.getID() }}">
                    <button type="button" class="addonext-modal__close" aria-label="<?php echo esc_attr__('Close', 'addonext'); ?>"><span class="addonext-modal__close-icon" aria-hidden="true">&times;</span></button>
                    <# if (settings.popup_title) { #><h3 class="addonext-modal__title" id="addonext-modal-title-{{ view.getID() }}">{{{ settings.popup_title }}}</h3><# } #>
                    <# if (settings.popup_image && settings.popup_image.url) { #><div class="addonext-modal__image"><img src="{{ settings.popup_image.url }}" alt=""></div><# } #>
                    <# if (settings.popup_content) { #><div class="addonext-modal__body">{{{ settings.popup_content }}}</div><# } #>
                </div>
            </div>
        </div>
        <?php
    }
}
