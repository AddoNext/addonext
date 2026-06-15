<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$tabs     = isset($settings['tabs']) && is_array($settings['tabs']) ? $settings['tabs'] : [];
$this->add_render_attribute('wrapper', 'class', 'veloria-advanced-tabs');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="veloria-advanced-tabs__headers">
        <?php foreach ($tabs as $index => $tab) : ?>
            <?php
            $header_class = 'veloria-advanced-tabs__header' . ($index === 0 ? ' is-active' : '');
            $this->add_render_attribute('tabs.' . $index . '.tab_title', 'class', $header_class);
            $this->add_render_attribute('tabs.' . $index . '.tab_title', 'data-tab', $index);
            $this->add_inline_editing_attributes('tabs.' . $index . '.tab_title', 'none');
            ?>
            <div <?php $this->print_render_attribute_string('tabs.' . $index . '.tab_title'); ?>><?php echo esc_html($tab['tab_title'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
    <div class="veloria-advanced-tabs__panels">
        <?php foreach ($tabs as $index => $tab) : ?>
            <?php
            $panel_class = 'veloria-advanced-tabs__panel' . ($index === 0 ? ' is-active' : '');
            $this->add_render_attribute('tabs.' . $index . '.tab_content', 'class', $panel_class);
            $this->add_render_attribute('tabs.' . $index . '.tab_content', 'data-tab', $index);
            $this->add_inline_editing_attributes('tabs.' . $index . '.tab_content', 'advanced');
            ?>
            <div <?php $this->print_render_attribute_string('tabs.' . $index . '.tab_content'); ?>><?php echo wp_kses_post($tab['tab_content'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
</div>
