<?php
namespace Elementrify\Widgets\BeforeAfterSlider;

use Elementrify\Base\WidgetBase;
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
        wp_enqueue_style('elementrify-before-after-slider');
        wp_enqueue_script('elementrify-before-after-slider');
    }

    public function get_icon(): string
    {
        return 'eicon-image-before-after elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-before-after-slider';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Before After Slider', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Compare two images with a draggable divider', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-before-after-slider'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-before-after-slider'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-before-after-slider',
            ELEMENTRIFY_URL . 'assets/css/before-after-slider.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-before-after-slider',
            ELEMENTRIFY_URL . 'assets/js/before-after-slider.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('image_before', [
            'label' => __('Before image', 'elementrify'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('image_after', [
            'label' => __('After image', 'elementrify'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_group_control(Group_Control_Image_Size::get_type(), [
            'name'    => 'image_size',
            'default' => 'large',
        ]);

        $this->add_control('initial_position', [
            'label'      => __('Initial position', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range'      => ['%' => ['min' => 0, 'max' => 100]],
            'default'    => ['unit' => '%', 'size' => 50],
        ]);

        $this->add_control('slide_direction', [
            'label'   => __('Slide direction', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal', 'elementrify'),
                'vertical'   => __('Vertical', 'elementrify'),
            ],
        ]);

        $this->add_control('object_fit', [
            'label'   => __('Image fit', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover'   => __('Cover', 'elementrify'),
                'contain' => __('Contain', 'elementrify'),
                'fill'    => __('Fill', 'elementrify'),
            ],
            'selectors' => [
                '{{WRAPPER}} .elementrify-before-after__img' => 'object-fit: {{VALUE}};',
            ],
        ]);

        $this->add_control('show_labels', [
            'label'        => __('Show labels', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Show', 'elementrify'),
            'label_off'    => __('Hide', 'elementrify'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('label_before', [
            'label'     => __('Before label', 'elementrify'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Before', 'elementrify'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_control('label_after', [
            'label'     => __('After label', 'elementrify'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('After', 'elementrify'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_separator', [
            'label' => __('Separator & handle', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('line_width', [
            'label'      => __('Line width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 1, 'max' => 8]],
            'default'    => ['unit' => 'px', 'size' => 2],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-before-after' => '--elementrify-ba-line-w: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('line_color', [
            'label'     => __('Line color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-before-after' => '--elementrify-ba-line-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('handle_size', [
            'label'      => __('Handle size', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 28, 'max' => 80]],
            'default'    => ['unit' => 'px', 'size' => 48],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-before-after' => '--elementrify-ba-handle: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('handle_bg', [
            'label'     => __('Handle background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-before-after' => '--elementrify-ba-handle-bg: {{VALUE}};',
            ],
        ]);

        $this->add_control('handle_icon_color', [
            'label'     => __('Icon color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .elementrify-before-after__handle' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('handle_icon_size', [
            'label'      => __('Icon size', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 10, 'max' => 40]],
            'default'    => ['unit' => 'px', 'size' => 18],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-before-after__handle-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('handle_icon', [
            'label' => __('Handle icon', 'elementrify'),
            'type'  => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fas fa-arrows-alt-h',
                'library' => 'fa-solid',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_frame', [
            'label' => __('Container', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('frame_min_height', [
            'label'      => __('Min height', 'elementrify'),
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
                '{{WRAPPER}} .elementrify-before-after__frame' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('frame_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-before-after__frame' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('border_radius', [
            'label'      => __('Border radius', 'elementrify'),
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
                '{{WRAPPER}} .elementrify-before-after__frame' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'frame_border',
            'selector' => '{{WRAPPER}} .elementrify-before-after__frame',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'frame_shadow',
            'selector' => '{{WRAPPER}} .elementrify-before-after__frame',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_labels', [
            'label'     => __('Labels', 'elementrify'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'selector' => '{{WRAPPER}} .elementrify-before-after__label',
        ]);

        $this->add_control('label_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-before-after__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/before-after-slider/before-after-slider.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var split = 50;
        if ( settings.initial_position && settings.initial_position.size !== undefined ) {
            split = settings.initial_position.size;
        }
        var vertical = settings.slide_direction === 'vertical';
        var rootClass = 'elementrify-before-after' + ( vertical ? ' elementrify-before-after--vertical' : '' );
        var clipAfter = vertical ? 'inset(' + split + '% 0 0 0)' : 'inset(0 0 0 ' + split + '%)';
        var dividerStyle = vertical ? 'top:' + split + '%;' : 'left:' + split + '%;';
        var handleOrient = vertical ? 'vertical' : 'horizontal';
        var editorIcon = vertical ? 'fas fa-arrows-alt-v' : 'fas fa-arrows-alt-h';
        #>
        <div class="{{ rootClass }}" style="--ba-split: {{ split }}%; --ba-split-num: {{ split }};" data-initial-split="{{ split }}" data-ba-orientation="{{ handleOrient }}">
            <div class="elementrify-before-after__frame">
                <div class="elementrify-before-after__before">
                    <# if ( settings.image_before && settings.image_before.url ) { #>
                    <img class="elementrify-before-after__img" src="{{ settings.image_before.url }}" alt="" draggable="false">
                    <# } #>
                </div>
                <div class="elementrify-before-after__after" style="clip-path: {{ clipAfter }};">
                    <# if ( settings.image_after && settings.image_after.url ) { #>
                    <img class="elementrify-before-after__img" src="{{ settings.image_after.url }}" alt="" draggable="false">
                    <# } #>
                </div>
                <# if ( settings.show_labels === 'yes' ) { #>
                <span class="elementrify-before-after__label elementrify-before-after__label--before">{{{ settings.label_before }}}</span>
                <span class="elementrify-before-after__label elementrify-before-after__label--after">{{{ settings.label_after }}}</span>
                <# } #>
                <div class="elementrify-before-after__divider" style="{{ dividerStyle }}">
                    <span class="elementrify-before-after__line" aria-hidden="true"></span>
                    <button type="button" class="elementrify-before-after__handle" aria-label="<?php echo esc_attr__('Drag to compare images', 'elementrify'); ?>" role="slider" aria-orientation="{{ handleOrient }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ split }}">
                        <span class="elementrify-before-after__handle-icon">
                            <i class="{{ editorIcon }}" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
}
