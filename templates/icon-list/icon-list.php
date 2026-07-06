<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_items    = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];
$this->add_render_attribute('wrapper', 'class', 'addonext-icon-list');
?>
<ul <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($addonext_items as $addonext_index => $addonext_item) : ?>
        <li class="addonext-icon-list__item">
            <?php
            $addonext_icon_data = $addonext_item['selected_icon'] ?? $addonext_item['icon'] ?? [];
            if (!empty($addonext_icon_data['value'])) :
            ?>
                <span class="addonext-icon-list__icon"><?php \Elementor\Icons_Manager::render_icon($addonext_icon_data, ['aria-hidden' => 'true']); ?></span>
            <?php endif; ?>
            <?php
            $addonext_has_link = !empty($addonext_item['link']['url']);
            $addonext_link_key = 'item-link-' . $addonext_index;
            if ($addonext_has_link) {
                $this->add_link_attributes($addonext_link_key, $addonext_item['link']);
            }
            ?>
            <?php if ($addonext_has_link) : ?>
                <a <?php $this->print_render_attribute_string($addonext_link_key); ?> class="addonext-icon-list__link">
                    <span class="addonext-icon-list__text"><?php echo esc_html($addonext_item['text'] ?? ''); ?></span>
                </a>
            <?php else : ?>
                <span class="addonext-icon-list__text"><?php echo esc_html($addonext_item['text'] ?? ''); ?></span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
