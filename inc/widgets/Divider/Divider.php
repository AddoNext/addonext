<?php
namespace Elementrify\Widgets\Divider;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Divider extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-divider elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-divider';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Divider', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Section dividers & separators', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#6b7280';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-divider'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-divider',
            ELEMENTRIFY_URL . 'assets/css/divider.css',
            [],
            ELEMENTRIFY_VERSION
        );
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
            'style',
            [
                'label'   => __('Style', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'solid'  => __('Solid', 'elementrify'),
                    'double' => __('Double', 'elementrify'),
                    'dotted' => __('Dotted', 'elementrify'),
                    'dashed' => __('Dashed', 'elementrify'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-divider' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thickness',
            [
                'label'      => __('Thickness', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['max' => 20], 'em' => ['max' => 2]],
                'default'    => ['size' => 1, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'   => __('Alignment', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-h-align-left'],
                    'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-h-align-center'],
                    'right'  => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-h-align-right'],
                ],
                'default'      => 'center',
                'prefix_class' => 'elementrify-divider-wrapper--align-',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/divider/divider.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-divider-wrapper');
        view.addRenderAttribute('divider', 'class', 'elementrify-divider');
        view.addRenderAttribute('divider', 'class', 'elementrify-divider--' + settings.style);
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <hr {{{ view.getRenderAttributeString('divider') }}}>
        </div>
        <?php
    }
}
