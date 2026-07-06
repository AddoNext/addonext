<?php
namespace Addonext\Widgets\ImageGallery;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

defined('ABSPATH') or exit;

class ImageGallery extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-gallery-grid addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-image-gallery';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Image Gallery', 'addonext');
    }

    public function get_description(): string
    {
        return __('Grid and random-style image gallery layouts', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['addonext-image-gallery'];
    }

    public function get_script_depends(): array
    {
        return ['addonext-image-gallery'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-image-gallery',
            ADDONEXT_URL . 'assets/css/image-gallery.css',
            [],
            ADDONEXT_VERSION
        );
        wp_register_script(
            'addonext-image-gallery',
            ADDONEXT_URL . 'assets/js/image-gallery.js',
            [],
            ADDONEXT_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('view', [
            'label'   => __('View', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid'   => __('Grid', 'addonext'),
                'random' => __('Random', 'addonext'),
            ],
        ]);

        $r = new Repeater();
        $r->add_control('image', [
            'label'   => __('Image', 'addonext'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);
        $r->add_control('caption', [
            'label'   => __('Caption', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);
        $r->add_control('title', [
            'label'   => __('Title', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);
        $r->add_control('subtitle', [
            'label'   => __('Subtitle', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);

        $this->add_control('gallery', [
            'label'       => __('Images', 'addonext'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $r->get_controls(),
            'default'     => [
                ['image' => ['url' => Utils::get_placeholder_image_src()], 'caption' => ''],
                ['image' => ['url' => Utils::get_placeholder_image_src()], 'caption' => ''],
                ['image' => ['url' => Utils::get_placeholder_image_src()], 'caption' => ''],
                ['image' => ['url' => Utils::get_placeholder_image_src()], 'caption' => ''],
                ['image' => ['url' => Utils::get_placeholder_image_src()], 'caption' => ''],
                ['image' => ['url' => Utils::get_placeholder_image_src()], 'caption' => ''],
            ],
            'title_field' => '{{{ title || caption }}}',
        ]);

        $this->add_control('columns', [
            'label'     => __('Grid columns', 'addonext'),
            'type'      => Controls_Manager::SELECT,
            'default'   => '3',
            'options'   => [
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
            'condition' => ['view' => 'grid'],
        ]);

        $this->add_control('random_columns', [
            'label'     => __('Random columns', 'addonext'),
            'type'      => Controls_Manager::SELECT,
            'default'   => '5',
            'options'   => [
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
            'condition' => ['view' => 'random'],
        ]);

        $this->add_control('enable_preview', [
            'label'        => __('Enable image preview', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'addonext'),
            'label_off'    => __('No', 'addonext'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('overlay_animation', [
            'label'     => __('Overlay animation', 'addonext'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'fade-in',
            'options'   => [
                'fade-in'      => __('Fade in', 'addonext'),
                'fade-out'     => __('Fade out', 'addonext'),
                'slide-top'    => __('Slide top', 'addonext'),
                'slide-bottom' => __('Slide bottom', 'addonext'),
            ],
            'condition' => ['enable_preview' => 'yes'],
        ]);

        $this->add_responsive_control('gap', [
            'label'      => __('Gap', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-image-gallery' => '--addonext-image-gallery-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('border_radius', [
            'label'      => __('Image border radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 0, 'max' => 40],
                '%'  => ['min' => 0, 'max' => 50],
            ],
            'default'    => ['size' => 0, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-image-gallery__image' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/image-gallery/image-gallery.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?>
        <#
        var view = settings.view || 'grid';
        var cols = settings.columns || '3';
        var randomCols = settings.random_columns || '5';
        var enablePreview = settings.enable_preview === 'yes';
        var overlayAnimation = settings.overlay_animation || 'fade-in';
        var classes = 'addonext-image-gallery addonext-image-gallery--' + view;
        if (view === 'grid') {
            classes += ' addonext-image-gallery--cols-' + cols;
        } else {
            classes += ' addonext-image-gallery--random-cols-' + randomCols;
        }
        if (enablePreview) {
            classes += ' addonext-image-gallery--preview-enabled';
            classes += ' addonext-image-gallery--overlay-' + overlayAnimation;
        }
        var items = settings.gallery || [];
        var placeholder = '<?php echo esc_url(Utils::get_placeholder_image_src()); ?>';
        #>
        <div class="{{ classes }}">
            <# if (view === 'random') { #>
                <#
                var columnCount = parseInt(randomCols, 10);
                if (!columnCount || columnCount < 2) columnCount = 5;
                var grouped = [];
                for (var c = 0; c < columnCount; c++) {
                    grouped.push([]);
                }
                _.each(items, function(item, i) {
                    grouped[i % columnCount].push(item);
                });
                #>
                <# _.each(grouped, function(column) { #>
                    <div class="addonext-image-gallery__column">
                        <# _.each(column, function(item) { #>
                            <# var imageUrl = (item.image && item.image.url) ? item.image.url : placeholder; #>
                            <div class="addonext-image-gallery__item">
                                <img class="addonext-image-gallery__image" src="{{ imageUrl }}" alt="{{ item.caption || '' }}">
                                <# if (enablePreview) { #>
                                    <button type="button" class="addonext-image-gallery__preview" data-image-url="{{ imageUrl }}" data-image-alt="{{ item.caption || '' }}" aria-label="<?php echo esc_attr__('Preview image', 'addonext'); ?>">
                                        <span class="addonext-image-gallery__overlay-content">
                                            <# if (item.title) { #><span class="addonext-image-gallery__overlay-title">{{{ item.title }}}</span><# } #>
                                            <# if (item.subtitle) { #><span class="addonext-image-gallery__overlay-subtitle">{{{ item.subtitle }}}</span><# } #>
                                        </span>
                                        <span class="addonext-image-gallery__preview-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.5 12C4.3 8.4 7.8 6 12 6C16.2 6 19.7 8.4 21.5 12C19.7 15.6 16.2 18 12 18C7.8 18 4.3 15.6 2.5 12Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"></circle></svg>
                                        </span>
                                    </button>
                                <# } #>
                            </div>
                        <# }); #>
                    </div>
                <# }); #>
            <# } else { #>
                <# _.each(items, function(item) { #>
                    <# var imageUrl = (item.image && item.image.url) ? item.image.url : placeholder; #>
                    <div class="addonext-image-gallery__item">
                        <img class="addonext-image-gallery__image" src="{{ imageUrl }}" alt="{{ item.caption || '' }}">
                        <# if (enablePreview) { #>
                            <button type="button" class="addonext-image-gallery__preview" data-image-url="{{ imageUrl }}" data-image-alt="{{ item.caption || '' }}" aria-label="<?php echo esc_attr__('Preview image', 'addonext'); ?>">
                                <span class="addonext-image-gallery__overlay-content">
                                    <# if (item.title) { #><span class="addonext-image-gallery__overlay-title">{{{ item.title }}}</span><# } #>
                                    <# if (item.subtitle) { #><span class="addonext-image-gallery__overlay-subtitle">{{{ item.subtitle }}}</span><# } #>
                                </span>
                                <span class="addonext-image-gallery__preview-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.5 12C4.3 8.4 7.8 6 12 6C16.2 6 19.7 8.4 21.5 12C19.7 15.6 16.2 18 12 18C7.8 18 4.3 15.6 2.5 12Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"></circle></svg>
                                </span>
                            </button>
                        <# } #>
                    </div>
                <# }); #>
            <# } #>
        </div>
        <?php
    }
}
