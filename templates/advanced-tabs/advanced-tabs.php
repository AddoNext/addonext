<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_tabs     = isset($addonext_settings['tabs']) && is_array($addonext_settings['tabs']) ? $addonext_settings['tabs'] : [];
$this->add_render_attribute('wrapper', 'class', 'addonext-advanced-tabs');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="addonext-advanced-tabs__headers">
        <?php foreach ($addonext_tabs as $addonext_index => $addonext_tab) : ?>
            <?php
            $addonext_header_class = 'addonext-advanced-tabs__header' . ($addonext_index === 0 ? ' is-active' : '');
            $this->add_render_attribute('tabs.' . $addonext_index . '.tab_title', 'class', $addonext_header_class);
            $this->add_render_attribute('tabs.' . $addonext_index . '.tab_title', 'data-tab', $addonext_index);
            $this->add_inline_editing_attributes('tabs.' . $addonext_index . '.tab_title', 'none');
            ?>
            <div <?php $this->print_render_attribute_string('tabs.' . $addonext_index . '.tab_title'); ?>><?php echo esc_html($addonext_tab['tab_title'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
    <div class="addonext-advanced-tabs__panels">
        <?php foreach ($addonext_tabs as $addonext_index => $addonext_tab) : ?>
            <?php
            $addonext_panel_class = 'addonext-advanced-tabs__panel' . ($addonext_index === 0 ? ' is-active' : '');
            $this->add_render_attribute('tabs.' . $addonext_index . '.tab_content', 'class', $addonext_panel_class);
            $this->add_render_attribute('tabs.' . $addonext_index . '.tab_content', 'data-tab', $addonext_index);
            $this->add_inline_editing_attributes('tabs.' . $addonext_index . '.tab_content', 'advanced');
            ?>
            <div <?php $this->print_render_attribute_string('tabs.' . $addonext_index . '.tab_content'); ?>><?php echo wp_kses_post($addonext_tab['tab_content'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
</div>
