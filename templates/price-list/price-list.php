<?php
defined('ABSPATH') || exit;

use Elementor\Plugin;

$veloria_settings = $this->get_settings_for_display();
$veloria_headingTitle = isset($veloria_settings['heading_title']) ? (string) $veloria_settings['heading_title'] : '';
$veloria_headingSubtitle = isset($veloria_settings['heading_subtitle']) ? (string) $veloria_settings['heading_subtitle'] : '';
$veloria_footerNote = isset($veloria_settings['footer_note']) ? (string) $veloria_settings['footer_note'] : '';
$veloria_showSubtitle = ($veloria_settings['show_subtitle'] ?? 'yes') === 'yes';
$veloria_showHeaderRule = ($veloria_settings['show_header_rule'] ?? 'yes') === 'yes';
$veloria_showFooter = ($veloria_settings['show_footer'] ?? 'yes') === 'yes';
$veloria_items = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];
$veloria_isEditor = Plugin::$instance->editor->is_edit_mode();

$this->add_render_attribute('wrapper', 'class', 'veloria-price-list');
$this->add_render_attribute('heading_title', 'class', 'veloria-price-list__main-title');
$this->add_inline_editing_attributes('heading_title', 'basic');
$this->add_render_attribute('heading_subtitle', 'class', 'veloria-price-list__subtitle');
$this->add_inline_editing_attributes('heading_subtitle', 'advanced');
$this->add_render_attribute('footer_note', 'class', 'veloria-price-list__footer-note');
$this->add_inline_editing_attributes('footer_note', 'advanced');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="veloria-price-list__box">
        <header class="veloria-price-list__header">
            <?php if ($veloria_headingTitle !== '' || $veloria_isEditor) : ?>
                <h2 <?php $this->print_render_attribute_string('heading_title'); ?>><?php echo esc_html($veloria_headingTitle); ?></h2>
            <?php endif; ?>
            <?php if ($veloria_showSubtitle && ($veloria_headingSubtitle !== '' || $veloria_isEditor)) : ?>
                <p <?php $this->print_render_attribute_string('heading_subtitle'); ?>><?php echo wp_kses_post(nl2br(esc_html($veloria_headingSubtitle))); ?></p>
            <?php endif; ?>
            <?php if ($veloria_showHeaderRule) : ?>
                <span class="veloria-price-list__header-rule" aria-hidden="true"></span>
            <?php endif; ?>
        </header>

        <?php if (!empty($veloria_items)) : ?>
            <ul class="veloria-price-list__items">
                <?php foreach ($veloria_items as $veloria_index => $veloria_item) : ?>
                    <?php
                    $veloria_itemTitle = isset($veloria_item['title']) ? (string) $veloria_item['title'] : '';
                    $veloria_itemDesc = isset($veloria_item['description']) ? (string) $veloria_item['description'] : '';
                    $veloria_itemPrice = isset($veloria_item['price']) ? (string) $veloria_item['price'] : '';
                    $veloria_titleKey = 'items.' . $veloria_index . '.title';
                    $veloria_descKey = 'items.' . $veloria_index . '.description';
                    $veloria_priceKey = 'items.' . $veloria_index . '.price';
                    $this->add_render_attribute($veloria_titleKey, 'class', 'veloria-price-list__item-title');
                    $this->add_inline_editing_attributes($veloria_titleKey, 'basic');
                    $this->add_render_attribute($veloria_descKey, 'class', 'veloria-price-list__item-desc');
                    $this->add_inline_editing_attributes($veloria_descKey, 'advanced');
                    $this->add_render_attribute($veloria_priceKey, 'class', 'veloria-price-list__item-price');
                    $this->add_inline_editing_attributes($veloria_priceKey, 'basic');
                    ?>
                    <li class="veloria-price-list__item">
                        <div class="veloria-price-list__line">
                            <?php if ($veloria_itemTitle !== '' || $veloria_isEditor) : ?>
                                <span <?php $this->print_render_attribute_string($veloria_titleKey); ?>><?php echo esc_html($veloria_itemTitle); ?></span>
                            <?php endif; ?>
                            <?php if ($veloria_itemPrice !== '' || $veloria_isEditor) : ?>
                                <span <?php $this->print_render_attribute_string($veloria_priceKey); ?>><?php echo esc_html($veloria_itemPrice); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($veloria_itemDesc !== '' || $veloria_isEditor) : ?>
                            <p <?php $this->print_render_attribute_string($veloria_descKey); ?>><?php echo wp_kses_post(nl2br(esc_html($veloria_itemDesc))); ?></p>
                        <?php endif; ?>
                        <span class="veloria-price-list__separator" aria-hidden="true"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($veloria_showFooter && ($veloria_footerNote !== '' || $veloria_isEditor)) : ?>
            <footer class="veloria-price-list__footer">
                <p <?php $this->print_render_attribute_string('footer_note'); ?>><?php echo wp_kses_post(nl2br(esc_html($veloria_footerNote))); ?></p>
            </footer>
        <?php endif; ?>
    </div>
</div>
