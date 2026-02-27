<?php
namespace Elementrify\Widgets\AdvancedGoogleMap;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class AdvancedGoogleMap extends WidgetBase
{
    public function get_icon(): string { return 'eicon-google-maps elementrify-icon'; }
    public function get_name(): string { return 'elementrify-advanced-google-map'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Advanced Google Map', 'elementrify'); }
    public function get_description(): string { return __('Custom map styles & markers', 'elementrify'); }
    public function get_brand_color(): string { return '#4285f4'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('address', ['label' => __('Address / Coordinates', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => 'London, UK']);
        $this->add_control('zoom', ['label' => __('Zoom', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 1, 'max' => 20]], 'default' => ['size' => 10]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/advanced-google-map/advanced-google-map.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-google-map"><div class="elementrify-google-map__placeholder"><?php echo esc_html__('Map placeholder:', 'elementrify'); ?> {{{ settings.address }}}</div></div><?php
    }
}
