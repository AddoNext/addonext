<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_plans = isset($addonext_settings['plans']) && is_array($addonext_settings['plans']) ? $addonext_settings['plans'] : [];
$addonext_badge_text = isset($addonext_settings['badge_text']) ? $addonext_settings['badge_text'] : __('RECOMMENDED', 'addonext');

if (empty($addonext_plans)) {
    return;
}
?>
<div class="addonext-pricing-table">
    <div class="addonext-pricing-table__list">
        <?php
            $addonext_default_icons = ['eicon-star', 'eicon-upgrade-crown', 'eicon-theme-builder'];
            foreach ($addonext_plans as $addonext_index => $addonext_plan) :
            $addonext_featured = !empty($addonext_plan['featured']) && $addonext_plan['featured'] === 'yes';
            $addonext_button_primary = !empty($addonext_plan['button_primary']) && $addonext_plan['button_primary'] === 'yes';
            $addonext_btn_url = !empty($addonext_plan['button_url']['url']) ? $addonext_plan['button_url']['url'] : '#';
            $addonext_card_class = 'addonext-pricing-table__card';
            if ($addonext_featured) {
                $addonext_card_class .= ' addonext-pricing-table__card--featured';
            }
            if ($addonext_button_primary && !$addonext_featured) {
                $addonext_card_class .= ' addonext-pricing-table__card--primary-btn';
            }
            $addonext_btn_class = 'addonext-pricing-table__button';
            if ($addonext_button_primary) {
                $addonext_btn_class .= ' addonext-pricing-table__button--primary';
            }
            $addonext_icon_settings = isset($addonext_plan['icon']) && is_array($addonext_plan['icon']) ? $addonext_plan['icon'] : [];
            if (!empty($addonext_plan['icon']) && is_string($addonext_plan['icon'])) {
                $addonext_icon_settings = ['value' => $addonext_plan['icon'], 'library' => 'eicons'];
            }
            if (empty($addonext_icon_settings['value'])) {
                $addonext_fallback = $addonext_default_icons[$addonext_index % count($addonext_default_icons)] ?? 'eicon-star';
                $addonext_icon_settings = ['value' => $addonext_fallback, 'library' => 'eicons'];
            }
            ob_start();
            \Elementor\Icons_Manager::render_icon($addonext_icon_settings, ['aria-hidden' => 'true']);
            $addonext_icon_html = ob_get_clean();
        ?>
            <div class="<?php echo esc_attr($addonext_card_class); ?>">
                <?php if ($addonext_featured && $addonext_badge_text !== '') : ?>
                    <div class="addonext-pricing-table__badge"><?php echo esc_html($addonext_badge_text); ?></div>
                <?php endif; ?>
                <div class="addonext-pricing-table__inner">
                    <div class="addonext-pricing-table__icon-wrap"><?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                    echo $addonext_icon_html;
                    ?></div>
                    <h3 class="addonext-pricing-table__title"><?php echo esc_html($addonext_plan['title'] ?? ''); ?></h3>
                    <?php if (!empty($addonext_plan['description'])) : ?>
                        <p class="addonext-pricing-table__desc"><?php echo esc_html($addonext_plan['description']); ?></p>
                    <?php endif; ?>
                    <div class="addonext-pricing-table__price-wrap">
                        <span class="addonext-pricing-table__price"><?php echo esc_html($addonext_plan['price'] ?? ''); ?></span>
                        <span class="addonext-pricing-table__period"><?php echo esc_html($addonext_plan['period'] ?? ''); ?></span>
                    </div>
                    <a href="<?php echo esc_url($addonext_btn_url); ?>" class="<?php echo esc_attr($addonext_btn_class); ?>"><?php echo esc_html($addonext_plan['button_text'] ?? ''); ?></a>
                    <?php
                    $addonext_features = isset($addonext_plan['features']) && is_array($addonext_plan['features']) ? $addonext_plan['features'] : [];
                    if (!empty($addonext_features)) :
                    ?>
                        <ul class="addonext-pricing-table__features">
                            <?php foreach ($addonext_features as $addonext_f) :
                                $addonext_included = !empty($addonext_f['included']) && $addonext_f['included'] === 'yes';
                                $addonext_feature_class = 'addonext-pricing-table__feature addonext-pricing-table__feature--' . ($addonext_included ? 'included' : 'excluded');
                            ?>
                                <li class="<?php echo esc_attr($addonext_feature_class); ?>">
                                    <span class="addonext-pricing-table__feature-icon" aria-hidden="true">
                                        <?php if ($addonext_included) : ?>
                                            <svg class="addonext-pricing-table__check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <?php else : ?>
                                            <svg class="addonext-pricing-table__cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        <?php endif; ?>
                                    </span>
                                    <span class="addonext-pricing-table__feature-text"><?php echo esc_html($addonext_f['text'] ?? ''); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
