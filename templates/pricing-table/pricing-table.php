<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$plans = isset($settings['plans']) && is_array($settings['plans']) ? $settings['plans'] : [];
$badge_text = isset($settings['badge_text']) ? $settings['badge_text'] : __('RECOMMENDED', 'veloria');

if (empty($plans)) {
    return;
}
?>
<div class="veloria-pricing-table">
    <div class="veloria-pricing-table__list">
        <?php
            $default_icons = ['eicon-star', 'eicon-upgrade-crown', 'eicon-theme-builder'];
            foreach ($plans as $index => $plan) :
            $featured = !empty($plan['featured']) && $plan['featured'] === 'yes';
            $button_primary = !empty($plan['button_primary']) && $plan['button_primary'] === 'yes';
            $btn_url = !empty($plan['button_url']['url']) ? $plan['button_url']['url'] : '#';
            $card_class = 'veloria-pricing-table__card';
            if ($featured) {
                $card_class .= ' veloria-pricing-table__card--featured';
            }
            if ($button_primary && !$featured) {
                $card_class .= ' veloria-pricing-table__card--primary-btn';
            }
            $btn_class = 'veloria-pricing-table__button';
            if ($button_primary) {
                $btn_class .= ' veloria-pricing-table__button--primary';
            }
            $icon_settings = isset($plan['icon']) && is_array($plan['icon']) ? $plan['icon'] : [];
            if (!empty($plan['icon']) && is_string($plan['icon'])) {
                $icon_settings = ['value' => $plan['icon'], 'library' => 'eicons'];
            }
            if (empty($icon_settings['value'])) {
                $fallback = $default_icons[$index % count($default_icons)] ?? 'eicon-star';
                $icon_settings = ['value' => $fallback, 'library' => 'eicons'];
            }
            ob_start();
            \Elementor\Icons_Manager::render_icon($icon_settings, ['aria-hidden' => 'true']);
            $icon_html = ob_get_clean();
        ?>
            <div class="<?php echo esc_attr($card_class); ?>">
                <?php if ($featured && $badge_text !== '') : ?>
                    <div class="veloria-pricing-table__badge"><?php echo esc_html($badge_text); ?></div>
                <?php endif; ?>
                <div class="veloria-pricing-table__inner">
                    <div class="veloria-pricing-table__icon-wrap"><?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager::render_icon output.
                    echo $icon_html;
                    ?></div>
                    <h3 class="veloria-pricing-table__title"><?php echo esc_html($plan['title'] ?? ''); ?></h3>
                    <?php if (!empty($plan['description'])) : ?>
                        <p class="veloria-pricing-table__desc"><?php echo esc_html($plan['description']); ?></p>
                    <?php endif; ?>
                    <div class="veloria-pricing-table__price-wrap">
                        <span class="veloria-pricing-table__price"><?php echo esc_html($plan['price'] ?? ''); ?></span>
                        <span class="veloria-pricing-table__period"><?php echo esc_html($plan['period'] ?? ''); ?></span>
                    </div>
                    <a href="<?php echo esc_url($btn_url); ?>" class="<?php echo esc_attr($btn_class); ?>"><?php echo esc_html($plan['button_text'] ?? ''); ?></a>
                    <?php
                    $features = isset($plan['features']) && is_array($plan['features']) ? $plan['features'] : [];
                    if (!empty($features)) :
                    ?>
                        <ul class="veloria-pricing-table__features">
                            <?php foreach ($features as $f) :
                                $included = !empty($f['included']) && $f['included'] === 'yes';
                                $feature_class = 'veloria-pricing-table__feature veloria-pricing-table__feature--' . ($included ? 'included' : 'excluded');
                            ?>
                                <li class="<?php echo esc_attr($feature_class); ?>">
                                    <span class="veloria-pricing-table__feature-icon" aria-hidden="true">
                                        <?php if ($included) : ?>
                                            <svg class="veloria-pricing-table__check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <?php else : ?>
                                            <svg class="veloria-pricing-table__cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        <?php endif; ?>
                                    </span>
                                    <span class="veloria-pricing-table__feature-text"><?php echo esc_html($f['text'] ?? ''); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
