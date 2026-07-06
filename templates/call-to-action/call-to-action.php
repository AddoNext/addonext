<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_title   = isset($addonext_settings['title']) ? $addonext_settings['title'] : '';
$addonext_desc    = isset($addonext_settings['description']) ? $addonext_settings['description'] : '';
$addonext_btn_text = isset($addonext_settings['button_text']) ? $addonext_settings['button_text'] : '';
$addonext_btn_url  = isset($addonext_settings['button_url']['url']) ? $addonext_settings['button_url']['url'] : '#';
$addonext_btn_external = !empty($addonext_settings['button_url']['is_external']);
$addonext_btn_nofollow = !empty($addonext_settings['button_url']['nofollow']);

?>
<div class="addonext-cta">
    <div class="addonext-cta__inner">
        <?php if ($addonext_title !== '') : ?>
            <h3 class="addonext-cta__title"><?php echo esc_html($addonext_title); ?></h3>
        <?php endif; ?>
        <?php if ($addonext_desc !== '') : ?>
            <p class="addonext-cta__description"><?php echo esc_html($addonext_desc); ?></p>
        <?php endif; ?>
        <?php if ($addonext_btn_text !== '') : ?>
            <a href="<?php echo esc_url($addonext_btn_url); ?>"<?php
            if ($addonext_btn_external) {
                echo ' target="_blank"';
            }
            if ($addonext_btn_nofollow) {
                echo ' rel="nofollow"';
            }
            ?> class="addonext-cta__button"><?php echo esc_html($addonext_btn_text); ?></a>
        <?php endif; ?>
    </div>
</div>
