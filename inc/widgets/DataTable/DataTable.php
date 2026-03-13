<?php
namespace Elementrify\Widgets\DataTable;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class DataTable extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-table elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-data-table';
    }

    public function get_admin_category(): string
    {
        return 'data';
    }

    public function get_title(): string
    {
        return __('Data Table', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Sortable data tables with column headers and rows', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-data-table'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style('elementrify-data-table', ELEMENTRIFY_URL . 'assets/css/data-table.css', [], ELEMENTRIFY_VERSION);
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $columns_repeater = new Repeater();
        $columns_repeater->add_control(
            'header',
            [
                'label'   => __('Column header', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Column', 'elementrify'),
            ]
        );
        $columns_repeater->add_control(
            'type',
            [
                'label'   => __('Cell type', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'elementrify'),
                    'bold'    => __('Bold', 'elementrify'),
                    'badge'   => __('Status badge', 'elementrify'),
                    'number'  => __('Number (right-aligned)', 'elementrify'),
                ],
            ]
        );

        $cells_repeater = new Repeater();
        $cells_repeater->add_control(
            'content',
            [
                'label'   => __('Cell content', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $rows_repeater = new Repeater();
        $rows_repeater->add_control(
            'row_label',
            [
                'label'   => __('Row label (for list)', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Row', 'elementrify'),
            ]
        );
        $rows_repeater->add_control(
            'cells',
            [
                'label'       => __('Cells', 'elementrify'),
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
                'label'       => __('Columns', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $columns_repeater->get_controls(),
                'default'     => [
                    ['header' => __('ID', 'elementrify'), 'type' => 'default'],
                    ['header' => __('Name', 'elementrify'), 'type' => 'bold'],
                    ['header' => __('Email', 'elementrify'), 'type' => 'default'],
                    ['header' => __('Status', 'elementrify'), 'type' => 'badge'],
                    ['header' => __('Revenue', 'elementrify'), 'type' => 'number'],
                ],
                'title_field' => '{{{ header }}}',
            ]
        );

        $this->add_control(
            'rows',
            [
                'label'       => __('Rows', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $rows_repeater->get_controls(),
                'default'     => [
                    [
                        'row_label' => '#001',
                        'cells' => [
                            ['content' => '#001'],
                            ['content' => __('John Smith', 'elementrify')],
                            ['content' => 'john@example.com'],
                            ['content' => __('Active', 'elementrify')],
                            ['content' => '$12,450'],
                        ],
                    ],
                    [
                        'row_label' => '#002',
                        'cells' => [
                            ['content' => '#002'],
                            ['content' => __('Emma Wilson', 'elementrify')],
                            ['content' => 'emma@example.com'],
                            ['content' => __('Pending', 'elementrify')],
                            ['content' => '$8,200'],
                        ],
                    ],
                    [
                        'row_label' => '#003',
                        'cells' => [
                            ['content' => '#003'],
                            ['content' => __('Michael Brown', 'elementrify')],
                            ['content' => 'michael@example.com'],
                            ['content' => __('Inactive', 'elementrify')],
                            ['content' => '$0'],
                        ],
                    ],
                    [
                        'row_label' => '#004',
                        'cells' => [
                            ['content' => '#004'],
                            ['content' => __('Sarah Davis', 'elementrify')],
                            ['content' => 'sarah@example.com'],
                            ['content' => __('Active', 'elementrify')],
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
                'label'        => __('Sortable', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_table',
            [
                'label' => __('Table', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cell_border_color',
            [
                'label'     => __('Border color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__table' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-data-table__th,' .
                    '{{WRAPPER}} .elementrify-data-table__td'   => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__table,' .
                    '{{WRAPPER}} .elementrify-data-table__td' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => __('Header', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_typography',
                'selector' => '{{WRAPPER}} .elementrify-data-table__th',
            ]
        );

        $this->add_control(
            'header_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-data-table__th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cells',
            [
                'label' => __('Cells', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cell_typography',
                'selector' => '{{WRAPPER}} .elementrify-data-table__td',
            ]
        );

        $this->add_control(
            'cell_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cell_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-data-table__td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_badges',
            [
                'label' => __('Status badges', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_active_bg',
            [
                'label'     => __('Active background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#dcfce7',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__badge--active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_active_color',
            [
                'label'     => __('Active text', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4CAF50',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__badge--active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_pending_bg',
            [
                'label'     => __('Pending background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFBE0',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__badge--pending' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_pending_color',
            [
                'label'     => __('Pending text', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFA000',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__badge--pending' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_inactive_bg',
            [
                'label'     => __('Inactive background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFE8E8',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__badge--inactive' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_inactive_color',
            [
                'label'     => __('Inactive text', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F44336',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-data-table__badge--inactive' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/data-table/data-table.php';
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
            if (c === 'active') return 'elementrify-data-table__badge--active';
            if (c === 'pending') return 'elementrify-data-table__badge--pending';
            if (c === 'inactive') return 'elementrify-data-table__badge--inactive';
            return 'elementrify-data-table__badge--default';
        }
        #>
        <div class="elementrify-data-table">
            <table class="elementrify-data-table__table">
                <thead>
                    <tr>
                        <# _.each(columns, function(col) { #>
                            <th class="elementrify-data-table__th">{{{ col.header }}}</th>
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
                                var cellClass = 'elementrify-data-table__td';
                                if (type === 'bold') cellClass += ' elementrify-data-table__td--bold';
                                if (type === 'number') cellClass += ' elementrify-data-table__td--number';
                                if (type === 'badge') cellClass += ' elementrify-data-table__td--badge';
                                var badgeClassVal = type === 'badge' ? badgeClass(content) : '';
                            #>
                                <# if (type === 'badge') { #>
                                <td class="{{ cellClass }}"><span class="elementrify-data-table__badge {{ badgeClassVal }}">{{{ content }}}</span></td>
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
