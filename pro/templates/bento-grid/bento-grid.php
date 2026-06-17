<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_rows = isset($veloria_settings['rows']) && is_array($veloria_settings['rows']) ? $veloria_settings['rows'] : [];
?>
<div class="veloria-bento-grid">
    <?php foreach ($veloria_rows as $veloria_row) : ?>
        <?php
        $veloria_columns = isset($veloria_row['columns']) && is_array($veloria_row['columns']) ? $veloria_row['columns'] : [];
        if (empty($veloria_columns)) {
            continue;
        }
        ?>
        <div class="veloria-bento-grid__row">
            <?php foreach ($veloria_columns as $veloria_column) : ?>
                <?php
                $veloria_spanDesktop = isset($veloria_column['span_desktop']) ? max(1, min(12, (int) $veloria_column['span_desktop'])) : 6;
                $veloria_spanTablet  = isset($veloria_column['span_tablet']) ? max(1, min(12, (int) $veloria_column['span_tablet'])) : 6;
                $veloria_spanMobile  = isset($veloria_column['span_mobile']) ? max(1, min(12, (int) $veloria_column['span_mobile'])) : 12;
                $veloria_title = isset($veloria_column['title']) ? (string) $veloria_column['title'] : '';
                $veloria_subtitle = isset($veloria_column['subtitle']) ? (string) $veloria_column['subtitle'] : '';
                $veloria_iconColor = !empty($veloria_column['icon_color']) ? (string) $veloria_column['icon_color'] : '#2563eb';

                $veloria_backgroundStyle = 'background: #eef6ff;';
                if (($veloria_column['background_type'] ?? 'solid') === 'gradient') {
                    $veloria_angle = isset($veloria_column['gradient_angle']) ? (float) $veloria_column['gradient_angle'] : 135;
                    $veloria_start = !empty($veloria_column['gradient_start']) ? (string) $veloria_column['gradient_start'] : '#eef6ff';
                    $veloria_end = !empty($veloria_column['gradient_end']) ? (string) $veloria_column['gradient_end'] : '#e8f7ff';
                    $veloria_backgroundStyle = 'background: linear-gradient(' . $veloria_angle . 'deg, ' . esc_attr($veloria_start) . ', ' . esc_attr($veloria_end) . ');';
                } elseif (!empty($veloria_column['background_color'])) {
                    $veloria_backgroundStyle = 'background: ' . esc_attr((string) $veloria_column['background_color']) . ';';
                }

                $veloria_tileStyle = '--er-bento-span-d:' . $veloria_spanDesktop . ';--er-bento-span-t:' . $veloria_spanTablet . ';--er-bento-span-m:' . $veloria_spanMobile . ';--er-bento-icon-color:' . esc_attr($veloria_iconColor) . ';' . $veloria_backgroundStyle;
                ?>
                <div class="veloria-bento-grid__tile" style="<?php echo esc_attr($veloria_tileStyle); ?>">
                    <?php if (!empty($veloria_column['icon']['value'])) : ?>
                        <div class="veloria-bento-grid__icon">
                            <?php \Elementor\Icons_Manager::render_icon($veloria_column['icon'], ['aria-hidden' => 'true']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($veloria_title !== '') : ?>
                        <h4 class="veloria-bento-grid__title"><?php echo esc_html($veloria_title); ?></h4>
                    <?php endif; ?>

                    <?php if ($veloria_subtitle !== '') : ?>
                        <p class="veloria-bento-grid__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php
    }
}
