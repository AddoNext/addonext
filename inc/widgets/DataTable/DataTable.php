<?php
namespace Elementrify\Widgets\DataTable;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class DataTable extends WidgetBase
{
    public function get_icon(): string { return 'eicon-table elementrify-icon'; }
    public function get_name(): string { return 'elementrify-data-table'; }
    public function get_admin_category(): string { return 'data'; }
    public function get_title(): string { return __('Data Table', 'elementrify'); }
    public function get_description(): string { return __('Sortable data tables', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('header_row', ['label' => __('Header (comma-separated)', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Name, Value, Status', 'elementrify')]);
        $r = new Repeater();
        $r->add_control('cells', ['label' => __('Row (comma-separated)', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('rows', ['label' => __('Rows', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['cells' => 'A, 1, Active']], 'title_field' => '{{{ cells }}}']);
        $this->add_control('sortable', ['label' => __('Sortable', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/data-table/data-table.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-data-table"><table><thead><tr><# var headers = (settings.header_row || '').split(','); _.each(headers, function(h) { #><th>{{{ h.trim() }}}</th><# }); #></tr></thead><tbody><# _.each(settings.rows, function(row) { var cells = (row.cells || '').split(','); #><tr><# _.each(cells, function(c) { #><td>{{{ c.trim() }}}</td><# }); #></tr><# }); #></tbody></table></div><?php
    }
}
