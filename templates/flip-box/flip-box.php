<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$effect  = isset($settings['hover_effect']) ? $settings['hover_effect'] : 'flip-horizontal';
$trigger = isset($settings['trigger']) ? $settings['trigger'] : 'hover';
$back_url = isset($settings['back_button_url']['url']) ? $settings['back_button_url']['url'] : '#';
$back_external = !empty($settings['back_button_url']['is_external']);
$back_nofollow = !empty($settings['back_button_url']['nofollow']);
$back_attrs = [];
if ($back_external) {
    $back_attrs[] = 'target="_blank"';
}
if ($back_nofollow) {
    $back_attrs[] = 'rel="nofollow"';
}
$back_attrs_str = implode(' ', $back_attrs);
?>
<div class="elementrify-flip-box elementrify-flip-box--<?php echo esc_attr($effect); ?> elementrify-flip-box--trigger-<?php echo esc_attr($trigger); ?>">
    <div class="elementrify-flip-box__inner">
        <div class="elementrify-flip-box__front">
            <div class="elementrify-flip-box__front-content">
                <div class="elementrify-flip-box__front-icon">
                    <?php
                    $is_default_cube = !empty($settings['front_icon']['value'])
                        && $settings['front_icon']['value'] === 'eicon-cube'
                        && isset($settings['front_icon']['library'])
                        && $settings['front_icon']['library'] === 'eicons';
                    $icon_html = '';
                    if (!empty($settings['front_icon']['value']) && !$is_default_cube) {
                        ob_start();
                        \Elementor\Icons_Manager::render_icon($settings['front_icon'], ['aria-hidden' => 'true']);
                        $icon_html = ob_get_clean();
                    }
                    if ($icon_html !== '' && trim($icon_html) !== '') {
                        echo $icon_html;
                    } else {
                        echo \Elementrify\Widgets\FlipBox\FlipBox::get_cube_icon_svg();
                    }
                    ?>
                </div>
                <?php if (isset($settings['front_title']) && $settings['front_title'] !== '') : ?>
                    <h3 class="elementrify-flip-box__front-title"><?php echo esc_html($settings['front_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($settings['front_description']) && $settings['front_description'] !== '') : ?>
                    <p class="elementrify-flip-box__front-desc"><?php echo esc_html($settings['front_description']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="elementrify-flip-box__back">
            <div class="elementrify-flip-box__back-content">
                <?php if (isset($settings['back_title']) && $settings['back_title'] !== '') : ?>
                    <h3 class="elementrify-flip-box__back-title"><?php echo esc_html($settings['back_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($settings['back_description']) && $settings['back_description'] !== '') : ?>
                    <p class="elementrify-flip-box__back-desc"><?php echo esc_html($settings['back_description']); ?></p>
                <?php endif; ?>
                <?php if (isset($settings['back_button_text']) && $settings['back_button_text'] !== '') : ?>
                    <a href="<?php echo esc_url($back_url); ?>" <?php echo $back_attrs_str ? $back_attrs_str : ''; ?> class="elementrify-flip-box__back-button"><?php echo esc_html($settings['back_button_text']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
