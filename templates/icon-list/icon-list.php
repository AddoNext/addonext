<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$items    = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];
$this->add_render_attribute('wrapper', 'class', 'elementrify-icon-list');
?>
<ul <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($items as $item) : ?>
        <li class="elementrify-icon-list__item">
            <?php if (!empty($item['icon']['value'])) : ?>
                <span class="elementrify-icon-list__icon"><?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></span>
            <?php endif; ?>
            <span class="elementrify-icon-list__text"><?php echo esc_html($item['text'] ?? ''); ?></span>
        </li>
    <?php endforeach; ?>
</ul>
