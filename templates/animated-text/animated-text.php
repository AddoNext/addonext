<?php
defined('ABSPATH') || exit;

$addonext_s = $this->get_settings_for_display();

$addonext_before        = isset($addonext_s['before_text']) ? (string) $addonext_s['before_text'] : '';
$addonext_after         = isset($addonext_s['after_text']) ? (string) $addonext_s['after_text'] : '';
$addonext_phrases       = \Addonext\Widgets\AnimatedText\AnimatedText::get_phrases_from_settings($addonext_s);
$addonext_animation     = isset($addonext_s['animation_type']) ? $addonext_s['animation_type'] : 'typewriter';
$addonext_reveal_split  = isset($addonext_s['reveal_split']) ? $addonext_s['reveal_split'] : 'word';
$addonext_fill          = isset($addonext_s['animated_fill']) ? $addonext_s['animated_fill'] : 'solid';

$addonext_tw_speed      = isset($addonext_s['typewriter_speed']['size']) ? (int) $addonext_s['typewriter_speed']['size'] : 100;
$addonext_tw_delay      = isset($addonext_s['typewriter_start_delay']['size']) ? (int) $addonext_s['typewriter_start_delay']['size'] : 0;
$addonext_tw_cursor     = isset($addonext_s['typewriter_show_cursor']) && $addonext_s['typewriter_show_cursor'] === 'yes';
$addonext_tw_cursor_ch  = isset($addonext_s['typewriter_cursor_char']) ? (string) $addonext_s['typewriter_cursor_char'] : '|';
$addonext_tw_loop       = isset($addonext_s['typewriter_loop']) && $addonext_s['typewriter_loop'] === 'yes';
$addonext_tw_loop_pause = isset($addonext_s['typewriter_loop_pause']['size']) ? (int) $addonext_s['typewriter_loop_pause']['size'] : 2000;
$addonext_tw_between    = isset($addonext_s['typewriter_between_phrases']['size']) ? (int) $addonext_s['typewriter_between_phrases']['size'] : 600;

$addonext_rev_stagger   = isset($addonext_s['reveal_stagger']['size']) ? (int) $addonext_s['reveal_stagger']['size'] : 80;
$addonext_rev_duration  = isset($addonext_s['reveal_duration']['size']) ? (int) $addonext_s['reveal_duration']['size'] : 500;
$addonext_rev_dir       = isset($addonext_s['reveal_direction']) ? $addonext_s['reveal_direction'] : 'up';
$addonext_rev_once      = isset($addonext_s['reveal_once']) && $addonext_s['reveal_once'] === 'yes';
$addonext_rev_between   = isset($addonext_s['reveal_between_phrases']['size']) ? (int) $addonext_s['reveal_between_phrases']['size'] : 600;

$addonext_sq_squeeze = isset($addonext_s['square_squeeze_duration']['size']) ? (int) $addonext_s['square_squeeze_duration']['size'] : 600;
$addonext_sq_expand  = isset($addonext_s['square_expand_duration']['size']) ? (int) $addonext_s['square_expand_duration']['size'] : 600;
$addonext_sq_hold    = isset($addonext_s['square_hold_duration']['size']) ? (int) $addonext_s['square_hold_duration']['size'] : 800;
$addonext_sq_delay   = isset($addonext_s['square_start_delay']['size']) ? (int) $addonext_s['square_start_delay']['size'] : 0;
$addonext_sq_once    = isset($addonext_s['square_squeeze_once']) && $addonext_s['square_squeeze_once'] === 'yes';

$addonext_phrase_list_loop = isset($addonext_s['phrase_list_loop']) && $addonext_s['phrase_list_loop'] === 'yes';

$addonext_animated_first = $addonext_phrases[0] ?? '';

$addonext_classes = ['addonext-animated-text', 'addonext-animated-text--' . sanitize_html_class($addonext_animation)];
if ($addonext_fill === 'gradient') {
    $addonext_classes[] = 'addonext-animated-text--gradient';
}

$addonext_inline_style = '';
if ($addonext_animation === 'reveal') {
    $addonext_inline_style = '--addonext-at-stagger:' . max(0, $addonext_rev_stagger) . 'ms;--addonext-at-duration:' . max(50, $addonext_rev_duration) . 'ms;';
}

$addonext_phrases_json = wp_json_encode(array_values($addonext_phrases), JSON_UNESCAPED_UNICODE);

$addonext_attrs = [
    'data-animation'          => esc_attr($addonext_animation),
    'data-typewriter-speed'   => (string) max(10, $addonext_tw_speed),
    'data-typewriter-delay'   => (string) max(0, $addonext_tw_delay),
    'data-typewriter-loop'    => $addonext_tw_loop ? '1' : '0',
    'data-typewriter-pause'   => (string) max(100, $addonext_tw_loop_pause),
    'data-typewriter-between' => (string) max(0, $addonext_tw_between),
    'data-reveal-stagger'     => (string) max(0, $addonext_rev_stagger),
    'data-reveal-duration'    => (string) max(50, $addonext_rev_duration),
    'data-reveal-direction'   => esc_attr($addonext_rev_dir),
    'data-reveal-once'        => $addonext_rev_once ? '1' : '0',
    'data-reveal-between'     => (string) max(0, $addonext_rev_between),
    'data-phrase-list-loop'   => $addonext_phrase_list_loop ? '1' : '0',
    'data-square-squeeze'     => (string) max(100, $addonext_sq_squeeze),
    'data-square-expand'      => (string) max(100, $addonext_sq_expand),
    'data-square-hold'        => (string) max(0, $addonext_sq_hold),
    'data-square-delay'       => (string) max(0, $addonext_sq_delay),
    'data-square-once'        => $addonext_sq_once ? '1' : '0',
];

$addonext_attr_html = '';
foreach ($addonext_attrs as $addonext_k => $addonext_v) {
    $addonext_attr_html .= ' ' . esc_attr($addonext_k) . '="' . esc_attr($addonext_v) . '"';
}

?>
<span class="<?php echo esc_attr(implode(' ', $addonext_classes)); ?>"<?php echo $addonext_attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $addonext_inline_style !== '' ? ' style="' . esc_attr($addonext_inline_style) . '"' : ''; ?> data-animated-phrases="<?php echo esc_attr($addonext_phrases_json); ?>">
    <span class="addonext-animated-text__before"><?php echo esc_html($addonext_before); ?></span><?php
    if ($addonext_animation === 'typewriter') :
        ?><span class="addonext-animated-text__animated-wrap"><?php
        ?><span class="addonext-animated-text__animated"><span class="addonext-animated-text__typed" aria-live="polite"></span></span><?php
        if ($addonext_tw_cursor) {
            ?><span class="addonext-animated-text__cursor" aria-hidden="true"><?php echo esc_html($addonext_tw_cursor_ch); ?></span><?php
        }
        ?></span><?php
    elseif ($addonext_animation === 'square_squeeze') :
        ?><span class="addonext-animated-text__squeeze"><?php
        ?><span class="addonext-animated-text__squeeze-frame"><?php
        ?><span class="addonext-animated-text__squeeze-inner"><?php
        ?><span class="addonext-animated-text__animated addonext-animated-text__squeeze-sizer" aria-hidden="true"><?php echo esc_html($addonext_animated_first); ?></span><?php
        ?><span class="addonext-animated-text__animated addonext-animated-text__squeeze-text"><?php echo esc_html($addonext_animated_first); ?></span><?php
        ?></span><?php
        ?><span class="addonext-animated-text__squeeze-corner addonext-animated-text__squeeze-corner--tl" aria-hidden="true"></span><?php
        ?><span class="addonext-animated-text__squeeze-corner addonext-animated-text__squeeze-corner--tr" aria-hidden="true"></span><?php
        ?><span class="addonext-animated-text__squeeze-corner addonext-animated-text__squeeze-corner--br" aria-hidden="true"></span><?php
        ?><span class="addonext-animated-text__squeeze-corner addonext-animated-text__squeeze-corner--bl" aria-hidden="true"></span><?php
        ?></span></span><?php
    else :
        $addonext_segments = '';
        if ($addonext_animated_first !== '') {
            $addonext_segments = $addonext_reveal_split === 'char'
                ? \Addonext\Widgets\AnimatedText\AnimatedText::build_reveal_segments_chars($addonext_animated_first)
                : \Addonext\Widgets\AnimatedText\AnimatedText::build_reveal_segments($addonext_animated_first);
        }
        ?><span class="addonext-animated-text__animated"><?php echo $addonext_segments; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php
    endif;
    ?><span class="addonext-animated-text__after"><?php echo esc_html($addonext_after); ?></span>
</span>
