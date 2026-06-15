<?php
namespace Veloria\Widgets\AnimatedText;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

defined('ABSPATH') or exit;

/**
 * Animated text widget (typewriter and reveal).
 */
class AnimatedText extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-animation-text veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-animated-text';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Animated Text', 'veloria');
    }

    public function get_description(): string
    {
        return __('Typewriter and reveal text with gradient, shadows, and more.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#db2777';
    }

    public function get_style_depends(): array
    {
        return ['veloria-animated-text'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-animated-text'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-animated-text',
            VELORIA_URL . 'assets/css/animated-text.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-animated-text',
            VELORIA_URL . 'assets/js/animated-text.js',
            ['jquery'],
            VELORIA_VERSION,
            true
        );
    }

    /**
     * Split animated string into word/space spans for reveal animation.
     */
    public static function build_reveal_segments(string $text): string
    {
        if ($text === '') {
            return '';
        }
        $parts = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $html  = '';
        $index = 0;
        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }
            if (preg_match('/^\s+$/u', $part)) {
                $html .= '<span class="veloria-animated-text__space">' . esc_html($part) . '</span>';
            } else {
                $html .= '<span class="veloria-animated-text__word" style="--word-index: ' . (int) $index . ';">' . esc_html($part) . '</span>';
                ++$index;
            }
        }
        return $html;
    }

    /**
     * Character spans for reveal animation.
     */
    public static function build_reveal_segments_chars(string $text): string
    {
        if ($text === '') {
            return '';
        }
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($chars)) {
            return '';
        }
        $html = '';
        $i    = 0;
        foreach ($chars as $ch) {
            $html .= '<span class="veloria-animated-text__word veloria-animated-text__char" style="--word-index: ' . (int) $i . ';">' . esc_html($ch) . '</span>';
            ++$i;
        }
        return $html;
    }

    /**
     * Collect non-empty phrases from repeater (or legacy single field).
     *
     * @param array<string, mixed> $settings Widget settings.
     * @return list<string>
     */
    public static function get_phrases_from_settings(array $settings): array
    {
        $phrases = [];
        if (!empty($settings['animated_phrases']) && is_array($settings['animated_phrases'])) {
            foreach ($settings['animated_phrases'] as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $p = isset($row['phrase']) ? trim((string) $row['phrase']) : '';
                if ($p !== '') {
                    $phrases[] = $p;
                }
            }
        }
        if (empty($phrases) && !empty($settings['animated_text'])) {
            $legacy = trim((string) $settings['animated_text']);
            if ($legacy !== '') {
                $phrases[] = $legacy;
            }
        }
        return $phrases;
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('before_text', [
            'label'   => __('Before text', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('What a', 'veloria'),
        ]);

        $phrase_repeater = new \Elementor\Repeater();
        $phrase_repeater->add_control('phrase', [
            'label'       => __('Phrase', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'label_block' => true,
        ]);

        $this->add_control('animated_phrases', [
            'label'       => __('Animated phrases', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $phrase_repeater->get_controls(),
            'default'     => [
                ['phrase' => __('Beautiful motion', 'veloria')],
                ['phrase' => __('Amazing experience', 'veloria')],
            ],
            'title_field' => '{{{ phrase }}}',
        ]);

        $this->add_control('after_text', [
            'label'   => __('After text', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);

        $this->add_control('animation_type', [
            'label'   => __('Animation', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'typewriter',
            'options' => [
                'typewriter'     => __('Typewriter', 'veloria'),
                'reveal'         => __('Reveal', 'veloria'),
                'square_squeeze' => __('Square squeeze', 'veloria'),
            ],
        ]);

        $this->add_control('typewriter_speed', [
            'label'      => __('Typing speed', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 10, 'max' => 400]],
            'default'    => ['unit' => 'ms', 'size' => 100],
            'condition'  => ['animation_type' => 'typewriter'],
        ]);

        $this->add_control('typewriter_start_delay', [
            'label'      => __('Start delay', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 0, 'max' => 5000]],
            'default'    => ['unit' => 'ms', 'size' => 0],
            'condition'  => ['animation_type' => 'typewriter'],
        ]);

        $this->add_control('typewriter_show_cursor', [
            'label'        => __('Show cursor', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['animation_type' => 'typewriter'],
        ]);

        $this->add_control('typewriter_cursor_char', [
            'label'     => __('Cursor character', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => '|',
            'condition' => [
                'animation_type'         => 'typewriter',
                'typewriter_show_cursor' => 'yes',
            ],
        ]);

        $this->add_control('typewriter_loop', [
            'label'        => __('Loop animation', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['animation_type' => 'typewriter'],
        ]);

        $this->add_control('typewriter_loop_pause', [
            'label'       => __('Pause before repeat', 'veloria'),
            'description' => __('Used after the full phrase list finishes (when loop is on).', 'veloria'),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => ['ms'],
            'range'       => ['ms' => ['min' => 500, 'max' => 8000]],
            'default'     => ['unit' => 'ms', 'size' => 2000],
            'condition'   => [
                'animation_type'  => 'typewriter',
                'typewriter_loop' => 'yes',
            ],
        ]);

        $this->add_control('typewriter_between_phrases', [
            'label'      => __('Pause between phrases', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 0, 'max' => 5000]],
            'default'    => ['unit' => 'ms', 'size' => 600],
            'condition'  => ['animation_type' => 'typewriter'],
        ]);

        $this->add_control('reveal_split', [
            'label'     => __('Reveal by', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'word',
            'options'   => [
                'word' => __('Word', 'veloria'),
                'char' => __('Character', 'veloria'),
            ],
            'condition' => ['animation_type' => 'reveal'],
        ]);

        $this->add_control('reveal_stagger', [
            'label'      => __('Stagger', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 0, 'max' => 500]],
            'default'    => ['unit' => 'ms', 'size' => 80],
            'condition'  => ['animation_type' => 'reveal'],
        ]);

        $this->add_control('reveal_duration', [
            'label'      => __('Reveal duration', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 100, 'max' => 2000]],
            'default'    => ['unit' => 'ms', 'size' => 500],
            'condition'  => ['animation_type' => 'reveal'],
        ]);

        $this->add_control('reveal_direction', [
            'label'     => __('Reveal direction', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'up',
            'options'   => [
                'up'    => __('Up', 'veloria'),
                'down'  => __('Down', 'veloria'),
                'left'  => __('Left', 'veloria'),
                'right' => __('Right', 'veloria'),
                'fade'  => __('Fade only', 'veloria'),
            ],
            'condition' => ['animation_type' => 'reveal'],
        ]);

        $this->add_control('reveal_once', [
            'label'        => __('Animate once', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'description'  => __('When off, animation replays each time the block enters the viewport.', 'veloria'),
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['animation_type' => 'reveal'],
        ]);

        $this->add_control('reveal_between_phrases', [
            'label'      => __('Pause between phrases', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 0, 'max' => 5000]],
            'default'    => ['unit' => 'ms', 'size' => 600],
            'condition'  => ['animation_type' => 'reveal'],
        ]);

        $this->add_control('square_squeeze_duration', [
            'label'      => __('Squeeze duration', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 200, 'max' => 3000]],
            'default'    => ['unit' => 'ms', 'size' => 600],
            'condition'  => ['animation_type' => 'square_squeeze'],
        ]);

        $this->add_control('square_expand_duration', [
            'label'      => __('Expand duration', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 200, 'max' => 3000]],
            'default'    => ['unit' => 'ms', 'size' => 600],
            'condition'  => ['animation_type' => 'square_squeeze'],
        ]);

        $this->add_control('square_hold_duration', [
            'label'      => __('Hold before next squeeze', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 0, 'max' => 8000]],
            'default'    => ['unit' => 'ms', 'size' => 800],
            'condition'  => ['animation_type' => 'square_squeeze'],
        ]);

        $this->add_control('square_start_delay', [
            'label'      => __('Start delay', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['ms'],
            'range'      => ['ms' => ['min' => 0, 'max' => 5000]],
            'default'    => ['unit' => 'ms', 'size' => 0],
            'condition'  => ['animation_type' => 'square_squeeze'],
        ]);

        $this->add_control('square_squeeze_once', [
            'label'        => __('Animate once', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'description'  => __('When off, the sequence restarts each time the block enters the viewport.', 'veloria'),
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['animation_type' => 'square_squeeze'],
        ]);

        $this->add_control('phrase_list_loop', [
            'label'        => __('Repeat phrase list', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'description'  => __('Cycle through all phrases repeatedly.', 'veloria'),
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_layout', [
            'label' => __('Layout', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('text_align', [
            'label'     => __('Alignment', 'veloria'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [
                    'title' => __('Left', 'veloria'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'veloria'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'  => [
                    'title' => __('Right', 'veloria'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'left',
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-text' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('text_font_size', [
            'label'      => __('Font size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'range'      => ['px' => ['min' => 10, 'max' => 120]],
            'default'    => ['unit' => 'px', 'size' => 32],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'label'    => __('Typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-animated-text',
                'exclude'  => ['font_size'],
            ]
        );

        $this->add_responsive_control('segment_gap', [
            'label'      => __('Space between parts', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => [
                'px' => ['min' => 0, 'max' => 80],
                'em' => ['min' => 0, 'max' => 3],
            ],
            'default'    => ['unit' => 'px', 'size' => 8],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__before' => 'margin-inline-end: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .veloria-animated-text__after'   => 'margin-inline-start: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('static_color', [
            'label'     => __('Before & after color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-text__before' => 'color: {{VALUE}};',
                '{{WRAPPER}} .veloria-animated-text__after'   => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_animated', [
            'label' => __('Animated text', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('animated_fill', [
            'label'   => __('Fill', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'gradient',
            'options' => [
                'solid'    => __('Solid', 'veloria'),
                'gradient' => __('Gradient', 'veloria'),
            ],
        ]);

        $this->add_control('animated_color', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'condition' => ['animated_fill' => 'solid'],
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-text__animated' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('animated_gradient_start', [
            'label'     => __('Gradient start', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ec4899',
            'condition' => ['animated_fill' => 'gradient'],
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-text__animated' => '--veloria-at-gradient-start: {{VALUE}};',
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => '--veloria-at-gradient-start: {{VALUE}};',
            ],
        ]);

        $this->add_control('animated_gradient_end', [
            'label'     => __('Gradient end', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#8b5cf6',
            'condition' => ['animated_fill' => 'gradient'],
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-text__animated' => '--veloria-at-gradient-end: {{VALUE}};',
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => '--veloria-at-gradient-end: {{VALUE}};',
            ],
        ]);

        $this->add_control('animated_gradient_angle', [
            'label'      => __('Gradient angle', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range'      => ['deg' => ['min' => 0, 'max' => 360]],
            'default'    => ['unit' => 'deg', 'size' => 120],
            'condition'  => ['animated_fill' => 'gradient'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__animated' => '--veloria-at-gradient-angle: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => '--veloria-at-gradient-angle: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('animated_letter_spacing', [
            'label'      => __('Letter spacing', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => -5, 'max' => 40]],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__animated' => 'letter-spacing: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('animated_text_stroke_width', [
            'label'      => __('Outline width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 8]],
            'default'    => ['unit' => 'px', 'size' => 0],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__animated' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; text-stroke-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('animated_text_stroke_color', [
            'label'     => __('Outline color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#000000',
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-text__animated' => '-webkit-text-stroke-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_square_squeeze', [
            'label'     => __('Square squeeze', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['animation_type' => 'square_squeeze'],
        ]);

        $this->add_responsive_control('square_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'    => '2',
                'right'  => '6',
                'bottom' => '2',
                'left'   => '6',
                'unit'   => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('square_border_width', [
            'label'      => __('Border width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 6]],
            'default'    => ['unit' => 'px', 'size' => 1],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('square_border_color', [
            'label'     => __('Border color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d4a012',
            'condition' => [
                'animated_fill!' => 'gradient',
            ],
            'selectors' => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('square_corner_size', [
            'label'      => __('Corner square size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 4, 'max' => 24]],
            'default'    => ['unit' => 'px', 'size' => 8],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => '--veloria-at-sq-corner: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('square_corner_fill', [
            'label'     => __('Corner fill', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'gradient',
            'options'   => [
                'gradient' => __('Gradient', 'veloria'),
                'solid'    => __('Solid', 'veloria'),
            ],
            'condition' => [
                'animated_fill!' => 'gradient',
            ],
        ]);

        $this->add_control('square_corner_color', [
            'label'      => __('Corner color', 'veloria'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#ffffff',
            'conditions' => [
                'relation' => 'and',
                'terms'    => [
                    [
                        'name'     => 'animated_fill',
                        'operator' => '!=',
                        'value'    => 'gradient',
                    ],
                    [
                        'name'     => 'square_corner_fill',
                        'operator' => '==',
                        'value'    => 'solid',
                    ],
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-corner' => 'background-color: {{VALUE}}; background-image: none;',
            ],
        ]);

        $this->add_control('square_corner_gradient_start', [
            'label'      => __('Corner gradient start', 'veloria'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#fde68a',
            'conditions' => [
                'relation' => 'and',
                'terms'    => [
                    [
                        'name'     => 'animated_fill',
                        'operator' => '!=',
                        'value'    => 'gradient',
                    ],
                    [
                        'name'     => 'square_corner_fill',
                        'operator' => '==',
                        'value'    => 'gradient',
                    ],
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => '--veloria-at-sq-corner-start: {{VALUE}};',
            ],
        ]);

        $this->add_control('square_corner_gradient_end', [
            'label'      => __('Corner gradient end', 'veloria'),
            'type'       => Controls_Manager::COLOR,
            'default'    => '#d97706',
            'conditions' => [
                'relation' => 'and',
                'terms'    => [
                    [
                        'name'     => 'animated_fill',
                        'operator' => '!=',
                        'value'    => 'gradient',
                    ],
                    [
                        'name'     => 'square_corner_fill',
                        'operator' => '==',
                        'value'    => 'gradient',
                    ],
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => '--veloria-at-sq-corner-end: {{VALUE}};',
            ],
        ]);

        $this->add_control('square_corner_gradient_angle', [
            'label'       => __('Corner gradient angle', 'veloria'),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => ['deg'],
            'range'       => ['deg' => ['min' => 0, 'max' => 360]],
            'default'     => ['unit' => 'deg', 'size' => 135],
            'conditions'  => [
                'relation' => 'and',
                'terms'    => [
                    [
                        'name'     => 'animated_fill',
                        'operator' => '!=',
                        'value'    => 'gradient',
                    ],
                    [
                        'name'     => 'square_corner_fill',
                        'operator' => '==',
                        'value'    => 'gradient',
                    ],
                ],
            ],
            'selectors'   => [
                '{{WRAPPER}} .veloria-animated-text__squeeze-frame' => '--veloria-at-sq-corner-angle: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_shadow', [
            'label' => __('Text shadow', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'animated_text_shadow',
                'label'    => __('Shadow', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-animated-text__animated',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/animated-text/animated-text.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
