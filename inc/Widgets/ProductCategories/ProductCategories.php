<?php
namespace Veloria\Widgets\ProductCategories;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined('ABSPATH') or exit;

/**
 * WooCommerce product category cards (grid or slider) with icons and imagery.
 */
class ProductCategories extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('swiper');
        wp_enqueue_style('veloria-product-categories');
        wp_enqueue_script('swiper');
        wp_enqueue_script('veloria-product-categories');
    }

    public function get_icon(): string
    {
        return 'eicon-products veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-product-categories';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Product Categories', 'veloria');
    }

    public function get_description(): string
    {
        return __('Product category cards with icons, images, grid or slider.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'veloria-product-categories'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementor-frontend', 'veloria-product-categories'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-product-categories',
            VELORIA_URL . 'assets/css/product-categories.css',
            ['swiper'],
            VELORIA_VERSION
        );

        wp_register_script(
            'veloria-product-categories',
            VELORIA_URL . 'assets/js/product-categories.js',
            ['swiper', 'elementor-frontend'],
            VELORIA_VERSION,
            true
        );
    }

    /**
     * @return array<string, string>
     */
    private function get_product_category_select_options(): array
    {
        if (!taxonomy_exists('product_cat')) {
            return [];
        }

        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        if (is_wp_error($terms) || !is_array($terms)) {
            return ['' => __('No categories found', 'veloria')];
        }

        $options = [];
        foreach ($terms as $term) {
            $options[(string) (int) $term->term_id] = sprintf('%s (ID %d)', $term->name, (int) $term->term_id);
        }

        return $options !== [] ? $options : ['' => __('No categories found', 'veloria')];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function get_default_category_repeater_items(): array
    {
        if (!taxonomy_exists('product_cat')) {
            return [];
        }

        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => 0,
            'number' => 16,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        if (is_wp_error($terms) || $terms === []) {
            $terms = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'number' => 16,
                'orderby' => 'name',
                'order' => 'ASC',
            ]);
        }

        if (is_wp_error($terms) || !is_array($terms)) {
            return [];
        }

        $icons = [
            ['value' => 'fas fa-laptop', 'library' => 'fa-solid'],
            ['value' => 'fas fa-tshirt', 'library' => 'fa-solid'],
            ['value' => 'fas fa-house', 'library' => 'fa-solid'],
            ['value' => 'fas fa-dumbbell', 'library' => 'fa-solid'],
            ['value' => 'fas fa-car', 'library' => 'fa-solid'],
            ['value' => 'fas fa-book', 'library' => 'fa-solid'],
            ['value' => 'fas fa-heart', 'library' => 'fa-solid'],
            ['value' => 'fas fa-gamepad', 'library' => 'fa-solid'],
        ];

        $colors = ['#2563eb', '#7c3aed', '#16a34a', '#ea580c', '#dc2626', '#0f766e', '#db2777', '#ca8a04'];

        $rows = [];
        foreach ($terms as $i => $term) {
            $rows[] = [
                'row_name' => $term->name,
                'category_id' => (string) (int) $term->term_id,
                'item_icon' => $icons[$i % count($icons)],
                'icon_color' => $colors[$i % count($colors)],
                'override_image' => '',
                'custom_image' => ['id' => '', 'url' => ''],
                'hide_image' => '',
            ];
        }

        return $rows;
    }

    public function register_controls(): void
    {
        $category_options = $this->get_product_category_select_options();

        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'veloria'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('layout_mode', [
            'label' => __('Layout', 'veloria'),
            'type' => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid' => __('Grid', 'veloria'),
                'slider' => __('Slider', 'veloria'),
            ],
        ]);

        $this->add_control('grid_columns', [
            'label' => __('Column count', 'veloria'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
            'default' => '4',
            'description' => __('Number of cards per row in grid layout (wide screens).', 'veloria'),
            'condition' => ['layout_mode' => 'grid'],
        ]);

        $this->add_control('grid_gap', [
            'label' => __('Gap', 'veloria'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 64]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'condition' => ['layout_mode' => 'grid'],
        ]);

        $this->add_control('slides_desktop', [
            'label' => __('Slides per view (desktop)', 'veloria'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 6,
            'default' => 4,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slides_tablet', [
            'label' => __('Slides per view (tablet)', 'veloria'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 4,
            'default' => 2,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slides_mobile', [
            'label' => __('Slides per view (mobile)', 'veloria'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 3,
            'default' => 1,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slide_gap', [
            'label' => __('Space between slides', 'veloria'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 48]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slider_nav', [
            'label' => __('Navigation', 'veloria'),
            'type' => Controls_Manager::SELECT,
            'default' => 'both',
            'options' => [
                'none' => __('None', 'veloria'),
                'arrows' => __('Arrows', 'veloria'),
                'dots' => __('Dots', 'veloria'),
                'both' => __('Arrows & dots', 'veloria'),
            ],
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slider_loop', [
            'label' => __('Loop', 'veloria'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_content', [
            'label' => __('Categories', 'veloria'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_count', [
            'label' => __('Product count', 'veloria'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'label_on' => __('Show', 'veloria'),
            'label_off' => __('Hide', 'veloria'),
            'description' => __('Toggle the “X products” line under the category title.', 'veloria'),
        ]);

        $this->add_control('image_hover_effect', [
            'label' => __('Image hover effect', 'veloria'),
            'type' => Controls_Manager::SELECT,
            'default' => 'zoom-in',
            'options' => [
                'none' => __('None', 'veloria'),
                'zoom-in' => __('Zoom in', 'veloria'),
                'zoom-out' => __('Zoom out', 'veloria'),
                'jump' => __('Jump', 'veloria'),
                'tilt' => __('Tilt', 'veloria'),
            ],
        ]);

        $repeater = new Repeater();

        $repeater->add_control('row_name', [
            'label' => __('List label (editor)', 'veloria'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Helps identify this row in the panel.', 'veloria'),
        ]);

        $first_cat = '';
        foreach (array_keys($category_options) as $cid) {
            $first_cat = (string) $cid;
            break;
        }

        $repeater->add_control('category_id', [
            'label' => __('Category', 'veloria'),
            'type' => Controls_Manager::SELECT,
            'options' => $category_options,
            'default' => $first_cat,
        ]);

        $repeater->add_control('item_icon', [
            'label' => __('Icon', 'veloria'),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-folder',
                'library' => 'fa-solid',
            ],
        ]);

        $repeater->add_control('icon_color', [
            'label' => __('Icon color', 'veloria'),
            'type' => Controls_Manager::COLOR,
            'default' => '#2563eb',
        ]);

        $repeater->add_control('override_image', [
            'label' => __('Override category image', 'veloria'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'description' => __('When off, the category thumbnail from WooCommerce is used.', 'veloria'),
        ]);

        $repeater->add_control('custom_image', [
            'label' => __('Custom image', 'veloria'),
            'type' => Controls_Manager::MEDIA,
            'condition' => ['override_image' => 'yes'],
        ]);

        $repeater->add_control('hide_image', [
            'label' => __('Hide background image', 'veloria'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'description' => __('Shows a colored underlay using the icon color instead.', 'veloria'),
        ]);

        $this->add_control('category_items', [
            'label' => __('Category cards', 'veloria'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => self::get_default_category_repeater_items(),
            'title_field' => '{{{ row_name }}} ({{{ category_id }}})',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_card_style', [
            'label' => __('Card', 'veloria'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_min_height', [
            'label' => __('Min height', 'veloria'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 120, 'max' => 400]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .veloria-pc-card' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label' => __('Border radius', 'veloria'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 32]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .veloria-pc-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .veloria-pc-card__media' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_title_style', [
            'label' => __('Title', 'veloria'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .veloria-pc-card__title',
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'veloria'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-pc-card__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_count_style', [
            'label' => __('Count', 'veloria'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'count_typography',
            'selector' => '{{WRAPPER}} .veloria-pc-card__count',
        ]);

        $this->add_control('count_color', [
            'label' => __('Color', 'veloria'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.92)',
            'selectors' => [
                '{{WRAPPER}} .veloria-pc-card__count' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/product-categories/product-categories.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
