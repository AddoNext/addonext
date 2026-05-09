<?php
namespace Elementrify\Widgets\Tooltip;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Tooltip extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-info-circle elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-tooltip';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Tooltip', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Interactive hover tooltips', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#3667F8';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-tooltip'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-tooltip'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-tooltip',
            ELEMENTRIFY_URL . 'assets/css/tooltip.css',
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

        $this->add_control(
            'trigger_text',
            [
                'label'   => __('Button text', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Information', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'   => __('Icon', 'elementrify'),
                'type'    => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'value'   => 'fas fa-info',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'   => __('Link', 'elementrify'),
                'type'    => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'default' => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_control(
            'tooltip_text',
            [
                'label'   => __('Tooltip text', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Get detailed information here', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .elementrify-tooltip__trigger',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control(
            'button_icon_size',
            [
                'label'      => __('Icon size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 8, 'max' => 32]],
                'default'    => ['unit' => 'px', 'size' => 14],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-tooltip__trigger .elementrify-tooltip__icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-tooltip__trigger .elementrify-tooltip__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3667F8',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-tooltip__trigger' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => __('Text & icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-tooltip__trigger' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-tooltip__trigger svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['unit' => 'px', 'size' => 6],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-tooltip__trigger' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => [
                    'top' => '10', 'right' => '18', 'bottom' => '10', 'left' => '18',
                    'unit' => 'px', 'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-tooltip__trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_spacing',
            [
                'label'      => __('Icon spacing', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['unit' => 'px', 'size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-tooltip__trigger .elementrify-tooltip__icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_tooltip',
            [
                'label' => __('Tooltip', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tooltip_typography',
                'selector' => '{{WRAPPER}} .elementrify-tooltip__content',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control(
            'tooltip_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#212529',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-tooltip__content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-tooltip__content::after' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-tooltip--below .elementrify-tooltip__content::after' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-tooltip__content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['unit' => 'px', 'size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-tooltip__content' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => [
                    'top' => '10', 'right' => '14', 'bottom' => '10', 'left' => '14',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-tooltip__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/tooltip/tooltip.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var triggerText = settings.trigger_text || '<?php echo esc_js(__('Information', 'elementrify')); ?>';
        var tooltipText = settings.tooltip_text || '<?php echo esc_js(__('Get detailed information here', 'elementrify')); ?>';
        var hasLink = settings.link && settings.link.url;
        var linkUrl = hasLink ? settings.link.url : '#';
        var targetAttr = (settings.link && settings.link.is_external) ? ' target="_blank"' : '';
        var relAttr = (settings.link && settings.link.nofollow) ? ' rel="nofollow"' : '';
        var iconHtml = settings.selected_icon && settings.selected_icon.value
            ? elementor.helpers.renderIcon(view, settings.selected_icon, { 'aria-hidden': 'true' }, 'i', 'object').value
            : '';
        #>
        <div class="elementrify-tooltip">
            <# if (hasLink && linkUrl !== '#') { #>
                <a href="{{ linkUrl }}" class="elementrify-tooltip__trigger"{{{ targetAttr }}}{{{ relAttr }}}>
            <# } else { #>
                <span class="elementrify-tooltip__trigger" tabindex="0" role="button">
            <# } #>
                <# if (iconHtml) { #><span class="elementrify-tooltip__icon">{{{ iconHtml }}}</span><# } #>
                <span class="elementrify-tooltip__label">{{{ triggerText }}}</span>
            <# if (hasLink && linkUrl !== '#') { #></a><# } else { #></span><# } #>
            <span class="elementrify-tooltip__content">{{{ tooltipText }}}</span>
        </div>
        <?php
    }
}
