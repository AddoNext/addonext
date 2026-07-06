<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_effect  = isset($addonext_settings['hover_effect']) ? $addonext_settings['hover_effect'] : 'flip-horizontal';
$addonext_trigger = isset($addonext_settings['trigger']) ? $addonext_settings['trigger'] : 'hover';
$addonext_back_url = isset($addonext_settings['back_button_url']['url']) ? $addonext_settings['back_button_url']['url'] : '#';
$addonext_back_external = !empty($addonext_settings['back_button_url']['is_external']);
$addonext_back_nofollow = !empty($addonext_settings['back_button_url']['nofollow']);
?>
<div class="addonext-flip-box addonext-flip-box--<?php echo esc_attr($addonext_effect); ?> addonext-flip-box--trigger-<?php echo esc_attr($addonext_trigger); ?>">
    <div class="addonext-flip-box__inner">
        <div class="addonext-flip-box__front">
            <div class="addonext-flip-box__front-content">
                <div class="addonext-flip-box__front-icon">
                    <?php
                    $addonext_is_default_cube = !empty($addonext_settings['front_icon']['value'])
                        && $addonext_settings['front_icon']['value'] === 'eicon-cube'
                        && isset($addonext_settings['front_icon']['library'])
                        && $addonext_settings['front_icon']['library'] === 'eicons';
                    $addonext_icon_html = '';
                    if (!empty($addonext_settings['front_icon']['value']) && !$addonext_is_default_cube) {
                        ob_start();
                        \Elementor\Icons_Manager::render_icon($addonext_settings['front_icon'], ['aria-hidden' => 'true']);
                        $addonext_icon_html = ob_get_clean();
                    }
                    if ($addonext_icon_html !== '' && trim($addonext_icon_html) !== '') {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                        echo $addonext_icon_html;
                    } else {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from plugin.
                        echo \Addonext\Widgets\FlipBox\FlipBox::get_cube_icon_svg();
                    }
                    ?>
                </div>
                <?php if (isset($addonext_settings['front_title']) && $addonext_settings['front_title'] !== '') : ?>
                    <h3 class="addonext-flip-box__front-title"><?php echo esc_html($addonext_settings['front_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($addonext_settings['front_description']) && $addonext_settings['front_description'] !== '') : ?>
                    <p class="addonext-flip-box__front-desc"><?php echo esc_html($addonext_settings['front_description']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="addonext-flip-box__back">
            <div class="addonext-flip-box__back-content">
                <?php if (isset($addonext_settings['back_title']) && $addonext_settings['back_title'] !== '') : ?>
                    <h3 class="addonext-flip-box__back-title"><?php echo esc_html($addonext_settings['back_title']); ?></h3>
                <?php endif; ?>
                <?php if (isset($addonext_settings['back_description']) && $addonext_settings['back_description'] !== '') : ?>
                    <p class="addonext-flip-box__back-desc"><?php echo esc_html($addonext_settings['back_description']); ?></p>
                <?php endif; ?>
                <?php if (isset($addonext_settings['back_button_text']) && $addonext_settings['back_button_text'] !== '') : ?>
                    <a href="<?php echo esc_url($addonext_back_url); ?>"<?php
                    if ($addonext_back_external) {
                        echo ' target="_blank"';
                    }
                    if ($addonext_back_nofollow) {
                        echo ' rel="nofollow"';
                    }
                    ?> class="addonext-flip-box__back-button"><?php echo esc_html($addonext_settings['back_button_text']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
