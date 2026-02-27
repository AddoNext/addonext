<?php
namespace Elementrify\Widgets\SocialShare;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class SocialShare extends WidgetBase
{
    public function get_icon(): string { return 'eicon-share elementrify-icon'; }
    public function get_name(): string { return 'elementrify-social-share'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Social Share', 'elementrify'); }
    public function get_description(): string { return __('Boost sharing buttons', 'elementrify'); }
    public function get_brand_color(): string { return '#0ea5e9'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('network', ['label' => __('Network', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'facebook', 'options' => ['facebook' => 'Facebook', 'twitter' => 'Twitter', 'linkedin' => 'LinkedIn', 'pinterest' => 'Pinterest']]);
        $this->add_control('networks', ['label' => __('Networks', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['network' => 'facebook'], ['network' => 'twitter']], 'title_field' => '{{{ network }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/social-share/social-share.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-social-share"><# _.each(settings.networks, function(n) { #><a href="#" class="elementrify-social-share__link elementrify-social-share__link--{{{ n.network }}}" aria-label="Share on {{{ n.network }}}">{{{ n.network }}}</a><# }); #></div><?php
    }
}
