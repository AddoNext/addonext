<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$this->add_render_attribute('wrapper', 'class', 'elementrify-blockquote');
$this->add_inline_editing_attributes('quote', 'none');

$show_icon = !empty($settings['show_quote_icon']) && $settings['show_quote_icon'] === 'yes';
$has_author = !empty($settings['author']) || !empty($settings['author_title']) || !empty($settings['author_image']['url']);
?>
<blockquote <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="elementrify-blockquote__body">
        <?php if ($show_icon) : ?>
            <span class="elementrify-blockquote__icon" aria-hidden="true">&ldquo;</span>
        <?php endif; ?>
        <p class="elementrify-blockquote__text" <?php $this->print_render_attribute_string('quote'); ?>><?php echo wp_kses_post($settings['quote'] ?? ''); ?></p>
    </div>
    <?php if ($has_author) : ?>
        <footer class="elementrify-blockquote__footer">
            <?php if (!empty($settings['author_image']['url'])) : ?>
                <span class="elementrify-blockquote__avatar">
                    <img src="<?php echo esc_url($settings['author_image']['url']); ?>" alt="<?php echo esc_attr($settings['author'] ?? ''); ?>">
                </span>
            <?php endif; ?>
            <span class="elementrify-blockquote__author-info">
                <?php if (!empty($settings['author'])) : ?>
                    <cite class="elementrify-blockquote__author-name"><?php echo esc_html($settings['author']); ?></cite>
                <?php endif; ?>
                <?php if (!empty($settings['author_title'])) : ?>
                    <span class="elementrify-blockquote__author-title"><?php echo esc_html($settings['author_title']); ?></span>
                <?php endif; ?>
            </span>
        </footer>
    <?php endif; ?>
</blockquote>
