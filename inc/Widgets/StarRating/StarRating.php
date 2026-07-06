<?php
namespace Addonext\Widgets\StarRating;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

defined('ABSPATH') or exit;

class StarRating extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-star addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-star-rating';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Star Rating', 'addonext');
    }

    public function get_description(): string
    {
        return __('Display rating with star icons', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#eab308';
    }

    public function get_style_depends(): array
    {
        return ['addonext-star-rating'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'rating',
            [
                'label'   => __('Rating', 'addonext'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 0.5,
                'default' => 3.5,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'max_stars',
            [
                'label'   => __('Number of stars', 'addonext'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 10,
                'default' => 5,
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'   => __('Icon', 'addonext'),
                'type'    => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'align',
            [
                'label'   => __('Alignment', 'addonext'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [
                        'title' => __('Left', 'addonext'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'addonext'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'addonext'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors_dictionary' => [
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-star-rating' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => __('Show title', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'addonext'),
                'label_off'    => __('No', 'addonext'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'     => __('Title', 'addonext'),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'dynamic'   => ['active' => true],
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_stars',
            [
                'label' => __('Stars', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label'     => __('Star color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#eab308',
                'selectors' => [
                    '{{WRAPPER}} .addonext-star-rating__star-filled' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_unfilled_color',
            [
                'label'     => __('Unfilled color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .addonext-star-rating__star-empty' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_size',
            [
                'label'      => __('Size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 48, 'step' => 2],
                ],
                'default'    => ['size' => 18],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-star-rating__star-empty' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .addonext-star-rating__star-filled' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gap',
            [
                'label'      => __('Gap between stars', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 16, 'step' => 2],
                ],
                'default'    => ['size' => 2],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-star-rating__stars' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label'     => __('Title', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .addonext-star-rating__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .addonext-star-rating__title',
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label'      => __('Spacing', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 24, 'step' => 2],
                ],
                'default'    => ['size' => 6],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-star-rating__title' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        if (wp_style_is('addonext-star-rating', 'registered')) {
            wp_enqueue_style('addonext-star-rating');
        }
        $path = ADDONEXT_DIR . 'templates/star-rating/star-rating.php';
        if (is_readable($path)) {
            include $path;
        } else {
            echo '<div class="addonext-star-rating"><div class="addonext-star-rating__stars">';
            echo '<span class="addonext-star-rating__star"><span class="addonext-star-rating__star-empty">★</span></span>';
            echo '</div></div>';
        }
    }

    protected function content_template(): void
    {
        $star_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
        ?>
        <#
        var rating = parseFloat(settings.rating) || 0;
        var maxStars = Math.min(10, Math.max(1, parseInt(settings.max_stars, 10) || 5));
        var showTitle = settings.show_title === 'yes' && settings.title;
        var starSvg = <?php echo wp_json_encode($star_svg); ?>;
        #>
        <div class="addonext-star-rating">
            <div class="addonext-star-rating__stars">
                <# _.times(maxStars, function(i) {
                    var remainder = rating - i;
                    var fill = Math.min(1, Math.max(0, remainder));
                    var fillPercent = Math.round(fill * 100);
                #>
                    <span class="addonext-star-rating__star">
                        <span class="addonext-star-rating__star-empty">{{{ starSvg }}}</span>
                        <span class="addonext-star-rating__star-fill" style="width: {{ fillPercent }}%">
                            <span class="addonext-star-rating__star-filled">{{{ starSvg }}}</span>
                        </span>
                    </span>
                <# }); #>
            </div>
            <# if (showTitle) { #>
                <span class="addonext-star-rating__title">{{{ settings.title }}}</span>
            <# } #>
        </div>
        <?php
    }
}
