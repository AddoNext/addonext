<?php
namespace Addonext\Widgets\ProductCategories;

use Addonext\Base\WidgetBase;
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
        wp_enqueue_style('addonext-product-categories');
        wp_enqueue_script('swiper');
        wp_enqueue_script('addonext-product-categories');
    }

    public function get_icon(): string
    {
        return 'eicon-products addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-product-categories';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Product Categories', 'addonext');
    }

    public function get_description(): string
    {
        return __('Product category cards with icons, images, grid or slider.', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'addonext-product-categories'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementor-frontend', 'addonext-product-categories'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-product-categories',
            ADDONEXT_URL . 'assets/css/product-categories.css',
            ['swiper'],
            ADDONEXT_VERSION
        );

        wp_register_script(
            'addonext-product-categories',
            ADDONEXT_URL . 'assets/js/product-categories.js',
            ['swiper', 'elementor-frontend'],
            ADDONEXT_VERSION,
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
            return ['' => __('No categories found', 'addonext')];
        }

        $options = [];
        foreach ($terms as $term) {
            $options[(string) (int) $term->term_id] = sprintf('%s (ID %d)', $term->name, (int) $term->term_id);
        }

        return $options !== [] ? $options : ['' => __('No categories found', 'addonext')];
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
            'label' => __('Layout', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('layout_mode', [
            'label' => __('Layout', 'addonext'),
            'type' => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid' => __('Grid', 'addonext'),
                'slider' => __('Slider', 'addonext'),
            ],
        ]);

        $this->add_control('grid_columns', [
            'label' => __('Column count', 'addonext'),
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
            'description' => __('Number of cards per row in grid layout (wide screens).', 'addonext'),
            'condition' => ['layout_mode' => 'grid'],
        ]);

        $this->add_control('grid_gap', [
            'label' => __('Gap', 'addonext'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 64]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'condition' => ['layout_mode' => 'grid'],
        ]);

        $this->add_control('slides_desktop', [
            'label' => __('Slides per view (desktop)', 'addonext'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 6,
            'default' => 4,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slides_tablet', [
            'label' => __('Slides per view (tablet)', 'addonext'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 4,
            'default' => 2,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slides_mobile', [
            'label' => __('Slides per view (mobile)', 'addonext'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 3,
            'default' => 1,
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slide_gap', [
            'label' => __('Space between slides', 'addonext'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 48]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slider_nav', [
            'label' => __('Navigation', 'addonext'),
            'type' => Controls_Manager::SELECT,
            'default' => 'both',
            'options' => [
                'none' => __('None', 'addonext'),
                'arrows' => __('Arrows', 'addonext'),
                'dots' => __('Dots', 'addonext'),
                'both' => __('Arrows & dots', 'addonext'),
            ],
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->add_control('slider_loop', [
            'label' => __('Loop', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => ['layout_mode' => 'slider'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_content', [
            'label' => __('Categories', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_count', [
            'label' => __('Product count', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'label_on' => __('Show', 'addonext'),
            'label_off' => __('Hide', 'addonext'),
            'description' => __('Toggle the “X products” line under the category title.', 'addonext'),
        ]);

        $this->add_control('image_hover_effect', [
            'label' => __('Image hover effect', 'addonext'),
            'type' => Controls_Manager::SELECT,
            'default' => 'zoom-in',
            'options' => [
                'none' => __('None', 'addonext'),
                'zoom-in' => __('Zoom in', 'addonext'),
                'zoom-out' => __('Zoom out', 'addonext'),
                'jump' => __('Jump', 'addonext'),
                'tilt' => __('Tilt', 'addonext'),
            ],
        ]);

        $repeater = new Repeater();

        $repeater->add_control('row_name', [
            'label' => __('List label (editor)', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Helps identify this row in the panel.', 'addonext'),
        ]);

        $first_cat = '';
        foreach (array_keys($category_options) as $cid) {
            $first_cat = (string) $cid;
            break;
        }

        $repeater->add_control('category_id', [
            'label' => __('Category', 'addonext'),
            'type' => Controls_Manager::SELECT,
            'options' => $category_options,
            'default' => $first_cat,
        ]);

        $repeater->add_control('item_icon', [
            'label' => __('Icon', 'addonext'),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-folder',
                'library' => 'fa-solid',
            ],
        ]);

        $repeater->add_control('icon_color', [
            'label' => __('Icon color', 'addonext'),
            'type' => Controls_Manager::COLOR,
            'default' => '#2563eb',
        ]);

        $repeater->add_control('override_image', [
            'label' => __('Override category image', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'description' => __('When off, the category thumbnail from WooCommerce is used.', 'addonext'),
        ]);

        $repeater->add_control('custom_image', [
            'label' => __('Custom image', 'addonext'),
            'type' => Controls_Manager::MEDIA,
            'condition' => ['override_image' => 'yes'],
        ]);

        $repeater->add_control('hide_image', [
            'label' => __('Hide background image', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'description' => __('Shows a colored underlay using the icon color instead.', 'addonext'),
        ]);

        $this->add_control('category_items', [
            'label' => __('Category cards', 'addonext'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => self::get_default_category_repeater_items(),
            'title_field' => '{{{ row_name }}} ({{{ category_id }}})',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_card_style', [
            'label' => __('Card', 'addonext'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_min_height', [
            'label' => __('Min height', 'addonext'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 120, 'max' => 400]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .addonext-pc-card' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label' => __('Border radius', 'addonext'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 32]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .addonext-pc-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .addonext-pc-card__media' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_title_style', [
            'label' => __('Title', 'addonext'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .addonext-pc-card__title',
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'addonext'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-pc-card__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_count_style', [
            'label' => __('Count', 'addonext'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'count_typography',
            'selector' => '{{WRAPPER}} .addonext-pc-card__count',
        ]);

        $this->add_control('count_color', [
            'label' => __('Color', 'addonext'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.92)',
            'selectors' => [
                '{{WRAPPER}} .addonext-pc-card__count' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/product-categories/product-categories.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
