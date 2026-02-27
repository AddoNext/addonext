<?php
namespace Elementrify\Widgets\ContentToggle;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ContentToggle extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_script_in_preview']);
    }

    public function enqueue_script_in_preview(): void
    {
        $this->enqueue_assets();
        wp_enqueue_script('elementrify-content-toggle');
    }

    public function get_icon(): string
    {
        return 'eicon-toggle elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-content-toggle';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Content Toggle', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Switch between content blocks', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-content-toggle'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-content-toggle'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-content-toggle',
            ELEMENTRIFY_URL . 'assets/css/content-toggle.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-content-toggle',
            ELEMENTRIFY_URL . 'assets/js/content-toggle.js',
            [],
            ELEMENTRIFY_VERSION,
            true
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
            'primary_label',
            [
                'label'   => __('Primary label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Basic', 'elementrify'),
            ]
        );

        $this->add_control(
            'primary_content',
            [
                'label'   => __('Primary content', 'elementrify'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => '<p class="elementrify-content-toggle__price">$49<small>/year</small></p><p class="elementrify-content-toggle__sub">That\'s only $4.08/month</p><ul><li>5 websites</li><li>Email support</li><li>1 year updates</li></ul><p><a href="#" class="elementrify-content-toggle__cta">Get Started</a></p>',
            ]
        );

        $this->add_control(
            'secondary_label',
            [
                'label'   => __('Secondary label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Pro', 'elementrify'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label'       => __('Badge text', 'elementrify'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Save 25%', 'elementrify'),
                'placeholder' => __('e.g. Save 25%', 'elementrify'),
            ]
        );

        $this->add_control(
            'default_active',
            [
                'label'   => __('Default view', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'secondary',
                'options' => [
                    'primary'   => __('Primary', 'elementrify'),
                    'secondary' => __('Secondary', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'secondary_content',
            [
                'label'   => __('Secondary content', 'elementrify'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => '<p class="elementrify-content-toggle__price">$99<small>/year</small></p><p class="elementrify-content-toggle__sub">That\'s only $8.25/month</p><ul><li>Unlimited websites</li><li>Premium support</li><li>Lifetime updates</li></ul><p><a href="#" class="elementrify-content-toggle__cta">Upgrade Now</a></p>',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => __('Container', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-content-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => ['top' => 12, 'right' => 12, 'bottom' => 12, 'left' => 12, 'unit' => 'px'],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'           => 'border',
                'selector'      => '{{WRAPPER}} .elementrify-content-toggle',
                'fields_options' => [
                    'border' => ['default' => 'solid'],
                    'color'  => ['default' => '#e6e6e6'],
                    'width'  => ['default' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-content-toggle__panels' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => ['top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label'      => __('Margin', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-content-toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_labels',
            [
                'label' => __('Toggle labels', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_background',
            [
                'label'     => __('Pill background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__labels' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .elementrify-content-toggle__label',
            ]
        );

        $this->add_control(
            'label_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__label:not(.is-active)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-content-toggle__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => ['top' => 10, 'right' => 24, 'bottom' => 10, 'left' => 24, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => __('Badge', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#22c55e',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'selector' => '{{WRAPPER}} .elementrify-content-toggle__badge',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_active_label',
            [
                'label' => __('Active label', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'active_label_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__label.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_label_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__label.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-content-toggle__panels' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .elementrify-content-toggle__panel',
            ]
        );

        $this->add_control(
            'content_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__panel' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-content-toggle__panel p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-content-toggle__panel ul li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_heading_color',
            [
                'label'     => __('Heading color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-content-toggle__panel h1,' .
                    '{{WRAPPER}} .elementrify-content-toggle__panel h2,' .
                    '{{WRAPPER}} .elementrify-content-toggle__panel h3,' .
                    '{{WRAPPER}} .elementrify-content-toggle__panel h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/content-toggle/content-toggle.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var secondaryFirst = settings.default_active === 'secondary';
        var primaryLabelClass = 'elementrify-content-toggle__label' + (secondaryFirst ? '' : ' is-active');
        var secondaryLabelClass = 'elementrify-content-toggle__label' + (secondaryFirst ? ' is-active' : '');
        var primaryPanelClass = 'elementrify-content-toggle__panel' + (secondaryFirst ? '' : ' is-active');
        var secondaryPanelClass = 'elementrify-content-toggle__panel' + (secondaryFirst ? ' is-active' : '');
        view.addRenderAttribute('wrapper', 'class', 'elementrify-content-toggle');
        view.addRenderAttribute('primary_label', 'class', primaryLabelClass);
        view.addRenderAttribute('primary_label', 'data-toggle', '0');
        view.addInlineEditingAttributes('primary_label', 'none');
        view.addRenderAttribute('secondary_label', 'class', secondaryLabelClass);
        view.addRenderAttribute('secondary_label', 'data-toggle', '1');
        view.addInlineEditingAttributes('secondary_label', 'none');
        view.addRenderAttribute('primary_content', 'class', primaryPanelClass);
        view.addRenderAttribute('primary_content', 'data-toggle', '0');
        view.addInlineEditingAttributes('primary_content', 'advanced');
        view.addRenderAttribute('secondary_content', 'class', secondaryPanelClass);
        view.addRenderAttribute('secondary_content', 'data-toggle', '1');
        view.addInlineEditingAttributes('secondary_content', 'advanced');
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <div class="elementrify-content-toggle__toggle-row">
                <div class="elementrify-content-toggle__labels">
                    <span {{{ view.getRenderAttributeString('primary_label') }}}>{{{ settings.primary_label }}}</span>
                    <span {{{ view.getRenderAttributeString('secondary_label') }}}>{{{ settings.secondary_label }}}</span>
                </div>
                <# if (settings.badge_text) { #>
                <span class="elementrify-content-toggle__badge">{{{ settings.badge_text }}}</span>
                <# } #>
            </div>
            <div class="elementrify-content-toggle__panels">
                <div {{{ view.getRenderAttributeString('primary_content') }}}>{{{ settings.primary_content }}}</div>
                <div {{{ view.getRenderAttributeString('secondary_content') }}}>{{{ settings.secondary_content }}}</div>
            </div>
        </div>
        <?php
    }
}
