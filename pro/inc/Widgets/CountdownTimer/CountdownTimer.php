<?php
namespace Veloria\Widgets\CountdownTimer;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined('ABSPATH') or exit;

class CountdownTimer extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('veloria-countdown-timer');
        wp_enqueue_script('veloria-countdown-timer');
    }

    public function get_icon(): string
    {
        return 'eicon-countdown veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-countdown-timer';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Countdown Timer', 'veloria');
    }

    public function get_description(): string
    {
        return __('Event countdown displays', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#dc2626';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['veloria-countdown-timer'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-countdown-timer'];
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'variant',
            [
                'label'   => __('Variant', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'blocks',
                'options' => [
                    'blocks'  => __('Colored blocks', 'veloria'),
                    'cards'   => __('Unified cards', 'veloria'),
                    'minimal' => __('Minimal (no background)', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'due_date',
            [
                'label'   => __('Due date', 'veloria'),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month')),
            ]
        );

        $this->add_control(
            'labels',
            [
                'label'   => __('Show labels', 'veloria'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'label_days',
            [
                'label'   => __('Days label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Days', 'veloria'),
            ]
        );

        $this->add_control(
            'label_hours',
            [
                'label'   => __('Hours label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Hours', 'veloria'),
            ]
        );

        $this->add_control(
            'label_minutes',
            [
                'label'   => __('Minutes label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Minutes', 'veloria'),
            ]
        );

        $this->add_control(
            'label_seconds',
            [
                'label'   => __('Seconds label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Seconds', 'veloria'),
            ]
        );

        $this->add_control(
            'enable_shutter_flip',
            [
                'label'        => __('Enable shutter flip animation', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => ['variant' => ['blocks', 'cards']],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_blocks',
            [
                'label'     => __('Segment colors (blocks)', 'veloria'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'blocks'],
            ]
        );

        $this->add_control(
            'color_days',
            [
                'label'     => __('Days', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E74C3C',
                'selectors' => [
                    '{{WRAPPER}} .veloria-countdown__segment[data-unit="days"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_hours',
            [
                'label'     => __('Hours', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F39C12',
                'selectors' => [
                    '{{WRAPPER}} .veloria-countdown__segment[data-unit="hours"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_minutes',
            [
                'label'     => __('Minutes', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F1C40F',
                'selectors' => [
                    '{{WRAPPER}} .veloria-countdown__segment[data-unit="minutes"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_seconds',
            [
                'label'     => __('Seconds', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2ECC71',
                'selectors' => [
                    '{{WRAPPER}} .veloria-countdown__segment[data-unit="seconds"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label'     => __('Cards variant', 'veloria'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'cards'],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'wrapper_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .veloria-countdown--cards',
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => __('Card background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#BF90FF',
                'selectors' => [
                    '{{WRAPPER}} .veloria-countdown--cards .veloria-countdown__segment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_common',
            [
                'label' => __('Numbers & labels', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'number_typography',
                'selector' => '{{WRAPPER}} .veloria-countdown__value, {{WRAPPER}} .veloria-countdown__shutter-value',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 32]],
                ],
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label'     => __('Number color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-countdown__value, {{WRAPPER}} .veloria-countdown__shutter-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .veloria-countdown__label',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 12]],
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => __('Label color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-countdown__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'segment_border_radius',
            [
                'label'      => __('Segment border radius', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['unit' => 'px', 'size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-countdown__segment' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'segment_gap',
            [
                'label'      => __('Gap between segments', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-countdown' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'segment_padding',
            [
                'label'      => __('Segment padding', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '16', 'right' => '20', 'bottom' => '16', 'left' => '20',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-countdown__segment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = VELORIA_DIR . 'pro/templates/countdown-timer/countdown-timer.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    protected function content_template(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $days    = esc_js(__('Days', 'veloria'));
        $hours   = esc_js(__('Hours', 'veloria'));
        $minutes = esc_js(__('Minutes', 'veloria'));
        $seconds = esc_js(__('Seconds', 'veloria'));
        ?>
        <#
        var variant = settings.variant || 'blocks';
        var due = settings.due_date || '';
        var showLabels = settings.labels !== 'no';
        var labelDays = settings.label_days || '<?php echo $days; ?>';
        var labelHours = settings.label_hours || '<?php echo $hours; ?>';
        var labelMinutes = settings.label_minutes || '<?php echo $minutes; ?>';
        var labelSeconds = settings.label_seconds || '<?php echo $seconds; ?>';
        var shutterFlip = (variant === 'blocks' || variant === 'cards') && settings.enable_shutter_flip === 'yes';
        #>
        <div class="veloria-countdown veloria-countdown--{{ variant }}" data-due="{{ due }}"<# if (shutterFlip) { #> data-shutter-flip="1"<# } #>>
            <# if (shutterFlip) { #>
            <span class="veloria-countdown__segment" data-unit="days"><span class="veloria-countdown__value-box"><span class="veloria-countdown__value">0</span><span class="veloria-countdown__shutter" aria-hidden="true"><span class="veloria-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelDays }}}</span><# } #></span>
            <span class="veloria-countdown__segment" data-unit="hours"><span class="veloria-countdown__value-box"><span class="veloria-countdown__value">0</span><span class="veloria-countdown__shutter" aria-hidden="true"><span class="veloria-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelHours }}}</span><# } #></span>
            <span class="veloria-countdown__segment" data-unit="minutes"><span class="veloria-countdown__value-box"><span class="veloria-countdown__value">0</span><span class="veloria-countdown__shutter" aria-hidden="true"><span class="veloria-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelMinutes }}}</span><# } #></span>
            <span class="veloria-countdown__segment" data-unit="seconds"><span class="veloria-countdown__value-box"><span class="veloria-countdown__value">0</span><span class="veloria-countdown__shutter" aria-hidden="true"><span class="veloria-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelSeconds }}}</span><# } #></span>
            <# } else { #>
            <span class="veloria-countdown__segment" data-unit="days"><span class="veloria-countdown__value">0</span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelDays }}}</span><# } #></span>
            <span class="veloria-countdown__segment" data-unit="hours"><span class="veloria-countdown__value">0</span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelHours }}}</span><# } #></span>
            <span class="veloria-countdown__segment" data-unit="minutes"><span class="veloria-countdown__value">0</span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelMinutes }}}</span><# } #></span>
            <span class="veloria-countdown__segment" data-unit="seconds"><span class="veloria-countdown__value">0</span><# if (showLabels) { #><span class="veloria-countdown__label">{{{ labelSeconds }}}</span><# } #></span>
            <# } #>
        </div>
        <?php
                }
        }
}
}
