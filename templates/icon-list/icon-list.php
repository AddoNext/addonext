<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$items    = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];
$this->add_render_attribute('wrapper', 'class', 'elementrify-icon-list');
?>
<ul <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($items as $index => $item) : ?>
        <li class="elementrify-icon-list__item">
            <?php
            $icon_data = $item['selected_icon'] ?? $item['icon'] ?? [];
            if (!empty($icon_data['value'])) :
            ?>
                <span class="elementrify-icon-list__icon"><?php \Elementor\Icons_Manager::render_icon($icon_data, ['aria-hidden' => 'true']); ?></span>
            <?php endif; ?>
            <?php
            $has_link = !empty($item['link']['url']);
            $link_key = 'item-link-' . $index;
            if ($has_link) {
                $this->add_link_attributes($link_key, $item['link']);
            }
            ?>
            <?php if ($has_link) : ?>
                <a <?php $this->print_render_attribute_string($link_key); ?> class="elementrify-icon-list__link">
                    <span class="elementrify-icon-list__text"><?php echo esc_html($item['text'] ?? ''); ?></span>
                </a>
            <?php else : ?>
                <span class="elementrify-icon-list__text"><?php echo esc_html($item['text'] ?? ''); ?></span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
