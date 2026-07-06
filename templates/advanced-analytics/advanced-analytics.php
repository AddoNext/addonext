<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_cards = isset($addonext_settings['cards']) && is_array($addonext_settings['cards']) ? $addonext_settings['cards'] : [];
$addonext_animate = isset($addonext_settings['enable_counter_animation']) && $addonext_settings['enable_counter_animation'] === 'yes' ? 'yes' : 'no';
$addonext_duration = isset($addonext_settings['counter_animation_duration']) && $addonext_settings['counter_animation_duration'] !== '' ? (int) $addonext_settings['counter_animation_duration'] : 1400;

?>
<div class="addonext-advanced-analytics" data-aa-animate="<?php echo esc_attr($addonext_animate); ?>" data-aa-duration="<?php echo esc_attr((string) $addonext_duration); ?>">
    <?php foreach ($addonext_cards as $addonext_index => $addonext_card) : ?>
        <?php
        $addonext_value = isset($addonext_card['value']) ? (string) $addonext_card['value'] : '';
        $addonext_label = isset($addonext_card['label']) ? (string) $addonext_card['label'] : '';
        $addonext_trend = isset($addonext_card['trend']) ? (string) $addonext_card['trend'] : '';
        $addonext_trendType = isset($addonext_card['trend_type']) && $addonext_card['trend_type'] === 'negative' ? 'negative' : 'positive';
        $addonext_iconBg = isset($addonext_card['icon_bg']) ? (string) $addonext_card['icon_bg'] : '#2563eb';
        $addonext_cardBg = isset($addonext_card['card_bg']) ? (string) $addonext_card['card_bg'] : '#dbeafe';
        $addonext_icon = $addonext_card['icon'] ?? $addonext_card['selected_icon'] ?? [];

        $addonext_valueKey = 'cards.' . $addonext_index . '.value';
        $addonext_labelKey = 'cards.' . $addonext_index . '.label';
        $addonext_trendKey = 'cards.' . $addonext_index . '.trend';

        $this->add_render_attribute($addonext_valueKey, 'class', 'addonext-advanced-analytics__value');
        $this->add_inline_editing_attributes($addonext_valueKey, 'basic');

        $this->add_render_attribute($addonext_labelKey, 'class', 'addonext-advanced-analytics__label');
        $this->add_inline_editing_attributes($addonext_labelKey, 'basic');

        $this->add_render_attribute($addonext_trendKey, 'class', 'addonext-advanced-analytics__trend addonext-advanced-analytics__trend--' . $addonext_trendType);
        $this->add_inline_editing_attributes($addonext_trendKey, 'basic');
        ?>
        <div class="addonext-advanced-analytics__card" style="--aa-card-bg: <?php echo esc_attr($addonext_cardBg); ?>; --aa-icon-bg: <?php echo esc_attr($addonext_iconBg); ?>;">
            <div class="addonext-advanced-analytics__top">
                <div class="addonext-advanced-analytics__icon">
                    <?php if (!empty($addonext_icon['value'])) {
                        \Elementor\Icons_Manager::render_icon($addonext_icon, ['aria-hidden' => 'true']);
                    } ?>
                </div>
                <span <?php $this->print_render_attribute_string($addonext_trendKey); ?>><?php echo esc_html($addonext_trend); ?></span>
            </div>

            <div class="addonext-advanced-analytics__bottom">
                <div <?php $this->print_render_attribute_string($addonext_valueKey); ?> data-aa-value="<?php echo esc_attr($addonext_value); ?>"><?php echo esc_html($addonext_value); ?></div>
                <div <?php $this->print_render_attribute_string($addonext_labelKey); ?>><?php echo esc_html($addonext_label); ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
