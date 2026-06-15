<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$effect  = isset($settings['hover_effect']) ? $settings['hover_effect'] : 'flip-horizontal';
$trigger = isset($settings['trigger']) ? $settings['trigger'] : 'hover';
$back_url = isset($settings['back_button_url']['url']) ? $settings['back_button_url']['url'] : '#';
$back_external = !empty($settings['back_button_url']['is_external']);
$back_nofollow = !empty($settings['back_button_url']['nofollow']);
?>
<div class="veloria-flip-box veloria-flip-box--<?php echo esc_attr($effect); ?> veloria-flip-box--trigger-<?php echo esc_attr($trigger); ?>">
    <div class="veloria-flip-box__inner">
        <div class="veloria-flip-box__front">
            <div class="veloria-flip-box__front-content">
                <div class="veloria-flip-box__front-icon">
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
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                        echo $icon_html;
                    } else {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from plugin.
                        echo \Veloria\Widgets\FlipBox\FlipBox::get_cube_icon_svg();
                    }
                    ?>
                </div>
                <?php if (isset($settings['front_title']) && $settings['front_title'] !== '') : ?>
                    <h3 class="veloria-flip-box__front-title"><?php echo esc_html($settings['front_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($settings['front_description']) && $settings['front_description'] !== '') : ?>
                    <p class="veloria-flip-box__front-desc"><?php echo esc_html($settings['front_description']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="veloria-flip-box__back">
            <div class="veloria-flip-box__back-content">
                <?php if (isset($settings['back_title']) && $settings['back_title'] !== '') : ?>
                    <h3 class="veloria-flip-box__back-title"><?php echo esc_html($settings['back_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($settings['back_description']) && $settings['back_description'] !== '') : ?>
                    <p class="veloria-flip-box__back-desc"><?php echo esc_html($settings['back_description']); ?></p>
                <?php endif; ?>
                <?php if (isset($settings['back_button_text']) && $settings['back_button_text'] !== '') : ?>
                    <a href="<?php echo esc_url($back_url); ?>"<?php
                    if ($back_external) {
                        echo ' target="_blank"';
                    }
                    if ($back_nofollow) {
                        echo ' rel="nofollow"';
                    }
                    ?> class="veloria-flip-box__back-button"><?php echo esc_html($settings['back_button_text']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
