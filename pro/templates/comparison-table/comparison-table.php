<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_first_header = isset($addonext_settings['first_column_header']) ? $addonext_settings['first_column_header'] : __('Features', 'addonext');
$addonext_columns     = isset($addonext_settings['columns']) && is_array($addonext_settings['columns']) ? $addonext_settings['columns'] : [];
$addonext_rows        = isset($addonext_settings['rows']) && is_array($addonext_settings['rows']) ? $addonext_settings['rows'] : [];

if (empty($addonext_columns)) {
    return;
}
?>
<div class="addonext-comparison-table">
    <table class="addonext-comparison-table__table">
        <thead>
            <tr>
                <th class="addonext-comparison-table__th addonext-comparison-table__cell--feature"><?php echo esc_html($addonext_first_header); ?></th>
                <?php foreach ($addonext_columns as $addonext_col) :
                    $addonext_highlighted = !empty($addonext_col['highlighted']) && $addonext_col['highlighted'] === 'yes';
                    $addonext_cell_class  = 'addonext-comparison-table__th addonext-comparison-table__cell--' . ($addonext_highlighted ? 'highlighted' : 'default');
                    ?>
                    <th class="<?php echo esc_attr($addonext_cell_class); ?>"><?php echo esc_html($addonext_col['header'] ?? ''); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($addonext_rows as $addonext_row) :
                $addonext_cells = isset($addonext_row['cells']) && is_array($addonext_row['cells']) ? $addonext_row['cells'] : [];
                ?>
                <tr>
                    <td class="addonext-comparison-table__td addonext-comparison-table__cell--feature"><?php echo esc_html($addonext_row['feature'] ?? ''); ?></td>
                    <?php foreach ($addonext_columns as $addonext_col_index => $addonext_col) :
                        $addonext_cell      = $addonext_cells[$addonext_col_index] ?? [];
                        $addonext_type      = $addonext_cell['type'] ?? 'text';
                        $addonext_content   = $addonext_cell['content'] ?? '';
                        $addonext_bold      = !empty($addonext_cell['bold']) && $addonext_cell['bold'] === 'yes';
                        $addonext_highlighted = !empty($addonext_col['highlighted']) && $addonext_col['highlighted'] === 'yes';
                        $addonext_cell_class = 'addonext-comparison-table__td addonext-comparison-table__cell--' . ($addonext_highlighted ? 'highlighted' : 'default');
                        ?>
                        <td class="<?php echo esc_attr($addonext_cell_class); ?>">
                            <?php if ($addonext_type === 'check') : ?>
                                <span class="addonext-comparison-table__icon addonext-comparison-table__icon--check" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                            <?php elseif ($addonext_type === 'cross') : ?>
                                <span class="addonext-comparison-table__icon addonext-comparison-table__icon--cross" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </span>
                            <?php else : ?>
                                <span class="addonext-comparison-table__cell-text<?php echo $addonext_bold ? ' addonext-comparison-table__cell-text--bold' : ''; ?>"><?php echo esc_html($addonext_content); ?></span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
    }
}
