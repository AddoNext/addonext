<?php
namespace Veloria\Widgets\SmartPostList;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

class SmartPostList extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-post-list veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-smart-post-list';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Smart Post List', 'veloria');
    }

    public function get_description(): string
    {
        return __('Display posts in a card grid with image, category, excerpt and author', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#059669';
    }

    public function get_style_depends(): array
    {
        return ['veloria-smart-post-list'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_header',
            [
                'label' => __('Header', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label'   => __('Title', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Latest Articles', 'veloria'),
            ]
        );

        $this->add_control(
            'sort_by',
            [
                'label'   => __('Sort by', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date_desc',
                'options' => [
                    'date_desc' => __('Most Recent', 'veloria'),
                    'date_asc'  => __('Oldest First', 'veloria'),
                    'title_asc' => __('Title A–Z', 'veloria'),
                    'title_desc'=> __('Title Z–A', 'veloria'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_query',
            [
                'label' => __('Query', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label'   => __('Post type', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => [
                    'post' => __('Posts', 'veloria'),
                    'page' => __('Pages', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => __('Posts per page', 'veloria'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 50,
                'default' => 9,
            ]
        );

        $this->add_control(
            'category_ids',
            [
                'label'    => __('Categories', 'veloria'),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => $this->get_post_categories(),
                'condition'=> ['post_type' => 'post'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => __('Columns', 'veloria'),
                'type'           => Controls_Manager::SELECT,
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'        => __('Show pagination', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'veloria'),
                'label_off'    => __('No', 'veloria'),
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_card',
            [
                'label' => __('Card', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label'        => __('Show image', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label'        => __('Show category', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label'        => __('Show date', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label'        => __('Show excerpt', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label'     => __('Excerpt length', 'veloria'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 10,
                'max'       => 200,
                'default'   => 80,
                'condition' => ['show_excerpt' => 'yes'],
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label'        => __('Show author', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_read_time',
            [
                'label'        => __('Show read time', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => __('Header', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_title_color',
            [
                'label'     => __('Title color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_title_typography',
                'selector' => '{{WRAPPER}} .veloria-smart-post-list__title',
            ]
        );

        $this->add_control(
            'sort_select_color',
            [
                'label'     => __('Sort dropdown color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__sort-select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => __('Cards', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => ['top' => 8, 'right' => 8, 'bottom' => 8, 'left' => 8, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-smart-post-list__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-smart-post-list__image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_control(
            'card_title_color',
            [
                'label'     => __('Title color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'card_title_typography',
                'selector' => '{{WRAPPER}} .veloria-smart-post-list__card-title',
            ]
        );

        $this->add_control(
            'card_excerpt_color',
            [
                'label'     => __('Excerpt color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'card_excerpt_typography',
                'selector' => '{{WRAPPER}} .veloria-smart-post-list__excerpt',
            ]
        );

        $this->add_control(
            'card_meta_color',
            [
                'label'     => __('Meta color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__meta-date' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-smart-post-list__author-name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-smart-post-list__read-time' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_category',
            [
                'label' => __('Category tag', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'category_text_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'selector' => '{{WRAPPER}} .veloria-smart-post-list__category',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_pagination',
            [
                'label'     => __('Pagination', 'veloria'),
                'tab'      => Controls_Manager::TAB_STYLE,
                'condition' => ['show_pagination' => 'yes'],
            ]
        );

        $this->add_control(
            'pagination_active_bg',
            [
                'label'     => __('Active background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__page-link.is-active' => 'background-color: {{VALUE}}; color: #fff; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .veloria-smart-post-list__page-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-smart-post-list__page-prev,' .
                    '{{WRAPPER}} .veloria-smart-post-list__page-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_post_categories(): array
    {
        $terms = get_terms(['taxonomy' => 'category', 'hide_empty' => true]);
        if (!is_array($terms)) {
            return [];
        }
        $out = [];
        foreach ($terms as $term) {
            $out[$term->term_id] = $term->name;
        }
        return $out;
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/smart-post-list/smart-post-list.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <div class="veloria-smart-post-list">
            <div class="veloria-smart-post-list__header">
                <h2 class="veloria-smart-post-list__title">{{{ settings.section_title }}}</h2>
                <select class="veloria-smart-post-list__sort-select" disabled>
                    <option><# if (settings.sort_by === 'date_desc') { #><?php echo esc_attr(__('Most Recent', 'veloria')); ?><# } #></option>
                </select>
            </div>
            <div class="veloria-smart-post-list__grid">
                <div class="veloria-smart-post-list__card">
                    <div class="veloria-smart-post-list__image-wrap"></div>
                    <div class="veloria-smart-post-list__card-body">
                        <span class="veloria-smart-post-list__category"><?php echo esc_html__('Sample', 'veloria'); ?></span>
                        <h3 class="veloria-smart-post-list__card-title"><?php echo esc_html__('Article title placeholder', 'veloria'); ?></h3>
                        <p class="veloria-smart-post-list__excerpt"><?php echo esc_html__('Excerpt text...', 'veloria'); ?></p>
                        <div class="veloria-smart-post-list__footer">
                            <span class="veloria-smart-post-list__author-name"><?php echo esc_html__('Author', 'veloria'); ?></span>
                            <span class="veloria-smart-post-list__read-time"><?php echo esc_html__('5 min read', 'veloria'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
