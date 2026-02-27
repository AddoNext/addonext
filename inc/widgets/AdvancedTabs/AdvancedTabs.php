<?php
namespace Elementrify\Widgets\AdvancedTabs;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class AdvancedTabs extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_script_in_preview']);
    }

    public function enqueue_script_in_preview(): void
    {
        $this->enqueue_assets();
        wp_enqueue_script('elementrify-advanced-tabs');
    }

    public function get_icon(): string
    {
        return 'eicon-tabs elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-advanced-tabs';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Advanced Tabs', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Tabbed content display', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-advanced-tabs'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-advanced-tabs'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-advanced-tabs',
            ELEMENTRIFY_URL . 'assets/css/advanced-tabs.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-advanced-tabs',
            ELEMENTRIFY_URL . 'assets/js/advanced-tabs.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control('tab_title', [
            'label'   => __('Title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Tab Title', 'elementrify'),
        ]);
        $repeater->add_control('tab_content', [
            'label'   => __('Content', 'elementrify'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => __('Tab content.', 'elementrify'),
        ]);

        $this->add_control(
            'tabs',
            [
                'label'       => __('Tabs', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['tab_title' => __('Tab 1', 'elementrify'), 'tab_content' => __('Content for tab 1.', 'elementrify')],
                    ['tab_title' => __('Tab 2', 'elementrify'), 'tab_content' => __('Content for tab 2.', 'elementrify')],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/advanced-tabs/advanced-tabs.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-advanced-tabs');
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <div class="elementrify-advanced-tabs__headers">
                <# _.each(settings.tabs, function(item, index) {
                    var activeClass = index === 0 ? ' is-active' : '';
                #>
                <div class="elementrify-advanced-tabs__header{{ activeClass }}" data-tab="{{ index }}">{{{ item.tab_title }}}</div>
                <# }); #>
            </div>
            <div class="elementrify-advanced-tabs__panels">
                <# _.each(settings.tabs, function(item, index) {
                    var activeClass = index === 0 ? ' is-active' : '';
                #>
                <div class="elementrify-advanced-tabs__panel{{ activeClass }}" data-tab="{{ index }}">{{{ item.tab_content }}}</div>
                <# }); #>
            </div>
        </div>
        <?php
    }
}
