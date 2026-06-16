<?php
namespace Veloria\Widgets\BeforeAfterSlider;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
defined('ABSPATH') or exit;

/**
 * Before / after image comparison with draggable separator.
 */
class BeforeAfterSlider extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('veloria-before-after-slider');
        wp_enqueue_script('veloria-before-after-slider');
    }

    public function get_icon(): string
    {
        return 'eicon-image-before-after veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-before-after-slider';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Before After Slider', 'veloria');
    }

    public function get_description(): string
    {
        return __('Compare two images with a draggable divider', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['veloria-before-after-slider'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-before-after-slider'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-before-after-slider',
            VELORIA_URL . 'assets/css/before-after-slider.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-before-after-slider',
            VELORIA_URL . 'assets/js/before-after-slider.js',
            [],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('image_before', [
            'label' => __('Before image', 'veloria'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('image_after', [
            'label' => __('After image', 'veloria'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_group_control(Group_Control_Image_Size::get_type(), [
            'name'    => 'image_size',
            'default' => 'large',
        ]);

        $this->add_control('initial_position', [
            'label'      => __('Initial position', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range'      => ['%' => ['min' => 0, 'max' => 100]],
            'default'    => ['unit' => '%', 'size' => 50],
        ]);

        $this->add_control('slide_direction', [
            'label'   => __('Slide direction', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal', 'veloria'),
                'vertical'   => __('Vertical', 'veloria'),
            ],
        ]);

        $this->add_control('object_fit', [
            'label'   => __('Image fit', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover'   => __('Cover', 'veloria'),
                'contain' => __('Contain', 'veloria'),
                'fill'    => __('Fill', 'veloria'),
            ],
            'selectors' => [
                '{{WRAPPER}} .veloria-before-after__img' => 'object-fit: {{VALUE}};',
            ],
        ]);

        $this->add_control('show_labels', [
            'label'        => __('Show labels', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Show', 'veloria'),
            'label_off'    => __('Hide', 'veloria'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('label_before', [
            'label'     => __('Before label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Before', 'veloria'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_control('label_after', [
            'label'     => __('After label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('After', 'veloria'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_separator', [
            'label' => __('Separator & handle', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('line_width', [
            'label'      => __('Line width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 1, 'max' => 8]],
            'default'    => ['unit' => 'px', 'size' => 2],
            'selectors'  => [
                '{{WRAPPER}} .veloria-before-after' => '--veloria-ba-line-w: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('line_color', [
            'label'     => __('Line color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-before-after' => '--veloria-ba-line-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('handle_size', [
            'label'      => __('Handle size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 28, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 48],
            'selectors'  => [
                '{{WRAPPER}} .veloria-before-after' => '--veloria-ba-handle: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('handle_bg', [
            'label'     => __('Handle background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-before-after' => '--veloria-ba-handle-bg: {{VALUE}};',
            ],
        ]);

        $this->add_control('handle_icon_color', [
            'label'     => __('Icon color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .veloria-before-after__handle' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('handle_icon_size', [
            'label'      => __('Icon size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 10, 'max' => 40]],
            'default'    => ['unit' => 'px', 'size' => 18],
            'selectors'  => [
                '{{WRAPPER}} .veloria-before-after__handle-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('handle_icon', [
            'label' => __('Handle icon', 'veloria'),
            'type'  => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fas fa-arrows-alt-h',
                'library' => 'fa-solid',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_frame', [
            'label' => __('Container', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('frame_min_height', [
            'label'      => __('Min height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range'      => [
                'px' => ['min' => 200, 'max' => 1200, 'step' => 1],
                'vh' => ['min' => 20, 'max' => 100, 'step' => 1],
            ],
            'default'    => [
                'unit' => 'px',
                'size' => 400,
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-before-after__frame' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('frame_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-before-after__frame' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('border_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top' => 12,
                'right' => 12,
                'bottom' => 12,
                'left' => 12,
                'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-before-after__frame' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'frame_border',
            'selector' => '{{WRAPPER}} .veloria-before-after__frame',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'frame_shadow',
            'selector' => '{{WRAPPER}} .veloria-before-after__frame',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_labels', [
            'label'     => __('Labels', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'selector' => '{{WRAPPER}} .veloria-before-after__label',
        ]);

        $this->add_control('label_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-before-after__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = VELORIA_DIR . 'pro/templates/before-after-slider/before-after-slider.php';
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

        ?>
        <#
        var split = 50;
        if ( settings.initial_position && settings.initial_position.size !== undefined ) {
            split = settings.initial_position.size;
        }
        var vertical = settings.slide_direction === 'vertical';
        var rootClass = 'veloria-before-after' + ( vertical ? ' veloria-before-after--vertical' : '' );
        var clipAfter = vertical ? 'inset(' + split + '% 0 0 0)' : 'inset(0 0 0 ' + split + '%)';
        var dividerStyle = vertical ? 'top:' + split + '%;' : 'left:' + split + '%;';
        var handleOrient = vertical ? 'vertical' : 'horizontal';
        var editorIcon = vertical ? 'fas fa-arrows-alt-v' : 'fas fa-arrows-alt-h';
        #>
        <div class="{{ rootClass }}" style="--ba-split: {{ split }}%; --ba-split-num: {{ split }};" data-initial-split="{{ split }}" data-ba-orientation="{{ handleOrient }}">
            <div class="veloria-before-after__frame">
                <div class="veloria-before-after__before">
                    <# if ( settings.image_before && settings.image_before.url ) { #>
                    <img class="veloria-before-after__img" src="{{ settings.image_before.url }}" alt="" draggable="false">
                    <# } #>
                </div>
                <div class="veloria-before-after__after" style="clip-path: {{ clipAfter }};">
                    <# if ( settings.image_after && settings.image_after.url ) { #>
                    <img class="veloria-before-after__img" src="{{ settings.image_after.url }}" alt="" draggable="false">
                    <# } #>
                </div>
                <# if ( settings.show_labels === 'yes' ) { #>
                <span class="veloria-before-after__label veloria-before-after__label--before">{{{ settings.label_before }}}</span>
                <span class="veloria-before-after__label veloria-before-after__label--after">{{{ settings.label_after }}}</span>
                <# } #>
                <div class="veloria-before-after__divider" style="{{ dividerStyle }}">
                    <span class="veloria-before-after__line" aria-hidden="true"></span>
                    <button type="button" class="veloria-before-after__handle" aria-label="<?php echo esc_attr__('Drag to compare images', 'veloria'); ?>" role="slider" aria-orientation="{{ handleOrient }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ split }}">
                        <span class="veloria-before-after__handle-icon">
                            <i class="{{ editorIcon }}" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <?php
                }
        }
}
}
