<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$this->add_render_attribute('wrapper', 'class', 'addonext-blockquote');
$this->add_inline_editing_attributes('quote', 'none');

$addonext_show_icon = !empty($addonext_settings['show_quote_icon']) && $addonext_settings['show_quote_icon'] === 'yes';
$addonext_has_author = !empty($addonext_settings['author']) || !empty($addonext_settings['author_title']) || !empty($addonext_settings['author_image']['url']);
?>
<blockquote <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="addonext-blockquote__body">
        <?php if ($addonext_show_icon) : ?>
            <span class="addonext-blockquote__icon" aria-hidden="true">&ldquo;</span>
        <?php endif; ?>
        <p class="addonext-blockquote__text" <?php $this->print_render_attribute_string('quote'); ?>><?php echo wp_kses_post($addonext_settings['quote'] ?? ''); ?></p>
    </div>
    <?php if ($addonext_has_author) : ?>
        <footer class="addonext-blockquote__footer">
            <?php if (!empty($addonext_settings['author_image']['url'])) : ?>
                <span class="addonext-blockquote__avatar">
                    <img src="<?php echo esc_url($addonext_settings['author_image']['url']); ?>" alt="<?php echo esc_attr($addonext_settings['author'] ?? ''); ?>">
                </span>
            <?php endif; ?>
            <span class="addonext-blockquote__author-info">
                <?php if (!empty($addonext_settings['author'])) : ?>
                    <cite class="addonext-blockquote__author-name"><?php echo esc_html($addonext_settings['author']); ?></cite>
                <?php endif; ?>
                <?php if (!empty($addonext_settings['author_title'])) : ?>
                    <span class="addonext-blockquote__author-title"><?php echo esc_html($addonext_settings['author_title']); ?></span>
                <?php endif; ?>
            </span>
        </footer>
    <?php endif; ?>
</blockquote>
