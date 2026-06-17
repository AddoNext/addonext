<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_plans = isset($veloria_settings['plans']) && is_array($veloria_settings['plans']) ? $veloria_settings['plans'] : [];
$veloria_badge_text = isset($veloria_settings['badge_text']) ? $veloria_settings['badge_text'] : __('RECOMMENDED', 'veloria');

if (empty($veloria_plans)) {
    return;
}
?>
<div class="veloria-pricing-table">
    <div class="veloria-pricing-table__list">
        <?php
            $veloria_default_icons = ['eicon-star', 'eicon-upgrade-crown', 'eicon-theme-builder'];
            foreach ($veloria_plans as $veloria_index => $veloria_plan) :
            $veloria_featured = !empty($veloria_plan['featured']) && $veloria_plan['featured'] === 'yes';
            $veloria_button_primary = !empty($veloria_plan['button_primary']) && $veloria_plan['button_primary'] === 'yes';
            $veloria_btn_url = !empty($veloria_plan['button_url']['url']) ? $veloria_plan['button_url']['url'] : '#';
            $veloria_card_class = 'veloria-pricing-table__card';
            if ($veloria_featured) {
                $veloria_card_class .= ' veloria-pricing-table__card--featured';
            }
            if ($veloria_button_primary && !$veloria_featured) {
                $veloria_card_class .= ' veloria-pricing-table__card--primary-btn';
            }
            $veloria_btn_class = 'veloria-pricing-table__button';
            if ($veloria_button_primary) {
                $veloria_btn_class .= ' veloria-pricing-table__button--primary';
            }
            $veloria_icon_settings = isset($veloria_plan['icon']) && is_array($veloria_plan['icon']) ? $veloria_plan['icon'] : [];
            if (!empty($veloria_plan['icon']) && is_string($veloria_plan['icon'])) {
                $veloria_icon_settings = ['value' => $veloria_plan['icon'], 'library' => 'eicons'];
            }
            if (empty($veloria_icon_settings['value'])) {
                $veloria_fallback = $veloria_default_icons[$veloria_index % count($veloria_default_icons)] ?? 'eicon-star';
                $veloria_icon_settings = ['value' => $veloria_fallback, 'library' => 'eicons'];
            }
            ob_start();
            \Elementor\Icons_Manager::render_icon($veloria_icon_settings, ['aria-hidden' => 'true']);
            $veloria_icon_html = ob_get_clean();
        ?>
            <div class="<?php echo esc_attr($veloria_card_class); ?>">
                <?php if ($veloria_featured && $veloria_badge_text !== '') : ?>
                    <div class="veloria-pricing-table__badge"><?php echo esc_html($veloria_badge_text); ?></div>
                <?php endif; ?>
                <div class="veloria-pricing-table__inner">
                    <div class="veloria-pricing-table__icon-wrap"><?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                    echo $veloria_icon_html;
                    ?></div>
                    <h3 class="veloria-pricing-table__title"><?php echo esc_html($veloria_plan['title'] ?? ''); ?></h3>
                    <?php if (!empty($veloria_plan['description'])) : ?>
                        <p class="veloria-pricing-table__desc"><?php echo esc_html($veloria_plan['description']); ?></p>
                    <?php endif; ?>
                    <div class="veloria-pricing-table__price-wrap">
                        <span class="veloria-pricing-table__price"><?php echo esc_html($veloria_plan['price'] ?? ''); ?></span>
                        <span class="veloria-pricing-table__period"><?php echo esc_html($veloria_plan['period'] ?? ''); ?></span>
                    </div>
                    <a href="<?php echo esc_url($veloria_btn_url); ?>" class="<?php echo esc_attr($veloria_btn_class); ?>"><?php echo esc_html($veloria_plan['button_text'] ?? ''); ?></a>
                    <?php
                    $veloria_features = isset($veloria_plan['features']) && is_array($veloria_plan['features']) ? $veloria_plan['features'] : [];
                    if (!empty($veloria_features)) :
                    ?>
                        <ul class="veloria-pricing-table__features">
                            <?php foreach ($veloria_features as $veloria_f) :
                                $veloria_included = !empty($veloria_f['included']) && $veloria_f['included'] === 'yes';
                                $veloria_feature_class = 'veloria-pricing-table__feature veloria-pricing-table__feature--' . ($veloria_included ? 'included' : 'excluded');
                            ?>
                                <li class="<?php echo esc_attr($veloria_feature_class); ?>">
                                    <span class="veloria-pricing-table__feature-icon" aria-hidden="true">
                                        <?php if ($veloria_included) : ?>
                                            <svg class="veloria-pricing-table__check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <?php else : ?>
                                            <svg class="veloria-pricing-table__cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        <?php endif; ?>
                                    </span>
                                    <span class="veloria-pricing-table__feature-text"><?php echo esc_html($veloria_f['text'] ?? ''); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
