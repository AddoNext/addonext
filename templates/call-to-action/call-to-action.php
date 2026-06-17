<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_title   = isset($veloria_settings['title']) ? $veloria_settings['title'] : '';
$veloria_desc    = isset($veloria_settings['description']) ? $veloria_settings['description'] : '';
$veloria_btn_text = isset($veloria_settings['button_text']) ? $veloria_settings['button_text'] : '';
$veloria_btn_url  = isset($veloria_settings['button_url']['url']) ? $veloria_settings['button_url']['url'] : '#';
$veloria_btn_external = !empty($veloria_settings['button_url']['is_external']);
$veloria_btn_nofollow = !empty($veloria_settings['button_url']['nofollow']);

?>
<div class="veloria-cta">
    <div class="veloria-cta__inner">
        <?php if ($veloria_title !== '') : ?>
            <h3 class="veloria-cta__title"><?php echo esc_html($veloria_title); ?></h3>
        <?php endif; ?>
        <?php if ($veloria_desc !== '') : ?>
            <p class="veloria-cta__description"><?php echo esc_html($veloria_desc); ?></p>
        <?php endif; ?>
        <?php if ($veloria_btn_text !== '') : ?>
            <a href="<?php echo esc_url($veloria_btn_url); ?>"<?php
            if ($veloria_btn_external) {
                echo ' target="_blank"';
            }
            if ($veloria_btn_nofollow) {
                echo ' rel="nofollow"';
            }
            ?> class="veloria-cta__button"><?php echo esc_html($veloria_btn_text); ?></a>
        <?php endif; ?>
    </div>
</div>
