<?php
namespace Elementrify\Widgets\ProductImage;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * WooCommerce product image with gallery thumbnails and zoom options.
 */
class ProductImage extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    /**
     * Loads assets inside the Elementor preview iframe.
     */
    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('elementrify-product-image');
        wp_enqueue_script('elementrify-product-image');
    }

    public function get_icon(): string
    {
        return 'eicon-product-images elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-product-image';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Product Image', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Main product image with optional gallery thumbnails, lightbox, or hover zoom.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-product-image'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'elementrify-product-image'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-product-image',
            ELEMENTRIFY_URL . 'assets/css/product-image.css',
            [],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            'elementrify-product-image',
            ELEMENTRIFY_URL . 'assets/js/product-image.js',
            ['elementor-frontend'],
            ELEMENTRIFY_VERSION,
            true
        );

        wp_localize_script(
            'elementrify-product-image',
            'elementrifyProductImage',
            [
                'prevLabel'  => __('Previous image', 'elementrify'),
                'nextLabel'  => __('Next image', 'elementrify'),
                'closeLabel' => __('Close preview', 'elementrify'),
            ]
        );
    }

    /**
     * True in Elementor editor or preview mode.
     */
    private function is_elementor_design_context(): bool
    {
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }

        $plugin = \Elementor\Plugin::$instance;

        return ($plugin->editor && $plugin->editor->is_edit_mode())
            || ($plugin->preview && $plugin->preview->is_preview_mode());
    }

    /**
     * Resolves which product to display.
     *
     * @param array<string, mixed> $settings
     */
    private function resolve_product_id(array $settings): int
    {
        $product_id = isset($settings['product_id']) ? absint($settings['product_id']) : 0;
        if ($product_id > 0) {
            return $product_id;
        }

        if (is_singular('product')) {
            return (int) get_queried_object_id();
        }

        if (function_exists('in_the_loop') && in_the_loop() && get_post_type() === 'product') {
            return (int) get_the_ID();
        }

        if ($this->is_elementor_design_context() && function_exists('wc_get_products')) {
            $products = wc_get_products([
                'limit'   => 1,
                'status'  => 'publish',
                'orderby' => 'date',
                'order'   => 'DESC',
            ]);
            if (!empty($products[0]) && is_object($products[0]) && method_exists($products[0], 'get_id')) {
                return (int) $products[0]->get_id();
            }
        }

        return 0;
    }

    /**
     * Builds ordered image data for the main area and thumbnails.
     *
     * @param \WC_Product $product
     * @return array<int, array{thumb:string,main:string,full:string,alt:string}>
     */
    public static function build_gallery_images($product): array
    {
        $ids = [];
        $featured = (int) $product->get_image_id();
        if ($featured > 0) {
            $ids[] = $featured;
        }
        foreach ($product->get_gallery_image_ids() as $gid) {
            $gid = (int) $gid;
            if ($gid > 0 && !in_array($gid, $ids, true)) {
                $ids[] = $gid;
            }
        }

        if ($ids === []) {
            $placeholder = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src('woocommerce_single') : '';
            if (!is_string($placeholder) || $placeholder === '') {
                $placeholder = includes_url('images/media/default.png');
            }

            return [
                [
                    'thumb' => $placeholder,
                    'main'  => $placeholder,
                    'full'  => $placeholder,
                    'alt'   => '',
                ],
            ];
        }

        $out = [];
        foreach ($ids as $attachment_id) {
            $thumb = wp_get_attachment_image_url($attachment_id, 'woocommerce_gallery_thumbnail');
            if (!is_string($thumb) || $thumb === '') {
                $thumb = wp_get_attachment_image_url($attachment_id, 'thumbnail') ?: '';
            }
            $main = wp_get_attachment_image_url($attachment_id, 'woocommerce_single');
            if (!is_string($main) || $main === '') {
                $main = wp_get_attachment_image_url($attachment_id, 'large') ?: '';
            }
            $full = wp_get_attachment_image_url($attachment_id, 'full');
            if (!is_string($full) || $full === '') {
                $full = $main;
            }
            $alt = (string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

            $out[] = [
                'thumb' => $thumb,
                'main'  => $main,
                'full'  => $full,
                'alt'   => $alt,
            ];
        }

        return $out;
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_product', [
            'label' => __('Product', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('product_id', [
            'label'       => __('Product ID', 'elementrify'),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 0,
            'description' => __('Leave empty on single product pages or inside a product loop to use the current product.', 'elementrify'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_gallery', [
            'label' => __('Gallery', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_thumbnails', [
            'label'        => __('Show gallery thumbnails', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('zoom_mode', [
            'label'   => __('Zoom / preview', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'lightbox',
            'options' => [
                'lightbox' => __('Lightbox on click', 'elementrify'),
                'pan'      => __('Click to zoom & drag to pan', 'elementrify'),
            ],
        ]);

        $this->add_control('pan_zoom_scale', [
            'label'       => __('Click zoom level', 'elementrify'),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 1.5,
            'max'         => 3.5,
            'step'        => 0.05,
            'default'     => 2.25,
            'description' => __('Used when “Click to zoom & drag to pan” is selected.', 'elementrify'),
            'condition'   => [
                'zoom_mode!' => 'lightbox',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_main', [
            'label' => __('Main image', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('main_bg', [
            'label'     => __('Background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f1f5f9',
            'selectors' => [
                '{{WRAPPER}} .elementrify-pi__main' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('main_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('main_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__main' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .elementrify-pi__visual' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('main_box_ratio', [
            'label'     => __('Image box ratio', 'elementrify'),
            'type'      => Controls_Manager::SELECT,
            'options'   => [
                '1 / 1'  => '1:1',
                '4 / 3'  => '4:3',
                '3 / 4'  => '3:4',
                '16 / 9' => '16:9',
            ],
            'default'   => '1 / 1',
            'selectors' => [
                '{{WRAPPER}} .elementrify-pi__stage' => 'aspect-ratio: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('main_box_max_height', [
            'label'      => __('Image box max height', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range'      => [
                'px' => ['min' => 200, 'max' => 900],
                'vh' => ['min' => 30, 'max' => 100],
            ],
            'default'    => [
                'unit' => 'px',
                'size' => 560,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__stage' => 'max-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('main_img_radius', [
            'label'      => __('Image radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 32]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__main-img' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_thumbs', [
            'label'     => __('Thumbnails', 'elementrify'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_thumbnails' => 'yes'],
        ]);

        $this->add_responsive_control('thumbs_gap', [
            'label'      => __('Gap', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 32]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__thumbs' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('thumb_width', [
            'label'      => __('Width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 48, 'max' => 160]],
            'default'    => ['size' => 80, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__thumb' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('thumb_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__thumb' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('thumb_border_color', [
            'label'     => __('Border', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e2e8f0',
            'selectors' => [
                '{{WRAPPER}} .elementrify-pi__thumb' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('thumb_border_active', [
            'label'     => __('Active border', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => [
                '{{WRAPPER}} .elementrify-pi__thumb.is-active' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('thumb_border_width', [
            'label'      => __('Border width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 1, 'max' => 4]],
            'default'    => ['size' => 2, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-pi__thumb' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        if (!class_exists('WooCommerce')) {
            echo '<div class="elementrify-pi elementrify-pi--inactive">';
            echo esc_html__('WooCommerce is required for this widget.', 'elementrify');
            echo '</div>';

            return;
        }

        $settings = $this->get_settings_for_display();
        $product_id = $this->resolve_product_id($settings);

        if ($product_id <= 0) {
            echo '<div class="elementrify-pi elementrify-pi--empty">';
            echo esc_html__('Set a product ID, or place this widget on a product page or inside a product loop.', 'elementrify');
            echo '</div>';

            return;
        }

        $product = wc_get_product($product_id);
        if (!$product || !is_a($product, 'WC_Product')) {
            echo '<div class="elementrify-pi elementrify-pi--empty">';
            echo esc_html__('Product not found.', 'elementrify');
            echo '</div>';

            return;
        }

        $gallery_images = self::build_gallery_images($product);
        $show_thumbnails = !isset($settings['show_thumbnails']) || $settings['show_thumbnails'] === 'yes';
        $raw_zoom = isset($settings['zoom_mode']) ? (string) $settings['zoom_mode'] : 'lightbox';
        $zoom_mode = ($raw_zoom === 'pan' || $raw_zoom === 'hover') ? 'pan' : 'lightbox';
        $has_multiple = count($gallery_images) > 1;
        $show_thumb_row = $show_thumbnails && $has_multiple;

        $pan_scale = isset($settings['pan_zoom_scale']) ? (float) $settings['pan_zoom_scale'] : 2.25;
        if ($pan_scale < 1.25) {
            $pan_scale = 1.25;
        }
        if ($pan_scale > 4) {
            $pan_scale = 4;
        }

        $images_json = wp_json_encode(
            $gallery_images,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );
        if (!is_string($images_json)) {
            $images_json = '[]';
        }

        $path = ELEMENTRIFY_DIR . 'templates/product-image/product-image.php';
        if (!is_readable($path)) {
            return;
        }

        include $path;
    }
}
