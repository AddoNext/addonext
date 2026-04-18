<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$items = isset($settings['notice_items']) && is_array($settings['notice_items']) ? $settings['notice_items'] : [];
$expand_all = !empty($settings['expand_all_initial']) && $settings['expand_all_initial'] === 'yes';
$widget_id = $this->get_id();
$root_class = 'elementrify-notice-board' . ($expand_all ? ' elementrify-notice-board--expand-all' : '');
?>
<div class="<?php echo esc_attr($root_class); ?>">
    <div class="elementrify-notice-board__frame">
        <div class="elementrify-notice-board__header-bar" aria-hidden="true"></div>
        <div class="elementrify-notice-board__surface">
            <?php if ($items === []) : ?>
                <p class="elementrify-notice-board__empty"><?php esc_html_e('Add notices in the widget panel.', 'elementrify'); ?></p>
            <?php else : ?>
                <div class="elementrify-notice-board__list">
                    <?php foreach ($items as $index => $item) : ?>
                        <?php
                        if (!is_array($item)) {
                            continue;
                        }
                        $date = isset($item['item_date']) ? (string) $item['item_date'] : '';
                        $title = isset($item['item_title']) ? (string) $item['item_title'] : '';
                        $description = isset($item['item_description']) ? (string) $item['item_description'] : '';
                        $head_id = 'nb-h-' . $widget_id . '-' . (int) $index;
                        $panel_id = 'nb-p-' . $widget_id . '-' . (int) $index;
                        ?>
                        <div class="elementrify-notice-board__item">
                            <div class="elementrify-notice-board__item-inner">
                                <span class="elementrify-notice-board__pin" aria-hidden="true"></span>
                                <?php if ($expand_all) : ?>
                                    <div class="elementrify-notice-board__heading" id="<?php echo esc_attr($head_id); ?>">
                                        <?php if ($date !== '') : ?>
                                            <span class="elementrify-notice-board__date"><?php echo esc_html($date); ?></span>
                                        <?php endif; ?>
                                        <?php if ($title !== '') : ?>
                                            <span class="elementrify-notice-board__title"><?php echo esc_html($title); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="elementrify-notice-board__panel" id="<?php echo esc_attr($panel_id); ?>" role="region" aria-labelledby="<?php echo esc_attr($head_id); ?>">
                                        <div class="elementrify-notice-board__description">
                                            <?php echo wp_kses_post(wpautop($description)); ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <button type="button" class="elementrify-notice-board__trigger" id="<?php echo esc_attr($head_id); ?>"
                                        aria-expanded="false" aria-controls="<?php echo esc_attr($panel_id); ?>">
                                        <?php if ($date !== '') : ?>
                                            <span class="elementrify-notice-board__date"><?php echo esc_html($date); ?></span>
                                        <?php endif; ?>
                                        <?php if ($title !== '') : ?>
                                            <span class="elementrify-notice-board__title"><?php echo esc_html($title); ?></span>
                                        <?php endif; ?>
                                        <span class="elementrify-notice-board__chevron" aria-hidden="true"></span>
                                    </button>
                                    <div class="elementrify-notice-board__panel" id="<?php echo esc_attr($panel_id); ?>" role="region"
                                        aria-labelledby="<?php echo esc_attr($head_id); ?>" hidden>
                                        <div class="elementrify-notice-board__description">
                                            <?php echo wp_kses_post(wpautop($description)); ?>
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
