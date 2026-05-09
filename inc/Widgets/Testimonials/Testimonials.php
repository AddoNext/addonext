<?php
namespace Elementrify\Widgets\Testimonials;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') or exit;

class Testimonials extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('swiper');
        wp_enqueue_style('elementrify-testimonials');
        wp_enqueue_script('swiper');
        wp_enqueue_script('elementrify-testimonials');
    }

    public function get_icon(): string
    {
        return 'eicon-testimonial elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-testimonials';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Testimonials', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Customer reviews displays', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'elementrify-testimonials'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementrify-testimonials'];
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
            'view',
            [
                'label'   => __('View', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid'   => __('Grid', 'elementrify'),
                    'slider' => __('Slider', 'elementrify'),
                ],
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'content',
            [
                'label'   => __('Quote', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Great product!', 'elementrify'),
            ]
        );
        $repeater->add_control(
            'name',
            [
                'label'   => __('Name', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('John', 'elementrify'),
            ]
        );
        $repeater->add_control(
            'role',
            [
                'label'   => __('Role / Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label'   => __('Avatar', 'elementrify'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );
        $repeater->add_control(
            'rating',
            [
                'label'   => __('Rating', 'elementrify'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 5,
                'step'    => 0.5,
                'default' => 5,
            ]
        );

        $repeater->add_control(
            'card_accent_color',
            [
                'label'   => __('Card accent color', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#e0e7ff',
                'description' => __('Used for border and optional background tint.', 'elementrify'),
            ]
        );

        $repeater->add_control(
            'card_use_gradient',
            [
                'label'        => __('Subtle gradient from accent', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $placeholder_src = \Elementor\Utils::get_placeholder_image_src();
        $this->add_control(
            'items',
            [
                'label'       => __('Testimonials', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'content' => __('Absolutely fantastic! The widgets are beautifully designed and incredibly easy to use. Our website looks professional now.', 'elementrify'),
                        'name' => __('Emma Davis', 'elementrify'),
                        'role' => __('Marketing Director', 'elementrify'),
                        'image' => ['url' => $placeholder_src],
                        'rating' => 5,
                        'card_accent_color' => '#bfdbfe',
                        'card_use_gradient' => 'yes',
                    ],
                    [
                        'content' => __('Game changer for our business. The customization options are endless and the support team is incredibly responsive.', 'elementrify'),
                        'name' => __('Michael Chen', 'elementrify'),
                        'role' => __('CEO, TechStart', 'elementrify'),
                        'image' => ['url' => $placeholder_src],
                        'rating' => 5,
                        'card_accent_color' => '#fce7f3',
                        'card_use_gradient' => 'yes',
                    ],
                    [
                        'content' => __('Perfect solution for our agency. Clients love the modern designs and we save hours on development time.', 'elementrify'),
                        'name' => __('Sarah Wilson', 'elementrify'),
                        'role' => __('Creative Director', 'elementrify'),
                        'image' => ['url' => $placeholder_src],
                        'rating' => 5,
                        'card_accent_color' => '#bbf7d0',
                        'card_use_gradient' => 'yes',
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'     => __('Columns', 'elementrify'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 4,
                'default'   => 3,
                'condition' => ['view' => 'grid'],
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label'      => __('Gap between cards', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48, 'step' => 4]],
                'default'    => ['size' => 24],
                'condition'  => ['view' => 'grid'],
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label'     => __('Navigation', 'elementrify'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'both',
                'options'   => [
                    'both'   => __('Arrows and Dots', 'elementrify'),
                    'arrows' => __('Arrows', 'elementrify'),
                    'dots'   => __('Dots', 'elementrify'),
                    'none'   => __('None', 'elementrify'),
                ],
                'condition'  => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label'     => __('Pagination type', 'elementrify'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'bullets',
                'options'   => [
                    'bullets'     => __('Circle', 'elementrify'),
                    'square'      => __('Square', 'elementrify'),
                    'progressbar' => __('Bar', 'elementrify'),
                ],
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => __('Autoplay', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => __('Autoplay interval (seconds)', 'elementrify'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 30,
                'step'      => 1,
                'default'   => 5,
                'condition' => ['view' => 'slider', 'autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'        => __('Loop', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'slides_per_view',
            [
                'label'     => __('Cards per view', 'elementrify'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 5,
                'default'   => 3,
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'slide_gap',
            [
                'label'      => __('Gap between cards', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48, 'step' => 4]],
                'default'    => ['size' => 24],
                'condition'  => ['view' => 'slider'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => __('Cards', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 24, 'step' => 2],
                    '%'  => ['min' => 0, 'max' => 50],
                ],
                'default'    => ['unit' => 'px', 'size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-testimonial-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonial-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .elementrify-testimonial-card',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_quote',
            [
                'label' => __('Quote', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'quote_typography',
                'selector' => '{{WRAPPER}} .elementrify-testimonial-card__quote',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonial-card__quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_name',
            [
                'label' => __('Name', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .elementrify-testimonial-card__name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonial-card__name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_role',
            [
                'label' => __('Role / Title', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'role_typography',
                'selector' => '{{WRAPPER}} .elementrify-testimonial-card__role',
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonial-card__role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_stars',
            [
                'label' => __('Stars', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stars_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#eab308',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonial__star-filled' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-testimonial__star-filled svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_arrows',
            [
                'label'     => __('Arrows', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'view'        => 'slider',
                    'navigation!' => 'none',
                    'navigation!' => 'dots',
                ],
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label'      => __('Button size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 28, 'max' => 56, 'step' => 2]],
                'default'    => ['size' => 40],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-testimonials__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label'     => __('Background color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonials__arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_icon_color',
            [
                'label'     => __('Icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonials__arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-testimonials__arrow svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_dots',
            [
                'label'     => __('Pagination dots', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'view'        => 'slider',
                    'navigation!' => 'none',
                    'navigation!' => 'arrows',
                ],
            ]
        );

        $this->add_control(
            'dots_gap',
            [
                'label'      => __('Space between', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24, 'step' => 2]],
                'default'    => ['size' => 8],
            ]
        );

        $this->add_control(
            'bar_width',
            [
                'label'      => __('Bar width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 14, 'max' => 56, 'step' => 2]],
                'default'    => ['size' => 28],
                'condition'  => ['pagination_type' => 'progressbar'],
            ]
        );

        $this->add_control(
            'dot_size',
            [
                'label'      => __('Size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 4, 'max' => 16, 'step' => 1]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-testimonials--slider .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => ['pagination_type' => ['bullets', 'square']],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonials--slider .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color_active',
            [
                'label'     => __('Active color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.85)',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-testimonials--slider .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/testimonials/testimonials.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        $placeholder_url = \Elementor\Utils::get_placeholder_image_src();
        ?>
        <#
        var items = settings.items || [];
        var view = settings.view || 'grid';
        var columns = (view === 'grid' && settings.columns) ? settings.columns : 3;
        var columnGap = (view === 'grid' && settings.column_gap && settings.column_gap.size !== undefined) ? settings.column_gap.size : 24;
        var nav = settings.navigation || 'both';
        var showArrows = view === 'slider' && (nav === 'both' || nav === 'arrows') && items.length > 1;
        var showDots = view === 'slider' && (nav === 'both' || nav === 'dots') && items.length > 1;
        var autoplay = view === 'slider' && settings.autoplay === 'yes';
        var autoplayDelay = (autoplay && settings.autoplay_delay && settings.autoplay_delay > 0) ? settings.autoplay_delay : 5;
        var loop = view === 'slider' && settings.loop === 'yes';
        var paginationType = settings.pagination_type || 'bullets';
        var dotsGap = (settings.dots_gap && settings.dots_gap.size) ? settings.dots_gap.size : 8;
        var barWidth = (paginationType === 'progressbar' && settings.bar_width && settings.bar_width.size) ? settings.bar_width.size : 28;
        var slidesPerView = (view === 'slider' && settings.slides_per_view) ? Math.min(5, Math.max(1, parseInt(settings.slides_per_view, 10) || 3)) : 3;
        var slideGap = (view === 'slider' && settings.slide_gap && settings.slide_gap.size !== undefined) ? settings.slide_gap.size : 24;
        var placeholderUrl = '<?php echo esc_url($placeholder_url); ?>';
        var paginationClass = '';
        if (paginationType === 'square') paginationClass = ' elementrify-pagination-square';
        else if (paginationType === 'progressbar') paginationClass = ' elementrify-pagination-bar';
        var styleVars = '';
        if (view === 'grid') {
            styleVars = '--elementrify-testimonials-columns: ' + columns + '; --elementrify-testimonials-gap: ' + columnGap + 'px;';
        } else {
            styleVars = '--elementrify-dots-gap: ' + dotsGap + 'px;';
            if (paginationType === 'progressbar') styleVars += ' --elementrify-bar-width: ' + barWidth + 'px;';
        }
        #>
        <# if (view === 'grid') { #>
            <div class="elementrify-testimonials elementrify-testimonials--grid" style="{{ styleVars }}">
                <# _.each(items, function(item) {
                    var avatarUrl = (item.image && item.image.url) ? item.image.url : placeholderUrl;
                    var rating = Math.min(5, Math.max(0, parseFloat(item.rating) || 0));
                    var accent = item.card_accent_color ? (item.card_accent_color + '').trim() : '';
                    var useGrad = item.card_use_gradient === 'yes';
                    var cardStyle = '';
                    if (accent) {
                        cardStyle = 'border-color: ' + accent + '; ';
                        if (useGrad) {
                            var h = accent.replace(/^#/, '');
                            if (h.length === 3) h = h[0]+h[0]+h[1]+h[1]+h[2]+h[2];
                            var r = parseInt(h.slice(0,2), 16), g = parseInt(h.slice(2,4), 16), b = parseInt(h.slice(4,6), 16);
                            cardStyle += 'background: linear-gradient(135deg, rgba('+r+','+g+','+b+',0.14) 0%, rgba('+r+','+g+','+b+',0.04) 45%, #ffffff 100%);';
                        } else { cardStyle += 'background-color: #ffffff;'; }
                    }
                #>
                    <div class="elementrify-testimonial-card"<# if (cardStyle) { #> style="{{ cardStyle }}"<# } #>>
                        <div class="elementrify-testimonial__stars">
                            <# _.each([0,1,2,3,4], function(i) {
                                var remainder = rating - i;
                                var fillPct = Math.round(Math.min(1, Math.max(0, remainder)) * 100);
                            #>
                                <span class="elementrify-testimonial__star"><span class="elementrify-testimonial__star-empty"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span><span class="elementrify-testimonial__star-fill" style="width:{{ fillPct }}%"><span class="elementrify-testimonial__star-filled"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span></span></span>
                            <# }); #>
                        </div>
                        <blockquote class="elementrify-testimonial-card__quote">"{{{ item.content }}}"</blockquote>
                        <div class="elementrify-testimonial-card__footer">
                            <img class="elementrify-testimonial-card__avatar" src="{{ avatarUrl }}" alt="{{ item.name }}">
                            <div class="elementrify-testimonial-card__meta">
                                <span class="elementrify-testimonial-card__name">{{{ item.name }}}</span>
                                <# if (item.role) { #><span class="elementrify-testimonial-card__role">{{{ item.role }}}</span><# } #>
                            </div>
                        </div>
                    </div>
                <# }); #>
            </div>
        <# } else { #>
            <div class="elementrify-testimonials elementrify-testimonials--slider swiper{{ paginationClass }}" data-autoplay="{{ autoplay ? '1' : '0' }}" data-autoplay-delay="{{ autoplayDelay }}" data-loop="{{ loop ? '1' : '0' }}" data-pagination-type="{{ paginationType }}" data-slides-per-view="{{ slidesPerView }}" data-space-between="{{ slideGap }}" style="{{ styleVars }}">
                <div class="swiper-wrapper">
                    <# _.each(items, function(item) {
                        var avatarUrl = (item.image && item.image.url) ? item.image.url : placeholderUrl;
                        var rating = Math.min(5, Math.max(0, parseFloat(item.rating) || 0));
                        var accent = item.card_accent_color ? (item.card_accent_color + '').trim() : '';
                        var useGrad = item.card_use_gradient === 'yes';
                        var cardStyle = '';
                        if (accent) {
                            cardStyle = 'border-color: ' + accent + '; ';
                            if (useGrad) {
                                var h = accent.replace(/^#/, '');
                                if (h.length === 3) h = h[0]+h[0]+h[1]+h[1]+h[2]+h[2];
                                var r = parseInt(h.slice(0,2), 16), g = parseInt(h.slice(2,4), 16), b = parseInt(h.slice(4,6), 16);
                                cardStyle += 'background: linear-gradient(135deg, rgba('+r+','+g+','+b+',0.14) 0%, rgba('+r+','+g+','+b+',0.04) 45%, #ffffff 100%);';
                            } else { cardStyle += 'background-color: #ffffff;'; }
                        }
                    #>
                        <div class="swiper-slide">
                            <div class="elementrify-testimonial-card"<# if (cardStyle) { #> style="{{ cardStyle }}"<# } #>>
                                <div class="elementrify-testimonial__stars">
                                    <# _.each([0,1,2,3,4], function(k) {
                                        var rem = rating - k;
                                        var fillPct2 = Math.round(Math.min(1, Math.max(0, rem)) * 100);
                                    #>
                                        <span class="elementrify-testimonial__star"><span class="elementrify-testimonial__star-empty"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span><span class="elementrify-testimonial__star-fill" style="width:{{ fillPct2 }}%"><span class="elementrify-testimonial__star-filled"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span></span></span>
                                    <# }); #>
                                </div>
                                <blockquote class="elementrify-testimonial-card__quote">"{{{ item.content }}}"</blockquote>
                                <div class="elementrify-testimonial-card__footer">
                                    <img class="elementrify-testimonial-card__avatar" src="{{ avatarUrl }}" alt="{{ item.name }}">
                                    <div class="elementrify-testimonial-card__meta">
                                        <span class="elementrify-testimonial-card__name">{{{ item.name }}}</span>
                                        <# if (item.role) { #><span class="elementrify-testimonial-card__role">{{{ item.role }}}</span><# } #>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <# }); #>
                </div>
                <# if (showArrows) { #>
                    <div class="elementrify-testimonials__arrow elementrify-testimonials__arrow--prev swiper-button-prev" role="button" tabindex="0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                    </div>
                    <div class="elementrify-testimonials__arrow elementrify-testimonials__arrow--next swiper-button-next" role="button" tabindex="0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                    </div>
                <# } #>
                <# if (showDots) { #>
                    <div class="swiper-pagination"></div>
                <# } #>
            </div>
        <# } #>
        <?php
    }
}
