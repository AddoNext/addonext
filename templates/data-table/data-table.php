<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_columns  = isset($veloria_settings['columns']) && is_array($veloria_settings['columns']) ? $veloria_settings['columns'] : [];
$veloria_rows     = isset($veloria_settings['rows']) && is_array($veloria_settings['rows']) ? $veloria_settings['rows'] : [];

function veloria_data_table_badge_class($veloria_content) {
    if ($veloria_content === '') {
        return 'veloria-data-table__badge--default';
    }
    $veloria_c = strtolower(trim($veloria_content));
    if ($veloria_c === 'active') {
        return 'veloria-data-table__badge--active';
    }
    if ($veloria_c === 'pending') {
        return 'veloria-data-table__badge--pending';
    }
    if ($veloria_c === 'inactive') {
        return 'veloria-data-table__badge--inactive';
    }
    return 'veloria-data-table__badge--default';
}

if (empty($veloria_columns)) {
    return;
}
?>
<div class="veloria-data-table">
    <table class="veloria-data-table__table">
        <thead>
            <tr>
                <?php foreach ($veloria_columns as $veloria_col) : ?>
                    <th class="veloria-data-table__th"><?php echo esc_html($veloria_col['header'] ?? ''); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($veloria_rows as $veloria_row) :
                $veloria_cells = isset($veloria_row['cells']) && is_array($veloria_row['cells']) ? $veloria_row['cells'] : [];
                ?>
                <tr>
                    <?php foreach ($veloria_columns as $veloria_col_index => $veloria_col) :
                        $veloria_cell   = $veloria_cells[$veloria_col_index] ?? [];
                        $veloria_content = isset($veloria_cell['content']) ? $veloria_cell['content'] : '';
                        $veloria_type   = $veloria_col['type'] ?? 'default';
                        $veloria_td_class = 'veloria-data-table__td';
                        if ($veloria_type === 'bold') {
                            $veloria_td_class .= ' veloria-data-table__td--bold';
                        }
                        if ($veloria_type === 'number') {
                            $veloria_td_class .= ' veloria-data-table__td--number';
                        }
                        if ($veloria_type === 'badge') {
                            $veloria_td_class .= ' veloria-data-table__td--badge';
                        }
                        ?>
                        <td class="<?php echo esc_attr($veloria_td_class); ?>">
                            <?php if ($veloria_type === 'badge') :
                                $veloria_badge_class = veloria_data_table_badge_class($veloria_content);
                                ?>
                                <span class="veloria-data-table__badge <?php echo esc_attr($veloria_badge_class); ?>"><?php echo esc_html($veloria_content); ?></span>
                            <?php else :
                                echo esc_html($veloria_content);
                            endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
