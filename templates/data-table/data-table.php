<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$columns  = isset($settings['columns']) && is_array($settings['columns']) ? $settings['columns'] : [];
$rows     = isset($settings['rows']) && is_array($settings['rows']) ? $settings['rows'] : [];

function veloria_data_table_badge_class($content) {
    if ($content === '') {
        return 'veloria-data-table__badge--default';
    }
    $c = strtolower(trim($content));
    if ($c === 'active') {
        return 'veloria-data-table__badge--active';
    }
    if ($c === 'pending') {
        return 'veloria-data-table__badge--pending';
    }
    if ($c === 'inactive') {
        return 'veloria-data-table__badge--inactive';
    }
    return 'veloria-data-table__badge--default';
}

if (empty($columns)) {
    return;
}
?>
<div class="veloria-data-table">
    <table class="veloria-data-table__table">
        <thead>
            <tr>
                <?php foreach ($columns as $col) : ?>
                    <th class="veloria-data-table__th"><?php echo esc_html($col['header'] ?? ''); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) :
                $cells = isset($row['cells']) && is_array($row['cells']) ? $row['cells'] : [];
                ?>
                <tr>
                    <?php foreach ($columns as $col_index => $col) :
                        $cell   = $cells[$col_index] ?? [];
                        $content = isset($cell['content']) ? $cell['content'] : '';
                        $type   = $col['type'] ?? 'default';
                        $td_class = 'veloria-data-table__td';
                        if ($type === 'bold') {
                            $td_class .= ' veloria-data-table__td--bold';
                        }
                        if ($type === 'number') {
                            $td_class .= ' veloria-data-table__td--number';
                        }
                        if ($type === 'badge') {
                            $td_class .= ' veloria-data-table__td--badge';
                        }
                        ?>
                        <td class="<?php echo esc_attr($td_class); ?>">
                            <?php if ($type === 'badge') :
                                $badge_class = veloria_data_table_badge_class($content);
                                ?>
                                <span class="veloria-data-table__badge <?php echo esc_attr($badge_class); ?>"><?php echo esc_html($content); ?></span>
                            <?php else :
                                echo esc_html($content);
                            endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
