<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_rows = isset($addonext_settings['rows']) && is_array($addonext_settings['rows']) ? $addonext_settings['rows'] : [];
?>
<div class="addonext-bento-grid">
    <?php foreach ($addonext_rows as $addonext_row) : ?>
        <?php
        $addonext_columns = isset($addonext_row['columns']) && is_array($addonext_row['columns']) ? $addonext_row['columns'] : [];
        if (empty($addonext_columns)) {
            continue;
        }
        ?>
        <div class="addonext-bento-grid__row">
            <?php foreach ($addonext_columns as $addonext_column) : ?>
                <?php
                $addonext_spanDesktop = isset($addonext_column['span_desktop']) ? max(1, min(12, (int) $addonext_column['span_desktop'])) : 6;
                $addonext_spanTablet  = isset($addonext_column['span_tablet']) ? max(1, min(12, (int) $addonext_column['span_tablet'])) : 6;
                $addonext_spanMobile  = isset($addonext_column['span_mobile']) ? max(1, min(12, (int) $addonext_column['span_mobile'])) : 12;
                $addonext_title = isset($addonext_column['title']) ? (string) $addonext_column['title'] : '';
                $addonext_subtitle = isset($addonext_column['subtitle']) ? (string) $addonext_column['subtitle'] : '';
                $addonext_iconColor = !empty($addonext_column['icon_color']) ? (string) $addonext_column['icon_color'] : '#2563eb';

                $addonext_backgroundStyle = 'background: #eef6ff;';
                if (($addonext_column['background_type'] ?? 'solid') === 'gradient') {
                    $addonext_angle = isset($addonext_column['gradient_angle']) ? (float) $addonext_column['gradient_angle'] : 135;
                    $addonext_start = !empty($addonext_column['gradient_start']) ? (string) $addonext_column['gradient_start'] : '#eef6ff';
                    $addonext_end = !empty($addonext_column['gradient_end']) ? (string) $addonext_column['gradient_end'] : '#e8f7ff';
                    $addonext_backgroundStyle = 'background: linear-gradient(' . $addonext_angle . 'deg, ' . esc_attr($addonext_start) . ', ' . esc_attr($addonext_end) . ');';
                } elseif (!empty($addonext_column['background_color'])) {
                    $addonext_backgroundStyle = 'background: ' . esc_attr((string) $addonext_column['background_color']) . ';';
                }

                $addonext_tileStyle = '--er-bento-span-d:' . $addonext_spanDesktop . ';--er-bento-span-t:' . $addonext_spanTablet . ';--er-bento-span-m:' . $addonext_spanMobile . ';--er-bento-icon-color:' . esc_attr($addonext_iconColor) . ';' . $addonext_backgroundStyle;
                ?>
                <div class="addonext-bento-grid__tile" style="<?php echo esc_attr($addonext_tileStyle); ?>">
                    <?php if (!empty($addonext_column['icon']['value'])) : ?>
                        <div class="addonext-bento-grid__icon">
                            <?php \Elementor\Icons_Manager::render_icon($addonext_column['icon'], ['aria-hidden' => 'true']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($addonext_title !== '') : ?>
                        <h4 class="addonext-bento-grid__title"><?php echo esc_html($addonext_title); ?></h4>
                    <?php endif; ?>

                    <?php if ($addonext_subtitle !== '') : ?>
                        <p class="addonext-bento-grid__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php
    }
}
