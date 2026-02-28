<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$list     = $settings['icon_list'] ?? [];

$gap_val   = isset($settings['gap']['size']) && $settings['gap']['size'] !== '' ? $settings['gap']['size'] . ($settings['gap']['unit'] ?? 'px') : '8px';
$align     = !empty($settings['align']) ? $settings['align'] : 'center';
$wrap      = !empty($settings['flex_wrap']) ? $settings['flex_wrap'] : 'wrap';
$direction = !empty($settings['flex_direction']) ? $settings['flex_direction'] : 'row';
$icon_size = isset($settings['icon_size']['size']) && $settings['icon_size']['size'] !== '' ? $settings['icon_size']['size'] . ($settings['icon_size']['unit'] ?? 'px') : '';
$box_size  = isset($settings['box_size']['size']) && $settings['box_size']['size'] !== '' ? $settings['box_size']['size'] . ($settings['box_size']['unit'] ?? 'px') : '30px';
$padding   = isset($settings['icon_padding']['size']) && $settings['icon_padding']['size'] !== '' ? $settings['icon_padding']['size'] . ($settings['icon_padding']['unit'] ?? 'px') : '7px';
$radius    = isset($settings['border_radius']['size']) && $settings['border_radius']['size'] !== '' ? $settings['border_radius']['size'] . ($settings['border_radius']['unit'] ?? '%') : '50%';
$border_w  = $settings['border_width'] ?? [];
$border_sz = isset($border_w['size']) ? (float) $border_w['size'] : 0;
$border_un = $border_w['unit'] ?? 'px';
$border_cl = !empty($settings['border_color']) ? $settings['border_color'] : 'transparent';

$wrapper_style = '--elementrify-ib-gap:' . esc_attr($gap_val) . ';--elementrify-ib-align:' . esc_attr($align) . ';--elementrify-ib-wrap:' . esc_attr($wrap) . ';--elementrify-ib-direction:' . esc_attr($direction) . ';--elementrify-ib-box-size:' . esc_attr($box_size) . ';--elementrify-ib-padding:' . esc_attr($padding) . ';--elementrify-ib-radius:' . esc_attr($radius) . ';';
if ($icon_size !== '') {
    $wrapper_style .= '--elementrify-ib-icon-size:' . esc_attr($icon_size) . ';';
}

$this->add_render_attribute('wrapper', 'class', 'elementrify-icon-box');
$this->add_render_attribute('wrapper', 'style', $wrapper_style);
$this->add_render_attribute('list', 'class', 'elementrify-icon-box__list');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div <?php $this->print_render_attribute_string('list'); ?>>
        <?php
        foreach ($list as $index => $item) {
            $link_key  = 'link_' . $index;
            $bg_color  = !empty($item['background_color']) ? $item['background_color'] : '#3B5998';
            $link_style = 'background-color:' . esc_attr($bg_color);
            if ($border_sz > 0) {
                $link_style .= ';border:' . esc_attr($border_sz . $border_un) . ' solid ' . esc_attr($border_cl);
            }

            $this->add_render_attribute($link_key, 'class', [
                'elementrify-icon-box__link',
                'elementor-repeater-item-' . ($item['_id'] ?? ''),
            ]);
            $this->add_render_attribute($link_key, 'style', $link_style);

            if (!empty($item['link']['url'])) {
                $this->add_link_attributes($link_key, $item['link']);
            } else {
                $this->add_render_attribute($link_key, 'href', '#');
            }
            $icon_color = !empty($settings['icon_color']) ? $settings['icon_color'] : '#ffffff';
            ?>
            <a <?php $this->print_render_attribute_string($link_key); ?>>
                <?php
                if (!empty($item['social_icon']['value'])) {
                    echo '<span class="elementrify-icon-box__icon-inner" style="color:' . esc_attr($icon_color) . ';">';
                    \Elementor\Icons_Manager::render_icon($item['social_icon'], ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                ?>
            </a>
        <?php } ?>
    </div>
</div>
