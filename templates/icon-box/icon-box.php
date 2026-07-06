<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_list     = $addonext_settings['icon_list'] ?? [];

$addonext_gap_val   = isset($addonext_settings['gap']['size']) && $addonext_settings['gap']['size'] !== '' ? $addonext_settings['gap']['size'] . ($addonext_settings['gap']['unit'] ?? 'px') : '8px';
$addonext_align     = !empty($addonext_settings['align']) ? $addonext_settings['align'] : 'center';
$addonext_wrap      = !empty($addonext_settings['flex_wrap']) ? $addonext_settings['flex_wrap'] : 'wrap';
$addonext_direction = !empty($addonext_settings['flex_direction']) ? $addonext_settings['flex_direction'] : 'row';
$addonext_icon_size = isset($addonext_settings['icon_size']['size']) && $addonext_settings['icon_size']['size'] !== '' ? $addonext_settings['icon_size']['size'] . ($addonext_settings['icon_size']['unit'] ?? 'px') : '';
$addonext_box_size  = isset($addonext_settings['box_size']['size']) && $addonext_settings['box_size']['size'] !== '' ? $addonext_settings['box_size']['size'] . ($addonext_settings['box_size']['unit'] ?? 'px') : '30px';
$addonext_padding   = isset($addonext_settings['icon_padding']['size']) && $addonext_settings['icon_padding']['size'] !== '' ? $addonext_settings['icon_padding']['size'] . ($addonext_settings['icon_padding']['unit'] ?? 'px') : '7px';
$addonext_radius    = isset($addonext_settings['border_radius']['size']) && $addonext_settings['border_radius']['size'] !== '' ? $addonext_settings['border_radius']['size'] . ($addonext_settings['border_radius']['unit'] ?? '%') : '50%';
$addonext_border_w  = $addonext_settings['border_width'] ?? [];
$addonext_border_sz = isset($addonext_border_w['size']) ? (float) $addonext_border_w['size'] : 0;
$addonext_border_un = $addonext_border_w['unit'] ?? 'px';
$addonext_border_cl = !empty($addonext_settings['border_color']) ? $addonext_settings['border_color'] : 'transparent';

$addonext_wrapper_style = '--addonext-ib-gap:' . esc_attr($addonext_gap_val) . ';--addonext-ib-align:' . esc_attr($addonext_align) . ';--addonext-ib-wrap:' . esc_attr($addonext_wrap) . ';--addonext-ib-direction:' . esc_attr($addonext_direction) . ';--addonext-ib-box-size:' . esc_attr($addonext_box_size) . ';--addonext-ib-padding:' . esc_attr($addonext_padding) . ';--addonext-ib-radius:' . esc_attr($addonext_radius) . ';';
if ($addonext_icon_size !== '') {
    $addonext_wrapper_style .= '--addonext-ib-icon-size:' . esc_attr($addonext_icon_size) . ';';
}

$this->add_render_attribute('wrapper', 'class', 'addonext-icon-box');
$this->add_render_attribute('wrapper', 'style', $addonext_wrapper_style);
$this->add_render_attribute('list', 'class', 'addonext-icon-box__list');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div <?php $this->print_render_attribute_string('list'); ?>>
        <?php
        foreach ($addonext_list as $addonext_index => $addonext_item) {
            $addonext_link_key  = 'link_' . $addonext_index;
            $addonext_bg_color  = !empty($addonext_item['background_color']) ? $addonext_item['background_color'] : '#3B5998';
            $addonext_link_style = 'background-color:' . esc_attr($addonext_bg_color);
            if ($addonext_border_sz > 0) {
                $addonext_link_style .= ';border:' . esc_attr($addonext_border_sz . $addonext_border_un) . ' solid ' . esc_attr($addonext_border_cl);
            }

            $this->add_render_attribute($addonext_link_key, 'class', [
                'addonext-icon-box__link',
                'elementor-repeater-item-' . ($addonext_item['_id'] ?? ''),
            ]);
            $this->add_render_attribute($addonext_link_key, 'style', $addonext_link_style);

            if (!empty($addonext_item['link']['url'])) {
                $this->add_link_attributes($addonext_link_key, $addonext_item['link']);
            } else {
                $this->add_render_attribute($addonext_link_key, 'href', '#');
            }
            $addonext_icon_color = !empty($addonext_settings['icon_color']) ? $addonext_settings['icon_color'] : '#ffffff';
            ?>
            <a <?php $this->print_render_attribute_string($addonext_link_key); ?>>
                <?php
                if (!empty($addonext_item['social_icon']['value'])) {
                    echo '<span class="addonext-icon-box__icon-inner" style="color:' . esc_attr($addonext_icon_color) . ';">';
                    \Elementor\Icons_Manager::render_icon($addonext_item['social_icon'], ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                ?>
            </a>
        <?php } ?>
    </div>
</div>
