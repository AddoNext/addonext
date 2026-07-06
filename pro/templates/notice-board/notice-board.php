<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_items = isset($addonext_settings['notice_items']) && is_array($addonext_settings['notice_items']) ? $addonext_settings['notice_items'] : [];
$addonext_expand_all = !empty($addonext_settings['expand_all_initial']) && $addonext_settings['expand_all_initial'] === 'yes';
$addonext_widget_id = $this->get_id();
$addonext_root_class = 'addonext-notice-board' . ($addonext_expand_all ? ' addonext-notice-board--expand-all' : '');
?>
<div class="<?php echo esc_attr($addonext_root_class); ?>">
    <div class="addonext-notice-board__frame">
        <div class="addonext-notice-board__header-bar" aria-hidden="true"></div>
        <div class="addonext-notice-board__surface">
            <?php if ($addonext_items === []) : ?>
                <p class="addonext-notice-board__empty"><?php esc_html_e('Add notices in the widget panel.', 'addonext'); ?></p>
            <?php else : ?>
                <div class="addonext-notice-board__list">
                    <?php foreach ($addonext_items as $addonext_index => $addonext_item) : ?>
                        <?php
                        if (!is_array($addonext_item)) {
                            continue;
                        }
                        $addonext_date = isset($addonext_item['item_date']) ? (string) $addonext_item['item_date'] : '';
                        $addonext_title = isset($addonext_item['item_title']) ? (string) $addonext_item['item_title'] : '';
                        $addonext_description = isset($addonext_item['item_description']) ? (string) $addonext_item['item_description'] : '';
                        $addonext_head_id = 'nb-h-' . $addonext_widget_id . '-' . (int) $addonext_index;
                        $addonext_panel_id = 'nb-p-' . $addonext_widget_id . '-' . (int) $addonext_index;
                        ?>
                        <div class="addonext-notice-board__item">
                            <div class="addonext-notice-board__item-inner">
                                <span class="addonext-notice-board__pin" aria-hidden="true"></span>
                                <?php if ($addonext_expand_all) : ?>
                                    <div class="addonext-notice-board__heading" id="<?php echo esc_attr($addonext_head_id); ?>">
                                        <?php if ($addonext_date !== '') : ?>
                                            <span class="addonext-notice-board__date"><?php echo esc_html($addonext_date); ?></span>
                                        <?php endif; ?>
                                        <?php if ($addonext_title !== '') : ?>
                                            <span class="addonext-notice-board__title"><?php echo esc_html($addonext_title); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="addonext-notice-board__panel" id="<?php echo esc_attr($addonext_panel_id); ?>" role="region" aria-labelledby="<?php echo esc_attr($addonext_head_id); ?>">
                                        <div class="addonext-notice-board__description">
                                            <?php echo wp_kses_post(wpautop($addonext_description)); ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <button type="button" class="addonext-notice-board__trigger" id="<?php echo esc_attr($addonext_head_id); ?>"
                                        aria-expanded="false" aria-controls="<?php echo esc_attr($addonext_panel_id); ?>">
                                        <?php if ($addonext_date !== '') : ?>
                                            <span class="addonext-notice-board__date"><?php echo esc_html($addonext_date); ?></span>
                                        <?php endif; ?>
                                        <?php if ($addonext_title !== '') : ?>
                                            <span class="addonext-notice-board__title"><?php echo esc_html($addonext_title); ?></span>
                                        <?php endif; ?>
                                        <span class="addonext-notice-board__chevron" aria-hidden="true"></span>
                                    </button>
                                    <div class="addonext-notice-board__panel" id="<?php echo esc_attr($addonext_panel_id); ?>" role="region"
                                        aria-labelledby="<?php echo esc_attr($addonext_head_id); ?>" hidden>
                                        <div class="addonext-notice-board__description">
                                            <?php echo wp_kses_post(wpautop($addonext_description)); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
    }
}
