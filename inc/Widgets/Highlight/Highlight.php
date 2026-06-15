<?php
namespace Veloria\Widgets\Highlight;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Highlight extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-editor-underline veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-highlight';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Highlight', 'veloria');
    }

    public function get_description(): string
    {
        return __('Custom highlighted text styles within a paragraph.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#eab308';
    }

    public function get_style_depends(): array
    {
        return ['veloria-highlight'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-highlight',
            VELORIA_URL . 'assets/css/highlight.css',
            [],
            VELORIA_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $default_content = __('Our platform provides exceptional performance and industry-leading security for all your business needs. We ensure that every feature is carefully optimized to deliver the best user experience possible.', 'veloria');

        $this->add_control('content', [
            'label'   => __('Paragraph', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => $default_content,
            'rows'    => 4,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('phrase', [
            'label'       => __('Text to highlight', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => __('e.g. exceptional performance', 'veloria'),
        ]);

        $repeater->add_control('style_type', [
            'label'   => __('Background', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'solid'   => __('Solid color', 'veloria'),
                'gradient' => __('Gradient', 'veloria'),
            ],
        ]);

        $repeater->add_control('bg_color', [
            'label'     => __('Background color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fef08a',
            'condition' => ['style_type' => 'solid'],
        ]);

        $repeater->add_control('gradient_color_a', [
            'label'     => __('Gradient start', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4a7cf8',
            'condition' => ['style_type' => 'gradient'],
        ]);

        $repeater->add_control('gradient_color_b', [
            'label'     => __('Gradient end', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#8b5cf6',
            'condition' => ['style_type' => 'gradient'],
        ]);

        $repeater->add_control('gradient_angle', [
            'label'     => __('Gradient angle', 'veloria'),
            'type'      => Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range'     => ['deg' => ['min' => 0, 'max' => 360]],
            'default'   => ['unit' => 'deg', 'size' => 90],
            'condition' => ['style_type' => 'gradient'],
        ]);

        $repeater->add_control('text_color', [
            'label'   => __('Text color', 'veloria'),
            'type'    => Controls_Manager::COLOR,
            'default' => '',
        ]);

        $repeater->add_control('border_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 4],
        ]);

        $repeater->add_control('padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'    => '2',
                'right'  => '6',
                'bottom' => '2',
                'left'   => '6',
            ],
        ]);

        $this->add_control('highlights', [
            'label'       => __('Highlights', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'phrase'       => 'exceptional performance',
                    'style_type'   => 'solid',
                    'bg_color'     => '#fef08a',
                    'text_color'   => '',
                    'border_radius' => ['size' => 4],
                    'padding'      => ['top' => '2', 'right' => '6', 'bottom' => '2', 'left' => '6'],
                ],
                [
                    'phrase'           => 'industry-leading security',
                    'style_type'       => 'gradient',
                    'gradient_color_a' => '#4a7cf8',
                    'gradient_color_b' => '#8b5cf6',
                    'gradient_angle'   => ['unit' => 'deg', 'size' => 90],
                    'text_color'       => '#ffffff',
                    'border_radius'    => ['size' => 4],
                    'padding'         => ['top' => '2', 'right' => '6', 'bottom' => '2', 'left' => '6'],
                ],
                [
                    'phrase'       => 'carefully optimized',
                    'style_type'   => 'solid',
                    'bg_color'     => '#bbf7d0',
                    'text_color'   => '#166534',
                    'border_radius' => ['size' => 4],
                    'padding'      => ['top' => '2', 'right' => '6', 'bottom' => '2', 'left' => '6'],
                ],
            ],
            'title_field' => '{{{ phrase }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_paragraph', [
            'label' => __('Paragraph', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('paragraph_font_size', [
            'label'      => __('Font size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 10, 'max' => 72]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .veloria-highlight__content' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'paragraph_typography',
                'selector' => '{{WRAPPER}} .veloria-highlight__content',
                'exclude'  => ['font_size'],
            ]
        );

        $this->add_control('paragraph_color', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => [
                '{{WRAPPER}} .veloria-highlight__content' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public static function build_highlighted_content(string $content, array $highlights): string
    {
        $content_escaped = esc_html($content);
        $len = strlen($content_escaped);
        $ranges = [];
        foreach ($highlights as $h) {
            $phrase = isset($h['phrase']) ? trim((string) $h['phrase']) : '';
            if ($phrase === '') {
                continue;
            }
            $phrase_escaped = esc_html($phrase);
            $idx = strpos($content_escaped, $phrase_escaped);
            if ($idx === false) {
                continue;
            }
            $end = $idx + strlen($phrase_escaped);
            $styles = [];
            if (!empty($h['style_type']) && $h['style_type'] === 'gradient') {
                $angle = isset($h['gradient_angle']['size']) ? (int) $h['gradient_angle']['size'] : 90;
                $ca = !empty($h['gradient_color_a']) ? $h['gradient_color_a'] : '#4a7cf8';
                $cb = !empty($h['gradient_color_b']) ? $h['gradient_color_b'] : '#8b5cf6';
                $styles[] = 'background:linear-gradient(' . $angle . 'deg,' . esc_attr($ca) . ',' . esc_attr($cb) . ')';
            } else {
                $bg = !empty($h['bg_color']) ? $h['bg_color'] : '#fef08a';
                $styles[] = 'background-color:' . esc_attr($bg);
            }
            if (!empty($h['text_color'])) {
                $styles[] = 'color:' . esc_attr($h['text_color']);
            }
            $br = isset($h['border_radius']['size']) ? $h['border_radius']['size'] : 4;
            $br_unit = isset($h['border_radius']['unit']) ? $h['border_radius']['unit'] : 'px';
            $styles[] = 'border-radius:' . $br . $br_unit;
            $p = isset($h['padding']) && is_array($h['padding']) ? $h['padding'] : [];
            $pt = isset($p['top']) ? $p['top'] : 2;
            $pr = isset($p['right']) ? $p['right'] : 6;
            $pb = isset($p['bottom']) ? $p['bottom'] : 2;
            $pl = isset($p['left']) ? $p['left'] : 6;
            $pu = isset($p['unit']) ? $p['unit'] : 'px';
            $styles[] = 'padding:' . $pt . $pu . ' ' . $pr . $pu . ' ' . $pb . $pu . ' ' . $pl . $pu;
            $html = '<span class="veloria-highlight__mark" style="' . implode(';', $styles) . '">' . $phrase_escaped . '</span>';
            $ranges[] = ['start' => $idx, 'end' => $end, 'html' => $html];
        }
        usort($ranges, function ($a, $b) {
            return $a['start'] - $b['start'];
        });
        $pos = 0;
        $out = '';
        foreach ($ranges as $r) {
            if ($r['start'] < $pos) {
                continue;
            }
            $out .= substr($content_escaped, $pos, $r['start'] - $pos);
            $out .= $r['html'];
            $pos = $r['end'];
        }
        if ($pos < $len) {
            $out .= substr($content_escaped, $pos);
        }
        return $out !== '' ? $out : $content_escaped;
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/highlight/highlight.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var content = settings.content || '';
        var highlights = settings.highlights || [];
        var escaped = _.escape(content);
        var ranges = [];
        for (var i = 0; i < highlights.length; i++) {
            var h = highlights[i];
            var phrase = (h.phrase || '').trim();
            if (!phrase) continue;
            var phraseEsc = _.escape(phrase);
            var idx = escaped.indexOf(phraseEsc);
            if (idx === -1) continue;
            var end = idx + phraseEsc.length;
            var styles = [];
            if (h.style_type === 'gradient') {
                var angle = (h.gradient_angle && h.gradient_angle.size != null) ? h.gradient_angle.size : 90;
                var ca = h.gradient_color_a || '#4a7cf8';
                var cb = h.gradient_color_b || '#8b5cf6';
                styles.push('background:linear-gradient(' + angle + 'deg,' + ca + ',' + cb + ')');
            } else {
                styles.push('background-color:' + (h.bg_color || '#fef08a'));
            }
            if (h.text_color) styles.push('color:' + h.text_color);
            var br = (h.border_radius && h.border_radius.size != null) ? h.border_radius.size : 4;
            var u = (h.border_radius && h.border_radius.unit) ? h.border_radius.unit : 'px';
            styles.push('border-radius:' + br + u);
            var p = h.padding || {};
            var pt = (p.top != null ? p.top : 2) + (p.unit || 'px');
            var pr = (p.right != null ? p.right : 6) + (p.unit || 'px');
            var pb = (p.bottom != null ? p.bottom : 2) + (p.unit || 'px');
            var pl = (p.left != null ? p.left : 6) + (p.unit || 'px');
            styles.push('padding:' + pt + ' ' + pr + ' ' + pb + ' ' + pl);
            ranges.push({ start: idx, end: end, html: '<span class="veloria-highlight__mark" style="' + styles.join(';') + '">' + phraseEsc + '</span>' });
        }
        ranges.sort(function(a, b) { return a.start - b.start; });
        var pos = 0;
        var out = '';
        for (var j = 0; j < ranges.length; j++) {
            var r = ranges[j];
            if (r.start < pos) continue;
            out += escaped.substring(pos, r.start);
            out += r.html;
            pos = r.end;
        }
        if (pos < escaped.length) out += escaped.substring(pos);
        if (out === '') out = escaped;
        #>
        <p class="veloria-highlight__content">{{{ out }}}</p>
        <?php
    }
}
