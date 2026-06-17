<?php
namespace Veloria\Widgets\NavigationMenu;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

/**
 * WordPress navigation menu with horizontal layout, hover presets, and multi-level dropdowns.
 */
class NavigationMenu extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_assets_in_preview']);
    }

    /**
     * Loads widget assets in the Elementor editor canvas.
     */
    public function enqueue_assets_in_preview(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('veloria-navigation-menu');
        wp_enqueue_script('veloria-navigation-menu');
    }

    public function get_icon(): string
    {
        return 'eicon-nav-menu veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-navigation-menu';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Navigation Menu', 'veloria');
    }

    public function get_description(): string
    {
        return __('Horizontal WordPress menu with multi-level dropdowns and selectable hover styles.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function get_style_depends(): array
    {
        return ['veloria-navigation-menu'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-navigation-menu'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-navigation-menu',
            VELORIA_URL . 'assets/css/navigation-menu.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-navigation-menu',
            VELORIA_URL . 'assets/js/navigation-menu.js',
            ['jquery', 'elementor-frontend'],
            VELORIA_VERSION,
            true
        );
    }

    /**
     * Registered menus for the control select.
     *
     * @return array<string, string>
     */
    public static function get_nav_menu_control_options(): array
    {
        $choices = ['__none__' => __('— Select menu —', 'veloria')];
        $menus   = wp_get_nav_menus();
        if (!is_array($menus)) {
            return $choices;
        }
        foreach ($menus as $menu) {
            $choices[(string) $menu->term_id] = $menu->name;
        }

        return $choices;
    }

    /**
     * True when the Elementor editor canvas is active (editor may be absent during document save).
     */
    public static function is_elementor_edit_canvas(): bool
    {
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }
        $plugin = \Elementor\Plugin::$instance;
        if (!$plugin || !isset($plugin->editor) || !$plugin->editor) {
            return false;
        }

        return (bool) $plugin->editor->is_edit_mode();
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'nav_menu',
            [
                'label'   => __('Menu', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'options' => self::get_nav_menu_control_options(),
                'default' => '__none__',
            ]
        );

        $this->add_responsive_control(
            'layout_align',
            [
                'label'     => __('Alignment', 'veloria'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => __('Start', 'veloria'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'     => [
                        'title' => __('Center', 'veloria'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'   => [
                        'title' => __('End', 'veloria'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'      => 'flex-start',
                'tablet_default' => 'flex-start',
                'mobile_default' => 'flex-start',
                'selectors'    => [
                    '{{WRAPPER}} .veloria-nav-menu__inner' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_trigger',
            [
                'label'       => __('Open submenus with', 'veloria'),
                'description' => __('Below the mobile breakpoint, submenus always open by tap.', 'veloria'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'hover',
                'options'     => [
                    'hover' => __('Mouse hover', 'veloria'),
                    'click' => __('Click', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'mobile_breakpoint',
            [
                'label'       => __('Mobile layout below (px)', 'veloria'),
                'description' => __('Stacked layout and tap-to-open submenus below this width.', 'veloria'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 320,
                'max'         => 1400,
                'default'     => 921,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_menu_style',
            [
                'label' => __('Menu', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'menu_typography',
                'label'    => __('Typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-nav-menu__root > .menu-item > a',
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label'      => __('Space between items', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 80]],
                'default'    => ['size' => 28, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-nav-menu__root' => 'column-gap: {{SIZE}}{{UNIT}}; row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'link_padding_vertical',
            [
                'label'      => __('Link vertical padding', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 40]],
                'default'    => ['size' => 8, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-nav-menu__root > .menu-item > a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color_default',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu__root > .menu-item > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_hover',
            [
                'label'     => __('Hover text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu__root > .menu-item > a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_active',
            [
                'label'     => __('Active text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu__root > .menu-item.current-menu-item > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-nav-menu__root > .menu-item.current-menu-ancestor > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_hover_style',
            [
                'label' => __('Hover effect', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label'   => __('Effect', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'gradient_border',
                'options' => [
                    'gradient_border' => __('Gradient bottom line', 'veloria'),
                    'solid_underline' => __('Solid bottom line', 'veloria'),
                    'pill_background' => __('Soft background', 'veloria'),
                    'lift'            => __('Lift and shadow', 'veloria'),
                    'glow_text'       => __('Text glow', 'veloria'),
                    'none'            => __('None', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'hover_line_gradient_start',
            [
                'label'     => __('Gradient start', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6366f1',
                'condition' => ['hover_effect' => 'gradient_border'],
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu' => '--nm-grad-a: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_line_gradient_end',
            [
                'label'     => __('Gradient end', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#a855f7',
                'condition' => ['hover_effect' => 'gradient_border'],
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu' => '--nm-grad-b: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_line_height',
            [
                'label'      => __('Line height', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 1, 'max' => 12]],
                'default'    => ['size' => 3, 'unit' => 'px'],
                'condition'  => [
                    'hover_effect' => ['gradient_border', 'solid_underline'],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-nav-menu' => '--nm-line-h: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_solid_color',
            [
                'label'     => __('Line color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'condition' => ['hover_effect' => 'solid_underline'],
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu' => '--nm-solid-line: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_pill_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#eef2ff',
                'condition' => ['hover_effect' => 'pill_background'],
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu' => '--nm-pill-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_lift_shadow_color',
            [
                'label'     => __('Shadow color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(79, 70, 229, 0.18)',
                'condition' => ['hover_effect' => 'lift'],
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu' => '--nm-lift-shadow: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_glow_color',
            [
                'label'     => __('Glow color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(99, 102, 241, 0.55)',
                'condition' => ['hover_effect' => 'glow_text'],
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu' => '--nm-glow: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_submenu_style',
            [
                'label' => __('Submenu', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'submenu_typography',
                'label'    => __('Typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-nav-menu__sub .menu-item > a',
            ]
        );

        $this->add_control(
            'submenu_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu__sub' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_link_color',
            [
                'label'     => __('Link color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu__sub .menu-item > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_link_hover',
            [
                'label'     => __('Link hover color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4f46e5',
                'selectors' => [
                    '{{WRAPPER}} .veloria-nav-menu__sub .menu-item > a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_min_width',
            [
                'label'      => __('Min width', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 120, 'max' => 400]],
                'default'    => ['size' => 220, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-nav-menu__sub' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_padding',
            [
                'label'      => __('Padding', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '10', 'right' => '0', 'bottom' => '10', 'left' => '0', 'unit' => 'px', 'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-nav-menu__sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-nav-menu__sub' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'submenu_shadow',
                'label'    => __('Box shadow', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-nav-menu__sub',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $raw_menu = isset($settings['nav_menu']) ? (string) $settings['nav_menu'] : '__none__';
        if ($raw_menu === '' || $raw_menu === '__none__') {
            $veloria_menu_id = 0;
        } else {
            $veloria_menu_id = (int) $raw_menu;
        }

        $hover = isset($settings['hover_effect']) ? (string) $settings['hover_effect'] : 'gradient_border';
        if ($hover === '') {
            $hover = 'gradient_border';
        }

        $bp = isset($settings['mobile_breakpoint']) ? (int) $settings['mobile_breakpoint'] : 921;
        if ($bp < 320) {
            $bp = 320;
        }

        $submenu_trigger = isset($settings['submenu_trigger']) ? (string) $settings['submenu_trigger'] : 'hover';
        if ($submenu_trigger !== 'click') {
            $submenu_trigger = 'hover';
        }

        $nav_classes = [
            'veloria-nav-menu',
            'veloria-nav-menu--hover-' . sanitize_html_class($hover),
        ];
        if ($submenu_trigger === 'click') {
            $nav_classes[] = 'veloria-nav-menu--sub-click';
        }

        $this->add_render_attribute('nav', 'class', $nav_classes);
        $this->add_render_attribute('nav', 'data-mobile-breakpoint', (string) $bp);
        $this->add_render_attribute('nav', 'data-submenu-trigger', $submenu_trigger);
        $this->add_render_attribute('nav', 'aria-label', esc_attr__('Primary', 'veloria'));

        if (!class_exists(Walker_Veloria_Nav_Menu::class, false)) {
            require_once __DIR__ . '/Walker_Veloria_Nav_Menu.php';
        }

        $path = VELORIA_DIR . 'templates/navigation-menu/navigation-menu.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
