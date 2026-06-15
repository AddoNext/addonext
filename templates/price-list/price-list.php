<?php
defined('ABSPATH') || exit;

use Elementor\Plugin;

$settings = $this->get_settings_for_display();
$headingTitle = isset($settings['heading_title']) ? (string) $settings['heading_title'] : '';
$headingSubtitle = isset($settings['heading_subtitle']) ? (string) $settings['heading_subtitle'] : '';
$footerNote = isset($settings['footer_note']) ? (string) $settings['footer_note'] : '';
$showSubtitle = ($settings['show_subtitle'] ?? 'yes') === 'yes';
$showHeaderRule = ($settings['show_header_rule'] ?? 'yes') === 'yes';
$showFooter = ($settings['show_footer'] ?? 'yes') === 'yes';
$items = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];
$isEditor = Plugin::$instance->editor->is_edit_mode();

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
            <?php if ($headingTitle !== '' || $isEditor) : ?>
                <h2 <?php $this->print_render_attribute_string('heading_title'); ?>><?php echo esc_html($headingTitle); ?></h2>
            <?php endif; ?>
            <?php if ($showSubtitle && ($headingSubtitle !== '' || $isEditor)) : ?>
                <p <?php $this->print_render_attribute_string('heading_subtitle'); ?>><?php echo wp_kses_post(nl2br(esc_html($headingSubtitle))); ?></p>
            <?php endif; ?>
            <?php if ($showHeaderRule) : ?>
                <span class="veloria-price-list__header-rule" aria-hidden="true"></span>
            <?php endif; ?>
        </header>

        <?php if (!empty($items)) : ?>
            <ul class="veloria-price-list__items">
                <?php foreach ($items as $index => $item) : ?>
                    <?php
                    $itemTitle = isset($item['title']) ? (string) $item['title'] : '';
                    $itemDesc = isset($item['description']) ? (string) $item['description'] : '';
                    $itemPrice = isset($item['price']) ? (string) $item['price'] : '';
                    $titleKey = 'items.' . $index . '.title';
                    $descKey = 'items.' . $index . '.description';
                    $priceKey = 'items.' . $index . '.price';
                    $this->add_render_attribute($titleKey, 'class', 'veloria-price-list__item-title');
                    $this->add_inline_editing_attributes($titleKey, 'basic');
                    $this->add_render_attribute($descKey, 'class', 'veloria-price-list__item-desc');
                    $this->add_inline_editing_attributes($descKey, 'advanced');
                    $this->add_render_attribute($priceKey, 'class', 'veloria-price-list__item-price');
                    $this->add_inline_editing_attributes($priceKey, 'basic');
                    ?>
                    <li class="veloria-price-list__item">
                        <div class="veloria-price-list__line">
                            <?php if ($itemTitle !== '' || $isEditor) : ?>
                                <span <?php $this->print_render_attribute_string($titleKey); ?>><?php echo esc_html($itemTitle); ?></span>
                            <?php endif; ?>
                            <?php if ($itemPrice !== '' || $isEditor) : ?>
                                <span <?php $this->print_render_attribute_string($priceKey); ?>><?php echo esc_html($itemPrice); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($itemDesc !== '' || $isEditor) : ?>
                            <p <?php $this->print_render_attribute_string($descKey); ?>><?php echo wp_kses_post(nl2br(esc_html($itemDesc))); ?></p>
                        <?php endif; ?>
                        <span class="veloria-price-list__separator" aria-hidden="true"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($showFooter && ($footerNote !== '' || $isEditor)) : ?>
            <footer class="veloria-price-list__footer">
                <p <?php $this->print_render_attribute_string('footer_note'); ?>><?php echo wp_kses_post(nl2br(esc_html($footerNote))); ?></p>
            </footer>
        <?php endif; ?>
    </div>
</div>
