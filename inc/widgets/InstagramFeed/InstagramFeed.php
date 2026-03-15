<?php
namespace Elementrify\Widgets\InstagramFeed;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

class InstagramFeed extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-instagram-gallery elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-instagram-feed';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Instagram Feed', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Display Instagram posts', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#e4405f';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'elementrify-instagram-feed'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementrify-instagram-feed'];
    }

    public function enqueue_assets(): void
    {
        wp_enqueue_style('swiper');
        wp_enqueue_script('swiper');
        wp_register_style(
            'elementrify-instagram-feed',
            ELEMENTRIFY_URL . 'assets/css/instagram-feed.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-instagram-feed',
            ELEMENTRIFY_URL . 'assets/js/instagram-feed.js',
            ['swiper'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public static function get_cache_duration(string $select): int
    {
        $map = [
            'minute' => MINUTE_IN_SECONDS,
            'hour'   => HOUR_IN_SECONDS,
            'day'    => DAY_IN_SECONDS,
            'week'   => WEEK_IN_SECONDS,
        ];
        return isset($map[ $select ]) ? $map[ $select ] : 0;
    }

    public function fetch_instagram_feed(string $access_token, array $settings): array
    {
        $limit = isset($settings['limit']) ? max(1, min(12, (int) $settings['limit'])) : 6;
        $key   = 'elementrify_instagram_feed_' . $this->get_id() . '_' . md5($access_token . (string) ($settings['cache_timeout'] ?? 'hour'));

        $cached = get_transient($key);
        if (is_array($cached) && !empty($cached)) {
            return array_slice($cached, 0, $limit);
        }

        $url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,thumbnail_url,permalink,username,caption,timestamp&access_token=' . rawurlencode($access_token) . '&limit=' . $limit;
        $response = wp_remote_get($url);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return [];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($body['data']) || !is_array($body['data'])) {
            return [];
        }

        $duration = self::get_cache_duration($settings['cache_timeout'] ?? 'hour');
        if ($duration > 0) {
            set_transient($key, $body['data'], $duration);
        }

        return array_slice($body['data'], 0, $limit);
    }

    public function register_controls(): void
    {
        $this->register_content_api_section();
        $this->register_content_layout_section();
        $this->register_content_elements_section();
        $this->register_content_follow_section();
        $this->register_style_layout_section();
        $this->register_style_image_section();
        $this->register_style_overlay_section();
        $this->register_style_caption_section();
        $this->register_style_carousel_section();
    }

    protected function register_content_api_section(): void
    {
        $this->start_controls_section(
            'section_api',
            [
                'label' => __('Integration', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'access_token',
            [
                'label'       => __('Access Token', 'elementrify'),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => __('Paste your Instagram Graph API access token', 'elementrify'),
                'description' => __('Get token via Facebook for Developers (Instagram Basic Display or Graph API).', 'elementrify'),
            ]
        );

        $this->add_control(
            'cache_timeout',
            [
                'label'       => __('Cache Timeout', 'elementrify'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'hour',
                'options'     => [
                    'none'   => __('None', 'elementrify'),
                    'minute' => __('Minute', 'elementrify'),
                    'hour'   => __('Hour', 'elementrify'),
                    'day'    => __('Day', 'elementrify'),
                    'week'   => __('Week', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'   => __('Number of Posts', 'elementrify'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'max'     => 12,
            ]
        );

        $this->end_controls_section();
    }

    protected function register_content_layout_section(): void
    {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'        => __('Layout', 'elementrify'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'grid',
                'options'      => [
                    'grid'     => __('Grid', 'elementrify'),
                    'list'     => __('List', 'elementrify'),
                    'carousel' => __('Carousel', 'elementrify'),
                ],
                'prefix_class' => 'elementrify-instagram-feed--',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'     => __('Columns', 'elementrify'),
                'type'      => Controls_Manager::SELECT,
                'default'   => '3',
                'options'   => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-instagram-feed__grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
                'condition' => [
                    'layout' => ['grid', 'list'],
                ],
            ]
        );

        $this->add_responsive_control(
            'gutter',
            [
                'label'      => __('Gutter', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 50]],
                'default'    => ['size' => 10],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-instagram-feed__grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'layout' => ['grid', 'list'],
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_slides',
            [
                'label'     => __('Slides to Show', 'elementrify'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3,
                'min'       => 1,
                'max'       => 6,
                'condition' => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'carousel_space',
            [
                'label'     => __('Space Between', 'elementrify'),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 10,
                'min'      => 0,
                'condition' => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'carousel_nav',
            [
                'label'        => __('Navigation Arrows', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'condition'    => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'carousel_pagination',
            [
                'label'        => __('Pagination Dots', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'condition'    => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'carousel_autoplay',
            [
                'label'     => __('Autoplay', 'elementrify'),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'carousel_autoplay_delay',
            [
                'label'     => __('Autoplay Delay (ms)', 'elementrify'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3000,
                'condition' => [
                    'layout' => 'carousel',
                    'carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'carousel_loop',
            [
                'label'     => __('Loop', 'elementrify'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'carousel_speed',
            [
                'label'     => __('Transition Speed (ms)', 'elementrify'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 500,
                'condition' => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'force_square',
            [
                'label'     => __('Force Square Images', 'elementrify'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => '',
                'condition' => ['layout!' => 'list'],
            ]
        );

        $this->add_control(
            'open_new_tab',
            [
                'label'   => __('Open Links in New Tab', 'elementrify'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'overlay_links',
            [
                'label'   => __('Overlay Links to Post', 'elementrify'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_content_elements_section(): void
    {
        $this->start_controls_section(
            'section_elements',
            [
                'label' => __('Content Elements', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'element',
            [
                'label'   => __('Element', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'username' => __('Username', 'elementrify'),
                    'caption'  => __('Caption', 'elementrify'),
                    'date'     => __('Date', 'elementrify'),
                    'icon'     => __('Instagram Icon', 'elementrify'),
                    'lightbox' => __('Lightbox', 'elementrify'),
                ],
            ]
        );
        $repeater->add_control(
            'location',
            [
                'label'   => __('Location', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'over',
                'options' => [
                    'over'   => __('Over Media', 'elementrify'),
                    'below'  => __('Below Media', 'elementrify'),
                ],
            ]
        );
        $repeater->add_control(
            'align',
            [
                'label'   => __('Align', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'elements',
            [
                'label'       => __('Elements', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['element' => 'icon', 'location' => 'over', 'align' => 'center'],
                    ['element' => 'date', 'location' => 'below', 'align' => 'left'],
                    ['element' => 'caption', 'location' => 'below', 'align' => 'left'],
                ],
                'title_field' => '{{{ element }}}',
            ]
        );

        $this->add_control(
            'caption_words',
            [
                'label'   => __('Caption Word Count', 'elementrify'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 15,
                'min'     => 1,
            ]
        );

        $this->end_controls_section();
    }

    protected function register_content_follow_section(): void
    {
        $this->start_controls_section(
            'section_follow',
            [
                'label' => __('Follow Button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_follow',
            [
                'label'   => __('Show Follow Button', 'elementrify'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'follow_text',
            [
                'label'     => __('Button Text', 'elementrify'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Follow on Instagram', 'elementrify'),
                'condition' => ['show_follow' => 'yes'],
            ]
        );

        $this->add_control(
            'follow_url',
            [
                'label'       => __('Button URL', 'elementrify'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://instagram.com/',
                'condition'   => ['show_follow' => 'yes'],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_layout_section(): void
    {
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __('Layout', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'list_media_width',
            [
                'label'      => __('List Media Width (%)', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'      => ['%' => ['min' => 20, 'max' => 60]],
                'default'    => ['size' => 35],
                'selectors'  => [
                    '{{WRAPPER}}.elementrify-instagram-feed--list .elementrify-instagram-feed__media-wrap' => 'width: {{SIZE}}%;',
                ],
                'condition'  => ['layout' => 'list'],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_image_section(): void
    {
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Image', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_effect',
            [
                'label'   => __('Hover Effect', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'zoom-in',
                'options' => [
                    'none'      => __('None', 'elementrify'),
                    'zoom-in'   => __('Zoom In', 'elementrify'),
                    'zoom-out'  => __('Zoom Out', 'elementrify'),
                    'grayscale' => __('Grayscale', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label'      => __('Border Radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 50]],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-instagram-feed__media-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-instagram-feed__media-wrap img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_overlay_section(): void
    {
        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => __('Overlay', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'overlay_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementrify-instagram-feed__overlay',
                'fields_options' => [
                    'color' => ['default' => 'rgba(0,0,0,0.35)'],
                ],
            ]
        );

        $this->add_control(
            'overlay_blend',
            [
                'label'   => __('Blend Mode', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => __('Normal', 'elementrify'),
                    'multiply' => __('Multiply', 'elementrify'),
                    'screen' => __('Screen', 'elementrify'),
                    'overlay' => __('Overlay', 'elementrify'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-instagram-feed__overlay' => 'mix-blend-mode: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_caption_section(): void
    {
        $this->start_controls_section(
            'section_style_caption',
            [
                'label' => __('Caption & Date', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'caption_typography',
                'selector' => '{{WRAPPER}} .elementrify-instagram-feed__caption',
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label'     => __('Caption Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-instagram-feed__caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label'     => __('Date Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-instagram-feed__date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_carousel_section(): void
    {
        $this->start_controls_section(
            'section_style_carousel',
            [
                'label'     => __('Carousel', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['layout' => 'carousel'],
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label'     => __('Arrows Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-instagram-feed .swiper-button-next, {{WRAPPER}} .elementrify-instagram-feed .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Pagination Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-instagram-feed .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label'     => __('Pagination Active', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-instagram-feed .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render_instagram_item(array $item, array $settings, bool $overlay_links, string $new_tab, string $extra_class = ''): void
    {
        $permalink = $item['permalink'] ?? '#';
        $media_url = $item['media_url'] ?? '';
        $thumbnail_url = $item['thumbnail_url'] ?? $media_url;
        $media_type = $item['media_type'] ?? 'IMAGE';
        $img_src = ($media_type === 'VIDEO') ? $thumbnail_url : $media_url;
        if ($img_src === '') {
            return;
        }
        ?>
        <figure class="elementrify-instagram-feed__figure">
            <div class="elementrify-instagram-feed__media-wrap<?php echo esc_attr($extra_class); ?>">
                <?php if ($overlay_links) : ?>
                    <a href="<?php echo esc_url($permalink); ?>" target="<?php echo esc_attr($new_tab); ?>" rel="noopener noreferrer" class="elementrify-instagram-feed__overlay-link" aria-label="<?php esc_attr_e('View post', 'elementrify'); ?>"></a>
                <?php endif; ?>
                <img src="<?php echo esc_url($img_src); ?>" alt="" class="elementrify-instagram-feed__img" loading="lazy" />
                <div class="elementrify-instagram-feed__overlay">
                    <?php $this->render_elements_at_location('over', $settings, $item); ?>
                </div>
            </div>
            <figcaption class="elementrify-instagram-feed__below">
                <?php $this->render_elements_at_location('below', $settings, $item); ?>
            </figcaption>
        </figure>
        <?php
    }

    public function render_elements_at_location(string $location, array $settings, array $item): void
    {
        $elements = isset($settings['elements']) && is_array($settings['elements']) ? $settings['elements'] : [];
        $caption_words = isset($settings['caption_words']) ? max(1, (int) $settings['caption_words']) : 15;
        $new_tab = !empty($settings['open_new_tab']) ? '_blank' : '_self';
        $permalink = $item['permalink'] ?? '#';
        $username = $item['username'] ?? '';
        $caption = $item['caption'] ?? '';
        $timestamp = $item['timestamp'] ?? '';
        $media_url = $item['media_url'] ?? '';
        $thumbnail_url = $item['thumbnail_url'] ?? $media_url;
        $media_type = $item['media_type'] ?? 'IMAGE';

        foreach ($elements as $el) {
            $loc = $el['location'] ?? 'over';
            if ($loc !== $location) {
                continue;
            }
            $align = $el['align'] ?? 'center';
            $type = $el['element'] ?? '';
            $class = 'elementrify-instagram-feed__el elementrify-instagram-feed__el--' . esc_attr($type) . ' elementrify-instagram-feed__el--' . esc_attr($align);
            echo '<div class="' . $class . '">';
            if ($type === 'username' && $username !== '') {
                echo '<a href="' . esc_url($permalink) . '" target="' . esc_attr($new_tab) . '" rel="noopener noreferrer" class="elementrify-instagram-feed__username">' . esc_html($username) . '</a>';
            } elseif ($type === 'caption' && $caption !== '') {
                echo '<p class="elementrify-instagram-feed__caption">' . esc_html(wp_trim_words($caption, $caption_words)) . '</p>';
            } elseif ($type === 'date' && $timestamp !== '') {
                echo '<span class="elementrify-instagram-feed__date">' . esc_html(date_i18n(get_option('date_format'), strtotime($timestamp))) . '</span>';
            } elseif ($type === 'icon') {
                echo '<a href="' . esc_url($permalink) . '" target="' . esc_attr($new_tab) . '" rel="noopener noreferrer" class="elementrify-instagram-feed__icon" aria-label="Instagram">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
                echo '</a>';
            } elseif ($type === 'lightbox') {
                $src = ($media_type === 'VIDEO') ? $thumbnail_url : $media_url;
                echo '<a href="' . esc_url($src) . '" class="elementrify-instagram-feed__lightbox" data-elementor-lightbox="single" data-elementor-lightbox-title="' . esc_attr(wp_trim_words($caption, 10)) . '" aria-label="' . esc_attr__('View', 'elementrify') . '">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>';
                echo '</a>';
            }
            echo '</div>';
        }
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $token = isset($settings['access_token']) ? trim($settings['access_token']) : '';

        if ($token === '') {
            if (current_user_can('manage_options')) {
                echo '<div class="elementrify-instagram-feed elementrify-instagram-feed--empty"><p class="elementrify-instagram-feed__notice">' . esc_html__('Please add an Instagram Access Token in the widget settings.', 'elementrify') . '</p></div>';
            }
            return;
        }

        $items = $this->fetch_instagram_feed($token, $settings);
        if (empty($items)) {
            if (current_user_can('manage_options')) {
                echo '<div class="elementrify-instagram-feed elementrify-instagram-feed--empty"><p class="elementrify-instagram-feed__notice">' . esc_html__('No posts found. Check your token and try again.', 'elementrify') . '</p></div>';
            }
            return;
        }

        $path = ELEMENTRIFY_DIR . 'templates/instagram-feed/instagram-feed.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <# if (!settings.access_token || settings.access_token.trim() === '') { #>
            <div class="elementrify-instagram-feed elementrify-instagram-feed--empty">
                <p class="elementrify-instagram-feed__notice"><?php echo esc_html__('Please add an Instagram Access Token in the widget settings.', 'elementrify'); ?></p>
            </div>
        <# } else { #>
            <div class="elementrify-instagram-feed elementrify-instagram-feed--grid">
                <p class="elementrify-instagram-feed__notice"><?php echo esc_html__('Preview: Connect API to show posts.', 'elementrify'); ?></p>
            </div>
        <# } #>
        <?php
    }
}
