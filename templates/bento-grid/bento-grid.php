<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$rows = isset($settings['rows']) && is_array($settings['rows']) ? $settings['rows'] : [];
?>
<div class="elementrify-bento-grid">
    <?php foreach ($rows as $row) : ?>
        <?php
        $columns = isset($row['columns']) && is_array($row['columns']) ? $row['columns'] : [];
        if (empty($columns)) {
            continue;
        }
        ?>
        <div class="elementrify-bento-grid__row">
            <?php foreach ($columns as $column) : ?>
                <?php
                $spanDesktop = isset($column['span_desktop']) ? max(1, min(12, (int) $column['span_desktop'])) : 6;
                $spanTablet  = isset($column['span_tablet']) ? max(1, min(12, (int) $column['span_tablet'])) : 6;
                $spanMobile  = isset($column['span_mobile']) ? max(1, min(12, (int) $column['span_mobile'])) : 12;
                $title = isset($column['title']) ? (string) $column['title'] : '';
                $subtitle = isset($column['subtitle']) ? (string) $column['subtitle'] : '';
                $iconColor = !empty($column['icon_color']) ? (string) $column['icon_color'] : '#2563eb';

                $backgroundStyle = 'background: #eef6ff;';
                if (($column['background_type'] ?? 'solid') === 'gradient') {
                    $angle = isset($column['gradient_angle']) ? (float) $column['gradient_angle'] : 135;
                    $start = !empty($column['gradient_start']) ? (string) $column['gradient_start'] : '#eef6ff';
                    $end = !empty($column['gradient_end']) ? (string) $column['gradient_end'] : '#e8f7ff';
                    $backgroundStyle = 'background: linear-gradient(' . $angle . 'deg, ' . esc_attr($start) . ', ' . esc_attr($end) . ');';
                } elseif (!empty($column['background_color'])) {
                    $backgroundStyle = 'background: ' . esc_attr((string) $column['background_color']) . ';';
                }

                $tileStyle = '--er-bento-span-d:' . $spanDesktop . ';--er-bento-span-t:' . $spanTablet . ';--er-bento-span-m:' . $spanMobile . ';--er-bento-icon-color:' . esc_attr($iconColor) . ';' . $backgroundStyle;
                ?>
                <div class="elementrify-bento-grid__tile" style="<?php echo esc_attr($tileStyle); ?>">
                    <?php if (!empty($column['icon']['value'])) : ?>
                        <div class="elementrify-bento-grid__icon">
                            <?php \Elementor\Icons_Manager::render_icon($column['icon'], ['aria-hidden' => 'true']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($title !== '') : ?>
                        <h4 class="elementrify-bento-grid__title"><?php echo esc_html($title); ?></h4>
                    <?php endif; ?>

                    <?php if ($subtitle !== '') : ?>
                        <p class="elementrify-bento-grid__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php
    }
}
