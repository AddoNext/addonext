<?php
namespace Addonext\Extensions\ParticleEffects;

use Addonext\Base\ExtensionBase;

defined('ABSPATH') or exit;

/**
 * Optional canvas particle overlays on widgets and layout elements (Style tab).
 *
 * @package AddoNext
 * @since 1.0.0
 */
class ParticleEffects extends ExtensionBase
{
    /**
     * @var bool
     */
    private static $handles_registered = false;

    /**
     * @return string
     */
    public function get_id(): string
    {
        return 'particle-effects';
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return __('Particle Effects', 'addonext');
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return __('Add animated particle backgrounds from the Style tab on widgets, sections, columns, and containers.', 'addonext');
    }

    /**
     * @return string
     */
    public function get_icon(): string
    {
        return 'eicon-animation addonext-icon';
    }

    /**
     * @return string
     */
    public function get_category(): string
    {
        return 'content';
    }

    /**
     * @return string
     */
    public function get_date_launched(): string
    {
        return '2026-04-18';
    }

    /**
     * @return string
     */
    public function get_date_updated(): string
    {
        return '2026-04-18';
    }

    /**
     * @return string
     */
    public function is_preview_only(): bool
    {
        return true;
    }

    /**
     * @return void
     */
    public function register_hooks(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {
                add_action('elementor/element/after_section_end', [$this, 'register_style_tab_section'], 15, 3);
                add_action('elementor/frontend/before_render', [$this, 'prepare_particle_wrapper'], 10, 1);
            }
        }
    }

    /**
     * @return void
     */
    public function enqueue_assets(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {
                $this->register_particle_handles();
                wp_enqueue_style('particle-effects');
                wp_enqueue_script('particle-effects');
            }
        }
    }

    /**
     * Ensures assets load inside the Elementor editor preview iframe (admin preview requests).
     *
     * @return void
     */
    public function enqueue_for_elementor_preview(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {
                $this->register_particle_handles();
                wp_enqueue_style('particle-effects');
                wp_enqueue_script('particle-effects');
            }
        }
    }

    /**
     * Registers styles/scripts. The bundled particles.min.js maps window-mode pointer
     * coordinates into canvas space so interactivity works when the canvas is offset.
     *
     * @return void
     */
    private function register_particle_handles(): void
    {
        if (self::$handles_registered) {
            return;
        }

        self::$handles_registered = true;

        wp_register_style(
            'particle-effects',
            ADDONEXT_URL . 'assets/css/extensions/particle-effects.css',
            [],
            ADDONEXT_VERSION
        );
        wp_register_script(
            'particlesjs',
            ADDONEXT_URL . 'assets/js/lib/particles.min.js',
            [],
            '2.0.0',
            true
        );
        wp_register_script(
            'particle-effects',
            ADDONEXT_URL . 'assets/js/extensions/particle-effects.js',
            ['jquery', 'particlesjs', 'elementor-frontend'],
            ADDONEXT_VERSION,
            true
        );
    }

    /**
     * @param \Elementor\Controls_Stack $element
     * @param string $section_id
     * @param array<string, mixed> $args
     * @return void
     */
    public function register_style_tab_section($element, string $section_id, array $args = []): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {
                if (!$this->is_supported_element($element)) {
                    return;
                }

                $tab = isset($args['tab']) ? $args['tab'] : '';

                if ($tab !== \Elementor\Controls_Manager::TAB_STYLE) {
                    return;
                }

                static $registered = [];

                $stack_name = $element->get_name();

                if (isset($registered[$stack_name])) {
                    return;
                }

                $registered[$stack_name] = true;

                $element->start_controls_section(
            'section_addonext_particles',
            [
                'label' => __('Particle effects', 'addonext'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $element->add_control(
            'addonext_particles_enable',
            [
                'label'              => __('Particle overlay', 'addonext'),
                'type'               => \Elementor\Controls_Manager::SWITCHER,
                'label_on'           => __('Yes', 'addonext'),
                'label_off'          => __('No', 'addonext'),
                'return_value'       => 'yes',
                'default'            => '',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'addonext_particles_preset',
            [
                'label'              => __('Effect preset', 'addonext'),
                'type'               => \Elementor\Controls_Manager::SELECT,
                'default'            => 'default',
                'frontend_available' => true,
                'options'            => [
                    'default'  => __('Default network', 'addonext'),
                    'snow'     => __('Snow', 'addonext'),
                    'bubbles'  => __('Bubbles', 'addonext'),
                    'stardust' => __('Stardust', 'addonext'),
                    'polygons' => __('Polygons', 'addonext'),
                    'links'    => __('Linked nodes', 'addonext'),
                    'nyan'     => __('Rainbow trail', 'addonext'),
                ],
                'condition'          => [
                    'addonext_particles_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'addonext_particles_density',
            [
                'label'              => __('Density', 'addonext'),
                'type'               => \Elementor\Controls_Manager::SLIDER,
                'size_units'         => ['px'],
                'range'              => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'default'            => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'frontend_available' => true,
                'condition'          => [
                    'addonext_particles_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'addonext_particles_speed',
            [
                'label'              => __('Animation speed', 'addonext'),
                'type'               => \Elementor\Controls_Manager::SLIDER,
                'size_units'         => ['px'],
                'range'              => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default'            => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'frontend_available' => true,
                'description'        => __('100% matches each preset’s default motion. Lower is slower, higher is faster.', 'addonext'),
                'condition'          => [
                    'addonext_particles_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'addonext_particles_node_color',
            [
                'label'              => __('Node color', 'addonext'),
                'type'               => \Elementor\Controls_Manager::COLOR,
                'default'            => '#ffffff',
                'frontend_available' => true,
                'condition'          => [
                    'addonext_particles_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'addonext_particles_line_color',
            [
                'label'              => __('Line color', 'addonext'),
                'description'        => __('Used for linked presets (Default, Polygons, Linked). Leave empty to match node color.', 'addonext'),
                'type'               => \Elementor\Controls_Manager::COLOR,
                'default'            => '',
                'frontend_available' => true,
                'condition'          => [
                    'addonext_particles_enable' => 'yes',
                ],
            ]
        );

                $element->end_controls_section();
            }
        }
    }

    /**
     * @param mixed $element
     * @return void
     */
    public function prepare_particle_wrapper($element): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {
                if (!$this->is_supported_element($element)) {
                    return;
                }

                $settings = $element->get_settings_for_display();

                if (empty($settings['addonext_particles_enable']) || $settings['addonext_particles_enable'] !== 'yes') {
                    return;
                }

                $preset = isset($settings['addonext_particles_preset']) ? (string) $settings['addonext_particles_preset'] : 'default';
                $allowed = ['default', 'snow', 'bubbles', 'stardust', 'polygons', 'links', 'nyan'];

                if (!in_array($preset, $allowed, true)) {
                    $preset = 'default';
                }

                $density = 80;

                if (isset($settings['addonext_particles_density']) && is_array($settings['addonext_particles_density'])) {
                    $density = isset($settings['addonext_particles_density']['size'])
                        ? (int) $settings['addonext_particles_density']['size']
                        : 80;
                }

                $density = max(20, min(200, $density));

                $speedPercent = 100;

                if (isset($settings['addonext_particles_speed']) && is_array($settings['addonext_particles_speed'])) {
                    $speedPercent = isset($settings['addonext_particles_speed']['size'])
                        ? (int) $settings['addonext_particles_speed']['size']
                        : 100;
                }

                $speedPercent = max(10, min(300, $speedPercent));

                $nodeRaw = $this->extract_color_input($settings, 'addonext_particles_node_color', 'addonext_particles_color');

                $nodeColor = $this->sanitize_color_value($nodeRaw, '#ffffff');

                $lineRaw = $this->extract_color_input($settings, 'addonext_particles_line_color', null);
                $lineColor = $lineRaw !== '' ? $this->sanitize_color_value($lineRaw, '') : '';

                $element->add_render_attribute('_wrapper', 'class', 'addonext-particles');
                $element->add_render_attribute('_wrapper', 'data-addonext-particles-host-id', esc_attr($element->get_id()));
                $element->add_render_attribute('_wrapper', 'data-addonext-particles', 'yes');
                $element->add_render_attribute('_wrapper', 'data-addonext-particles-preset', $preset);
                $element->add_render_attribute('_wrapper', 'data-addonext-particles-density', (string) $density);
                $element->add_render_attribute('_wrapper', 'data-addonext-particles-speed', (string) $speedPercent);
                $element->add_render_attribute('_wrapper', 'data-addonext-particles-node', esc_attr($nodeColor));

                if ($lineColor !== '') {
                    $element->add_render_attribute('_wrapper', 'data-addonext-particles-line', esc_attr($lineColor));
                }
            }
        }
    }

    /**
     * Reads a color control value (string or global / Elementor object shape).
     *
     * @param array<string, mixed> $settings
     */
    private function extract_color_input(array $settings, string $primaryKey, ?string $legacyKey): string
    {
        $raw = $settings[$primaryKey] ?? null;

        if ($legacyKey !== null && ($raw === null || $raw === '')) {
            $raw = $settings[$legacyKey] ?? null;
        }

        if (is_string($raw)) {
            return trim($raw);
        }

        if (is_array($raw)) {
            if (isset($raw['color']) && $raw['color'] !== '') {
                return trim((string) $raw['color']);
            }
            if (isset($raw['value']) && is_string($raw['value']) && trim($raw['value']) !== '') {
                return trim($raw['value']);
            }
        }

        return '';
    }

    /**
     * @param string $value
     * @param string $fallback
     * @return string
     */
    private function sanitize_color_value(string $value, string $fallback): string
    {
        $value = trim($value);

        if ($value === '') {
            return $fallback;
        }

        if (stripos($value, 'rgb') === 0) {
            return preg_replace('/[\r\n\t]+/', '', $value);
        }

        $hex = sanitize_hex_color($value);

        return $hex !== '' ? $hex : $fallback;
    }

    /**
     * @param mixed $element
     */
    private function is_supported_element($element): bool
    {
        if (!$element instanceof \Elementor\Element_Base) {
            return false;
        }

        return in_array($element->get_type(), ['widget', 'section', 'column', 'container'], true);
    }
}
