<?php
namespace Elementrify\Widgets\Spacer;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Spacer extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-spacer elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-spacer';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Spacer', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Add vertical spacing', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#9ca3af';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-spacer'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-spacer',
            ELEMENTRIFY_URL . 'assets/css/spacer.css',
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

        $this->add_responsive_control(
            'space',
            [
                'label'      => __('Space', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vh'],
                'range'      => [
                    'px'  => ['min' => 0, 'max' => 400],
                    'em'  => ['min' => 0, 'max' => 25],
                    'rem' => ['min' => 0, 'max' => 25],
                    'vh'  => ['min' => 0, 'max' => 50],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-spacer' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/spacer/spacer.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-spacer');
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}></div>
        <?php
    }
}
