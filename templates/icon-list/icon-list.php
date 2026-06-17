<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_items    = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];
$this->add_render_attribute('wrapper', 'class', 'veloria-icon-list');
?>
<ul <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($veloria_items as $veloria_index => $veloria_item) : ?>
        <li class="veloria-icon-list__item">
            <?php
            $veloria_icon_data = $veloria_item['selected_icon'] ?? $veloria_item['icon'] ?? [];
            if (!empty($veloria_icon_data['value'])) :
            ?>
                <span class="veloria-icon-list__icon"><?php \Elementor\Icons_Manager::render_icon($veloria_icon_data, ['aria-hidden' => 'true']); ?></span>
            <?php endif; ?>
            <?php
            $veloria_has_link = !empty($veloria_item['link']['url']);
            $veloria_link_key = 'item-link-' . $veloria_index;
            if ($veloria_has_link) {
                $this->add_link_attributes($veloria_link_key, $veloria_item['link']);
            }
            ?>
            <?php if ($veloria_has_link) : ?>
                <a <?php $this->print_render_attribute_string($veloria_link_key); ?> class="veloria-icon-list__link">
                    <span class="veloria-icon-list__text"><?php echo esc_html($veloria_item['text'] ?? ''); ?></span>
                </a>
            <?php else : ?>
                <span class="veloria-icon-list__text"><?php echo esc_html($veloria_item['text'] ?? ''); ?></span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
