<?php
defined('ABSPATH') || exit;

$veloria_settings   = $this->get_settings_for_display();
$veloria_tabs       = isset($veloria_settings['tabs']) ? $veloria_settings['tabs'] : [];
$veloria_first_open = isset($veloria_settings['first_expanded']) && $veloria_settings['first_expanded'] === 'yes';
$veloria_allow_multiple = isset($veloria_settings['allow_multiple']) && $veloria_settings['allow_multiple'] === 'yes';
$veloria_title_tag  = isset($veloria_settings['title_html_tag']) ? $veloria_settings['title_html_tag'] : 'div';
$veloria_title_tag  = \Elementor\Utils::validate_html_tag($veloria_title_tag);
$veloria_has_icon   = !empty($veloria_settings['selected_icon']['value']);

if (empty($veloria_tabs)) {
    return;
}

$veloria_accordion_id = 'veloria-accordion-' . $this->get_id();
$this->add_render_attribute('wrapper', 'id', $veloria_accordion_id);
$this->add_render_attribute('wrapper', 'class', 'veloria-accordion');
$this->add_render_attribute('wrapper', 'data-allow-multiple', $veloria_allow_multiple ? '1' : '0');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($veloria_tabs as $veloria_index => $veloria_item) :
        $veloria_is_open     = $veloria_first_open && $veloria_index === 0;
        $veloria_item_class  = 'veloria-accordion__item' . ($veloria_is_open ? ' is-open' : '');
        $veloria_item_key    = 'accordion_item_' . $veloria_index;
        $veloria_content_key = $this->get_repeater_setting_key('tab_content', 'tabs', $veloria_index);

        $this->add_render_attribute($veloria_item_key, 'class', $veloria_item_class);
        $this->add_render_attribute($veloria_content_key, 'class', 'veloria-accordion__content');
        $this->add_inline_editing_attributes($veloria_content_key, 'advanced');
    ?>
    <div <?php $this->print_render_attribute_string($veloria_item_key); ?>>
        <<?php echo esc_attr($veloria_title_tag); ?> class="veloria-accordion__header" role="button" tabindex="0">
            <?php if ($veloria_has_icon) : ?>
                <span class="veloria-accordion__icon veloria-accordion__icon--closed" aria-hidden="true">
                    <?php \Elementor\Icons_Manager::render_icon($veloria_settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
                <span class="veloria-accordion__icon veloria-accordion__icon--open" aria-hidden="true">
                    <?php
                    $veloria_active_icon = isset($veloria_settings['selected_active_icon']['value']) ? $veloria_settings['selected_active_icon'] : $veloria_settings['selected_icon'];
                    \Elementor\Icons_Manager::render_icon($veloria_active_icon, ['aria-hidden' => 'true']);
                    ?>
                </span>
            <?php endif; ?>
            <span class="veloria-accordion__title"><?php echo wp_kses_post($veloria_item['tab_title']); ?></span>
        </<?php echo esc_attr($veloria_title_tag); ?>>
        <div <?php $this->print_render_attribute_string($veloria_content_key); ?>>
            <?php $this->print_text_editor($veloria_item['tab_content']); ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
