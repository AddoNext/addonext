<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_tabs     = isset($veloria_settings['tabs']) && is_array($veloria_settings['tabs']) ? $veloria_settings['tabs'] : [];
$this->add_render_attribute('wrapper', 'class', 'veloria-advanced-tabs');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="veloria-advanced-tabs__headers">
        <?php foreach ($veloria_tabs as $veloria_index => $veloria_tab) : ?>
            <?php
            $veloria_header_class = 'veloria-advanced-tabs__header' . ($veloria_index === 0 ? ' is-active' : '');
            $this->add_render_attribute('tabs.' . $veloria_index . '.tab_title', 'class', $veloria_header_class);
            $this->add_render_attribute('tabs.' . $veloria_index . '.tab_title', 'data-tab', $veloria_index);
            $this->add_inline_editing_attributes('tabs.' . $veloria_index . '.tab_title', 'none');
            ?>
            <div <?php $this->print_render_attribute_string('tabs.' . $veloria_index . '.tab_title'); ?>><?php echo esc_html($veloria_tab['tab_title'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
    <div class="veloria-advanced-tabs__panels">
        <?php foreach ($veloria_tabs as $veloria_index => $veloria_tab) : ?>
            <?php
            $veloria_panel_class = 'veloria-advanced-tabs__panel' . ($veloria_index === 0 ? ' is-active' : '');
            $this->add_render_attribute('tabs.' . $veloria_index . '.tab_content', 'class', $veloria_panel_class);
            $this->add_render_attribute('tabs.' . $veloria_index . '.tab_content', 'data-tab', $veloria_index);
            $this->add_inline_editing_attributes('tabs.' . $veloria_index . '.tab_content', 'advanced');
            ?>
            <div <?php $this->print_render_attribute_string('tabs.' . $veloria_index . '.tab_content'); ?>><?php echo wp_kses_post($veloria_tab['tab_content'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
</div>
