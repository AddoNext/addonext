<?php
namespace Elementrify\Widgets\IconList;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class IconList extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-bullet-list elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-icon-list';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Icon List', 'elementrify');
    }

    public function get_description(): string
    {
        return __('List with custom icons', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-icon-list'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-icon-list',
            ELEMENTRIFY_URL . 'assets/css/icon-list.css',
            [],
            ELEMENTRIFY_VERSION
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
        $repeater->add_control('icon', [
            'label'   => __('Icon', 'elementrify'),
            'type'    => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('text', [
            'label'   => __('Text', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('List item', 'elementrify'),
        ]);

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['text' => __('Item 1', 'elementrify')],
                    ['text' => __('Item 2', 'elementrify')],
                    ['text' => __('Item 3', 'elementrify')],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/icon-list/icon-list.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-icon-list');
        #>
        <ul {{{ view.getRenderAttributeString('wrapper') }}}>
            <# _.each(settings.items, function(item) {
                var iconHtml = item.icon && item.icon.value ? elementor.helpers.renderIcon(view, item.icon, {}, 'i', 'object').value : '';
            #>
            <li class="elementrify-icon-list__item">
                <# if (iconHtml) { #><span class="elementrify-icon-list__icon">{{{ iconHtml }}}</span><# } #>
                <span class="elementrify-icon-list__text">{{{ item.text }}}</span>
            </li>
            <# }); #>
        </ul>
        <?php
    }
}
