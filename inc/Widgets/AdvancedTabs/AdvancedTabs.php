<?php
namespace Elementrify\Widgets\AdvancedTabs;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class AdvancedTabs extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_script_in_preview']);
    }

    public function enqueue_script_in_preview(): void
    {
        $this->enqueue_assets();
        wp_enqueue_script('elementrify-advanced-tabs');
    }

    public function get_icon(): string
    {
        return 'eicon-tabs elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-advanced-tabs';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Advanced Tabs', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Tabbed content display', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-advanced-tabs'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-advanced-tabs'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-advanced-tabs',
            ELEMENTRIFY_URL . 'assets/css/advanced-tabs.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-advanced-tabs',
            ELEMENTRIFY_URL . 'assets/js/advanced-tabs.js',
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

        $repeater = new Repeater();
        $repeater->add_control('tab_title', [
            'label'   => __('Title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Tab', 'elementrify'),
        ]);
        $repeater->add_control('tab_content', [
            'label'   => __('Content', 'elementrify'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . __('Content for this tab.', 'elementrify') . '</p>',
        ]);

        $this->add_control(
            'tabs',
            [
                'label'       => __('Tabs', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_title'   => __('Features', 'elementrify'),
                        'tab_content' => '<h3>Amazing Features</h3><p>Discover all the powerful features that make AddoNext the best choice for your website.</p><ul><li>Drag &amp; Drop Builder</li><li>100+ Premium Widgets</li></ul>',
                    ],
                    [
                        'tab_title'   => __('Pricing', 'elementrify'),
                        'tab_content' => '<h3>Simple Pricing</h3><p>Choose the plan that fits your needs.</p>',
                    ],
                    [
                        'tab_title'   => __('Support', 'elementrify'),
                        'tab_content' => '<h3>We\'re Here to Help</h3><p>Get in touch with our support team anytime.</p>',
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
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
                    '{{WRAPPER}} .elementrify-advanced-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => ['top' => 6, 'right' => 6, 'bottom' => 6, 'left' => 6, 'unit' => 'px'],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'selector' => '{{WRAPPER}} .elementrify-advanced-tabs',
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
                    '{{WRAPPER}} .elementrify-advanced-tabs__panels' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementrify-advanced-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_headers',
            [
                'label' => __('Tab headers', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__headers' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-advanced-tabs__header'  => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_border_color',
            [
                'label'     => __('Bottom border color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e6e6e6',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__headers' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-advanced-tabs__panels'   => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_typography',
                'selector' => '{{WRAPPER}} .elementrify-advanced-tabs__header',
            ]
        );

        $this->add_control(
            'header_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => ['top' => 12, 'right' => 20, 'bottom' => 12, 'left' => 20, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_active_tab',
            [
                'label' => __('Active tab', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'active_tab_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e6f0fa',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__header.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_tab_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__header.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_tab_underline_color',
            [
                'label'     => __('Underline color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__header.is-active' => 'border-bottom-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementrify-advanced-tabs' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-advanced-tabs__panels' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .elementrify-advanced-tabs__panel',
            ]
        );

        $this->add_control(
            'content_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-advanced-tabs__panel' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-advanced-tabs__panel p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-advanced-tabs__panel ul li' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementrify-advanced-tabs__panel h1,' .
                    '{{WRAPPER}} .elementrify-advanced-tabs__panel h2,' .
                    '{{WRAPPER}} .elementrify-advanced-tabs__panel h3,' .
                    '{{WRAPPER}} .elementrify-advanced-tabs__panel h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/advanced-tabs/advanced-tabs.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-advanced-tabs');
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <div class="elementrify-advanced-tabs__headers">
                <# _.each(settings.tabs, function(item, index) {
                    var activeClass = index === 0 ? ' is-active' : '';
                    var headerKey = 'tabs.' + index + '.tab_title';
                    view.addRenderAttribute(headerKey, 'class', 'elementrify-advanced-tabs__header' + activeClass);
                    view.addRenderAttribute(headerKey, 'data-tab', index);
                    view.addInlineEditingAttributes(headerKey, 'none');
                #>
                <div {{{ view.getRenderAttributeString(headerKey) }}}>{{{ item.tab_title }}}</div>
                <# }); #>
            </div>
            <div class="elementrify-advanced-tabs__panels">
                <# _.each(settings.tabs, function(item, index) {
                    var activeClass = index === 0 ? ' is-active' : '';
                    var panelKey = 'tabs.' + index + '.tab_content';
                    view.addRenderAttribute(panelKey, 'class', 'elementrify-advanced-tabs__panel' + activeClass);
                    view.addRenderAttribute(panelKey, 'data-tab', index);
                    view.addInlineEditingAttributes(panelKey, 'advanced');
                #>
                <div {{{ view.getRenderAttributeString(panelKey) }}}>{{{ item.tab_content }}}</div>
                <# }); #>
            </div>
        </div>
        <?php
    }
}
