<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_first_header = isset($veloria_settings['first_column_header']) ? $veloria_settings['first_column_header'] : __('Features', 'veloria');
$veloria_columns     = isset($veloria_settings['columns']) && is_array($veloria_settings['columns']) ? $veloria_settings['columns'] : [];
$veloria_rows        = isset($veloria_settings['rows']) && is_array($veloria_settings['rows']) ? $veloria_settings['rows'] : [];

if (empty($veloria_columns)) {
    return;
}
?>
<div class="veloria-comparison-table">
    <table class="veloria-comparison-table__table">
        <thead>
            <tr>
                <th class="veloria-comparison-table__th veloria-comparison-table__cell--feature"><?php echo esc_html($veloria_first_header); ?></th>
                <?php foreach ($veloria_columns as $veloria_col) :
                    $veloria_highlighted = !empty($veloria_col['highlighted']) && $veloria_col['highlighted'] === 'yes';
                    $veloria_cell_class  = 'veloria-comparison-table__th veloria-comparison-table__cell--' . ($veloria_highlighted ? 'highlighted' : 'default');
                    ?>
                    <th class="<?php echo esc_attr($veloria_cell_class); ?>"><?php echo esc_html($veloria_col['header'] ?? ''); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($veloria_rows as $veloria_row) :
                $veloria_cells = isset($veloria_row['cells']) && is_array($veloria_row['cells']) ? $veloria_row['cells'] : [];
                ?>
                <tr>
                    <td class="veloria-comparison-table__td veloria-comparison-table__cell--feature"><?php echo esc_html($veloria_row['feature'] ?? ''); ?></td>
                    <?php foreach ($veloria_columns as $veloria_col_index => $veloria_col) :
                        $veloria_cell      = $veloria_cells[$veloria_col_index] ?? [];
                        $veloria_type      = $veloria_cell['type'] ?? 'text';
                        $veloria_content   = $veloria_cell['content'] ?? '';
                        $veloria_bold      = !empty($veloria_cell['bold']) && $veloria_cell['bold'] === 'yes';
                        $veloria_highlighted = !empty($veloria_col['highlighted']) && $veloria_col['highlighted'] === 'yes';
                        $veloria_cell_class = 'veloria-comparison-table__td veloria-comparison-table__cell--' . ($veloria_highlighted ? 'highlighted' : 'default');
                        ?>
                        <td class="<?php echo esc_attr($veloria_cell_class); ?>">
                            <?php if ($veloria_type === 'check') : ?>
                                <span class="veloria-comparison-table__icon veloria-comparison-table__icon--check" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                            <?php elseif ($veloria_type === 'cross') : ?>
                                <span class="veloria-comparison-table__icon veloria-comparison-table__icon--cross" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </span>
                            <?php else : ?>
                                <span class="veloria-comparison-table__cell-text<?php echo $veloria_bold ? ' veloria-comparison-table__cell-text--bold' : ''; ?>"><?php echo esc_html($veloria_content); ?></span>
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
