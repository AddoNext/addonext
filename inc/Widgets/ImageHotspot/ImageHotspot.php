<?php
namespace Addonext\Widgets\ImageHotspot;

use Addonext\Base\WidgetBase;
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
        wp_enqueue_style('addonext-image-hotspot');
        wp_enqueue_script('addonext-image-hotspot');
    }

    public function get_icon(): string
    {
        return 'eicon-image-hotspot addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-image-hotspot';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Image Hotspot', 'addonext');
    }

    public function get_description(): string
    {
        return __('Interactive image markers', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#ea580c';
    }

    public function get_style_depends(): array
    {
        return ['addonext-image-hotspot'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'addonext-image-hotspot'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_image',
            [
                'label' => __('Image', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label'   => __('Image', 'addonext'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => __('Width', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 1, 'max' => 2000],
                    '%'  => ['min' => 1, 'max' => 100],
                ],
                'default'    => ['unit' => '%', 'size' => 100],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-image-hotspot' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .addonext-image-hotspot__img' => 'width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => __('Height', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 1, 'max' => 2000],
                    '%'  => ['min' => 1, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-image-hotspot' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .addonext-image-hotspot__img' => 'height: 100%; object-fit: cover;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_hotspots',
            [
                'label' => __('Hotspots', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'x',
            [
                'label'      => __('Horizontal position (%)', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'      => ['%' => ['min' => 0, 'max' => 100]],
                'default'    => ['size' => 50],
            ]
        );

        $repeater->add_control(
            'y',
            [
                'label'      => __('Vertical position (%)', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'      => ['%' => ['min' => 0, 'max' => 100]],
                'default'    => ['size' => 50],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Hotspot', 'addonext'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'   => __('Description', 'addonext'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'price',
            [
                'label'   => __('Price / Label', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'hotspot_color',
            [
                'label'   => __('Hotspot color', 'addonext'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#605BE5',
            ]
        );

        $this->add_control(
            'hotspots',
            [
                'label'       => __('Hotspots', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['title' => __('Collaboration Hub', 'addonext'), 'description' => __('Central station for team meetings and creative brainstorming.', 'addonext'), 'price' => '$1,299', 'x' => ['size' => 70], 'y' => ['size' => 35], 'hotspot_color' => '#E91E8C'],
                    ['title' => __('Meeting Point', 'addonext'), 'x' => ['size' => 30], 'y' => ['size' => 40], 'hotspot_color' => '#2196F3'],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'tooltip_trigger',
            [
                'label'   => __('Show tooltip', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __('On hover', 'addonext'),
                    'click' => __('On click', 'addonext'),
                ],
            ]
        );

        $this->add_control(
            'tooltip_position',
            [
                'label'   => __('Tooltip position', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top'    => __('Top', 'addonext'),
                    'bottom' => __('Bottom', 'addonext'),
                    'left'   => __('Left', 'addonext'),
                    'right'  => __('Right', 'addonext'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_hotspot',
            [
                'label' => __('Hotspot', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hotspot_size',
            [
                'label'      => __('Size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 8, 'max' => 48]],
                'default'    => ['size' => 20],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-image-hotspot__point' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-top: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_control(
            'active_ring_color',
            [
                'label'     => __('Active ring color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4ADE80',
                'selectors' => [
                    '{{WRAPPER}} .addonext-image-hotspot__point.is-active::after' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 2px {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspot_animation',
            [
                'label'        => __('Pulse animation', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'addonext'),
                'label_off'    => __('No', 'addonext'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_tooltip',
            [
                'label' => __('Tooltip', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tooltip_title_typography',
                'selector' => '{{WRAPPER}} .addonext-image-hotspot__tooltip-title',
            ]
        );

        $this->add_control(
            'tooltip_title_color',
            [
                'label'     => __('Title color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .addonext-image-hotspot__tooltip-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tooltip_desc_typography',
                'selector' => '{{WRAPPER}} .addonext-image-hotspot__tooltip-desc',
            ]
        );

        $this->add_control(
            'tooltip_desc_color',
            [
                'label'     => __('Description color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .addonext-image-hotspot__tooltip-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_price_color',
            [
                'label'     => __('Price color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E91E8C',
                'selectors' => [
                    '{{WRAPPER}} .addonext-image-hotspot__tooltip-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_background',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-image-hotspot__tooltip' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-image-hotspot__tooltip' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '14', 'right' => '16', 'bottom' => '14', 'left' => '16',
                    'unit' => 'px', 'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-image-hotspot__tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .addonext-image-hotspot__tooltip',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/image-hotspot/image-hotspot.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        $placeholder = esc_url(\Elementor\Utils::get_placeholder_image_src());
        ?>
        <#
        var imageUrl = (settings.image && settings.image.url) ? settings.image.url : '<?php echo esc_url($placeholder); ?>';
        var hotspots = settings.hotspots || [];
        var trigger = settings.tooltip_trigger || 'hover';
        var tooltipPos = settings.tooltip_position || 'bottom';
        var pulse = settings.hotspot_animation === 'yes';
        var pulseClass = pulse ? ' addonext-image-hotspot--pulse' : '';
        var clickableClass = (trigger === 'click') ? ' elementor-clickable' : '';
        #>
        <div class="addonext-image-hotspot addonext-image-hotspot--trigger-{{ trigger }} addonext-image-hotspot--tooltip-{{ tooltipPos }}{{ pulseClass }}{{ clickableClass }}" data-trigger="{{ trigger }}">
            <img src="{{ imageUrl }}" alt="" class="addonext-image-hotspot__img">
            <# _.each(hotspots, function(h, i) {
                var x = (h.x && h.x.size !== undefined) ? h.x.size : 50;
                var y = (h.y && h.y.size !== undefined) ? h.y.size : 50;
                var color = h.hotspot_color || '#605BE5';
            #>
            <span class="addonext-image-hotspot__point elementor-repeater-item-{{ h._id }}" style="left:{{ x }}%; top:{{ y }}%; background-color: {{ color }}; --hotspot-color: {{ color }};" data-index="{{ i }}">
                <# if (pulse) { #>
                <span class="addonext-image-hotspot__pulse" aria-hidden="true"></span>
                <span class="addonext-image-hotspot__pulse" aria-hidden="true"></span>
                <span class="addonext-image-hotspot__pulse" aria-hidden="true"></span>
                <# } #>
                <span class="addonext-image-hotspot__tooltip">
                    <# if (h.title) { #><span class="addonext-image-hotspot__tooltip-title">{{{ h.title }}}</span><# } #>
                    <# if (h.description) { #><span class="addonext-image-hotspot__tooltip-desc">{{{ h.description }}}</span><# } #>
                    <# if (h.price) { #><span class="addonext-image-hotspot__tooltip-price">{{{ h.price }}}</span><# } #>
                </span>
            </span>
            <# }); #>
        </div>
        <?php
    }
}
