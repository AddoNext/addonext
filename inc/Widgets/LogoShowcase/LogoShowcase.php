<?php
namespace Addonext\Widgets\LogoShowcase;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class LogoShowcase extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('swiper');
        wp_enqueue_style('addonext-logo-showcase');
        wp_enqueue_script('swiper');
        wp_enqueue_script('addonext-logo-showcase');
    }

    public function get_icon(): string
    {
        return 'eicon-logo addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-logo-showcase';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Logo Showcase', 'addonext');
    }

    public function get_description(): string
    {
        return __('Client logos in grid or slider', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#64748b';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'addonext-logo-showcase'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'addonext-logo-showcase'];
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
            'show_title',
            [
                'label'        => __('Show title', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'     => __('Title', 'addonext'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Trusted by Leading Companies', 'addonext'),
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => __('View', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid'   => __('Grid', 'addonext'),
                    'slider' => __('Slider', 'addonext'),
                ],
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'logo',
            [
                'label' => __('Logo', 'addonext'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'addonext'),
                'type'  => Controls_Manager::URL,
            ]
        );
        $repeater->add_control(
            'alt',
            [
                'label'   => __('Alt text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'logos',
            [
                'label'       => __('Logos', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [],
                'title_field' => '{{{ alt }}}',
            ]
        );

        $this->add_control(
            'columns_mode',
            [
                'label'     => __('Columns', 'addonext'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'auto',
                'options'   => [
                    'auto'   => __('Auto (fit to width)', 'addonext'),
                    'custom' => __('Custom count', 'addonext'),
                ],
                'condition' => ['view' => 'grid'],
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'     => __('Column count', 'addonext'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 6,
                'default'   => 4,
                'condition' => ['view' => 'grid', 'columns_mode' => 'custom'],
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label'      => __('Gap between items', 'addonext'),
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
                'label'     => __('Navigation', 'addonext'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'none',
                'options'   => [
                    'both'   => __('Arrows and Dots', 'addonext'),
                    'arrows' => __('Arrows', 'addonext'),
                    'dots'   => __('Dots', 'addonext'),
                    'none'   => __('None', 'addonext'),
                ],
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label'     => __('Pagination type', 'addonext'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'bullets',
                'options'   => [
                    'bullets'     => __('Circle', 'addonext'),
                    'square'      => __('Square', 'addonext'),
                    'progressbar' => __('Bar', 'addonext'),
                ],
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => __('Autoplay', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => __('Autoplay interval (seconds)', 'addonext'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 30,
                'default'   => 3,
                'condition' => ['view' => 'slider', 'autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'        => __('Loop', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'slides_per_view',
            [
                'label'     => __('Logos per view', 'addonext'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 6,
                'default'   => 4,
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'slide_gap',
            [
                'label'      => __('Gap between items', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48, 'step' => 4]],
                'default'    => ['size' => 24],
                'condition'  => ['view' => 'slider'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .addonext-logo-showcase__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6D6D7C',
                'selectors' => [
                    '{{WRAPPER}} .addonext-logo-showcase__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label'      => __('Spacing below', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 80]],
                'default'    => ['size' => 32],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-logo-showcase__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => __('Logo cards', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 6],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-logo-showcase__card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-logo-showcase__card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'logo_grayscale',
            [
                'label'        => __('Grayscale logos', 'addonext'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'selectors'    => [
                    '{{WRAPPER}} .addonext-logo-showcase__img' => 'filter: grayscale(100%);',
                ],
            ]
        );

        $this->add_control(
            'logo_color',
            [
                'label'     => __('Logo tint', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6D6D7C',
                'selectors' => [
                    '{{WRAPPER}} .addonext-logo-showcase__img' => 'filter: grayscale(100%) brightness(0) saturate(100%); opacity: 0.75;',
                ],
                'condition' => ['logo_grayscale' => ''],
            ]
        );

        $this->add_control(
            'logo_max_height',
            [
                'label'      => __('Logo max height', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 24, 'max' => 120]],
                'default'    => ['size' => 48],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-logo-showcase__img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_slider_nav',
            [
                'label'     => __('Slider navigation', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'arrows_size',
            [
                'label'      => __('Arrows size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 24, 'max' => 56]],
                'default'    => ['size' => 40],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-logo-showcase__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dots_gap',
            [
                'label'      => __('Dots gap', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-logo-showcase--slider' => '--addonext-dots-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/logo-showcase/logo-showcase.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        $placeholder = \Elementor\Utils::get_placeholder_image_src();
        ?>
        <#
        var logos = settings.logos || [];
        var showTitle = settings.show_title === 'yes';
        var title = showTitle && settings.title ? settings.title : '';
        var view = settings.view || 'grid';
        var columnsMode = (view === 'grid' && settings.columns_mode) ? settings.columns_mode : 'auto';
        var columns = (view === 'grid' && columnsMode === 'custom' && settings.columns) ? Math.max(1, Math.min(6, parseInt(settings.columns, 10) || 4)) : 4;
        var columnGap = (view === 'grid' && settings.column_gap && settings.column_gap.size != null) ? settings.column_gap.size : 24;
        var gridAutoClass = (view === 'grid' && columnsMode === 'auto') ? ' addonext-logo-showcase__grid--auto' : '';
        var nav = (view === 'slider' && settings.navigation) ? settings.navigation : 'none';
        var showArrows = view === 'slider' && (nav === 'both' || nav === 'arrows');
        var showDots = view === 'slider' && (nav === 'both' || nav === 'dots');
        var autoplay = view === 'slider' && settings.autoplay === 'yes';
        var autoplayDelay = (autoplay && settings.autoplay_delay && parseInt(settings.autoplay_delay, 10) > 0) ? parseInt(settings.autoplay_delay, 10) : 3;
        var loop = view === 'slider' && (settings.loop !== 'no');
        var paginationType = settings.pagination_type || 'bullets';
        var slidesPerView = (view === 'slider' && settings.slides_per_view) ? Math.max(1, Math.min(6, parseInt(settings.slides_per_view, 10) || 4)) : 4;
        var slideGap = (view === 'slider' && settings.slide_gap && settings.slide_gap.size != null) ? settings.slide_gap.size : 24;
        var dotsGap = (settings.dots_gap && settings.dots_gap.size != null) ? settings.dots_gap.size : 8;
        var paginationClass = (paginationType === 'square') ? ' addonext-pagination-square' : ((paginationType === 'progressbar') ? ' addonext-pagination-bar' : '');
        var gridStyleVars = view === 'grid' ? ('--addonext-logo-showcase-gap: ' + columnGap + 'px;' + (columnsMode === 'custom' ? ' --addonext-logo-showcase-columns: ' + columns + ';' : '')) : '';
        var sliderStyleVars = view === 'slider' ? ('--addonext-dots-gap: ' + dotsGap + 'px;') : '';
        #>
        <?php if (true) : ?>
        <div class="addonext-logo-showcase">
            <# if (showTitle && title) { #>
                <h3 class="addonext-logo-showcase__title">{{{ title }}}</h3>
            <# } #>
            <# if (view === 'grid') { #>
                <div class="addonext-logo-showcase__grid{{ gridAutoClass }}" style="{{ gridStyleVars }}">
                    <# _.each(logos, function(item) {
                        var imgUrl = (item.logo && item.logo.url) ? item.logo.url : '<?php echo esc_url($placeholder); ?>';
                        var linkUrl = (item.link && item.link.url) ? item.link.url : '';
                        var alt = item.alt || '';
                    #>
                        <div class="addonext-logo-showcase__card">
                            <# if (linkUrl) { #><a href="{{ linkUrl }}" class="addonext-logo-showcase__link"><# } #>
                                <img class="addonext-logo-showcase__img" src="{{ imgUrl }}" alt="{{ alt }}">
                            <# if (linkUrl) { #></a><# } #>
                        </div>
                    <# }); #>
                </div>
            <# } else { #>
                <div class="addonext-logo-showcase--slider swiper{{ paginationClass }}" style="{{ sliderStyleVars }}" data-autoplay="{{ autoplay ? '1' : '0' }}" data-autoplay-delay="{{ autoplayDelay }}" data-loop="{{ loop ? '1' : '0' }}" data-pagination-type="{{ paginationType }}" data-slides-per-view="{{ slidesPerView }}" data-space-between="{{ slideGap }}">
                    <div class="swiper-wrapper">
                        <# _.each(logos, function(item) {
                            var imgUrl = (item.logo && item.logo.url) ? item.logo.url : '<?php echo esc_url($placeholder); ?>';
                            var linkUrl = (item.link && item.link.url) ? item.link.url : '';
                            var alt = item.alt || '';
                        #>
                            <div class="swiper-slide">
                                <div class="addonext-logo-showcase__card">
                                    <# if (linkUrl) { #><a href="{{ linkUrl }}" class="addonext-logo-showcase__link"><# } #>
                                        <img class="addonext-logo-showcase__img" src="{{ imgUrl }}" alt="{{ alt }}">
                                    <# if (linkUrl) { #></a><# } #>
                                </div>
                            </div>
                        <# }); #>
                    </div>
                    <# if (showArrows) { #>
                        <div class="addonext-logo-showcase__arrow addonext-logo-showcase__arrow--prev swiper-button-prev" role="button" tabindex="0"></div>
                        <div class="addonext-logo-showcase__arrow addonext-logo-showcase__arrow--next swiper-button-next" role="button" tabindex="0"></div>
                    <# } #>
                    <# if (showDots) { #><div class="swiper-pagination"></div><# } #>
                </div>
            <# } #>
        </div>
        <?php endif; ?>
        <?php
    }
}
