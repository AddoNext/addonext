<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_list     = $veloria_settings['icon_list'] ?? [];

$veloria_gap_val   = isset($veloria_settings['gap']['size']) && $veloria_settings['gap']['size'] !== '' ? $veloria_settings['gap']['size'] . ($veloria_settings['gap']['unit'] ?? 'px') : '8px';
$veloria_align     = !empty($veloria_settings['align']) ? $veloria_settings['align'] : 'center';
$veloria_wrap      = !empty($veloria_settings['flex_wrap']) ? $veloria_settings['flex_wrap'] : 'wrap';
$veloria_direction = !empty($veloria_settings['flex_direction']) ? $veloria_settings['flex_direction'] : 'row';
$veloria_icon_size = isset($veloria_settings['icon_size']['size']) && $veloria_settings['icon_size']['size'] !== '' ? $veloria_settings['icon_size']['size'] . ($veloria_settings['icon_size']['unit'] ?? 'px') : '';
$veloria_box_size  = isset($veloria_settings['box_size']['size']) && $veloria_settings['box_size']['size'] !== '' ? $veloria_settings['box_size']['size'] . ($veloria_settings['box_size']['unit'] ?? 'px') : '30px';
$veloria_padding   = isset($veloria_settings['icon_padding']['size']) && $veloria_settings['icon_padding']['size'] !== '' ? $veloria_settings['icon_padding']['size'] . ($veloria_settings['icon_padding']['unit'] ?? 'px') : '7px';
$veloria_radius    = isset($veloria_settings['border_radius']['size']) && $veloria_settings['border_radius']['size'] !== '' ? $veloria_settings['border_radius']['size'] . ($veloria_settings['border_radius']['unit'] ?? '%') : '50%';
$veloria_border_w  = $veloria_settings['border_width'] ?? [];
$veloria_border_sz = isset($veloria_border_w['size']) ? (float) $veloria_border_w['size'] : 0;
$veloria_border_un = $veloria_border_w['unit'] ?? 'px';
$veloria_border_cl = !empty($veloria_settings['border_color']) ? $veloria_settings['border_color'] : 'transparent';

$veloria_wrapper_style = '--veloria-ib-gap:' . esc_attr($veloria_gap_val) . ';--veloria-ib-align:' . esc_attr($veloria_align) . ';--veloria-ib-wrap:' . esc_attr($veloria_wrap) . ';--veloria-ib-direction:' . esc_attr($veloria_direction) . ';--veloria-ib-box-size:' . esc_attr($veloria_box_size) . ';--veloria-ib-padding:' . esc_attr($veloria_padding) . ';--veloria-ib-radius:' . esc_attr($veloria_radius) . ';';
if ($veloria_icon_size !== '') {
    $veloria_wrapper_style .= '--veloria-ib-icon-size:' . esc_attr($veloria_icon_size) . ';';
}

$this->add_render_attribute('wrapper', 'class', 'veloria-icon-box');
$this->add_render_attribute('wrapper', 'style', $veloria_wrapper_style);
$this->add_render_attribute('list', 'class', 'veloria-icon-box__list');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div <?php $this->print_render_attribute_string('list'); ?>>
        <?php
        foreach ($veloria_list as $veloria_index => $veloria_item) {
            $veloria_link_key  = 'link_' . $veloria_index;
            $veloria_bg_color  = !empty($veloria_item['background_color']) ? $veloria_item['background_color'] : '#3B5998';
            $veloria_link_style = 'background-color:' . esc_attr($veloria_bg_color);
            if ($veloria_border_sz > 0) {
                $veloria_link_style .= ';border:' . esc_attr($veloria_border_sz . $veloria_border_un) . ' solid ' . esc_attr($veloria_border_cl);
            }

            $this->add_render_attribute($veloria_link_key, 'class', [
                'veloria-icon-box__link',
                'elementor-repeater-item-' . ($veloria_item['_id'] ?? ''),
            ]);
            $this->add_render_attribute($veloria_link_key, 'style', $veloria_link_style);

            if (!empty($veloria_item['link']['url'])) {
                $this->add_link_attributes($veloria_link_key, $veloria_item['link']);
            } else {
                $this->add_render_attribute($veloria_link_key, 'href', '#');
            }
            $veloria_icon_color = !empty($veloria_settings['icon_color']) ? $veloria_settings['icon_color'] : '#ffffff';
            ?>
            <a <?php $this->print_render_attribute_string($veloria_link_key); ?>>
                <?php
                if (!empty($veloria_item['social_icon']['value'])) {
                    echo '<span class="veloria-icon-box__icon-inner" style="color:' . esc_attr($veloria_icon_color) . ';">';
                    \Elementor\Icons_Manager::render_icon($veloria_item['social_icon'], ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                ?>
            </a>
        <?php } ?>
    </div>
</div>
