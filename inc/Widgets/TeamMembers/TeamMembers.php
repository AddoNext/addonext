<?php
namespace Addonext\Widgets\TeamMembers;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') or exit;

class TeamMembers extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('swiper');
        wp_enqueue_style('addonext-team-members');
        wp_enqueue_script('swiper');
        wp_enqueue_script('addonext-team-members');
    }

    public function get_icon(): string
    {
        return 'eicon-person addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-team-members';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Team Members', 'addonext');
    }

    public function get_description(): string
    {
        return __('Team profile cards in grid or slider', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'addonext-team-members'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'addonext-team-members'];
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
            'image',
            [
                'label'   => __('Photo', 'addonext'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );
        $repeater->add_control(
            'name',
            [
                'label'   => __('Name', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('John Doe', 'addonext'),
            ]
        );
        $repeater->add_control(
            'role',
            [
                'label'   => __('Job title / Role', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('CEO & Founder', 'addonext'),
            ]
        );
        $repeater->add_control(
            'link_linkedin',
            [
                'label'   => __('LinkedIn URL', 'addonext'),
                'type'    => Controls_Manager::URL,
                'default' => ['url' => '', 'is_external' => true],
            ]
        );
        $repeater->add_control(
            'link_twitter',
            [
                'label'   => __('Twitter / X URL', 'addonext'),
                'type'    => Controls_Manager::URL,
                'default' => ['url' => '', 'is_external' => true],
            ]
        );

        $placeholder_src = \Elementor\Utils::get_placeholder_image_src();
        $this->add_control(
            'items',
            [
                'label'       => __('Team members', 'addonext'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['name' => __('John Doe', 'addonext'), 'role' => __('CEO & Founder', 'addonext'), 'image' => ['url' => $placeholder_src]],
                    ['name' => __('Jane Smith', 'addonext'), 'role' => __('Lead Designer', 'addonext'), 'image' => ['url' => $placeholder_src]],
                    ['name' => __('Mike Wilson', 'addonext'), 'role' => __('Developer', 'addonext'), 'image' => ['url' => $placeholder_src]],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'     => __('Columns', 'addonext'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 6,
                'default'   => 3,
                'condition' => ['view' => 'grid'],
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label'      => __('Gap between cards', 'addonext'),
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
                'default'   => 'both',
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
                'label'     => __('Autoplay', 'addonext'),
                'type'      => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => __('Autoplay interval (seconds)', 'addonext'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 30,
                'default'   => 5,
                'condition' => ['view' => 'slider', 'autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'     => __('Loop', 'addonext'),
                'type'      => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'   => 'yes',
                'condition' => ['view' => 'slider'],
            ]
        );

        $this->add_control(
            'slides_per_view',
            [
                'label'     => __('Cards per view', 'addonext'),
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
                'label'      => __('Gap between cards', 'addonext'),
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
                'label' => __('Cards', 'addonext'),
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
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-team-members-card' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .addonext-team-members-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label'     => __('Border color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E0E0E0',
                'selectors' => [
                    '{{WRAPPER}} .addonext-team-members-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .addonext-team-members-card',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Photo', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label'      => __('Size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 60, 'max' => 280]],
                'default'    => ['size' => 120],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-team-members-card__image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_name',
            [
                'label' => __('Name', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .addonext-team-members-card__name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .addonext-team-members-card__name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_role',
            [
                'label' => __('Role / Title', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'role_typography',
                'selector' => '{{WRAPPER}} .addonext-team-members-card__role',
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .addonext-team-members-card__role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_social',
            [
                'label' => __('Social icons', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'social_icon_size',
            [
                'label'      => __('Icon size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 16, 'max' => 40]],
                'default'    => ['size' => 24],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-team-members-card__social a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .addonext-team-members-card__social svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .addonext-team-members-card__social a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-team-members-card__social svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_arrows',
            [
                'label'     => __('Arrows', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['view' => 'slider', 'navigation!' => 'none', 'navigation!' => 'dots'],
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label'      => __('Button size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 28, 'max' => 56]],
                'default'    => ['size' => 40],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-team-members__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_dots',
            [
                'label'     => __('Pagination dots', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['view' => 'slider', 'navigation!' => 'none', 'navigation!' => 'arrows'],
            ]
        );

        $this->add_control(
            'dots_gap',
            [
                'label'      => __('Space between', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8],
            ]
        );

        $this->add_control(
            'dot_size',
            [
                'label'      => __('Size', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 4, 'max' => 16]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-team-members--slider .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['pagination_type' => ['bullets', 'square']],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .addonext-team-members--slider .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color_active',
            [
                'label'     => __('Active color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.85)',
                'selectors' => [
                    '{{WRAPPER}} .addonext-team-members--slider .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/team-members/team-members.php';
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
        var autoplayDelay = (autoplay && settings.autoplay_delay) ? settings.autoplay_delay : 5;
        var loop = view === 'slider' && settings.loop === 'yes';
        var paginationType = settings.pagination_type || 'bullets';
        var dotsGap = (settings.dots_gap && settings.dots_gap.size) ? settings.dots_gap.size : 8;
        var slidesPerView = (view === 'slider' && settings.slides_per_view) ? Math.min(5, Math.max(1, parseInt(settings.slides_per_view, 10) || 3)) : 3;
        var slideGap = (view === 'slider' && settings.slide_gap && settings.slide_gap.size !== undefined) ? settings.slide_gap.size : 24;
        var placeholderUrl = '<?php echo esc_url($placeholder_url); ?>';
        var paginationClass = paginationType === 'square' ? ' addonext-pagination-square' : (paginationType === 'progressbar' ? ' addonext-pagination-bar' : '');
        var styleVars = view === 'grid' ? ('--addonext-team-members-columns: ' + columns + '; --addonext-team-members-gap: ' + columnGap + 'px;') : ('--addonext-dots-gap: ' + dotsGap + 'px;');
        #>
        <# if (view === 'grid') { #>
            <div class="addonext-team-members addonext-team-members--grid" style="{{ styleVars }}">
                <# _.each(items, function(item) {
                    var imgUrl = (item.image && item.image.url) ? item.image.url : placeholderUrl;
                    var linkedin = item.link_linkedin && item.link_linkedin.url ? item.link_linkedin.url : '#';
                    var twitter = item.link_twitter && item.link_twitter.url ? item.link_twitter.url : '#';
                    var linkedinAttr = linkedin !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
                    var twitterAttr = twitter !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
                #>
                    <div class="addonext-team-members-card">
                        <img class="addonext-team-members-card__image" src="{{ imgUrl }}" alt="{{ item.name }}">
                        <h3 class="addonext-team-members-card__name">{{{ item.name }}}</h3>
                        <p class="addonext-team-members-card__role">{{{ item.role }}}</p>
                        <div class="addonext-team-members-card__social">
                            <a href="{{ linkedin }}" aria-label="LinkedIn"{{{ linkedinAttr }}}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H4.5v8.37h3.77z"/></svg></a>
                            <a href="{{ twitter }}" aria-label="Twitter"{{{ twitterAttr }}}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                        </div>
                    </div>
                <# }); #>
            </div>
        <# } else { #>
            <div class="addonext-team-members addonext-team-members--slider swiper{{ paginationClass }}" data-autoplay="{{ autoplay ? '1' : '0' }}" data-autoplay-delay="{{ autoplayDelay }}" data-loop="{{ loop ? '1' : '0' }}" data-pagination-type="{{ paginationType }}" data-slides-per-view="{{ slidesPerView }}" data-space-between="{{ slideGap }}" style="{{ styleVars }}">
                <div class="swiper-wrapper">
                    <# _.each(items, function(item) {
                        var imgUrl = (item.image && item.image.url) ? item.image.url : placeholderUrl;
                        var linkedin = item.link_linkedin && item.link_linkedin.url ? item.link_linkedin.url : '#';
                        var twitter = item.link_twitter && item.link_twitter.url ? item.link_twitter.url : '#';
                        var linkedinAttr = linkedin !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
                        var twitterAttr = twitter !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '';
                    #>
                        <div class="swiper-slide">
                            <div class="addonext-team-members-card">
                                <img class="addonext-team-members-card__image" src="{{ imgUrl }}" alt="{{ item.name }}">
                                <h3 class="addonext-team-members-card__name">{{{ item.name }}}</h3>
                                <p class="addonext-team-members-card__role">{{{ item.role }}}</p>
                                <div class="addonext-team-members-card__social">
                                    <a href="{{ linkedin }}" aria-label="LinkedIn"{{{ linkedinAttr }}}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H4.5v8.37h3.77z"/></svg></a>
                                    <a href="{{ twitter }}" aria-label="Twitter"{{{ twitterAttr }}}><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                                </div>
                            </div>
                        </div>
                    <# }); #>
                </div>
                <# if (showArrows) { #>
                    <div class="addonext-team-members__arrow addonext-team-members__arrow--prev swiper-button-prev" role="button" tabindex="0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg></div>
                    <div class="addonext-team-members__arrow addonext-team-members__arrow--next swiper-button-next" role="button" tabindex="0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></div>
                <# } #>
                <# if (showDots) { #><div class="swiper-pagination"></div><# } #>
            </div>
        <# } #>
        <?php
    }
}
