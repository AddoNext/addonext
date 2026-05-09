<?php
namespace Elementrify\Widgets\ImageCarousel;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

defined('ABSPATH') or exit;

class ImageCarousel extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('swiper');
        wp_enqueue_style('elementrify-image-carousel');
        wp_enqueue_script('swiper');
        wp_enqueue_script('elementrify-image-carousel');
    }

    public function get_icon(): string
    {
        return 'eicon-slides elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-image-carousel';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Image Carousel', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Image slider with arrows and dots', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#0891b2';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'elementrify-image-carousel'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementrify-image-carousel'];
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

        $repeater = new Repeater();
        $repeater->add_control(
            'image',
            [
                'label'   => __('Image', 'elementrify'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );
        $repeater->add_control(
            'caption',
            [
                'label'   => __('Caption', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'slides',
            [
                'label'       => __('Slides', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['image' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'caption' => ''],
                    ['image' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'caption' => ''],
                    ['image' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'caption' => ''],
                ],
                'title_field' => '{{{ caption }}}',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'image_size',
                'default' => 'large',
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label'   => __('Navigation', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'both'   => __('Arrows and Dots', 'elementrify'),
                    'arrows' => __('Arrows', 'elementrify'),
                    'dots'   => __('Dots', 'elementrify'),
                    'none'   => __('None', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => __('Autoplay', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elementrify'),
                'label_off'    => __('No', 'elementrify'),
                'return_value' => 'yes',
                'default'      => '',
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
                'condition' => ['autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'        => __('Loop', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elementrify'),
                'label_off'    => __('No', 'elementrify'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_slides',
            [
                'label' => __('Slides', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'carousel_height',
            [
                'label'      => __('Height', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => ['min' => 100, 'max' => 800, 'step' => 10],
                    'vh' => ['min' => 10, 'max' => 100, 'step' => 5],
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 24, 'step' => 2],
                    '%'  => ['min' => 0, 'max' => 50],
                ],
                'default'    => ['unit' => 'px', 'size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-image-carousel' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
                    '{{WRAPPER}} .elementrify-image-carousel__slide img' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementrify-image-carousel__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementrify-image-carousel__arrow' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementrify-image-carousel__arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-image-carousel__arrow svg' => 'fill: {{VALUE}};',
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
                    'navigation!' => 'none',
                    'navigation!' => 'arrows',
                ],
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label'   => __('Type', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bullets',
                'options' => [
                    'bullets'     => __('Circle', 'elementrify'),
                    'square'      => __('Square', 'elementrify'),
                    'progressbar' => __('Bar', 'elementrify'),
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
                'condition'  => [
                    'pagination_type' => 'progressbar',
                ],
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
                    '{{WRAPPER}} .elementrify-image-carousel .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'pagination_type' => ['bullets', 'square'],
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-image-carousel .swiper-pagination-bullet' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .elementrify-image-carousel .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/image-carousel/image-carousel.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var slides = settings.slides || [];
        var nav = settings.navigation || 'both';
        var showArrows = (nav === 'both' || nav === 'arrows') && slides.length > 1;
        var showDots = (nav === 'both' || nav === 'dots') && slides.length > 1;
        var autoplay = settings.autoplay === 'yes';
        var autoplayDelay = (autoplay && settings.autoplay_delay && settings.autoplay_delay > 0) ? settings.autoplay_delay : 5;
        var loop = settings.loop === 'yes';
        var paginationType = settings.pagination_type || 'bullets';
        var dotsGap = (settings.dots_gap && settings.dots_gap.size) ? settings.dots_gap.size : 8;
        var barWidth = (paginationType === 'progressbar' && settings.bar_width && settings.bar_width.size) ? settings.bar_width.size : 28;
        var placeholderUrl = '<?php echo esc_url(\Elementor\Utils::get_placeholder_image_src()); ?>';
        var paginationClass = '';
        if (paginationType === 'square') paginationClass = ' elementrify-pagination-square';
        else if (paginationType === 'progressbar') paginationClass = ' elementrify-pagination-bar';
        var styleVars = '--elementrify-dots-gap: ' + dotsGap + 'px;';
        if (paginationType === 'progressbar') styleVars += ' --elementrify-bar-width: ' + barWidth + 'px;';
        if (settings.carousel_height && settings.carousel_height.size !== '' && settings.carousel_height.size > 0) {
            styleVars += ' --elementrify-carousel-height: ' + settings.carousel_height.size + (settings.carousel_height.unit || 'px') + ';';
        }
        #>
        <div class="elementrify-image-carousel swiper swiper-autoheight{{ paginationClass }}" data-autoplay="{{ autoplay ? '1' : '0' }}" data-autoplay-delay="{{ autoplayDelay }}" data-loop="{{ loop ? '1' : '0' }}" data-pagination-type="{{ paginationType }}" style="{{ styleVars }}">
            <div class="elementrify-image-carousel__wrapper swiper-wrapper">
                <# _.each(slides, function(slide) {
                    var url = (slide.image && slide.image.url) ? slide.image.url : placeholderUrl;
                #>
                    <div class="elementrify-image-carousel__slide swiper-slide">
                        <img src="{{ url }}" alt="{{ slide.caption || '' }}">
                    </div>
                <# }); #>
            </div>
            <# if (showArrows) { #>
                <div class="elementrify-image-carousel__arrow elementrify-image-carousel__arrow--prev swiper-button-prev" role="button" tabindex="0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                </div>
                <div class="elementrify-image-carousel__arrow elementrify-image-carousel__arrow--next swiper-button-next" role="button" tabindex="0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                </div>
            <# } #>
            <# if (showDots) { #>
                <div class="swiper-pagination"></div>
            <# } #>
        </div>
        <?php
    }
}
