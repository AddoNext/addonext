<?php
namespace Elementrify\Widgets\StarRating;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class StarRating extends WidgetBase
{
    public function get_icon(): string { return 'eicon-star elementrify-icon'; }
    public function get_name(): string { return 'elementrify-star-rating'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Star Rating', 'elementrify'); }
    public function get_description(): string { return __('Custom rating display', 'elementrify'); }
    public function get_brand_color(): string { return '#eab308'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('rating', ['label' => __('Rating', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'min' => 0, 'max' => 5, 'step' => 0.1, 'default' => 4]);
        $this->add_control('max_rating', ['label' => __('Max rating', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 5]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/star-rating/star-rating.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-star-rating" title="{{ settings.rating }}"><span class="elementrify-star-rating__stars">{{{ settings.rating }}}/{{{ settings.max_rating }}}</span><# if (settings.title) { #><span class="elementrify-star-rating__title">{{{ settings.title }}}</span><# } #></div><?php
    }
}
