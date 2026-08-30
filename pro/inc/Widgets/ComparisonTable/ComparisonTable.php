<?php
namespace Addonext\Widgets\ComparisonTable;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ComparisonTable extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-table addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-comparison-table';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Comparison Table', 'addonext');
    }

    public function get_description(): string
    {
        return __('Feature comparison across plans', 'addonext');
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
        return ['addonext-comparison-table'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style('addonext-comparison-table', ADDONEXT_URL . 'assets/css/comparison-table.css', [], ADDONEXT_VERSION);
    }

    public function register_controls(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'first_column_header',
            [
                'label'   => __('First column header', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Features', 'addonext'),
            ]
        );

        $columns_repeater = new Repeater();
        $columns_repeater->add_control(
            'header',
            [
                'label'   => __('Column header', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Plan', 'addonext'),
            ]
        );
        $columns_repeater->add_control(
            'highlighted',
            [
                'label'        => __('Highlighted column', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $cells_repeater = new Repeater();
        $cells_repeater->add_control(
            'type',
            [
                'label'   => __('Type', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text'  => __('Text', 'addonext'),
                    'check' => __('Checkmark', 'addonext'),
                    'cross' => __('Cross', 'addonext'),
                ],
            ]
        );
        $cells_repeater->add_control(
            'content',
            [
                'label'     => __('Content', 'addonext'),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => ['type' => 'text'],
            ]
        );
        $cells_repeater->add_control(
            'bold',
            [
                'label'        => __('Bold', 'addonext'),
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
                'label'   => __('Row label (for list)', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Row', 'addonext'),
            ]
        );
        $rows_repeater->add_control(
            'feature',
            [
                'label'   => __('Feature name', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $rows_repeater->add_control(
            'cells',
            [
                'label'       => __('Cells', 'addonext'),
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
                'label'       => __('Plan columns', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $columns_repeater->get_controls(),
                'default'     => [
                    ['header' => __('Free', 'addonext'), 'highlighted' => ''],
                    ['header' => __('Pro', 'addonext'), 'highlighted' => 'yes'],
                    ['header' => __('Agency', 'addonext'), 'highlighted' => ''],
                ],
                'title_field' => '{{{ header }}}',
            ]
        );

        $this->add_control(
            'rows',
            [
                'label'       => __('Rows', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $rows_repeater->get_controls(),
                'default'     => [
                    [
                        'row_label' => __('Number of Widgets', 'addonext'),
                        'feature'   => __('Number of Widgets', 'addonext'),
                        'cells'     => [
                            ['type' => 'text', 'content' => '20', 'bold' => ''],
                            ['type' => 'text', 'content' => '50+', 'bold' => 'yes'],
                            ['type' => 'text', 'content' => '50+', 'bold' => 'yes'],
                        ],
                    ],
                    [
                        'row_label' => __('Templates', 'addonext'),
                        'feature'   => __('Templates', 'addonext'),
                        'cells'     => [
                            ['type' => 'text', 'content' => '10 Basic', 'bold' => ''],
                            ['type' => 'text', 'content' => '100+ Pro', 'bold' => 'yes'],
                            ['type' => 'text', 'content' => '100+ Pro', 'bold' => 'yes'],
                        ],
                    ],
                    [
                        'row_label' => __('Website Licenses', 'addonext'),
                        'feature'   => __('Website Licenses', 'addonext'),
                        'cells'     => [
                            ['type' => 'text', 'content' => '1', 'bold' => ''],
                            ['type' => 'text', 'content' => '3', 'bold' => ''],
                            ['type' => 'text', 'content' => __('Unlimited', 'addonext'), 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('Support', 'addonext'),
                        'feature'   => __('Support', 'addonext'),
                        'cells'     => [
                            ['type' => 'text', 'content' => __('Community', 'addonext'), 'bold' => ''],
                            ['type' => 'text', 'content' => __('Priority Email', 'addonext'), 'bold' => 'yes'],
                            ['type' => 'text', 'content' => '24/7 Priority', 'bold' => 'yes'],
                        ],
                    ],
                    [
                        'row_label' => __('Advanced Extensions', 'addonext'),
                        'feature'   => __('Advanced Extensions', 'addonext'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('White Label', 'addonext'),
                        'feature'   => __('White Label', 'addonext'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('Custom Development', 'addonext'),
                        'feature'   => __('Custom Development', 'addonext'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('API Access', 'addonext'),
                        'feature'   => __('API Access', 'addonext'),
                        'cells'     => [
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'cross', 'content' => '', 'bold' => ''],
                            ['type' => 'check', 'content' => '', 'bold' => ''],
                        ],
                    ],
                    [
                        'row_label' => __('Lifetime Updates', 'addonext'),
                        'feature'   => __('Lifetime Updates', 'addonext'),
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
                'label' => __('Table', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_border_color',
            [
                'label'     => __('Border color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e9ecef',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__table' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-comparison-table__th,' .
                    '{{WRAPPER}} .addonext-comparison-table__td' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_bg',
            [
                'label'     => __('Default background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__table,' .
                    '{{WRAPPER}} .addonext-comparison-table__td.addonext-comparison-table__cell--feature,' .
                    '{{WRAPPER}} .addonext-comparison-table__td.addonext-comparison-table__cell--default' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'highlighted_bg',
            [
                'label'     => __('Highlighted column background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f6f7ff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__cell--highlighted' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => __('Header', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_typography',
                'selector' => '{{WRAPPER}} .addonext-comparison-table__th',
            ]
        );

        $this->add_control(
            'header_bg',
            [
                'label'     => __('Header background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__table thead .addonext-comparison-table__th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_highlighted_bg',
            [
                'label'     => __('Highlighted column header background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f6f7ff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__table thead .addonext-comparison-table__th.addonext-comparison-table__cell--highlighted' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343a40',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-comparison-table__th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cells',
            [
                'label' => __('Cells', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cell_typography',
                'selector' => '{{WRAPPER}} .addonext-comparison-table__td',
            ]
        );

        $this->add_control(
            'cell_color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343a40',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cell_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-comparison-table__td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'check_color',
            [
                'label'     => __('Checkmark color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#28a745',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__icon--check' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cross_color',
            [
                'label'     => __('Cross color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#adb5bd',
                'selectors' => [
                    '{{WRAPPER}} .addonext-comparison-table__icon--cross' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        $path = ADDONEXT_DIR . 'pro/templates/comparison-table/comparison-table.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    protected function content_template(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        ?>
        <#
        var firstHeader = settings.first_column_header || 'Features';
        var columns = settings.columns || [];
        var rows = settings.rows || [];
        #>
        <div class="addonext-comparison-table">
            <table class="addonext-comparison-table__table">
                <thead>
                    <tr>
                        <th class="addonext-comparison-table__th addonext-comparison-table__cell--feature">{{{ firstHeader }}}</th>
                        <# _.each(columns, function(col) { #>
                            <th class="addonext-comparison-table__th addonext-comparison-table__cell--<# print(col.highlighted === 'yes' ? 'highlighted' : 'default'); #>">{{{ col.header }}}</th>
                        <# }); #>
                    </tr>
                </thead>
                <tbody>
                    <# _.each(rows, function(row) {
                        var cells = row.cells || [];
                        #>
                        <tr>
                            <td class="addonext-comparison-table__td addonext-comparison-table__cell--feature">{{{ row.feature }}}</td>
                            <# _.each(columns, function(col, colIndex) {
                                var cell = cells[colIndex] || {};
                                var type = cell.type || 'text';
                                var content = cell.content || '';
                                var bold = cell.bold === 'yes';
                                var cellClass = 'addonext-comparison-table__td addonext-comparison-table__cell--' + (col.highlighted === 'yes' ? 'highlighted' : 'default');
                            #>
                                <td class="{{ cellClass }}">
                                    <# if (type === 'check') { #>
                                        <span class="addonext-comparison-table__icon addonext-comparison-table__icon--check" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                                    <# } else if (type === 'cross') { #>
                                        <span class="addonext-comparison-table__icon addonext-comparison-table__icon--cross" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>
                                    <# } else { #>
                                        <span class="addonext-comparison-table__cell-text<# if (bold) { #> addonext-comparison-table__cell-text--bold<# } #>">{{{ content }}}</span>
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
