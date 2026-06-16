<?php
namespace Veloria\Widgets\ComparisonTable;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ComparisonTable extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-table veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-comparison-table';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Comparison Table', 'veloria');
    }

    public function get_description(): string
    {
        return __('Feature comparison across plans', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['veloria-comparison-table'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style('veloria-comparison-table', VELORIA_URL . 'assets/css/comparison-table.css', [], VELORIA_VERSION);
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'first_column_header',
            [
                'label'   => __('First column header', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Features', 'veloria'),
            ]
        );

        $columns_repeater = new Repeater();
        $columns_repeater->add_control(
            'header',
            [
                'label'   => __('Column header', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Plan', 'veloria'),
            ]
        );
        $columns_repeater->add_control(
            'highlighted',
            [
                'label'        => __('Highlighted column', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $cells_repeater = new Repeater();
        $cells_repeater->add_control(
            'type',
            [
                'label'   => __('Type', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text'  => __('Text', 'veloria'),
                    'check' => __('Checkmark', 'veloria'),
                    'cross' => __('Cross', 'veloria'),
                ],
            ]
        );
        $cells_repeater->add_control(
            'content',
            [
                'label'     => __('Content', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => ['type' => 'text'],
            ]
        );
        $cells_repeater->add_control(
            'bold',
            [
                'label'        => __('Bold', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => ['type' => 'text'],
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
            'feature',
            [
                'label'   => __('Feature name', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $rows_repeater->add_control(
            'cells',
            [
                'label'       => __('Cells', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $cells_repeater->get_controls(),
                'default'     => [
                    ['type' => 'text', 'content' => '', 'bold' => ''],
                    ['type' => 'text', 'content' => '', 'bold' => ''],
                    ['type' => 'text', 'content' => '', 'bold' => ''],
                ],
                'title_field' => '{{{ type }}}',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'       => __('Plan columns', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $columns_repeater->get_controls(),
                'default'     => [
                    ['header' => __('Free', 'veloria'), 'highlighted' => ''],
                    ['header' => __('Pro', 'veloria'), 'highlighted' => 'yes'],
                    ['header' => __('Agency', 'veloria'), 'highlighted' => ''],
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
                        'row_label' => __('Number of Widgets', 'veloria'),
                        'feature'   => __('Number of Widgets', 'veloria'),
                        'cells'     => [
                            ['type' => 'text', 'content' => '20', 'bold' => ''],
                            ['type' => 'text', 'content' => '50+', 'bold' => 'yes'],
                            ['type' => 'text', 'content' => '50+', 'bold' => 'yes'],
                        ],
                    ],
                    [
                        'row_label' => __('Templates', 'veloria'),
                        'feature'   => __('Templates', 'veloria'),
                        'cells'     => [
                            ['type' => 'text', 'content' => '10 Basic', 'bold' => ''],
                            ['type' => 'text', 'content' => '100+ Pro', 'bold' => 'yes'],
                            ['type' => 'text', 'content' => '100+ Pro', 'bold' => 'yes'],
                        ],
                    ],
                    [
                        'row_label' => __('Website Licenses', 'veloria'),
                        'feature'   => __('Website Licenses', 'veloria'),
                        'cells'     => [
                            ['type' => 'text', 'content' => '1', 'bold' => ''],
                            ['type' => 'text', 'content' => '3', 'bold' => ''],
                            ['type' => 'text', 'content' => __('Unlimited', 'veloria'), 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('Support', 'veloria'),
                        'feature'   => __('Support', 'veloria'),
                        'cells'     => [
                            ['type' => 'text', 'content' => __('Community', 'veloria'), 'bold' => ''],
                            ['type' => 'text', 'content' => __('Priority Email', 'veloria'), 'bold' => 'yes'],
                            ['type' => 'text', 'content' => '24/7 Priority', 'bold' => 'yes'],
                        ],
                    ],
                    [
                        'row_label' => __('Advanced Extensions', 'veloria'),
                        'feature'   => __('Advanced Extensions', 'veloria'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('White Label', 'veloria'),
                        'feature'   => __('White Label', 'veloria'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('Custom Development', 'veloria'),
                        'feature'   => __('Custom Development', 'veloria'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('API Access', 'veloria'),
                        'feature'   => __('API Access', 'veloria'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('Lifetime Updates', 'veloria'),
                        'feature'   => __('Lifetime Updates', 'veloria'),
                        'cells'     => [
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                ],
                'title_field' => '{{{ row_label }}}',
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
            'table_border_color',
            [
                'label'     => __('Border color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e9ecef',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__table' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-comparison-table__th,' .
                    '{{WRAPPER}} .veloria-comparison-table__td' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_bg',
            [
                'label'     => __('Default background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__table,' .
                    '{{WRAPPER}} .veloria-comparison-table__td.veloria-comparison-table__cell--feature,' .
                    '{{WRAPPER}} .veloria-comparison-table__td.veloria-comparison-table__cell--default' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'highlighted_bg',
            [
                'label'     => __('Highlighted column background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f6f7ff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__cell--highlighted' => 'background-color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .veloria-comparison-table__th',
            ]
        );

        $this->add_control(
            'header_bg',
            [
                'label'     => __('Header background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__table thead .veloria-comparison-table__th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_highlighted_bg',
            [
                'label'     => __('Highlighted column header background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f6f7ff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__table thead .veloria-comparison-table__th.veloria-comparison-table__cell--highlighted' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343a40',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__th' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .veloria-comparison-table__th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .veloria-comparison-table__td',
            ]
        );

        $this->add_control(
            'cell_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343a40',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__td' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .veloria-comparison-table__td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'check_color',
            [
                'label'     => __('Checkmark color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#28a745',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__icon--check' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cross_color',
            [
                'label'     => __('Cross color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#adb5bd',
                'selectors' => [
                    '{{WRAPPER}} .veloria-comparison-table__icon--cross' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = VELORIA_DIR . 'pro/templates/comparison-table/comparison-table.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    protected function content_template(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        ?>
        <#
        var firstHeader = settings.first_column_header || 'Features';
        var columns = settings.columns || [];
        var rows = settings.rows || [];
        #>
        <div class="veloria-comparison-table">
            <table class="veloria-comparison-table__table">
                <thead>
                    <tr>
                        <th class="veloria-comparison-table__th veloria-comparison-table__cell--feature">{{{ firstHeader }}}</th>
                        <# _.each(columns, function(col) { #>
                            <th class="veloria-comparison-table__th veloria-comparison-table__cell--<# print(col.highlighted === 'yes' ? 'highlighted' : 'default'); #>">{{{ col.header }}}</th>
                        <# }); #>
                    </tr>
                </thead>
                <tbody>
                    <# _.each(rows, function(row) {
                        var cells = row.cells || [];
                        #>
                        <tr>
                            <td class="veloria-comparison-table__td veloria-comparison-table__cell--feature">{{{ row.feature }}}</td>
                            <# _.each(columns, function(col, colIndex) {
                                var cell = cells[colIndex] || {};
                                var type = cell.type || 'text';
                                var content = cell.content || '';
                                var bold = cell.bold === 'yes';
                                var cellClass = 'veloria-comparison-table__td veloria-comparison-table__cell--' + (col.highlighted === 'yes' ? 'highlighted' : 'default');
                            #>
                                <td class="{{ cellClass }}">
                                    <# if (type === 'check') { #>
                                        <span class="veloria-comparison-table__icon veloria-comparison-table__icon--check" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                                    <# } else if (type === 'cross') { #>
                                        <span class="veloria-comparison-table__icon veloria-comparison-table__icon--cross" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>
                                    <# } else { #>
                                        <span class="veloria-comparison-table__cell-text<# if (bold) { #> veloria-comparison-table__cell-text--bold<# } #>">{{{ content }}}</span>
                                    <# } #>
                                </td>
                            <# }); #>
                        </tr>
                    <# }); #>
                </tbody>
            </table>
        </div>
        <?php
                }
        }
}
}
