<?php
defined('ABSPATH') || exit;

$s = $this->get_settings_for_display();

$before        = isset($s['before_text']) ? (string) $s['before_text'] : '';
$after         = isset($s['after_text']) ? (string) $s['after_text'] : '';
$phrases       = \Elementrify\Widgets\AnimatedText\AnimatedText::get_phrases_from_settings($s);
$animation     = isset($s['animation_type']) ? $s['animation_type'] : 'typewriter';
$reveal_split  = isset($s['reveal_split']) ? $s['reveal_split'] : 'word';
$fill          = isset($s['animated_fill']) ? $s['animated_fill'] : 'solid';

$tw_speed      = isset($s['typewriter_speed']['size']) ? (int) $s['typewriter_speed']['size'] : 100;
$tw_delay      = isset($s['typewriter_start_delay']['size']) ? (int) $s['typewriter_start_delay']['size'] : 0;
$tw_cursor     = isset($s['typewriter_show_cursor']) && $s['typewriter_show_cursor'] === 'yes';
$tw_cursor_ch  = isset($s['typewriter_cursor_char']) ? (string) $s['typewriter_cursor_char'] : '|';
$tw_loop       = isset($s['typewriter_loop']) && $s['typewriter_loop'] === 'yes';
$tw_loop_pause = isset($s['typewriter_loop_pause']['size']) ? (int) $s['typewriter_loop_pause']['size'] : 2000;
$tw_between    = isset($s['typewriter_between_phrases']['size']) ? (int) $s['typewriter_between_phrases']['size'] : 600;

$rev_stagger   = isset($s['reveal_stagger']['size']) ? (int) $s['reveal_stagger']['size'] : 80;
$rev_duration  = isset($s['reveal_duration']['size']) ? (int) $s['reveal_duration']['size'] : 500;
$rev_dir       = isset($s['reveal_direction']) ? $s['reveal_direction'] : 'up';
$rev_once      = isset($s['reveal_once']) && $s['reveal_once'] === 'yes';
$rev_between   = isset($s['reveal_between_phrases']['size']) ? (int) $s['reveal_between_phrases']['size'] : 600;

$sq_squeeze = isset($s['square_squeeze_duration']['size']) ? (int) $s['square_squeeze_duration']['size'] : 600;
$sq_expand  = isset($s['square_expand_duration']['size']) ? (int) $s['square_expand_duration']['size'] : 600;
$sq_hold    = isset($s['square_hold_duration']['size']) ? (int) $s['square_hold_duration']['size'] : 800;
$sq_delay   = isset($s['square_start_delay']['size']) ? (int) $s['square_start_delay']['size'] : 0;
$sq_once    = isset($s['square_squeeze_once']) && $s['square_squeeze_once'] === 'yes';

$phrase_list_loop = isset($s['phrase_list_loop']) && $s['phrase_list_loop'] === 'yes';

$animated_first = $phrases[0] ?? '';

$classes = ['elementrify-animated-text', 'elementrify-animated-text--' . sanitize_html_class($animation)];
if ($fill === 'gradient') {
    $classes[] = 'elementrify-animated-text--gradient';
}

$inline_style = '';
if ($animation === 'reveal') {
    $inline_style = '--elementrify-at-stagger:' . max(0, $rev_stagger) . 'ms;--elementrify-at-duration:' . max(50, $rev_duration) . 'ms;';
}

$phrases_json = wp_json_encode(array_values($phrases), JSON_UNESCAPED_UNICODE);

$attrs = [
    'data-animation'          => esc_attr($animation),
    'data-typewriter-speed'   => (string) max(10, $tw_speed),
    'data-typewriter-delay'   => (string) max(0, $tw_delay),
    'data-typewriter-loop'    => $tw_loop ? '1' : '0',
    'data-typewriter-pause'   => (string) max(100, $tw_loop_pause),
    'data-typewriter-between' => (string) max(0, $tw_between),
    'data-reveal-stagger'     => (string) max(0, $rev_stagger),
    'data-reveal-duration'    => (string) max(50, $rev_duration),
    'data-reveal-direction'   => esc_attr($rev_dir),
    'data-reveal-once'        => $rev_once ? '1' : '0',
    'data-reveal-between'     => (string) max(0, $rev_between),
    'data-phrase-list-loop'   => $phrase_list_loop ? '1' : '0',
    'data-square-squeeze'     => (string) max(100, $sq_squeeze),
    'data-square-expand'      => (string) max(100, $sq_expand),
    'data-square-hold'        => (string) max(0, $sq_hold),
    'data-square-delay'       => (string) max(0, $sq_delay),
    'data-square-once'        => $sq_once ? '1' : '0',
];

$attr_html = '';
foreach ($attrs as $k => $v) {
    $attr_html .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"';
}

?>
<span class="<?php echo esc_attr(implode(' ', $classes)); ?>"<?php echo $attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $inline_style !== '' ? ' style="' . esc_attr($inline_style) . '"' : ''; ?> data-animated-phrases="<?php echo esc_attr($phrases_json); ?>">
    <span class="elementrify-animated-text__before"><?php echo esc_html($before); ?></span><?php
    if ($animation === 'typewriter') :
        ?><span class="elementrify-animated-text__animated-wrap"><?php
        ?><span class="elementrify-animated-text__animated"><span class="elementrify-animated-text__typed" aria-live="polite"></span></span><?php
        if ($tw_cursor) {
            ?><span class="elementrify-animated-text__cursor" aria-hidden="true"><?php echo esc_html($tw_cursor_ch); ?></span><?php
        }
        ?></span><?php
    elseif ($animation === 'square_squeeze') :
        ?><span class="elementrify-animated-text__squeeze"><?php
        ?><span class="elementrify-animated-text__squeeze-frame"><?php
        ?><span class="elementrify-animated-text__squeeze-inner"><?php
        ?><span class="elementrify-animated-text__animated elementrify-animated-text__squeeze-sizer" aria-hidden="true"><?php echo esc_html($animated_first); ?></span><?php
        ?><span class="elementrify-animated-text__animated elementrify-animated-text__squeeze-text"><?php echo esc_html($animated_first); ?></span><?php
        ?></span><?php
        ?><span class="elementrify-animated-text__squeeze-corner elementrify-animated-text__squeeze-corner--tl" aria-hidden="true"></span><?php
        ?><span class="elementrify-animated-text__squeeze-corner elementrify-animated-text__squeeze-corner--tr" aria-hidden="true"></span><?php
        ?><span class="elementrify-animated-text__squeeze-corner elementrify-animated-text__squeeze-corner--br" aria-hidden="true"></span><?php
        ?><span class="elementrify-animated-text__squeeze-corner elementrify-animated-text__squeeze-corner--bl" aria-hidden="true"></span><?php
        ?></span></span><?php
    else :
        $segments = '';
        if ($animated_first !== '') {
            $segments = $reveal_split === 'char'
                ? \Elementrify\Widgets\AnimatedText\AnimatedText::build_reveal_segments_chars($animated_first)
                : \Elementrify\Widgets\AnimatedText\AnimatedText::build_reveal_segments($animated_first);
        }
        ?><span class="elementrify-animated-text__animated"><?php echo $segments; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php
    endif;
    ?><span class="elementrify-animated-text__after"><?php echo esc_html($after); ?></span>
</span>
