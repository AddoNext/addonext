<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$this->add_render_attribute('wrapper', 'class', 'elementrify-blockquote');
$this->add_inline_editing_attributes('quote', 'none');
?>
<blockquote <?php $this->print_render_attribute_string('wrapper'); ?>>
    <p class="elementrify-blockquote__text" <?php $this->print_render_attribute_string('quote'); ?>><?php echo wp_kses_post($settings['quote']); ?></p>
    <?php if (!empty($settings['author'])) : ?>
        <cite class="elementrify-blockquote__author"><?php echo esc_html($settings['author']); ?></cite>
    <?php endif; ?>
</blockquote>
