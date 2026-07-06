<?php
defined('ABSPATH') || exit;

$addonext_settings   = $this->get_settings_for_display();
$addonext_tabs       = isset($addonext_settings['tabs']) ? $addonext_settings['tabs'] : [];
$addonext_first_open = isset($addonext_settings['first_expanded']) && $addonext_settings['first_expanded'] === 'yes';
$addonext_allow_multiple = isset($addonext_settings['allow_multiple']) && $addonext_settings['allow_multiple'] === 'yes';
$addonext_title_tag  = isset($addonext_settings['title_html_tag']) ? $addonext_settings['title_html_tag'] : 'div';
$addonext_title_tag  = \Elementor\Utils::validate_html_tag($addonext_title_tag);
$addonext_has_icon   = !empty($addonext_settings['selected_icon']['value']);

if (empty($addonext_tabs)) {
    return;
}

$addonext_accordion_id = 'addonext-accordion-' . $this->get_id();
$this->add_render_attribute('wrapper', 'id', $addonext_accordion_id);
$this->add_render_attribute('wrapper', 'class', 'addonext-accordion');
$this->add_render_attribute('wrapper', 'data-allow-multiple', $addonext_allow_multiple ? '1' : '0');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($addonext_tabs as $addonext_index => $addonext_item) :
        $addonext_is_open     = $addonext_first_open && $addonext_index === 0;
        $addonext_item_class  = 'addonext-accordion__item' . ($addonext_is_open ? ' is-open' : '');
        $addonext_item_key    = 'accordion_item_' . $addonext_index;
        $addonext_content_key = $this->get_repeater_setting_key('tab_content', 'tabs', $addonext_index);

        $this->add_render_attribute($addonext_item_key, 'class', $addonext_item_class);
        $this->add_render_attribute($addonext_content_key, 'class', 'addonext-accordion__content');
        $this->add_inline_editing_attributes($addonext_content_key, 'advanced');
    ?>
    <div <?php $this->print_render_attribute_string($addonext_item_key); ?>>
        <<?php echo esc_attr($addonext_title_tag); ?> class="addonext-accordion__header" role="button" tabindex="0">
            <?php if ($addonext_has_icon) : ?>
                <span class="addonext-accordion__icon addonext-accordion__icon--closed" aria-hidden="true">
                    <?php \Elementor\Icons_Manager::render_icon($addonext_settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
                <span class="addonext-accordion__icon addonext-accordion__icon--open" aria-hidden="true">
                    <?php
                    $addonext_active_icon = isset($addonext_settings['selected_active_icon']['value']) ? $addonext_settings['selected_active_icon'] : $addonext_settings['selected_icon'];
                    \Elementor\Icons_Manager::render_icon($addonext_active_icon, ['aria-hidden' => 'true']);
                    ?>
                </span>
            <?php endif; ?>
            <span class="addonext-accordion__title"><?php echo wp_kses_post($addonext_item['tab_title']); ?></span>
        </<?php echo esc_attr($addonext_title_tag); ?>>
        <div <?php $this->print_render_attribute_string($addonext_content_key); ?>>
            <?php $this->print_text_editor($addonext_item['tab_content']); ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
