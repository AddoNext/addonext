<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$first_header = isset($settings['first_column_header']) ? $settings['first_column_header'] : __('Features', 'elementrify');
$columns     = isset($settings['columns']) && is_array($settings['columns']) ? $settings['columns'] : [];
$rows        = isset($settings['rows']) && is_array($settings['rows']) ? $settings['rows'] : [];

if (empty($columns)) {
    return;
}
?>
<div class="elementrify-comparison-table">
    <table class="elementrify-comparison-table__table">
        <thead>
            <tr>
                <th class="elementrify-comparison-table__th elementrify-comparison-table__cell--feature"><?php echo esc_html($first_header); ?></th>
                <?php foreach ($columns as $col) :
                    $highlighted = !empty($col['highlighted']) && $col['highlighted'] === 'yes';
                    $cell_class  = 'elementrify-comparison-table__th elementrify-comparison-table__cell--' . ($highlighted ? 'highlighted' : 'default');
                    ?>
                    <th class="<?php echo esc_attr($cell_class); ?>"><?php echo esc_html($col['header'] ?? ''); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) :
                $cells = isset($row['cells']) && is_array($row['cells']) ? $row['cells'] : [];
                ?>
                <tr>
                    <td class="elementrify-comparison-table__td elementrify-comparison-table__cell--feature"><?php echo esc_html($row['feature'] ?? ''); ?></td>
                    <?php foreach ($columns as $col_index => $col) :
                        $cell      = $cells[$col_index] ?? [];
                        $type      = $cell['type'] ?? 'text';
                        $content   = $cell['content'] ?? '';
                        $bold      = !empty($cell['bold']) && $cell['bold'] === 'yes';
                        $highlighted = !empty($col['highlighted']) && $col['highlighted'] === 'yes';
                        $cell_class = 'elementrify-comparison-table__td elementrify-comparison-table__cell--' . ($highlighted ? 'highlighted' : 'default');
                        ?>
                        <td class="<?php echo esc_attr($cell_class); ?>">
                            <?php if ($type === 'check') : ?>
                                <span class="elementrify-comparison-table__icon elementrify-comparison-table__icon--check" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                            <?php elseif ($type === 'cross') : ?>
                                <span class="elementrify-comparison-table__icon elementrify-comparison-table__icon--cross" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </span>
                            <?php else : ?>
                                <span class="elementrify-comparison-table__cell-text<?php echo $bold ? ' elementrify-comparison-table__cell-text--bold' : ''; ?>"><?php echo esc_html($content); ?></span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
