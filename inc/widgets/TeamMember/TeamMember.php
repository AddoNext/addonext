<?php
namespace Elementrify\Widgets\TeamMember;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class TeamMember extends WidgetBase
{
    public function get_icon(): string { return 'eicon-person elementrify-icon'; }
    public function get_name(): string { return 'elementrify-team-member'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Team Member', 'elementrify'); }
    public function get_description(): string { return __('Showcase team cards', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('image', ['label' => __('Photo', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('name', ['label' => __('Name', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('John Doe', 'elementrify')]);
        $this->add_control('role', ['label' => __('Role', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Designer', 'elementrify')]);
        $this->add_control('bio', ['label' => __('Short bio', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/team-member/team-member.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-team-member"><# if (settings.image && settings.image.url) { #><img src="{{ settings.image.url }}" alt="{{ settings.name }}" class="elementrify-team-member__image"><# } #><h4 class="elementrify-team-member__name">{{{ settings.name }}}</h4><p class="elementrify-team-member__role">{{{ settings.role }}}</p><p class="elementrify-team-member__bio">{{{ settings.bio }}}</p></div><?php
    }
}
