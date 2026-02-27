<?php
namespace Elementrify\Widgets\FAQ;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class FAQ extends WidgetBase
{
    public function get_icon(): string { return 'eicon-help elementrify-icon'; }
    public function get_name(): string { return 'elementrify-faq'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('FAQ', 'elementrify'); }
    public function get_description(): string { return __('Frequently asked questions', 'elementrify'); }
    public function get_brand_color(): string { return '#2563eb'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('question', ['label' => __('Question', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Question?', 'elementrify')]);
        $r->add_control('answer', ['label' => __('Answer', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Answer.', 'elementrify')]);
        $this->add_control('items', ['label' => __('FAQ items', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['question' => __('Question one?', 'elementrify'), 'answer' => __('Answer one.', 'elementrify')]], 'title_field' => '{{{ question }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/faq/faq.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-faq"><# _.each(settings.items, function(item, i) { var open = i === 0 ? ' is-open' : ''; #><div class="elementrify-faq__item{{ open }}"><div class="elementrify-faq__question">{{{ item.question }}}</div><div class="elementrify-faq__answer">{{{ item.answer }}}</div></div><# }); #></div><?php
    }
}
