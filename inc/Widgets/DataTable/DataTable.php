<?php
namespace Veloria\Widgets\DataTable;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class DataTable extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-table veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-data-table';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Data Table', 'veloria');
    }

    public function get_description(): string
    {
        return __('Sortable data tables with column headers and rows', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['veloria-data-table'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style('veloria-data-table', VELORIA_URL . 'assets/css/data-table.css', [], VELORIA_VERSION);
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $columns_repeater = new Repeater();
        $columns_repeater->add_control(
            'header',
            [
                'label'   => __('Column header', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Column', 'veloria'),
            ]
        );
        $columns_repeater->add_control(
            'type',
            [
                'label'   => __('Cell type', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'veloria'),
                    'bold'    => __('Bold', 'veloria'),
                    'badge'   => __('Status badge', 'veloria'),
                    'number'  => __('Number (right-aligned)', 'veloria'),
                ],
            ]
        );

        $cells_repeater = new Repeater();
        $cells_repeater->add_control(
            'content',
            [
                'label'   => __('Cell content', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $rows_repeater = new Repeater();
        $rows_repeater->add_control(
            'row_label',
            [
                'label'   => __('Row label (for list)', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Row', 'veloria'),
            ]
        );
        $rows_repeater->add_control(
            'cells',
            [
                'label'       => __('Cells', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $cells_repeater->get_controls(),
                'default'     => [
                    ['content' => ''],
                    ['content' => ''],
                    ['content' => ''],
                    ['content' => ''],
                    ['content' => ''],
                ],
                'title_field' => '{{{ content }}}',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'       => __('Columns', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $columns_repeater->get_controls(),
                'default'     => [
                    ['header' => __('ID', 'veloria'), 'type' => 'default'],
                    ['header' => __('Name', 'veloria'), 'type' => 'bold'],
                    ['header' => __('Email', 'veloria'), 'type' => 'default'],
                    ['header' => __('Status', 'veloria'), 'type' => 'badge'],
                    ['header' => __('Revenue', 'veloria'), 'type' => 'number'],
                ],
                'title_field' => '{{{ header }}}',
            ]
        );

        $this->add_control(
            'rows',
            [
                'label'       => __('Rows', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $rows_repeater->get_controls(),
                'default'     => [
                    [
                        'row_label' => '#001',
                        'cells' => [
                            ['content' => '#001'],
                            ['content' => __('John Smith', 'veloria')],
                            ['content' => 'john@example.com'],
                            ['content' => __('Active', 'veloria')],
                            ['content' => '$12,450'],
                        ],
                    ],
                    [
                        'row_label' => '#002',
                        'cells' => [
                            ['content' => '#002'],
                            ['content' => __('Emma Wilson', 'veloria')],
                            ['content' => 'emma@example.com'],
                            ['content' => __('Pending', 'veloria')],
                            ['content' => '$8,200'],
                        ],
                    ],
                    [
                        'row_label' => '#003',
                        'cells' => [
                            ['content' => '#003'],
                            ['content' => __('Michael Brown', 'veloria')],
                            ['content' => 'michael@example.com'],
                            ['content' => __('Inactive', 'veloria')],
                            ['content' => '$0'],
                        ],
                    ],
                    [
                        'row_label' => '#004',
                        'cells' => [
                            ['content' => '#004'],
                            ['content' => __('Sarah Davis', 'veloria')],
                            ['content' => 'sarah@example.com'],
                            ['content' => __('Active', 'veloria')],
                            ['content' => '$15,780'],
                        ],
                    ],
                ],
                'title_field' => '{{{ row_label }}}',
            ]
        );

        $this->add_control(
            'sortable',
            [
                'label'        => __('Sortable', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_table',
            [
                'label' => __('Table', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cell_border_color',
            [
                'label'     => __('Border color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__table' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-data-table__th,' .
                    '{{WRAPPER}} .veloria-data-table__td'   => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__table,' .
                    '{{WRAPPER}} .veloria-data-table__td' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => __('Header', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_typography',
                'selector' => '{{WRAPPER}} .veloria-data-table__th',
            ]
        );

        $this->add_control(
            'header_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label'      => __('Padding', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-data-table__th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cells',
            [
                'label' => __('Cells', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cell_typography',
                'selector' => '{{WRAPPER}} .veloria-data-table__td',
            ]
        );

        $this->add_control(
            'cell_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cell_padding',
            [
                'label'      => __('Padding', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-data-table__td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_badges',
            [
                'label' => __('Status badges', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_active_bg',
            [
                'label'     => __('Active background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#dcfce7',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__badge--active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_active_color',
            [
                'label'     => __('Active text', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4CAF50',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__badge--active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_pending_bg',
            [
                'label'     => __('Pending background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFBE0',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__badge--pending' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_pending_color',
            [
                'label'     => __('Pending text', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFA000',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__badge--pending' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_inactive_bg',
            [
                'label'     => __('Inactive background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFE8E8',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__badge--inactive' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_inactive_color',
            [
                'label'     => __('Inactive text', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F44336',
                'selectors' => [
                    '{{WRAPPER}} .veloria-data-table__badge--inactive' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/data-table/data-table.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var columns = settings.columns || [];
        var rows = settings.rows || [];
        function badgeClass(content) {
            if (!content) return '';
            var c = (content + '').toLowerCase().trim();
            if (c === 'active') return 'veloria-data-table__badge--active';
            if (c === 'pending') return 'veloria-data-table__badge--pending';
            if (c === 'inactive') return 'veloria-data-table__badge--inactive';
            return 'veloria-data-table__badge--default';
        }
        #>
        <div class="veloria-data-table">
            <table class="veloria-data-table__table">
                <thead>
                    <tr>
                        <# _.each(columns, function(col) { #>
                            <th class="veloria-data-table__th">{{{ col.header }}}</th>
                        <# }); #>
                    </tr>
                </thead>
                <tbody>
                    <# _.each(rows, function(row) {
                        var cells = row.cells || [];
                        #>
                        <tr>
                            <# _.each(columns, function(col, colIndex) {
                                var cell = cells[colIndex] || {};
                                var content = cell.content || '';
                                var type = col.type || 'default';
                                var cellClass = 'veloria-data-table__td';
                                if (type === 'bold') cellClass += ' veloria-data-table__td--bold';
                                if (type === 'number') cellClass += ' veloria-data-table__td--number';
                                if (type === 'badge') cellClass += ' veloria-data-table__td--badge';
                                var badgeClassVal = type === 'badge' ? badgeClass(content) : '';
                            #>
                                <# if (type === 'badge') { #>
                                <td class="{{ cellClass }}"><span class="veloria-data-table__badge {{ badgeClassVal }}">{{{ content }}}</span></td>
                                <# } else { #>
                                <td class="{{ cellClass }}">{{{ content }}}</td>
                                <# } #>
                            <# }); #>
                        </tr>
                    <# }); #>
                </tbody>
            </table>
        </div>
        <?php
    }
}
