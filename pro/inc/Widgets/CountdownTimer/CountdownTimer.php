<?php
namespace Addonext\Widgets\CountdownTimer;

use Addonext\Base\WidgetBase;
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
        wp_enqueue_style('addonext-countdown-timer');
        wp_enqueue_script('addonext-countdown-timer');
    }

    public function get_icon(): string
    {
        return 'eicon-countdown addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-countdown-timer';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Countdown Timer', 'addonext');
    }

    public function get_description(): string
    {
        return __('Event countdown displays', 'addonext');
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
        return ['addonext-countdown-timer'];
    }

    public function get_script_depends(): array
    {
        return ['addonext-countdown-timer'];
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'variant',
            [
                'label'   => __('Variant', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'blocks',
                'options' => [
                    'blocks'  => __('Colored blocks', 'addonext'),
                    'cards'   => __('Unified cards', 'addonext'),
                    'minimal' => __('Minimal (no background)', 'addonext'),
                ],
            ]
        );

        $this->add_control(
            'due_date',
            [
                'label'   => __('Due date', 'addonext'),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month')),
            ]
        );

        $this->add_control(
            'labels',
            [
                'label'   => __('Show labels', 'addonext'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'label_days',
            [
                'label'   => __('Days label', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Days', 'addonext'),
            ]
        );

        $this->add_control(
            'label_hours',
            [
                'label'   => __('Hours label', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Hours', 'addonext'),
            ]
        );

        $this->add_control(
            'label_minutes',
            [
                'label'   => __('Minutes label', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Minutes', 'addonext'),
            ]
        );

        $this->add_control(
            'label_seconds',
            [
                'label'   => __('Seconds label', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Seconds', 'addonext'),
            ]
        );

        $this->add_control(
            'enable_shutter_flip',
            [
                'label'        => __('Enable shutter flip animation', 'addonext'),
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
                'label'     => __('Segment colors (blocks)', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'blocks'],
            ]
        );

        $this->add_control(
            'color_days',
            [
                'label'     => __('Days', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E74C3C',
                'selectors' => [
                    '{{WRAPPER}} .addonext-countdown__segment[data-unit="days"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_hours',
            [
                'label'     => __('Hours', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F39C12',
                'selectors' => [
                    '{{WRAPPER}} .addonext-countdown__segment[data-unit="hours"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_minutes',
            [
                'label'     => __('Minutes', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F1C40F',
                'selectors' => [
                    '{{WRAPPER}} .addonext-countdown__segment[data-unit="minutes"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_seconds',
            [
                'label'     => __('Seconds', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2ECC71',
                'selectors' => [
                    '{{WRAPPER}} .addonext-countdown__segment[data-unit="seconds"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label'     => __('Cards variant', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'cards'],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'wrapper_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .addonext-countdown--cards',
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => __('Card background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#BF90FF',
                'selectors' => [
                    '{{WRAPPER}} .addonext-countdown--cards .addonext-countdown__segment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_common',
            [
                'label' => __('Numbers & labels', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'number_typography',
                'selector' => '{{WRAPPER}} .addonext-countdown__value, {{WRAPPER}} .addonext-countdown__shutter-value',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 32]],
                ],
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label'     => __('Number color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-countdown__value, {{WRAPPER}} .addonext-countdown__shutter-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .addonext-countdown__label',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 12]],
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => __('Label color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-countdown__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'segment_border_radius',
            [
                'label'      => __('Segment border radius', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['unit' => 'px', 'size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-countdown__segment' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'segment_gap',
            [
                'label'      => __('Gap between segments', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-countdown' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'segment_padding',
            [
                'label'      => __('Segment padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '16', 'right' => '20', 'bottom' => '16', 'left' => '20',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-countdown__segment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $path = ADDONEXT_DIR . 'pro/templates/countdown-timer/countdown-timer.php';
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

        $days    = esc_js(__('Days', 'addonext'));
        $hours   = esc_js(__('Hours', 'addonext'));
        $minutes = esc_js(__('Minutes', 'addonext'));
        $seconds = esc_js(__('Seconds', 'addonext'));
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
        <div class="addonext-countdown addonext-countdown--{{ variant }}" data-due="{{ due }}"<# if (shutterFlip) { #> data-shutter-flip="1"<# } #>>
            <# if (shutterFlip) { #>
            <span class="addonext-countdown__segment" data-unit="days"><span class="addonext-countdown__value-box"><span class="addonext-countdown__value">0</span><span class="addonext-countdown__shutter" aria-hidden="true"><span class="addonext-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelDays }}}</span><# } #></span>
            <span class="addonext-countdown__segment" data-unit="hours"><span class="addonext-countdown__value-box"><span class="addonext-countdown__value">0</span><span class="addonext-countdown__shutter" aria-hidden="true"><span class="addonext-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelHours }}}</span><# } #></span>
            <span class="addonext-countdown__segment" data-unit="minutes"><span class="addonext-countdown__value-box"><span class="addonext-countdown__value">0</span><span class="addonext-countdown__shutter" aria-hidden="true"><span class="addonext-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelMinutes }}}</span><# } #></span>
            <span class="addonext-countdown__segment" data-unit="seconds"><span class="addonext-countdown__value-box"><span class="addonext-countdown__value">0</span><span class="addonext-countdown__shutter" aria-hidden="true"><span class="addonext-countdown__shutter-value">0</span></span></span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelSeconds }}}</span><# } #></span>
            <# } else { #>
            <span class="addonext-countdown__segment" data-unit="days"><span class="addonext-countdown__value">0</span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelDays }}}</span><# } #></span>
            <span class="addonext-countdown__segment" data-unit="hours"><span class="addonext-countdown__value">0</span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelHours }}}</span><# } #></span>
            <span class="addonext-countdown__segment" data-unit="minutes"><span class="addonext-countdown__value">0</span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelMinutes }}}</span><# } #></span>
            <span class="addonext-countdown__segment" data-unit="seconds"><span class="addonext-countdown__value">0</span><# if (showLabels) { #><span class="addonext-countdown__label">{{{ labelSeconds }}}</span><# } #></span>
            <# } #>
        </div>
        <?php
                }
        }
}
}
