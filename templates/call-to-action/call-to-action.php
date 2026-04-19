<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$title   = isset($settings['title']) ? $settings['title'] : '';
$desc    = isset($settings['description']) ? $settings['description'] : '';
$btn_text = isset($settings['button_text']) ? $settings['button_text'] : '';
$btn_url  = isset($settings['button_url']['url']) ? $settings['button_url']['url'] : '#';
$btn_external = !empty($settings['button_url']['is_external']);
$btn_nofollow = !empty($settings['button_url']['nofollow']);

?>
<div class="elementrify-cta">
    <div class="elementrify-cta__inner">
        <?php if ($title !== '') : ?>
            <h3 class="elementrify-cta__title"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        <?php if ($desc !== '') : ?>
            <p class="elementrify-cta__description"><?php echo esc_html($desc); ?></p>
        <?php endif; ?>
        <?php if ($btn_text !== '') : ?>
            <a href="<?php echo esc_url($btn_url); ?>"<?php
            if ($btn_external) {
                echo ' target="_blank"';
            }
            if ($btn_nofollow) {
                echo ' rel="nofollow"';
            }
            ?> class="elementrify-cta__button"><?php echo esc_html($btn_text); ?></a>
        <?php endif; ?>
    </div>
</div>
