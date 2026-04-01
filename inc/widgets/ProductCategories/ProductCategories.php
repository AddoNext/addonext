<?php
namespace Elementrify\Widgets\ProductCategories;

use Elementrify\Base\WidgetBase;
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
        wp_enqueue_style('elementrify-product-categories');
        wp_enqueue_script('swiper');
        wp_enqueue_script('elementrify-product-categories');
    }

    public function get_icon(): string
    {
        return 'eicon-products elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-product-categories';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Product Categories', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Product category cards with icons, images, grid or slider.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'elementrify-product-categories'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementor-frontend', 'elementrify-product-categories'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-product-categories',
            ELEMENTRIFY_URL . 'assets/css/product-categories.css',
            ['swiper'],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            'elementrify-product-categories',
            ELEMENTRIFY_URL . 'assets/js/product-categories.js',
            ['swiper', 'elementor-frontend'],
            ELEMENTRIFY_VERSION,
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
            return ['' => __('No categories found', 'elementrify')];
        }

        $options = [];
        foreach ($terms as $term) {
            $options[(string) (int) $term->term_id] = sprintf('%s (ID %d)', $term->name, (int) $term->term_id);
        }

        return $options !== [] ? $options : ['' => __('No categories found', 'elementrify')];
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
            'label' => __('Layout', 'elementrify'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('layout_mode', [
            'label' => __('Layout', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid' => __('Grid', 'elementrify'),
                'slider' => __('Slider', 'elementrify'),
            ],
        ]);

        $this->add_control('grid_columns', [
            'label' => __('Column count', 'elementrify'),
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
            'description' => __('Number of cards per row in grid layout (wide screens).', 'elementrify'),
            'condition' => ['layout_mode' => 'grid'],
        ]);

        $this->add_control('grid_gap', [
            'label' => __('Gap', 'elementrify'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 64]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'condition' => ['layout_mode' => 'grid'],
        ]);

        $this->add_control('slides_desktop', [
            'label' => __('Slides per view (desktop)', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 6,
            'default' => 4,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slides_tablet', [
            'label' => __('Slides per view (tablet)', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 4,
            'default' => 2,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slides_mobile', [
            'label' => __('Slides per view (mobile)', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 3,
            'default' => 1,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slide_gap', [
            'label' => __('Space between slides', 'elementrify'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 48]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slider_nav', [
            'label' => __('Navigation', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'default' => 'both',
            'options' => [
                'none' => __('None', 'elementrify'),
                'arrows' => __('Arrows', 'elementrify'),
                'dots' => __('Dots', 'elementrify'),
                'both' => __('Arrows & dots', 'elementrify'),
            ],
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slider_loop', [
            'label' => __('Loop', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_content', [
            'label' => __('Categories', 'elementrify'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_count', [
            'label' => __('Product count', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'label_on' => __('Show', 'elementrify'),
            'label_off' => __('Hide', 'elementrify'),
            'description' => __('Toggle the “X products” line under the category title.', 'elementrify'),
        ]);

        $this->add_control('image_hover_effect', [
            'label' => __('Image hover effect', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'default' => 'zoom-in',
            'options' => [
                'none' => __('None', 'elementrify'),
                'zoom-in' => __('Zoom in', 'elementrify'),
                'zoom-out' => __('Zoom out', 'elementrify'),
                'jump' => __('Jump', 'elementrify'),
                'tilt' => __('Tilt', 'elementrify'),
            ],
        ]);

        $repeater = new Repeater();

        $repeater->add_control('row_name', [
            'label' => __('List label (editor)', 'elementrify'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Helps identify this row in the panel.', 'elementrify'),
        ]);

        $first_cat = '';
        foreach (array_keys($category_options) as $cid) {
            $first_cat = (string) $cid;
            break;
        }

        $repeater->add_control('category_id', [
            'label' => __('Category', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'options' => $category_options,
            'default' => $first_cat,
        ]);

        $repeater->add_control('item_icon', [
            'label' => __('Icon', 'elementrify'),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-folder',
                'library' => 'fa-solid',
            ],
        ]);

        $repeater->add_control('icon_color', [
            'label' => __('Icon color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => '#2563eb',
        ]);

        $repeater->add_control('override_image', [
            'label' => __('Override category image', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'description' => __('When off, the category thumbnail from WooCommerce is used.', 'elementrify'),
        ]);

        $repeater->add_control('custom_image', [
            'label' => __('Custom image', 'elementrify'),
            'type' => Controls_Manager::MEDIA,
            'condition' => ['override_image' => 'yes'],
        ]);

        $repeater->add_control('hide_image', [
            'label' => __('Hide background image', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'description' => __('Shows a colored underlay using the icon color instead.', 'elementrify'),
        ]);

        $this->add_control('category_items', [
            'label' => __('Category cards', 'elementrify'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => self::get_default_category_repeater_items(),
            'title_field' => '{{{ row_name }}} ({{{ category_id }}})',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_card_style', [
            'label' => __('Card', 'elementrify'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_min_height', [
            'label' => __('Min height', 'elementrify'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 120, 'max' => 400]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .elementrify-pc-card' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label' => __('Border radius', 'elementrify'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 32]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .elementrify-pc-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .elementrify-pc-card__media' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_title_style', [
            'label' => __('Title', 'elementrify'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .elementrify-pc-card__title',
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-pc-card__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_count_style', [
            'label' => __('Count', 'elementrify'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'count_typography',
            'selector' => '{{WRAPPER}} .elementrify-pc-card__count',
        ]);

        $this->add_control('count_color', [
            'label' => __('Color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.92)',
            'selectors' => [
                '{{WRAPPER}} .elementrify-pc-card__count' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-categories/product-categories.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
