<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$cards = isset($settings['cards']) && is_array($settings['cards']) ? $settings['cards'] : [];
$animate = isset($settings['enable_counter_animation']) && $settings['enable_counter_animation'] === 'yes' ? 'yes' : 'no';
$duration = isset($settings['counter_animation_duration']) && $settings['counter_animation_duration'] !== '' ? (int) $settings['counter_animation_duration'] : 1400;

?>
<div class="elementrify-advanced-analytics" data-aa-animate="<?php echo esc_attr($animate); ?>" data-aa-duration="<?php echo esc_attr((string) $duration); ?>">
    <?php foreach ($cards as $index => $card) : ?>
        <?php
        $value = isset($card['value']) ? (string) $card['value'] : '';
        $label = isset($card['label']) ? (string) $card['label'] : '';
        $trend = isset($card['trend']) ? (string) $card['trend'] : '';
        $trendType = isset($card['trend_type']) && $card['trend_type'] === 'negative' ? 'negative' : 'positive';
        $iconBg = isset($card['icon_bg']) ? (string) $card['icon_bg'] : '#2563eb';
        $cardBg = isset($card['card_bg']) ? (string) $card['card_bg'] : '#dbeafe';
        $icon = $card['icon'] ?? $card['selected_icon'] ?? [];

        $valueKey = 'cards.' . $index . '.value';
        $labelKey = 'cards.' . $index . '.label';
        $trendKey = 'cards.' . $index . '.trend';

        $this->add_render_attribute($valueKey, 'class', 'elementrify-advanced-analytics__value');
        $this->add_inline_editing_attributes($valueKey, 'basic');

        $this->add_render_attribute($labelKey, 'class', 'elementrify-advanced-analytics__label');
        $this->add_inline_editing_attributes($labelKey, 'basic');

        $this->add_render_attribute($trendKey, 'class', 'elementrify-advanced-analytics__trend elementrify-advanced-analytics__trend--' . $trendType);
        $this->add_inline_editing_attributes($trendKey, 'basic');
        ?>
        <div class="elementrify-advanced-analytics__card" style="--aa-card-bg: <?php echo esc_attr($cardBg); ?>; --aa-icon-bg: <?php echo esc_attr($iconBg); ?>;">
            <div class="elementrify-advanced-analytics__top">
                <div class="elementrify-advanced-analytics__icon">
                    <?php if (!empty($icon['value'])) {
                        \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
                    } ?>
                </div>
                <span <?php $this->print_render_attribute_string($trendKey); ?>><?php echo esc_html($trend); ?></span>
            </div>

            <div class="elementrify-advanced-analytics__bottom">
                <div <?php $this->print_render_attribute_string($valueKey); ?> data-aa-value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></div>
                <div <?php $this->print_render_attribute_string($labelKey); ?>><?php echo esc_html($label); ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
