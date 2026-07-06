<?php
defined('ABSPATH') || exit;

use Elementor\Plugin;

$addonext_settings = $this->get_settings_for_display();
$addonext_headingTitle = isset($addonext_settings['heading_title']) ? (string) $addonext_settings['heading_title'] : '';
$addonext_headingSubtitle = isset($addonext_settings['heading_subtitle']) ? (string) $addonext_settings['heading_subtitle'] : '';
$addonext_footerNote = isset($addonext_settings['footer_note']) ? (string) $addonext_settings['footer_note'] : '';
$addonext_showSubtitle = ($addonext_settings['show_subtitle'] ?? 'yes') === 'yes';
$addonext_showHeaderRule = ($addonext_settings['show_header_rule'] ?? 'yes') === 'yes';
$addonext_showFooter = ($addonext_settings['show_footer'] ?? 'yes') === 'yes';
$addonext_items = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];
$addonext_isEditor = Plugin::$instance->editor->is_edit_mode();

$this->add_render_attribute('wrapper', 'class', 'addonext-price-list');
$this->add_render_attribute('heading_title', 'class', 'addonext-price-list__main-title');
$this->add_inline_editing_attributes('heading_title', 'basic');
$this->add_render_attribute('heading_subtitle', 'class', 'addonext-price-list__subtitle');
$this->add_inline_editing_attributes('heading_subtitle', 'advanced');
$this->add_render_attribute('footer_note', 'class', 'addonext-price-list__footer-note');
$this->add_inline_editing_attributes('footer_note', 'advanced');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="addonext-price-list__box">
        <header class="addonext-price-list__header">
            <?php if ($addonext_headingTitle !== '' || $addonext_isEditor) : ?>
                <h2 <?php $this->print_render_attribute_string('heading_title'); ?>><?php echo esc_html($addonext_headingTitle); ?></h2>
            <?php endif; ?>
            <?php if ($addonext_showSubtitle && ($addonext_headingSubtitle !== '' || $addonext_isEditor)) : ?>
                <p <?php $this->print_render_attribute_string('heading_subtitle'); ?>><?php echo wp_kses_post(nl2br(esc_html($addonext_headingSubtitle))); ?></p>
            <?php endif; ?>
            <?php if ($addonext_showHeaderRule) : ?>
                <span class="addonext-price-list__header-rule" aria-hidden="true"></span>
            <?php endif; ?>
        </header>

        <?php if (!empty($addonext_items)) : ?>
            <ul class="addonext-price-list__items">
                <?php foreach ($addonext_items as $addonext_index => $addonext_item) : ?>
                    <?php
                    $addonext_itemTitle = isset($addonext_item['title']) ? (string) $addonext_item['title'] : '';
                    $addonext_itemDesc = isset($addonext_item['description']) ? (string) $addonext_item['description'] : '';
                    $addonext_itemPrice = isset($addonext_item['price']) ? (string) $addonext_item['price'] : '';
                    $addonext_titleKey = 'items.' . $addonext_index . '.title';
                    $addonext_descKey = 'items.' . $addonext_index . '.description';
                    $addonext_priceKey = 'items.' . $addonext_index . '.price';
                    $this->add_render_attribute($addonext_titleKey, 'class', 'addonext-price-list__item-title');
                    $this->add_inline_editing_attributes($addonext_titleKey, 'basic');
                    $this->add_render_attribute($addonext_descKey, 'class', 'addonext-price-list__item-desc');
                    $this->add_inline_editing_attributes($addonext_descKey, 'advanced');
                    $this->add_render_attribute($addonext_priceKey, 'class', 'addonext-price-list__item-price');
                    $this->add_inline_editing_attributes($addonext_priceKey, 'basic');
                    ?>
                    <li class="addonext-price-list__item">
                        <div class="addonext-price-list__line">
                            <?php if ($addonext_itemTitle !== '' || $addonext_isEditor) : ?>
                                <span <?php $this->print_render_attribute_string($addonext_titleKey); ?>><?php echo esc_html($addonext_itemTitle); ?></span>
                            <?php endif; ?>
                            <?php if ($addonext_itemPrice !== '' || $addonext_isEditor) : ?>
                                <span <?php $this->print_render_attribute_string($addonext_priceKey); ?>><?php echo esc_html($addonext_itemPrice); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($addonext_itemDesc !== '' || $addonext_isEditor) : ?>
                            <p <?php $this->print_render_attribute_string($addonext_descKey); ?>><?php echo wp_kses_post(nl2br(esc_html($addonext_itemDesc))); ?></p>
                        <?php endif; ?>
                        <span class="addonext-price-list__separator" aria-hidden="true"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($addonext_showFooter && ($addonext_footerNote !== '' || $addonext_isEditor)) : ?>
            <footer class="addonext-price-list__footer">
                <p <?php $this->print_render_attribute_string('footer_note'); ?>><?php echo wp_kses_post(nl2br(esc_html($addonext_footerNote))); ?></p>
            </footer>
        <?php endif; ?>
    </div>
</div>
