<?php
namespace Elementrify\Widgets\CodeSnippet;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class CodeSnippet extends WidgetBase
{
    public function get_icon(): string { return 'eicon-code elementrify-icon'; }
    public function get_name(): string { return 'elementrify-code-snippet'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Code Snippet', 'elementrify'); }
    public function get_description(): string { return __('Highlight code blocks', 'elementrify'); }
    public function get_brand_color(): string { return '#1e293b'; }

    public function get_style_depends(): array { return ['elementrify-code-snippet']; }

    public function enqueue_assets(): void
    {
        wp_register_style('elementrify-code-snippet', ELEMENTRIFY_URL . 'assets/css/code-snippet.css', [], ELEMENTRIFY_VERSION);
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('code', ['label' => __('Code', 'elementrify'), 'type' => Controls_Manager::CODE, 'default' => '// Your code here']);
        $this->add_control('language', ['label' => __('Language', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/code-snippet/code-snippet.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><pre class="elementrify-code-snippet"><code>{{{ settings.code }}}</code></pre><?php
    }
}
