<?php
namespace Elementrify\Widgets\ImageHotspot;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ImageHotspot extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('elementrify-image-hotspot');
        wp_enqueue_script('elementrify-image-hotspot');
    }

    public function get_icon(): string
    {
        return 'eicon-image-hotspot elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-image-hotspot';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Image Hotspot', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Interactive image markers', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#ea580c';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-image-hotspot'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'elementrify-image-hotspot'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_image',
            [
                'label' => __('Image', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label'   => __('Image', 'elementrify'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => __('Width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 1, 'max' => 2000],
                    '%'  => ['min' => 1, 'max' => 100],
                ],
                'default'    => ['unit' => '%', 'size' => 100],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-hotspot' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-image-hotspot__img' => 'width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => __('Height', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 1, 'max' => 2000],
                    '%'  => ['min' => 1, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-hotspot' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-image-hotspot__img' => 'height: 100%; object-fit: cover;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_hotspots',
            [
                'label' => __('Hotspots', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'x',
            [
                'label'      => __('Horizontal position (%)', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'      => ['%' => ['min' => 0, 'max' => 100]],
                'default'    => ['size' => 50],
            ]
        );

        $repeater->add_control(
            'y',
            [
                'label'      => __('Vertical position (%)', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'      => ['%' => ['min' => 0, 'max' => 100]],
                'default'    => ['size' => 50],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Hotspot', 'elementrify'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'   => __('Description', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'price',
            [
                'label'   => __('Price / Label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'hotspot_color',
            [
                'label'   => __('Hotspot color', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#605BE5',
            ]
        );

        $this->add_control(
            'hotspots',
            [
                'label'       => __('Hotspots', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['title' => __('Collaboration Hub', 'elementrify'), 'description' => __('Central station for team meetings and creative brainstorming.', 'elementrify'), 'price' => '$1,299', 'x' => ['size' => 70], 'y' => ['size' => 35], 'hotspot_color' => '#E91E8C'],
                    ['title' => __('Meeting Point', 'elementrify'), 'x' => ['size' => 30], 'y' => ['size' => 40], 'hotspot_color' => '#2196F3'],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'tooltip_trigger',
            [
                'label'   => __('Show tooltip', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __('On hover', 'elementrify'),
                    'click' => __('On click', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'tooltip_position',
            [
                'label'   => __('Tooltip position', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top'    => __('Top', 'elementrify'),
                    'bottom' => __('Bottom', 'elementrify'),
                    'left'   => __('Left', 'elementrify'),
                    'right'  => __('Right', 'elementrify'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_hotspot',
            [
                'label' => __('Hotspot', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hotspot_size',
            [
                'label'      => __('Size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 8, 'max' => 48]],
                'default'    => ['size' => 20],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-hotspot__point' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-top: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_control(
            'active_ring_color',
            [
                'label'     => __('Active ring color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4ADE80',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-hotspot__point.is-active::after' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 2px {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspot_animation',
            [
                'label'        => __('Pulse animation', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elementrify'),
                'label_off'    => __('No', 'elementrify'),
                'return_value' => 'yes',
                'default'      => 'yes',
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
                'name'     => 'tooltip_title_typography',
                'selector' => '{{WRAPPER}} .elementrify-image-hotspot__tooltip-title',
            ]
        );

        $this->add_control(
            'tooltip_title_color',
            [
                'label'     => __('Title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-hotspot__tooltip-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tooltip_desc_typography',
                'selector' => '{{WRAPPER}} .elementrify-image-hotspot__tooltip-desc',
            ]
        );

        $this->add_control(
            'tooltip_desc_color',
            [
                'label'     => __('Description color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-hotspot__tooltip-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_price_color',
            [
                'label'     => __('Price color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E91E8C',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-hotspot__tooltip-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-hotspot__tooltip' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-hotspot__tooltip' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '14', 'right' => '16', 'bottom' => '14', 'left' => '16',
                    'unit' => 'px', 'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-hotspot__tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .elementrify-image-hotspot__tooltip',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/image-hotspot/image-hotspot.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        $placeholder = esc_url(\Elementor\Utils::get_placeholder_image_src());
        ?>
        <#
        var imageUrl = (settings.image && settings.image.url) ? settings.image.url : '<?php echo $placeholder; ?>';
        var hotspots = settings.hotspots || [];
        var trigger = settings.tooltip_trigger || 'hover';
        var tooltipPos = settings.tooltip_position || 'bottom';
        var pulse = settings.hotspot_animation === 'yes';
        var pulseClass = pulse ? ' elementrify-image-hotspot--pulse' : '';
        var clickableClass = (trigger === 'click') ? ' elementor-clickable' : '';
        #>
        <div class="elementrify-image-hotspot elementrify-image-hotspot--trigger-{{ trigger }} elementrify-image-hotspot--tooltip-{{ tooltipPos }}{{ pulseClass }}{{ clickableClass }}" data-trigger="{{ trigger }}">
            <img src="{{ imageUrl }}" alt="" class="elementrify-image-hotspot__img">
            <# _.each(hotspots, function(h, i) {
                var x = (h.x && h.x.size !== undefined) ? h.x.size : 50;
                var y = (h.y && h.y.size !== undefined) ? h.y.size : 50;
                var color = h.hotspot_color || '#605BE5';
            #>
            <span class="elementrify-image-hotspot__point elementor-repeater-item-{{ h._id }}" style="left:{{ x }}%; top:{{ y }}%; background-color: {{ color }}; --hotspot-color: {{ color }};" data-index="{{ i }}">
                <# if (pulse) { #>
                <span class="elementrify-image-hotspot__pulse" aria-hidden="true"></span>
                <span class="elementrify-image-hotspot__pulse" aria-hidden="true"></span>
                <span class="elementrify-image-hotspot__pulse" aria-hidden="true"></span>
                <# } #>
                <span class="elementrify-image-hotspot__tooltip">
                    <# if (h.title) { #><span class="elementrify-image-hotspot__tooltip-title">{{{ h.title }}}</span><# } #>
                    <# if (h.description) { #><span class="elementrify-image-hotspot__tooltip-desc">{{{ h.description }}}</span><# } #>
                    <# if (h.price) { #><span class="elementrify-image-hotspot__tooltip-price">{{{ h.price }}}</span><# } #>
                </span>
            </span>
            <# }); #>
        </div>
        <?php
    }
}
