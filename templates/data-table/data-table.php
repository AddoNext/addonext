<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_columns  = isset($addonext_settings['columns']) && is_array($addonext_settings['columns']) ? $addonext_settings['columns'] : [];
$addonext_rows     = isset($addonext_settings['rows']) && is_array($addonext_settings['rows']) ? $addonext_settings['rows'] : [];

function addonext_data_table_badge_class($addonext_content) {
    if ($addonext_content === '') {
        return 'addonext-data-table__badge--default';
    }
    $addonext_c = strtolower(trim($addonext_content));
    if ($addonext_c === 'active') {
        return 'addonext-data-table__badge--active';
    }
    if ($addonext_c === 'pending') {
        return 'addonext-data-table__badge--pending';
    }
    if ($addonext_c === 'inactive') {
        return 'addonext-data-table__badge--inactive';
    }
    return 'addonext-data-table__badge--default';
}

if (empty($addonext_columns)) {
    return;
}
?>
<div class="addonext-data-table">
    <table class="addonext-data-table__table">
        <thead>
            <tr>
                <?php foreach ($addonext_columns as $addonext_col) : ?>
                    <th class="addonext-data-table__th"><?php echo esc_html($addonext_col['header'] ?? ''); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($addonext_rows as $addonext_row) :
                $addonext_cells = isset($addonext_row['cells']) && is_array($addonext_row['cells']) ? $addonext_row['cells'] : [];
                ?>
                <tr>
                    <?php foreach ($addonext_columns as $addonext_col_index => $addonext_col) :
                        $addonext_cell   = $addonext_cells[$addonext_col_index] ?? [];
                        $addonext_content = isset($addonext_cell['content']) ? $addonext_cell['content'] : '';
                        $addonext_type   = $addonext_col['type'] ?? 'default';
                        $addonext_td_class = 'addonext-data-table__td';
                        if ($addonext_type === 'bold') {
                            $addonext_td_class .= ' addonext-data-table__td--bold';
                        }
                        if ($addonext_type === 'number') {
                            $addonext_td_class .= ' addonext-data-table__td--number';
                        }
                        if ($addonext_type === 'badge') {
                            $addonext_td_class .= ' addonext-data-table__td--badge';
                        }
                        ?>
                        <td class="<?php echo esc_attr($addonext_td_class); ?>">
                            <?php if ($addonext_type === 'badge') :
                                $addonext_badge_class = addonext_data_table_badge_class($addonext_content);
                                ?>
                                <span class="addonext-data-table__badge <?php echo esc_attr($addonext_badge_class); ?>"><?php echo esc_html($addonext_content); ?></span>
                            <?php else :
                                echo esc_html($addonext_content);
                            endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
