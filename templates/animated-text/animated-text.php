<?php
defined('ABSPATH') || exit;

$veloria_s = $this->get_settings_for_display();

$veloria_before        = isset($veloria_s['before_text']) ? (string) $veloria_s['before_text'] : '';
$veloria_after         = isset($veloria_s['after_text']) ? (string) $veloria_s['after_text'] : '';
$veloria_phrases       = \Veloria\Widgets\AnimatedText\AnimatedText::get_phrases_from_settings($veloria_s);
$veloria_animation     = isset($veloria_s['animation_type']) ? $veloria_s['animation_type'] : 'typewriter';
$veloria_reveal_split  = isset($veloria_s['reveal_split']) ? $veloria_s['reveal_split'] : 'word';
$veloria_fill          = isset($veloria_s['animated_fill']) ? $veloria_s['animated_fill'] : 'solid';

$veloria_tw_speed      = isset($veloria_s['typewriter_speed']['size']) ? (int) $veloria_s['typewriter_speed']['size'] : 100;
$veloria_tw_delay      = isset($veloria_s['typewriter_start_delay']['size']) ? (int) $veloria_s['typewriter_start_delay']['size'] : 0;
$veloria_tw_cursor     = isset($veloria_s['typewriter_show_cursor']) && $veloria_s['typewriter_show_cursor'] === 'yes';
$veloria_tw_cursor_ch  = isset($veloria_s['typewriter_cursor_char']) ? (string) $veloria_s['typewriter_cursor_char'] : '|';
$veloria_tw_loop       = isset($veloria_s['typewriter_loop']) && $veloria_s['typewriter_loop'] === 'yes';
$veloria_tw_loop_pause = isset($veloria_s['typewriter_loop_pause']['size']) ? (int) $veloria_s['typewriter_loop_pause']['size'] : 2000;
$veloria_tw_between    = isset($veloria_s['typewriter_between_phrases']['size']) ? (int) $veloria_s['typewriter_between_phrases']['size'] : 600;

$veloria_rev_stagger   = isset($veloria_s['reveal_stagger']['size']) ? (int) $veloria_s['reveal_stagger']['size'] : 80;
$veloria_rev_duration  = isset($veloria_s['reveal_duration']['size']) ? (int) $veloria_s['reveal_duration']['size'] : 500;
$veloria_rev_dir       = isset($veloria_s['reveal_direction']) ? $veloria_s['reveal_direction'] : 'up';
$veloria_rev_once      = isset($veloria_s['reveal_once']) && $veloria_s['reveal_once'] === 'yes';
$veloria_rev_between   = isset($veloria_s['reveal_between_phrases']['size']) ? (int) $veloria_s['reveal_between_phrases']['size'] : 600;

$veloria_sq_squeeze = isset($veloria_s['square_squeeze_duration']['size']) ? (int) $veloria_s['square_squeeze_duration']['size'] : 600;
$veloria_sq_expand  = isset($veloria_s['square_expand_duration']['size']) ? (int) $veloria_s['square_expand_duration']['size'] : 600;
$veloria_sq_hold    = isset($veloria_s['square_hold_duration']['size']) ? (int) $veloria_s['square_hold_duration']['size'] : 800;
$veloria_sq_delay   = isset($veloria_s['square_start_delay']['size']) ? (int) $veloria_s['square_start_delay']['size'] : 0;
$veloria_sq_once    = isset($veloria_s['square_squeeze_once']) && $veloria_s['square_squeeze_once'] === 'yes';

$veloria_phrase_list_loop = isset($veloria_s['phrase_list_loop']) && $veloria_s['phrase_list_loop'] === 'yes';

$veloria_animated_first = $veloria_phrases[0] ?? '';

$veloria_classes = ['veloria-animated-text', 'veloria-animated-text--' . sanitize_html_class($veloria_animation)];
if ($veloria_fill === 'gradient') {
    $veloria_classes[] = 'veloria-animated-text--gradient';
}

$veloria_inline_style = '';
if ($veloria_animation === 'reveal') {
    $veloria_inline_style = '--veloria-at-stagger:' . max(0, $veloria_rev_stagger) . 'ms;--veloria-at-duration:' . max(50, $veloria_rev_duration) . 'ms;';
}

$veloria_phrases_json = wp_json_encode(array_values($veloria_phrases), JSON_UNESCAPED_UNICODE);

$veloria_attrs = [
    'data-animation'          => esc_attr($veloria_animation),
    'data-typewriter-speed'   => (string) max(10, $veloria_tw_speed),
    'data-typewriter-delay'   => (string) max(0, $veloria_tw_delay),
    'data-typewriter-loop'    => $veloria_tw_loop ? '1' : '0',
    'data-typewriter-pause'   => (string) max(100, $veloria_tw_loop_pause),
    'data-typewriter-between' => (string) max(0, $veloria_tw_between),
    'data-reveal-stagger'     => (string) max(0, $veloria_rev_stagger),
    'data-reveal-duration'    => (string) max(50, $veloria_rev_duration),
    'data-reveal-direction'   => esc_attr($veloria_rev_dir),
    'data-reveal-once'        => $veloria_rev_once ? '1' : '0',
    'data-reveal-between'     => (string) max(0, $veloria_rev_between),
    'data-phrase-list-loop'   => $veloria_phrase_list_loop ? '1' : '0',
    'data-square-squeeze'     => (string) max(100, $veloria_sq_squeeze),
    'data-square-expand'      => (string) max(100, $veloria_sq_expand),
    'data-square-hold'        => (string) max(0, $veloria_sq_hold),
    'data-square-delay'       => (string) max(0, $veloria_sq_delay),
    'data-square-once'        => $veloria_sq_once ? '1' : '0',
];

$veloria_attr_html = '';
foreach ($veloria_attrs as $veloria_k => $veloria_v) {
    $veloria_attr_html .= ' ' . esc_attr($veloria_k) . '="' . esc_attr($veloria_v) . '"';
}

?>
<span class="<?php echo esc_attr(implode(' ', $veloria_classes)); ?>"<?php echo $veloria_attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $veloria_inline_style !== '' ? ' style="' . esc_attr($veloria_inline_style) . '"' : ''; ?> data-animated-phrases="<?php echo esc_attr($veloria_phrases_json); ?>">
    <span class="veloria-animated-text__before"><?php echo esc_html($veloria_before); ?></span><?php
    if ($veloria_animation === 'typewriter') :
        ?><span class="veloria-animated-text__animated-wrap"><?php
        ?><span class="veloria-animated-text__animated"><span class="veloria-animated-text__typed" aria-live="polite"></span></span><?php
        if ($veloria_tw_cursor) {
            ?><span class="veloria-animated-text__cursor" aria-hidden="true"><?php echo esc_html($veloria_tw_cursor_ch); ?></span><?php
        }
        ?></span><?php
    elseif ($veloria_animation === 'square_squeeze') :
        ?><span class="veloria-animated-text__squeeze"><?php
        ?><span class="veloria-animated-text__squeeze-frame"><?php
        ?><span class="veloria-animated-text__squeeze-inner"><?php
        ?><span class="veloria-animated-text__animated veloria-animated-text__squeeze-sizer" aria-hidden="true"><?php echo esc_html($veloria_animated_first); ?></span><?php
        ?><span class="veloria-animated-text__animated veloria-animated-text__squeeze-text"><?php echo esc_html($veloria_animated_first); ?></span><?php
        ?></span><?php
        ?><span class="veloria-animated-text__squeeze-corner veloria-animated-text__squeeze-corner--tl" aria-hidden="true"></span><?php
        ?><span class="veloria-animated-text__squeeze-corner veloria-animated-text__squeeze-corner--tr" aria-hidden="true"></span><?php
        ?><span class="veloria-animated-text__squeeze-corner veloria-animated-text__squeeze-corner--br" aria-hidden="true"></span><?php
        ?><span class="veloria-animated-text__squeeze-corner veloria-animated-text__squeeze-corner--bl" aria-hidden="true"></span><?php
        ?></span></span><?php
    else :
        $veloria_segments = '';
        if ($veloria_animated_first !== '') {
            $veloria_segments = $veloria_reveal_split === 'char'
                ? \Veloria\Widgets\AnimatedText\AnimatedText::build_reveal_segments_chars($veloria_animated_first)
                : \Veloria\Widgets\AnimatedText\AnimatedText::build_reveal_segments($veloria_animated_first);
        }
        ?><span class="veloria-animated-text__animated"><?php echo $veloria_segments; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php
    endif;
    ?><span class="veloria-animated-text__after"><?php echo esc_html($veloria_after); ?></span>
</span>
