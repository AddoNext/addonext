<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_cards = isset($veloria_settings['cards']) && is_array($veloria_settings['cards']) ? $veloria_settings['cards'] : [];
$veloria_animate = isset($veloria_settings['enable_counter_animation']) && $veloria_settings['enable_counter_animation'] === 'yes' ? 'yes' : 'no';
$veloria_duration = isset($veloria_settings['counter_animation_duration']) && $veloria_settings['counter_animation_duration'] !== '' ? (int) $veloria_settings['counter_animation_duration'] : 1400;

?>
<div class="veloria-advanced-analytics" data-aa-animate="<?php echo esc_attr($veloria_animate); ?>" data-aa-duration="<?php echo esc_attr((string) $veloria_duration); ?>">
    <?php foreach ($veloria_cards as $veloria_index => $veloria_card) : ?>
        <?php
        $veloria_value = isset($veloria_card['value']) ? (string) $veloria_card['value'] : '';
        $veloria_label = isset($veloria_card['label']) ? (string) $veloria_card['label'] : '';
        $veloria_trend = isset($veloria_card['trend']) ? (string) $veloria_card['trend'] : '';
        $veloria_trendType = isset($veloria_card['trend_type']) && $veloria_card['trend_type'] === 'negative' ? 'negative' : 'positive';
        $veloria_iconBg = isset($veloria_card['icon_bg']) ? (string) $veloria_card['icon_bg'] : '#2563eb';
        $veloria_cardBg = isset($veloria_card['card_bg']) ? (string) $veloria_card['card_bg'] : '#dbeafe';
        $veloria_icon = $veloria_card['icon'] ?? $veloria_card['selected_icon'] ?? [];

        $veloria_valueKey = 'cards.' . $veloria_index . '.value';
        $veloria_labelKey = 'cards.' . $veloria_index . '.label';
        $veloria_trendKey = 'cards.' . $veloria_index . '.trend';

        $this->add_render_attribute($veloria_valueKey, 'class', 'veloria-advanced-analytics__value');
        $this->add_inline_editing_attributes($veloria_valueKey, 'basic');

        $this->add_render_attribute($veloria_labelKey, 'class', 'veloria-advanced-analytics__label');
        $this->add_inline_editing_attributes($veloria_labelKey, 'basic');

        $this->add_render_attribute($veloria_trendKey, 'class', 'veloria-advanced-analytics__trend veloria-advanced-analytics__trend--' . $veloria_trendType);
        $this->add_inline_editing_attributes($veloria_trendKey, 'basic');
        ?>
        <div class="veloria-advanced-analytics__card" style="--aa-card-bg: <?php echo esc_attr($veloria_cardBg); ?>; --aa-icon-bg: <?php echo esc_attr($veloria_iconBg); ?>;">
            <div class="veloria-advanced-analytics__top">
                <div class="veloria-advanced-analytics__icon">
                    <?php if (!empty($veloria_icon['value'])) {
                        \Elementor\Icons_Manager::render_icon($veloria_icon, ['aria-hidden' => 'true']);
                    } ?>
                </div>
                <span <?php $this->print_render_attribute_string($veloria_trendKey); ?>><?php echo esc_html($veloria_trend); ?></span>
            </div>

            <div class="veloria-advanced-analytics__bottom">
                <div <?php $this->print_render_attribute_string($veloria_valueKey); ?> data-aa-value="<?php echo esc_attr($veloria_value); ?>"><?php echo esc_html($veloria_value); ?></div>
                <div <?php $this->print_render_attribute_string($veloria_labelKey); ?>><?php echo esc_html($veloria_label); ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
