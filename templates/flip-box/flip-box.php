<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_effect  = isset($veloria_settings['hover_effect']) ? $veloria_settings['hover_effect'] : 'flip-horizontal';
$veloria_trigger = isset($veloria_settings['trigger']) ? $veloria_settings['trigger'] : 'hover';
$veloria_back_url = isset($veloria_settings['back_button_url']['url']) ? $veloria_settings['back_button_url']['url'] : '#';
$veloria_back_external = !empty($veloria_settings['back_button_url']['is_external']);
$veloria_back_nofollow = !empty($veloria_settings['back_button_url']['nofollow']);
?>
<div class="veloria-flip-box veloria-flip-box--<?php echo esc_attr($veloria_effect); ?> veloria-flip-box--trigger-<?php echo esc_attr($veloria_trigger); ?>">
    <div class="veloria-flip-box__inner">
        <div class="veloria-flip-box__front">
            <div class="veloria-flip-box__front-content">
                <div class="veloria-flip-box__front-icon">
                    <?php
                    $veloria_is_default_cube = !empty($veloria_settings['front_icon']['value'])
                        && $veloria_settings['front_icon']['value'] === 'eicon-cube'
                        && isset($veloria_settings['front_icon']['library'])
                        && $veloria_settings['front_icon']['library'] === 'eicons';
                    $veloria_icon_html = '';
                    if (!empty($veloria_settings['front_icon']['value']) && !$veloria_is_default_cube) {
                        ob_start();
                        \Elementor\Icons_Manager::render_icon($veloria_settings['front_icon'], ['aria-hidden' => 'true']);
                        $veloria_icon_html = ob_get_clean();
                    }
                    if ($veloria_icon_html !== '' && trim($veloria_icon_html) !== '') {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                        echo $veloria_icon_html;
                    } else {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from plugin.
                        echo \Veloria\Widgets\FlipBox\FlipBox::get_cube_icon_svg();
                    }
                    ?>
                </div>
                <?php if (isset($veloria_settings['front_title']) && $veloria_settings['front_title'] !== '') : ?>
                    <h3 class="veloria-flip-box__front-title"><?php echo esc_html($veloria_settings['front_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($veloria_settings['front_description']) && $veloria_settings['front_description'] !== '') : ?>
                    <p class="veloria-flip-box__front-desc"><?php echo esc_html($veloria_settings['front_description']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="veloria-flip-box__back">
            <div class="veloria-flip-box__back-content">
                <?php if (isset($veloria_settings['back_title']) && $veloria_settings['back_title'] !== '') : ?>
                    <h3 class="veloria-flip-box__back-title"><?php echo esc_html($veloria_settings['back_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($veloria_settings['back_description']) && $veloria_settings['back_description'] !== '') : ?>
                    <p class="veloria-flip-box__back-desc"><?php echo esc_html($veloria_settings['back_description']); ?></p>
                <?php endif; ?>
                <?php if (isset($veloria_settings['back_button_text']) && $veloria_settings['back_button_text'] !== '') : ?>
                    <a href="<?php echo esc_url($veloria_back_url); ?>"<?php
                    if ($veloria_back_external) {
                        echo ' target="_blank"';
                    }
                    if ($veloria_back_nofollow) {
                        echo ' rel="nofollow"';
                    }
                    ?> class="veloria-flip-box__back-button"><?php echo esc_html($veloria_settings['back_button_text']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
