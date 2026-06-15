<?php
defined('ABSPATH') || exit;

$settings   = $this->get_settings_for_display();
$tabs       = isset($settings['tabs']) ? $settings['tabs'] : [];
$first_open = isset($settings['first_expanded']) && $settings['first_expanded'] === 'yes';
$allow_multiple = isset($settings['allow_multiple']) && $settings['allow_multiple'] === 'yes';
$title_tag  = isset($settings['title_html_tag']) ? $settings['title_html_tag'] : 'div';
$title_tag  = \Elementor\Utils::validate_html_tag($title_tag);
$has_icon   = !empty($settings['selected_icon']['value']);

if (empty($tabs)) {
    return;
}

$accordion_id = 'veloria-accordion-' . $this->get_id();
$this->add_render_attribute('wrapper', 'id', $accordion_id);
$this->add_render_attribute('wrapper', 'class', 'veloria-accordion');
$this->add_render_attribute('wrapper', 'data-allow-multiple', $allow_multiple ? '1' : '0');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($tabs as $index => $item) :
        $is_open     = $first_open && $index === 0;
        $item_class  = 'veloria-accordion__item' . ($is_open ? ' is-open' : '');
        $item_key    = 'accordion_item_' . $index;
        $content_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);

        $this->add_render_attribute($item_key, 'class', $item_class);
        $this->add_render_attribute($content_key, 'class', 'veloria-accordion__content');
        $this->add_inline_editing_attributes($content_key, 'advanced');
    ?>
    <div <?php $this->print_render_attribute_string($item_key); ?>>
        <<?php echo esc_attr($title_tag); ?> class="veloria-accordion__header" role="button" tabindex="0">
            <?php if ($has_icon) : ?>
                <span class="veloria-accordion__icon veloria-accordion__icon--closed" aria-hidden="true">
                    <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
                <span class="veloria-accordion__icon veloria-accordion__icon--open" aria-hidden="true">
                    <?php
                    $active_icon = isset($settings['selected_active_icon']['value']) ? $settings['selected_active_icon'] : $settings['selected_icon'];
                    \Elementor\Icons_Manager::render_icon($active_icon, ['aria-hidden' => 'true']);
                    ?>
                </span>
            <?php endif; ?>
            <span class="veloria-accordion__title"><?php echo wp_kses_post($item['tab_title']); ?></span>
        </<?php echo esc_attr($title_tag); ?>>
        <div <?php $this->print_render_attribute_string($content_key); ?>>
            <?php $this->print_text_editor($item['tab_content']); ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
