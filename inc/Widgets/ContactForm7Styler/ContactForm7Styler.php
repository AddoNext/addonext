<?php
namespace Elementrify\Widgets\ContactForm7Styler;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

/**
 * Style Contact Form 7 output inside Elementor with scoped controls.
 */
class ContactForm7Styler extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-mail elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-contact-form-7-styler';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Contact Form 7 Styler', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Select a CF7 form and tune layout, fields, button, and messages.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-contact-form-7-styler'];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    /**
     * Ensures styler CSS in the editor canvas.
     */
    public function enqueue_preview_assets(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('elementrify-contact-form-7-styler');
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-contact-form-7-styler',
            ELEMENTRIFY_URL . 'assets/css/contact-form-7-styler.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    /**
     * @return array<string, string>
     */
    public static function get_cf7_form_options(): array
    {
        $choices = ['__none__' => __('— Select form —', 'elementrify')];
        if (!post_type_exists('wpcf7_contact_form')) {
            return $choices;
        }
        $forms = get_posts([
            'post_type'      => 'wpcf7_contact_form',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);
        if (!is_array($forms)) {
            return $choices;
        }
        foreach ($forms as $form) {
            $choices[(string) $form->ID] = $form->post_title;
        }

        return $choices;
    }

    public static function is_cf7_active(): bool
    {
        return post_type_exists('wpcf7_contact_form') && function_exists('wpcf7_contact_form');
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_form',
            [
                'label' => __('Form', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cf7_form_id',
            [
                'label'       => __('Contact Form 7', 'elementrify'),
                'type'        => Controls_Manager::SELECT,
                'options'     => self::get_cf7_form_options(),
                'default'     => '__none__',
                'description' => self::is_cf7_active()
                    ? ''
                    : __('Install and activate the Contact Form 7 plugin to list forms.', 'elementrify'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_wrapper',
            [
                'label' => __('Wrapper', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrapper_align',
            [
                'label'     => __('Alignment', 'elementrify'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => ['title' => __('Start', 'elementrify'), 'icon' => 'eicon-h-align-left'],
                    'center'     => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-h-align-center'],
                    'flex-end'   => ['title' => __('End', 'elementrify'), 'icon' => 'eicon-h-align-right'],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler' => 'display: flex; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_max_width',
            [
                'label'      => __('Max width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 280, 'max' => 1200]],
                'default'    => ['size' => 640, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner' => 'width: 100%; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'wrapper_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner',
            ]
        );

        $this->add_responsive_control(
            'wrapper_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_shadow',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Field layout', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'field_stack_gap',
            [
                'label'      => __('Space between rows', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48]],
                'default'    => ['size' => 16, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form' => 'display: flex; flex-direction: column; gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_position',
            [
                'label'   => __('Label position', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => __('Above field', 'elementrify'),
                    'inline' => __('Inline (wide screens)', 'elementrify'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_labels',
            [
                'label' => __('Labels', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_spacing',
            [
                'label'      => __('Space below label', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 6, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_fields',
            [
                'label' => __('Fields', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $field_selectors = '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-text, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-email, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-url, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-tel, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-number, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-date, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form textarea.wpcf7-textarea, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form select.wpcf7-select, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-file';

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'field_typography',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-form-control, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form textarea.wpcf7-form-control, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form select.wpcf7-form-control',
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    $field_selectors => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    $field_selectors => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'field_border',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-form-control:not(.wpcf7-submit), {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form textarea.wpcf7-form-control, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form select.wpcf7-form-control',
            ]
        );

        $this->add_responsive_control(
            'field_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px', 'isLinked' => true],
                'selectors'  => [
                    $field_selectors => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => ['top' => '10', 'right' => '14', 'bottom' => '10', 'left' => '14', 'unit' => 'px', 'isLinked' => false],
                'selectors'  => [
                    $field_selectors => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_min_height',
            [
                'label'      => __('Textarea min height', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 80, 'max' => 400]],
                'default'    => ['size' => 140, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form textarea.wpcf7-textarea' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'field_focus_border',
            [
                'label'     => __('Focus border color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-form-control:focus:not(.wpcf7-submit), {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form textarea.wpcf7-form-control:focus, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form select.wpcf7-form-control:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'field_focus_shadow',
                'label'    => __('Focus shadow', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-form-control:focus:not(.wpcf7-submit), {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form textarea.wpcf7-form-control:focus, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form select.wpcf7-form-control:focus',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_placeholder',
            [
                'label' => __('Placeholder', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'placeholder_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#9ca3af',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form ::placeholder' => 'color: {{VALUE}}; opacity: 1;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_submit',
            [
                'label' => __('Submit button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'submit_align',
            [
                'label'     => __('Alignment', 'elementrify'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => ['title' => __('Start', 'elementrify'), 'icon' => 'eicon-h-align-left'],
                    'center'     => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-h-align-center'],
                    'flex-end'   => ['title' => __('End', 'elementrify'), 'icon' => 'eicon-h-align-right'],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form .wpcf7-submit-wrap' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form p:has(input.wpcf7-submit)' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_width',
            [
                'label'      => __('Button width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 80, 'max' => 400],
                    '%'  => ['min' => 10, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
                ],
            ]
        );

        $this->start_controls_tabs('submit_tabs');

        $this->start_controls_tab(
            'submit_tab_normal',
            [
                'label' => __('Normal', 'elementrify'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'submit_typography',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit',
            ]
        );

        $this->add_control(
            'submit_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'submit_border',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit',
            ]
        );

        $this->add_responsive_control(
            'submit_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px', 'isLinked' => true],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => ['top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24', 'unit' => 'px', 'isLinked' => false],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'submit_shadow',
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'submit_tab_hover',
            [
                'label' => __('Hover', 'elementrify'),
            ]
        );

        $this->add_control(
            'submit_hover_text',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_hover_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'submit_hover_shadow',
                'label'    => __('Shadow', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-form input.wpcf7-submit:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_checkbox',
            [
                'label' => __('Checkboxes & consent', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'list_item_gap',
            [
                'label'      => __('Space between options', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-list-item' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'acceptance_typography',
                'label'    => __('Acceptance label typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-acceptance label, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-checkbox .wpcf7-list-item-label, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-radio .wpcf7-list-item-label',
            ]
        );

        $this->add_control(
            'acceptance_label_color',
            [
                'label'     => __('Option text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-acceptance label, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-checkbox .wpcf7-list-item-label, {{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-radio .wpcf7-list-item-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_messages',
            [
                'label' => __('Messages', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'response_typography',
                'label'    => __('Response area typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-response-output',
            ]
        );

        $this->add_control(
            'response_text_color',
            [
                'label'     => __('Response text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-response-output' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'response_bg',
            [
                'label'     => __('Response background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-response-output' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'response_padding',
            [
                'label'      => __('Response padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-response-output' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'response_radius',
            [
                'label'      => __('Response radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-response-output' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tip_color',
            [
                'label'     => __('Validation tip color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#dc2626',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tip_typography',
                'label'    => __('Validation tip typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-not-valid-tip',
            ]
        );

        $this->add_control(
            'sent_ok_bg',
            [
                'label'     => __('Success notice background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ecfdf5',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-mail-sent-ok' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sent_ok_color',
            [
                'label'     => __('Success notice text', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#065f46',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-mail-sent-ok' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'validation_errors_bg',
            [
                'label'     => __('Error summary background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fef2f2',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-validation-errors' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'validation_errors_color',
            [
                'label'     => __('Error summary text', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#991b1b',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-validation-errors' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_spinner',
            [
                'label' => __('Spinner', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'spinner_size',
            [
                'label'      => __('Size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 12, 'max' => 48]],
                'default'    => ['size' => 24, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-spinner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'spinner_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cf7-styler-inner .wpcf7-spinner::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $raw_id   = isset($settings['cf7_form_id']) ? (string) $settings['cf7_form_id'] : '__none__';
        $form_id  = ($raw_id === '' || $raw_id === '__none__') ? 0 : (int) $raw_id;

        $label_pos = isset($settings['label_position']) ? (string) $settings['label_position'] : 'block';
        $classes   = ['elementrify-cf7-styler'];
        if ($label_pos === 'inline') {
            $classes[] = 'elementrify-cf7-styler--labels-inline';
        }

        $this->add_render_attribute('root', 'class', $classes);

        $path = ELEMENTRIFY_DIR . 'templates/contact-form-7-styler/contact-form-7-styler.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
