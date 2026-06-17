<?php
namespace Veloria\Widgets\AnimatedSvg;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

/**
 * Preset or uploaded SVG with CSS motion and style controls.
 */
class AnimatedSvg extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    /**
     * Loads widget assets in the editor canvas.
     */
    public function enqueue_preview_assets(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('veloria-animated-svg');
        wp_enqueue_script('veloria-animated-svg');
    }

    public function get_icon(): string
    {
        return 'eicon-animation veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-animated-svg';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Animated SVG', 'veloria');
    }

    public function get_description(): string
    {
        return __('Preset or custom SVG with motion, color, size, and shadow.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#8b5cf6';
    }

    public function get_keywords(): array
    {
        return ['veloria', 'svg', 'icon', 'animation', 'vector', 'motion'];
    }

    public function get_style_depends(): array
    {
        return ['veloria-animated-svg'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'veloria-animated-svg'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-animated-svg',
            VELORIA_URL . 'assets/css/animated-svg.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-animated-svg',
            VELORIA_URL . 'assets/js/animated-svg.js',
            ['elementor-frontend'],
            VELORIA_VERSION,
            true
        );
    }

    /**
     * Ensures the editor uses the live content template for control updates.
     *
     * @param bool $with_html_content Whether to include HTML content.
     * @return array<string, mixed>
     */
    public function get_raw_data($with_html_content = false)
    {
        $data = parent::get_raw_data($with_html_content);
        if ($with_html_content && isset($data['htmlCache'])) {
            unset($data['htmlCache']);
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    public static function get_preset_options(): array
    {
        return [
            'star'     => __('Star', 'veloria'),
            'heart'    => __('Heart', 'veloria'),
            'bolt'     => __('Bolt', 'veloria'),
            'check'    => __('Check', 'veloria'),
            'rocket'   => __('Rocket', 'veloria'),
            'sparkles' => __('Sparkles', 'veloria'),
        ];
    }

    /**
     * Returns raw SVG markup for a built-in preset (fill uses currentColor).
     */
    public static function get_preset_svg_markup(string $key): string
    {
        $svgs = [
            'star' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7-6.3-4.6-6.3 4.6 2.3-7-6-4.6h7.6z"/></svg>',
            'heart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>',
            'bolt' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11 21h-1l1-7H7.5c-.58 0-.57-.32-.38-.66.19-.34.05-.08.07-.12C8.48 10.94 10.42 7.54 13 3h1l-1 7h3.5c.49 0 .56.33.47.51l-.07.15C18.12 15.3 16 19 16 19c-.82 1.63-1.85 2-3 2z"/></svg>',
            'check' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>',
            'rocket' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>',
            'sparkles' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="6" cy="12" r="2"/><circle cx="18" cy="12" r="2"/><circle cx="12" cy="6" r="2"/><circle cx="12" cy="18" r="2"/><circle cx="12" cy="12" r="3"/></svg>',
        ];

        return $svgs[$key] ?? $svgs['star'];
    }

    /**
     * Strips risky nodes and attributes from inline SVG before output.
     */
    public static function sanitize_inline_svg(string $svg): string
    {
        $svg = trim($svg);
        if ($svg === '') {
            return '';
        }
        $svg = preg_replace('/<\?xml[^>]*\?>/i', '', $svg);
        $svg = preg_replace('/<!DOCTYPE[^>]*>/i', '', $svg);
        if (!class_exists('\DOMDocument')) {
            return '';
        }
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $wrapped = '<?xml encoding="UTF-8"?>' . $svg;
        if (!$dom->loadXML($wrapped, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING)) {
            libxml_clear_errors();

            return '';
        }
        $xpath = new \DOMXPath($dom);
        foreach (['script', 'iframe', 'foreignObject'] as $tag) {
            $nodes = $xpath->query('//' . $tag);
            if (!$nodes) {
                continue;
            }
            foreach ($nodes as $node) {
                if ($node->parentNode) {
                    $node->parentNode->removeChild($node);
                }
            }
        }
        foreach ($dom->getElementsByTagName('*') as $el) {
            $toRemove = [];
            foreach ($el->attributes as $attr) {
                $name = strtolower($attr->name);
                if (strpos($name, 'on') === 0) {
                    $toRemove[] = $attr->name;
                }
            }
            foreach ($toRemove as $name) {
                $el->removeAttribute($name);
            }
            if ($el->hasAttribute('href')) {
                $href = (string) $el->getAttribute('href');
                if (preg_match('/^\s*javascript:/i', $href)) {
                    $el->removeAttribute('href');
                }
            }
            if ($el->hasAttribute('xlink:href')) {
                $href = (string) $el->getAttribute('xlink:href');
                if (preg_match('/^\s*javascript:/i', $href)) {
                    $el->removeAttribute('xlink:href');
                }
            }
        }
        $root = $dom->documentElement;
        libxml_clear_errors();
        if (!$root || strtolower($root->nodeName) !== 'svg') {
            return '';
        }

        $out = $dom->saveXML($root);

        return is_string($out) ? $out : '';
    }

    /**
     * @param array<string, mixed> $settings
     *
     * @return array{mode: string, markup: string, url: string}
     */
    public static function build_svg_payload(array $settings): array
    {
        $source = isset($settings['svg_source']) ? (string) $settings['svg_source'] : 'preset';
        $empty   = ['mode' => 'inline', 'markup' => '', 'url' => ''];

        if ($source === 'preset') {
            $key = isset($settings['svg_preset']) ? (string) $settings['svg_preset'] : 'star';
            if (!array_key_exists($key, self::get_preset_options())) {
                $key = 'star';
            }

            return ['mode' => 'inline', 'markup' => self::get_preset_svg_markup($key), 'url' => ''];
        }

        if ($source === 'media') {
            $media = $settings['svg_media'] ?? [];
            if (!is_array($media) || empty($media['id'])) {
                return $empty;
            }
            $id   = (int) $media['id'];
            $path = get_attached_file($id);
            if ($path && is_readable($path) && strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) === 'svg') {
                $raw = file_get_contents($path);
                if (!is_string($raw) || $raw === '') {
                    return $empty;
                }
                $clean = self::sanitize_inline_svg($raw);

                return ['mode' => 'inline', 'markup' => $clean, 'url' => ''];
            }
            $url = isset($media['url']) ? esc_url_raw((string) $media['url']) : '';

            return ['mode' => 'img', 'markup' => '', 'url' => $url];
        }

        if ($source === 'url') {
            $url = isset($settings['svg_url']['url']) ? esc_url_raw((string) $settings['svg_url']['url']) : '';
            if ($url === '') {
                return $empty;
            }

            return ['mode' => 'img', 'markup' => '', 'url' => $url];
        }

        return $empty;
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_svg', [
            'label' => __('SVG', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('svg_source', [
            'label'   => __('Source', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'preset',
            'options' => [
                'preset' => __('Built-in icon', 'veloria'),
                'media'  => __('Media library', 'veloria'),
                'url'    => __('External URL', 'veloria'),
            ],
        ]);

        $this->add_control('svg_preset', [
            'label'     => __('Icon', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'star',
            'options'   => self::get_preset_options(),
            'condition' => ['svg_source' => 'preset'],
        ]);

        $this->add_control('svg_media', [
            'label'              => __('SVG file', 'veloria'),
            'type'               => Controls_Manager::MEDIA,
            'media_types'        => ['image'],
            'description'        => __('Upload an SVG file (SVG uploads may require a plugin such as Safe SVG). Raster images display as a static image.', 'veloria'),
            'frontend_available' => true,
            'condition'          => ['svg_source' => 'media'],
        ]);

        $this->add_control('svg_url', [
            'label'       => __('File URL', 'veloria'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://example.com/icon.svg',
            'dynamic'     => ['active' => true],
            'condition'   => ['svg_source' => 'url'],
        ]);

        $this->add_control('link', [
            'label'       => __('Link', 'veloria'),
            'type'        => Controls_Manager::URL,
            'dynamic'     => ['active' => true],
            'separator'   => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_motion', [
            'label' => __('Animation', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('animation', [
            'label'       => __('Effect', 'veloria'),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'pulse',
            'description' => __('Draw reveals each shape like a hand-drawn logo: stroke outline first, then fill (similar to tools such as SVG Artista). Inline SVG only; not for raster or external image URLs.', 'veloria'),
            'options'     => [
                'none'      => __('None', 'veloria'),
                'draw'      => __('Draw', 'veloria'),
                'pulse'     => __('Pulse', 'veloria'),
                'bounce'    => __('Bounce', 'veloria'),
                'rotate'    => __('Rotate', 'veloria'),
                'shake'     => __('Shake', 'veloria'),
                'float'     => __('Float', 'veloria'),
                'heartbeat' => __('Heartbeat', 'veloria'),
                'spin'      => __('Spin', 'veloria'),
                'wiggle'    => __('Wiggle', 'veloria'),
            ],
        ]);

        $this->add_control('anim_easing', [
            'label'     => __('Easing', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'ease-in-out',
            'options'   => [
                'ease'         => 'ease',
                'ease-in'      => 'ease-in',
                'ease-out'     => 'ease-out',
                'ease-in-out'  => 'ease-in-out',
                'linear'       => 'linear',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--e-asvg-ease: {{VALUE}};',
            ],
            'condition' => ['animation!' => 'none'],
        ]);

        $this->add_control('anim_duration', [
            'label'      => __('Duration (seconds)', 'veloria'),
            'type'       => Controls_Manager::NUMBER,
            'min'        => 0.3,
            'max'        => 8,
            'step'       => 0.1,
            'default'    => 1.6,
            'selectors'  => [
                '{{WRAPPER}}' => '--e-asvg-dur: {{VALUE}}s;',
            ],
            'condition'  => ['animation!' => 'none'],
        ]);

        $this->add_control('anim_delay', [
            'label'      => __('Delay (seconds)', 'veloria'),
            'type'       => Controls_Manager::NUMBER,
            'min'        => 0,
            'max'        => 5,
            'step'       => 0.1,
            'default'    => 0,
            'selectors'  => [
                '{{WRAPPER}}' => '--e-asvg-delay: {{VALUE}}s;',
            ],
            'condition'  => ['animation!' => 'none'],
        ]);

        $this->add_control('anim_repeat', [
            'label'        => __('Infinite loop', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'no',
            'condition'    => ['animation!' => 'none'],
        ]);

        $this->add_control('anim_iterations', [
            'label'      => __('Repeat count', 'veloria'),
            'type'       => Controls_Manager::NUMBER,
            'min'        => 1,
            'max'        => 50,
            'step'       => 1,
            'default'    => 1,
            'condition'  => [
                'animation!'   => 'none',
                'anim_repeat!' => 'yes',
            ],
        ]);

        $this->add_control('draw_fill_duration', [
            'label'       => __('Draw — fill reveal (seconds)', 'veloria'),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 0,
            'max'         => 4,
            'step'        => 0.05,
            'default'     => 0.75,
            'description' => __('Fade-in duration for each shape’s fill after its stroke finishes.', 'veloria'),
            'selectors'   => [
                '{{WRAPPER}}' => '--e-asvg-draw-fill-dur: {{VALUE}}s;',
            ],
            'condition'   => ['animation' => 'draw'],
        ]);

        $this->add_control('draw_stagger', [
            'label'       => __('Draw — stagger (seconds)', 'veloria'),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 0,
            'max'         => 2,
            'step'        => 0.02,
            'default'     => 0.12,
            'description' => __('Delay between each shape so paths draw in sequence.', 'veloria'),
            'selectors'   => [
                '{{WRAPPER}}' => '--e-asvg-draw-stagger: {{VALUE}}s;',
            ],
            'condition'   => ['animation' => 'draw'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_wrap', [
            'label' => __('Wrapper', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('align', [
            'label'     => __('Alignment', 'veloria'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => ['title' => __('Left', 'veloria'), 'icon' => 'eicon-text-align-left'],
                'center'     => ['title' => __('Center', 'veloria'), 'icon' => 'eicon-text-align-center'],
                'flex-end'   => ['title' => __('Right', 'veloria'), 'icon' => 'eicon-text-align-right'],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .veloria-asvg' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('inner_width', [
            'label'      => __('Max width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range'      => [
                'px' => ['min' => 16, 'max' => 600],
                '%'  => ['min' => 5, 'max' => 100],
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-asvg__inner' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('icon_width', [
            'label'      => __('Width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw', 'em'],
            'range'      => [
                'px' => ['min' => 8, 'max' => 400],
                '%'  => ['min' => 5, 'max' => 100],
            ],
            'default'    => ['size' => 96, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-asvg__inner svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                '{{WRAPPER}} .veloria-asvg__img'       => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_responsive_control('wrap_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-asvg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('icon_color', [
            'label'     => __('Icon color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6366f1',
            'selectors' => [
                '{{WRAPPER}} .veloria-asvg__inner' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('wrap_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .veloria-asvg__inner' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('inner_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-asvg__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'inner_border',
                'selector' => '{{WRAPPER}} .veloria-asvg__inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'inner_shadow',
                'selector' => '{{WRAPPER}} .veloria-asvg__inner',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_hover', [
            'label' => __('Hover', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('hover_icon_color', [
            'label'     => __('Icon color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .veloria-asvg__link:hover .veloria-asvg__inner' => 'color: {{VALUE}};',
                '{{WRAPPER}}:hover .veloria-asvg__inner' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('hover_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .veloria-asvg__link:hover .veloria-asvg__inner' => 'background-color: {{VALUE}};',
                '{{WRAPPER}}:hover .veloria-asvg__inner' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'hover_shadow',
                'label'    => __('Shadow', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-asvg__link:hover .veloria-asvg__inner, {{WRAPPER}}:hover .veloria-asvg__inner',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @param array<string, mixed> $settings
     */
    public static function animation_css_vars(array $settings): string
    {
        $animation = isset($settings['animation']) ? (string) $settings['animation'] : 'none';
        if ($animation === 'none') {
            return '';
        }
        $repeat = ($settings['anim_repeat'] ?? 'no') === 'yes';
        $iter   = $repeat ? 'infinite' : '1';

        return sprintf(
            '--e-asvg-iter: %s;',
            esc_attr($iter)
        );
    }

    /**
     * HTML data-* used by draw script (Elementor puts CSS vars on the outer widget, not this inner div).
     *
     * @param array<string, mixed> $settings
     *
     * @return array<string, string>
     */
    public static function get_draw_data_attributes(array $settings): array
    {
        $ease = isset($settings['anim_easing']) ? (string) $settings['anim_easing'] : 'ease-in-out';
        $allowedEase = ['ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear'];
        if (!in_array($ease, $allowedEase, true)) {
            $ease = 'ease-in-out';
        }
        $repeat = ($settings['anim_repeat'] ?? 'no') === 'yes';
        $iter    = $repeat ? 'infinite' : '1';

        return [
            'data-asvg-dur'       => (string) max(0.3, (float) ($settings['anim_duration'] ?? 1.6)),
            'data-asvg-delay'     => (string) max(0, (float) ($settings['anim_delay'] ?? 0)),
            'data-asvg-ease'      => $ease,
            'data-asvg-fill-dur'  => (string) max(0, (float) ($settings['draw_fill_duration'] ?? 0.75)),
            'data-asvg-stagger'   => (string) max(0, (float) ($settings['draw_stagger'] ?? 0.12)),
            'data-asvg-iter'      => $iter,
        ];
    }

    public function render(): void
    {
        $veloria_settings = $this->get_settings_for_display();
        $veloria_payload  = self::build_svg_payload($veloria_settings);

        $animation = isset($veloria_settings['animation']) ? (string) $veloria_settings['animation'] : 'none';
        $animation = preg_replace('/[^a-z\-]/', '', $animation);
        $allowed   = ['none', 'draw', 'pulse', 'bounce', 'rotate', 'shake', 'float', 'heartbeat', 'spin', 'wiggle'];
        if ($animation === '' || !in_array($animation, $allowed, true)) {
            $animation = 'pulse';
        }

        $classes = ['veloria-asvg'];
        if ($animation !== 'none') {
            $classes[] = 'veloria-asvg--anim-' . $animation;
        }

        $extra_style = self::animation_css_vars($veloria_settings);
        $this->add_render_attribute('wrapper', 'class', $classes);
        if ($extra_style !== '') {
            $this->add_render_attribute('wrapper', 'style', $extra_style);
        }
        if ($animation === 'draw') {
            foreach (self::get_draw_data_attributes($veloria_settings) as $attr => $value) {
                $this->add_render_attribute('wrapper', $attr, esc_attr($value));
            }
        }

        if (!empty($veloria_settings['link']['url'])) {
            $this->add_link_attributes('svg_link', $veloria_settings['link']);
        }

        $path = VELORIA_DIR . 'templates/animated-svg/animated-svg.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        $presetSvgs = [];
        foreach (array_keys(self::get_preset_options()) as $presetKey) {
            $presetSvgs[$presetKey] = self::get_preset_svg_markup($presetKey);
        }
        ?>
        <#
        var anim = settings.animation && settings.animation !== 'none' ? settings.animation : 'none';
        var cls = 'veloria-asvg';
        if (anim !== 'none') cls += ' veloria-asvg--anim-' + anim;
        var src = settings.svg_source || 'preset';
        var presetSvgs = <?php echo wp_json_encode($presetSvgs); ?>;
        var presetKey = settings.svg_preset || 'star';
        if (!presetSvgs[presetKey]) { presetKey = 'star'; }
        var presetMarkup = presetSvgs[presetKey] || '';
        var iter = settings.anim_repeat === 'yes' ? 'infinite' : 1;
        var extra = anim !== 'none' ? '--e-asvg-iter:' + iter + ';' : '';
        var drawData = '';
        if (anim === 'draw') {
            var dDur = parseFloat(settings.anim_duration) || 1.6;
            var dDel = parseFloat(settings.anim_delay) || 0;
            var dEase = settings.anim_easing || 'ease-in-out';
            var dFill = parseFloat(settings.draw_fill_duration);
            if (isNaN(dFill)) { dFill = 0.75; }
            var dSt = parseFloat(settings.draw_stagger);
            if (isNaN(dSt)) { dSt = 0.12; }
            var dIter = settings.anim_repeat === 'yes' ? 'infinite' : '1';
            drawData = ' data-asvg-dur="' + dDur + '" data-asvg-delay="' + dDel + '" data-asvg-ease="' + dEase + '" data-asvg-fill-dur="' + dFill + '" data-asvg-stagger="' + dSt + '" data-asvg-iter="' + dIter + '"';
        }
        #>
        <div class="{{ cls }}" style="{{ extra }}"{{{ drawData }}}>
            <# if (settings.link && settings.link.url) { #>
            <a class="veloria-asvg__link" href="{{ settings.link.url }}">
            <# } #>
            <span class="veloria-asvg__inner">
                <# if (src === 'media' && settings.svg_media && settings.svg_media.url) { #>
                <img class="veloria-asvg__img" src="{{ settings.svg_media.url }}" alt="" />
                <# } else if (src === 'url' && settings.svg_url && settings.svg_url.url) { #>
                <img class="veloria-asvg__img" src="{{ settings.svg_url.url }}" alt="" />
                <# } else { #>
                {{{ presetMarkup }}}
                <# } #>
            </span>
            <# if (settings.link && settings.link.url) { #>
            </a>
            <# } #>
        </div>
        <?php
    }
}
