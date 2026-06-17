<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_items = isset($veloria_settings['notice_items']) && is_array($veloria_settings['notice_items']) ? $veloria_settings['notice_items'] : [];
$veloria_expand_all = !empty($veloria_settings['expand_all_initial']) && $veloria_settings['expand_all_initial'] === 'yes';
$veloria_widget_id = $this->get_id();
$veloria_root_class = 'veloria-notice-board' . ($veloria_expand_all ? ' veloria-notice-board--expand-all' : '');
?>
<div class="<?php echo esc_attr($veloria_root_class); ?>">
    <div class="veloria-notice-board__frame">
        <div class="veloria-notice-board__header-bar" aria-hidden="true"></div>
        <div class="veloria-notice-board__surface">
            <?php if ($veloria_items === []) : ?>
                <p class="veloria-notice-board__empty"><?php esc_html_e('Add notices in the widget panel.', 'veloria'); ?></p>
            <?php else : ?>
                <div class="veloria-notice-board__list">
                    <?php foreach ($veloria_items as $veloria_index => $veloria_item) : ?>
                        <?php
                        if (!is_array($veloria_item)) {
                            continue;
                        }
                        $veloria_date = isset($veloria_item['item_date']) ? (string) $veloria_item['item_date'] : '';
                        $veloria_title = isset($veloria_item['item_title']) ? (string) $veloria_item['item_title'] : '';
                        $veloria_description = isset($veloria_item['item_description']) ? (string) $veloria_item['item_description'] : '';
                        $veloria_head_id = 'nb-h-' . $veloria_widget_id . '-' . (int) $veloria_index;
                        $veloria_panel_id = 'nb-p-' . $veloria_widget_id . '-' . (int) $veloria_index;
                        ?>
                        <div class="veloria-notice-board__item">
                            <div class="veloria-notice-board__item-inner">
                                <span class="veloria-notice-board__pin" aria-hidden="true"></span>
                                <?php if ($veloria_expand_all) : ?>
                                    <div class="veloria-notice-board__heading" id="<?php echo esc_attr($veloria_head_id); ?>">
                                        <?php if ($veloria_date !== '') : ?>
                                            <span class="veloria-notice-board__date"><?php echo esc_html($veloria_date); ?></span>
                                        <?php endif; ?>
                                        <?php if ($veloria_title !== '') : ?>
                                            <span class="veloria-notice-board__title"><?php echo esc_html($veloria_title); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="veloria-notice-board__panel" id="<?php echo esc_attr($veloria_panel_id); ?>" role="region" aria-labelledby="<?php echo esc_attr($veloria_head_id); ?>">
                                        <div class="veloria-notice-board__description">
                                            <?php echo wp_kses_post(wpautop($veloria_description)); ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <button type="button" class="veloria-notice-board__trigger" id="<?php echo esc_attr($veloria_head_id); ?>"
                                        aria-expanded="false" aria-controls="<?php echo esc_attr($veloria_panel_id); ?>">
                                        <?php if ($veloria_date !== '') : ?>
                                            <span class="veloria-notice-board__date"><?php echo esc_html($veloria_date); ?></span>
                                        <?php endif; ?>
                                        <?php if ($veloria_title !== '') : ?>
                                            <span class="veloria-notice-board__title"><?php echo esc_html($veloria_title); ?></span>
                                        <?php endif; ?>
                                        <span class="veloria-notice-board__chevron" aria-hidden="true"></span>
                                    </button>
                                    <div class="veloria-notice-board__panel" id="<?php echo esc_attr($veloria_panel_id); ?>" role="region"
                                        aria-labelledby="<?php echo esc_attr($veloria_head_id); ?>" hidden>
                                        <div class="veloria-notice-board__description">
                                            <?php echo wp_kses_post(wpautop($veloria_description)); ?>
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
