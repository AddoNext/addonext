<?php
namespace Elementrify\Widgets\CountdownTimer;

use Elementrify\Base\WidgetBase;
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
        wp_enqueue_style('elementrify-countdown-timer');
        wp_enqueue_script('elementrify-countdown-timer');
    }

    public function get_icon(): string
    {
        return 'eicon-countdown elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-countdown-timer';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Countdown Timer', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Event countdown displays', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#dc2626';
    }

    
    public function get_required_plan(): string
    {
        return 'pro';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-countdown-timer'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-countdown-timer'];
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'variant',
            [
                'label'   => __('Variant', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'blocks',
                'options' => [
                    'blocks'  => __('Colored blocks', 'elementrify'),
                    'cards'   => __('Unified cards', 'elementrify'),
                    'minimal' => __('Minimal (no background)', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'due_date',
            [
                'label'   => __('Due date', 'elementrify'),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month')),
            ]
        );

        $this->add_control(
            'labels',
            [
                'label'   => __('Show labels', 'elementrify'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'label_days',
            [
                'label'   => __('Days label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Days', 'elementrify'),
            ]
        );

        $this->add_control(
            'label_hours',
            [
                'label'   => __('Hours label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Hours', 'elementrify'),
            ]
        );

        $this->add_control(
            'label_minutes',
            [
                'label'   => __('Minutes label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Minutes', 'elementrify'),
            ]
        );

        $this->add_control(
            'label_seconds',
            [
                'label'   => __('Seconds label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Seconds', 'elementrify'),
            ]
        );

        $this->add_control(
            'enable_shutter_flip',
            [
                'label'        => __('Enable shutter flip animation', 'elementrify'),
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
                'label'     => __('Segment colors (blocks)', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'blocks'],
            ]
        );

        $this->add_control(
            'color_days',
            [
                'label'     => __('Days', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E74C3C',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-countdown__segment[data-unit="days"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_hours',
            [
                'label'     => __('Hours', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F39C12',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-countdown__segment[data-unit="hours"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_minutes',
            [
                'label'     => __('Minutes', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F1C40F',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-countdown__segment[data-unit="minutes"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_seconds',
            [
                'label'     => __('Seconds', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2ECC71',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-countdown__segment[data-unit="seconds"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label'     => __('Cards variant', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'cards'],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'wrapper_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementrify-countdown--cards',
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => __('Card background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#BF90FF',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-countdown--cards .elementrify-countdown__segment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_common',
            [
                'label' => __('Numbers & labels', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'number_typography',
                'selector' => '{{WRAPPER}} .elementrify-countdown__value, {{WRAPPER}} .elementrify-countdown__shutter-value',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 32]],
                ],
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label'     => __('Number color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-countdown__value, {{WRAPPER}} .elementrify-countdown__shutter-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .elementrify-countdown__label',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 12]],
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => __('Label color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-countdown__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'segment_border_radius',
            [
                'label'      => __('Segment border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['unit' => 'px', 'size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-countdown__segment' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'segment_gap',
            [
                'label'      => __('Gap between segments', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-countdown' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'segment_padding',
            [
                'label'      => __('Segment padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '16', 'right' => '20', 'bottom' => '16', 'left' => '20',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-countdown__segment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $path = ELEMENTRIFY_DIR . 'templates/countdown-timer/countdown-timer.php';
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

        $days    = esc_js(__('Days', 'elementrify'));
        $hours   = esc_js(__('Hours', 'elementrify'));
        $minutes = esc_js(__('Minutes', 'elementrify'));
        $seconds = esc_js(__('Seconds', 'elementrify'));
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
        <div class="elementrify-countdown elementrify-countdown--{{ variant }}" data-due="{{ due }}"<# if (shutterFlip) { #> data-shutter-flip="1"<# } #>>
            <# if (shutterFlip) { #>
            <span class="elementrify-countdown__segment" data-unit="days"><span class="elementrify-countdown__value-box"><span class="elementrify-countdown__value">0</span><span class="elementrify-countdown__shutter" aria-hidden="true"><span class="elementrify-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelDays }}}</span><# } #></span>
            <span class="elementrify-countdown__segment" data-unit="hours"><span class="elementrify-countdown__value-box"><span class="elementrify-countdown__value">0</span><span class="elementrify-countdown__shutter" aria-hidden="true"><span class="elementrify-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelHours }}}</span><# } #></span>
            <span class="elementrify-countdown__segment" data-unit="minutes"><span class="elementrify-countdown__value-box"><span class="elementrify-countdown__value">0</span><span class="elementrify-countdown__shutter" aria-hidden="true"><span class="elementrify-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelMinutes }}}</span><# } #></span>
            <span class="elementrify-countdown__segment" data-unit="seconds"><span class="elementrify-countdown__value-box"><span class="elementrify-countdown__value">0</span><span class="elementrify-countdown__shutter" aria-hidden="true"><span class="elementrify-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelSeconds }}}</span><# } #></span>
            <# } else { #>
            <span class="elementrify-countdown__segment" data-unit="days"><span class="elementrify-countdown__value">0</span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelDays }}}</span><# } #></span>
            <span class="elementrify-countdown__segment" data-unit="hours"><span class="elementrify-countdown__value">0</span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelHours }}}</span><# } #></span>
            <span class="elementrify-countdown__segment" data-unit="minutes"><span class="elementrify-countdown__value">0</span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelMinutes }}}</span><# } #></span>
            <span class="elementrify-countdown__segment" data-unit="seconds"><span class="elementrify-countdown__value">0</span><# if (showLabels) { #><span class="elementrify-countdown__label">{{{ labelSeconds }}}</span><# } #></span>
            <# } #>
        </div>
        <?php
                }
        }
}
}
