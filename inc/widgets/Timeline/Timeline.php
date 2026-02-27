<?php
namespace Elementrify\Widgets\Timeline;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class Timeline extends WidgetBase
{
    public function get_icon(): string { return 'eicon-time-line elementrify-icon'; }
    public function get_name(): string { return 'elementrify-timeline'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Timeline', 'elementrify'); }
    public function get_description(): string { return __('Vertical timeline layouts', 'elementrify'); }
    public function get_brand_color(): string { return '#4f46e5'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('date', ['label' => __('Date', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '2024']);
        $r->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Event', 'elementrify')]);
        $r->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->add_control('items', ['label' => __('Items', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['date' => '2024', 'title' => __('Event 1', 'elementrify')]], 'title_field' => '{{{ title }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/timeline/timeline.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-timeline"><# _.each(settings.items, function(item) { #><div class="elementrify-timeline__item"><span class="elementrify-timeline__date">{{{ item.date }}}</span><div class="elementrify-timeline__content"><h4>{{{ item.title }}}</h4><p>{{{ item.content }}}</p></div></div><# }); #></div><?php
    }
}
