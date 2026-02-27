<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$tabs     = isset($settings['tabs']) && is_array($settings['tabs']) ? $settings['tabs'] : [];
$this->add_render_attribute('wrapper', 'class', 'elementrify-advanced-tabs');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="elementrify-advanced-tabs__headers">
        <?php foreach ($tabs as $index => $tab) : ?>
            <div class="elementrify-advanced-tabs__header<?php echo $index === 0 ? ' is-active' : ''; ?>" data-tab="<?php echo esc_attr($index); ?>"><?php echo esc_html($tab['tab_title'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
    <div class="elementrify-advanced-tabs__panels">
        <?php foreach ($tabs as $index => $tab) : ?>
            <div class="elementrify-advanced-tabs__panel<?php echo $index === 0 ? ' is-active' : ''; ?>" data-tab="<?php echo esc_attr($index); ?>"><?php echo wp_kses_post($tab['tab_content'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
</div>
