<?php
namespace Addonext\Widgets\NewsTicker;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined('ABSPATH') || exit;

/**
 * News ticker widget with continuous single-line scrolling headlines.
 */
class NewsTicker extends WidgetBase
{
    /**
     * Ensures editor uses the live JS template for control updates.
     *
     * @param bool $with_html_content Whether to include HTML content.
     * @return array<string, mixed>
     */
    public function get_raw_data($with_html_content = false)
    {
        $data = parent::get_raw_data($with_html_content);
        if ($with_html_content && isset($data['htmlCache'])) {
            unset($data['htmlCache']);
        }

        return $data;
    }

    /**
     * Returns post type options for ticker source control.
     *
     * @return array<string, string>
     */
    public static function get_post_type_options(): array
    {
        $options = [];
        $post_types = get_post_types(
            [
                'public' => true,
            ],
            'objects'
        );
        foreach ($post_types as $post_type_name => $post_type_object) {
            if (!isset($post_type_object->label)) {
                continue;
            }
            $options[(string) $post_type_name] = (string) $post_type_object->label;
        }

        return $options;
    }

    /**
     * Builds normalized ticker entries.
     *
     * @param array<string, mixed> $settings Widget settings.
     * @return array<int, array{text: string, url: string}>
     */
    public static function build_ticker_entries(array $settings): array
    {
        $source_type = isset($settings['source_type']) ? (string) $settings['source_type'] : 'custom';
        if ($source_type === 'post_type') {
            $post_type = isset($settings['source_post_type']) ? sanitize_key((string) $settings['source_post_type']) : 'post';
            if (!post_type_exists($post_type)) {
                $post_type = 'post';
            }
            $posts_limit = isset($settings['posts_limit']) ? (int) $settings['posts_limit'] : 8;
            $posts_limit = max(1, min(50, $posts_limit));

            $query = new \WP_Query([
                'post_type'              => $post_type,
                'posts_per_page'         => $posts_limit,
                'post_status'            => 'publish',
                'ignore_sticky_posts'    => true,
                'no_found_rows'          => true,
                'orderby'                => 'date',
                'order'                  => 'DESC',
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ]);
            $entries = [];
            if (!empty($query->posts)) {
                foreach ($query->posts as $post_item) {
                    $post_id = isset($post_item->ID) ? (int) $post_item->ID : 0;
                    if ($post_id <= 0) {
                        continue;
                    }
                    $title = get_the_title($post_id);
                    if (!is_string($title) || trim($title) === '') {
                        continue;
                    }
                    $entries[] = [
                        'text' => $title,
                        'url'  => (string) get_permalink($post_id),
                    ];
                }
            }
            wp_reset_postdata();

            return $entries;
        }

        $items = isset($settings['ticker_items']) && is_array($settings['ticker_items']) ? $settings['ticker_items'] : [];
        $entries = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $text = isset($item['item_text']) ? trim((string) $item['item_text']) : '';
            if ($text === '') {
                continue;
            }
            $entries[] = [
                'text' => $text,
                'url'  => '',
            ];
        }

        return $entries;
    }

    /**
     * Returns the widget icon.
     */
    public function get_icon(): string
    {
        return 'eicon-site-search addonext-icon';
    }

    /**
     * Returns the widget unique name.
     */
    public function get_name(): string
    {
        return 'addonext-news-ticker';
    }

    /**
     * Returns the widget admin category.
     */
    public function get_admin_category(): string
    {
        return 'marketing';
    }

    /**
     * Returns the widget display title.
     */
    public function get_title(): string
    {
        return __('News Ticker', 'addonext');
    }

    /**
     * Returns the widget description.
     */
    public function get_description(): string
    {
        return __('Continuous breaking-news style ticker with repeater items.', 'addonext');
    }

    /**
     * Returns the widget brand color.
     */
    public function get_brand_color(): string
    {
        return '#dc2626';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    /**
     * Returns widget search keywords.
     *
     * @return array<int, string>
     */
    public function get_keywords(): array
    {
        return ['news', 'ticker', 'headline', 'marquee', 'breaking'];
    }

    /**
     * Returns style dependencies.
     *
     * @return array<int, string>
     */
    public function get_style_depends(): array
    {
        return ['addonext-news-ticker'];
    }

    /**
     * Registers all widget controls.
     */
    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('ticker_title', [
            'label'       => __('Title', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
            'default'     => __('Breaking News', 'addonext'),
            'dynamic'     => ['active' => true],
        ]);

        $this->add_control('source_type', [
            'label'   => __('Source', 'addonext'),
            'type'    => Controls_Manager::SELECT2,
            'default' => 'custom',
            'options' => [
                'custom'    => __('Custom', 'addonext'),
                'post_type' => __('Post Type', 'addonext'),
            ],
            'label_block' => true,
            'render_type' => 'template',
        ]);

        $repeater = new Repeater();
        $repeater->add_control('item_text', [
            'label'       => __('Text', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'dynamic'     => ['active' => true],
            'label_block' => true,
            'default'     => __('Breaking: New feature rollout starts today.', 'addonext'),
        ]);

        $this->add_control('ticker_items', [
            'label'       => __('Items', 'addonext'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                ['item_text' => __('Breaking: New feature rollout starts today.', 'addonext')],
                ['item_text' => __('Market update: Industry index closes higher.', 'addonext')],
                ['item_text' => __('Weather alert: Heavy rain expected tonight.', 'addonext')],
            ],
            'title_field' => '{{{ item_text }}}',
            'condition'   => [
                'source_type' => 'custom',
            ],
        ]);

        $this->add_control('source_post_type', [
            'label'     => __('Post type', 'addonext'),
            'type'      => Controls_Manager::SELECT2,
            'default'   => 'post',
            'options'   => self::get_post_type_options(),
            'label_block' => true,
            'render_type' => 'template',
            'condition' => [
                'source_type' => 'post_type',
            ],
        ]);

        $this->add_control('posts_limit', [
            'label'     => __('Post count', 'addonext'),
            'type'      => Controls_Manager::NUMBER,
            'min'       => 1,
            'max'       => 50,
            'step'      => 1,
            'default'   => 8,
            'render_type' => 'template',
            'condition' => [
                'source_type' => 'post_type',
            ],
        ]);

        $this->add_control('separator_text', [
            'label'       => __('Separator', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '•',
            'placeholder' => '•',
            'description' => __('Text between each ticker item.', 'addonext'),
        ]);

        $this->add_control('scroll_duration', [
            'label'      => __('Scroll duration (seconds)', 'addonext'),
            'type'       => Controls_Manager::NUMBER,
            'min'        => 5,
            'max'        => 120,
            'step'       => 1,
            'default'    => 24,
            'selectors'  => [
                '{{WRAPPER}} .addonext-news-ticker' => '--e-news-ticker-duration: {{VALUE}}s;',
            ],
        ]);

        $this->add_control('pause_on_hover', [
            'label'        => __('Pause on hover', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'addonext'),
            'label_off'    => __('No', 'addonext'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_ticker', [
            'label' => __('Ticker', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('ticker_bg_color', [
            'label'     => __('Background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .addonext-news-ticker' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('ticker_padding', [
            'label'      => __('Padding', 'addonext'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-news-ticker' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('item_gap', [
            'label'      => __('Item spacing', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'range'      => [
                'px' => ['min' => 4, 'max' => 120],
            ],
            'default'    => [
                'size' => 20,
                'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .addonext-news-ticker' => '--e-news-ticker-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_text', [
            'label' => __('Text', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .addonext-news-ticker__title',
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Title color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-news-ticker__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_background_color', [
            'label'     => __('Title background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#dc2626',
            'selectors' => [
                '{{WRAPPER}} .addonext-news-ticker' => '--e-news-ticker-title-bg: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'item_typography',
                'selector' => '{{WRAPPER}} .addonext-news-ticker__item',
            ]
        );

        $this->add_control('item_text_color', [
            'label'     => __('Item color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-news-ticker__item' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('separator_color', [
            'label'     => __('Separator color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ef4444',
            'selectors' => [
                '{{WRAPPER}} .addonext-news-ticker__separator' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
                }
        }
}

    /**
     * Renders widget output.
     */
    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $settings = $this->get_settings_for_display();
        $pause_on_hover = isset($settings['pause_on_hover']) && $settings['pause_on_hover'] === 'yes';
        $source_type = isset($settings['source_type']) ? (string) $settings['source_type'] : 'custom';
        $classes = ['addonext-news-ticker'];
        if ($pause_on_hover) {
            $classes[] = 'addonext-news-ticker--pause-hover';
        }
        if ($source_type === 'post_type') {
            $classes[] = 'addonext-news-ticker--source-post-type';
        }

        $this->add_render_attribute('wrapper', 'class', $classes);

        $path = ADDONEXT_DIR . 'pro/templates/news-ticker/news-ticker.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    /**
     * Renders live editor preview.
     */
    protected function content_template(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        ?>
        <#
        var sourceType = settings.source_type || 'custom';
        var titleText = settings.ticker_title || '';
        var items = settings.ticker_items || [];
        var separator = settings.separator_text || '•';
        var classes = 'addonext-news-ticker';
        if (settings.pause_on_hover === 'yes') {
            classes += ' addonext-news-ticker--pause-hover';
        }
        if (sourceType === 'post_type') {
            classes += ' addonext-news-ticker--source-post-type';
        }
        var previewItems = items;
        if (sourceType === 'post_type') {
            var previewPostType = settings.source_post_type || 'post';
            previewItems = [
                { item_text: '<?php echo esc_js(__('Latest', 'addonext')); ?> ' + previewPostType + ' #1' },
                { item_text: '<?php echo esc_js(__('Latest', 'addonext')); ?> ' + previewPostType + ' #2' },
                { item_text: '<?php echo esc_js(__('Latest', 'addonext')); ?> ' + previewPostType + ' #3' }
            ];
        }
        #>
        <div class="{{ classes }}">
            <# if (titleText !== '') { #>
                <span class="addonext-news-ticker__title">{{{ titleText }}}</span>
            <# } #>
            <div class="addonext-news-ticker__viewport" role="marquee" aria-label="<?php echo esc_attr__('News ticker', 'addonext'); ?>">
                <div class="addonext-news-ticker__track">
                    <div class="addonext-news-ticker__group">
                        <# if (previewItems.length === 0) { #>
                            <span class="addonext-news-ticker__item"><?php echo esc_html__('Add ticker items from the panel.', 'addonext'); ?></span>
                        <# } else { #>
                            <# _.each(previewItems, function(item) { #>
                                <span class="addonext-news-ticker__item">{{{ item.item_text }}}</span>
                                <# if (separator !== '') { #>
                                    <span class="addonext-news-ticker__separator" aria-hidden="true">{{{ separator }}}</span>
                                <# } #>
                            <# }); #>
                        <# } #>
                    </div>
                    <div class="addonext-news-ticker__group" aria-hidden="true">
                        <# if (previewItems.length === 0) { #>
                            <span class="addonext-news-ticker__item"><?php echo esc_html__('Add ticker items from the panel.', 'addonext'); ?></span>
                        <# } else { #>
                            <# _.each(previewItems, function(item) { #>
                                <span class="addonext-news-ticker__item">{{{ item.item_text }}}</span>
                                <# if (separator !== '') { #>
                                    <span class="addonext-news-ticker__separator" aria-hidden="true">{{{ separator }}}</span>
                                <# } #>
                            <# }); #>
                        <# } #>
                    </div>
                </div>
            </div>
        </div>
        <?php
                }
        }
}
}
