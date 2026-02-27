<?php
namespace Elementrify\Widgets\SmartPostList;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

class SmartPostList extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-post-list elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-smart-post-list';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Smart Post List', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Display posts in a card grid with image, category, excerpt and author', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#059669';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-smart-post-list'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_header',
            [
                'label' => __('Header', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Latest Articles', 'elementrify'),
            ]
        );

        $this->add_control(
            'sort_by',
            [
                'label'   => __('Sort by', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date_desc',
                'options' => [
                    'date_desc' => __('Most Recent', 'elementrify'),
                    'date_asc'  => __('Oldest First', 'elementrify'),
                    'title_asc' => __('Title A–Z', 'elementrify'),
                    'title_desc'=> __('Title Z–A', 'elementrify'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_query',
            [
                'label' => __('Query', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label'   => __('Post type', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => [
                    'post' => __('Posts', 'elementrify'),
                    'page' => __('Pages', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => __('Posts per page', 'elementrify'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 50,
                'default' => 9,
            ]
        );

        $this->add_control(
            'category_ids',
            [
                'label'    => __('Categories', 'elementrify'),
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
                'label' => __('Layout', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => __('Columns', 'elementrify'),
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
                    '{{WRAPPER}} .elementrify-smart-post-list__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'        => __('Show pagination', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'elementrify'),
                'label_off'    => __('No', 'elementrify'),
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_card',
            [
                'label' => __('Card', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label'        => __('Show image', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label'        => __('Show category', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label'        => __('Show date', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label'        => __('Show excerpt', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label'     => __('Excerpt length', 'elementrify'),
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
                'label'        => __('Show author', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'show_read_time',
            [
                'label'        => __('Show read time', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => __('Header', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_title_color',
            [
                'label'     => __('Title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'header_title_typography',
                'selector' => '{{WRAPPER}} .elementrify-smart-post-list__title',
            ]
        );

        $this->add_control(
            'sort_select_color',
            [
                'label'     => __('Sort dropdown color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__sort-select' => 'color: {{VALUE}};',
                ],
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
            'card_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => ['top' => 8, 'right' => 8, 'bottom' => 8, 'left' => 8, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-smart-post-list__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-smart-post-list__image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_control(
            'card_title_color',
            [
                'label'     => __('Title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'card_title_typography',
                'selector' => '{{WRAPPER}} .elementrify-smart-post-list__card-title',
            ]
        );

        $this->add_control(
            'card_excerpt_color',
            [
                'label'     => __('Excerpt color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'card_excerpt_typography',
                'selector' => '{{WRAPPER}} .elementrify-smart-post-list__excerpt',
            ]
        );

        $this->add_control(
            'card_meta_color',
            [
                'label'     => __('Meta color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__meta-date' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-smart-post-list__author-name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-smart-post-list__read-time' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_category',
            [
                'label' => __('Category tag', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'category_text_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'selector' => '{{WRAPPER}} .elementrify-smart-post-list__category',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_pagination',
            [
                'label'     => __('Pagination', 'elementrify'),
                'tab'      => Controls_Manager::TAB_STYLE,
                'condition' => ['show_pagination' => 'yes'],
            ]
        );

        $this->add_control(
            'pagination_active_bg',
            [
                'label'     => __('Active background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__page-link.is-active' => 'background-color: {{VALUE}}; color: #fff; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-smart-post-list__page-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-smart-post-list__page-prev,' .
                    '{{WRAPPER}} .elementrify-smart-post-list__page-next' => 'color: {{VALUE}};',
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
        $path = ELEMENTRIFY_DIR . 'templates/smart-post-list/smart-post-list.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <div class="elementrify-smart-post-list">
            <div class="elementrify-smart-post-list__header">
                <h2 class="elementrify-smart-post-list__title">{{{ settings.section_title }}}</h2>
                <select class="elementrify-smart-post-list__sort-select" disabled>
                    <option><# if (settings.sort_by === 'date_desc') { #><?php echo esc_attr(__('Most Recent', 'elementrify')); ?><# } #></option>
                </select>
            </div>
            <div class="elementrify-smart-post-list__grid">
                <div class="elementrify-smart-post-list__card">
                    <div class="elementrify-smart-post-list__image-wrap"></div>
                    <div class="elementrify-smart-post-list__card-body">
                        <span class="elementrify-smart-post-list__category"><?php echo esc_html__('Sample', 'elementrify'); ?></span>
                        <h3 class="elementrify-smart-post-list__card-title"><?php echo esc_html__('Article title placeholder', 'elementrify'); ?></h3>
                        <p class="elementrify-smart-post-list__excerpt"><?php echo esc_html__('Excerpt text...', 'elementrify'); ?></p>
                        <div class="elementrify-smart-post-list__footer">
                            <span class="elementrify-smart-post-list__author-name"><?php echo esc_html__('Author', 'elementrify'); ?></span>
                            <span class="elementrify-smart-post-list__read-time"><?php echo esc_html__('5 min read', 'elementrify'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
