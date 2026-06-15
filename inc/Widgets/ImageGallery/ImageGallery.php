<?php
namespace Veloria\Widgets\ImageGallery;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

defined('ABSPATH') or exit;

class ImageGallery extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-gallery-grid veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-image-gallery';
    }

    public function get_admin_category(): string
    {
        return 'creative';
    }

    public function get_title(): string
    {
        return __('Image Gallery', 'veloria');
    }

    public function get_description(): string
    {
        return __('Grid and random-style image gallery layouts', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#0d9488';
    }

    public function get_style_depends(): array
    {
        return ['veloria-image-gallery'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-image-gallery'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-image-gallery',
            VELORIA_URL . 'assets/css/image-gallery.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-image-gallery',
            VELORIA_URL . 'assets/js/image-gallery.js',
            [],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('view', [
            'label'   => __('View', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid'   => __('Grid', 'veloria'),
                'random' => __('Random', 'veloria'),
            ],
        ]);

        $r = new Repeater();
        $r->add_control('image', [
            'label'   => __('Image', 'veloria'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);
        $r->add_control('caption', [
            'label'   => __('Caption', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);
        $r->add_control('title', [
            'label'   => __('Title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);
        $r->add_control('subtitle', [
            'label'   => __('Subtitle', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
        ]);

        $this->add_control('gallery', [
            'label'       => __('Images', 'veloria'),
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
            'label'     => __('Grid columns', 'veloria'),
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
            'label'     => __('Random columns', 'veloria'),
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
            'label'        => __('Enable image preview', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'veloria'),
            'label_off'    => __('No', 'veloria'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('overlay_animation', [
            'label'     => __('Overlay animation', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'fade-in',
            'options'   => [
                'fade-in'      => __('Fade in', 'veloria'),
                'fade-out'     => __('Fade out', 'veloria'),
                'slide-top'    => __('Slide top', 'veloria'),
                'slide-bottom' => __('Slide bottom', 'veloria'),
            ],
            'condition' => ['enable_preview' => 'yes'],
        ]);

        $this->add_responsive_control('gap', [
            'label'      => __('Gap', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-image-gallery' => '--veloria-image-gallery-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('border_radius', [
            'label'      => __('Image border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 0, 'max' => 40],
                '%'  => ['min' => 0, 'max' => 50],
            ],
            'default'    => ['size' => 0, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-image-gallery__image' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/image-gallery/image-gallery.php';
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
        var classes = 'veloria-image-gallery veloria-image-gallery--' + view;
        if (view === 'grid') {
            classes += ' veloria-image-gallery--cols-' + cols;
        } else {
            classes += ' veloria-image-gallery--random-cols-' + randomCols;
        }
        if (enablePreview) {
            classes += ' veloria-image-gallery--preview-enabled';
            classes += ' veloria-image-gallery--overlay-' + overlayAnimation;
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
                    <div class="veloria-image-gallery__column">
                        <# _.each(column, function(item) { #>
                            <# var imageUrl = (item.image && item.image.url) ? item.image.url : placeholder; #>
                            <div class="veloria-image-gallery__item">
                                <img class="veloria-image-gallery__image" src="{{ imageUrl }}" alt="{{ item.caption || '' }}">
                                <# if (enablePreview) { #>
                                    <button type="button" class="veloria-image-gallery__preview" data-image-url="{{ imageUrl }}" data-image-alt="{{ item.caption || '' }}" aria-label="<?php echo esc_attr__('Preview image', 'veloria'); ?>">
                                        <span class="veloria-image-gallery__overlay-content">
                                            <# if (item.title) { #><span class="veloria-image-gallery__overlay-title">{{{ item.title }}}</span><# } #>
                                            <# if (item.subtitle) { #><span class="veloria-image-gallery__overlay-subtitle">{{{ item.subtitle }}}</span><# } #>
                                        </span>
                                        <span class="veloria-image-gallery__preview-icon" aria-hidden="true">
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
                    <div class="veloria-image-gallery__item">
                        <img class="veloria-image-gallery__image" src="{{ imageUrl }}" alt="{{ item.caption || '' }}">
                        <# if (enablePreview) { #>
                            <button type="button" class="veloria-image-gallery__preview" data-image-url="{{ imageUrl }}" data-image-alt="{{ item.caption || '' }}" aria-label="<?php echo esc_attr__('Preview image', 'veloria'); ?>">
                                <span class="veloria-image-gallery__overlay-content">
                                    <# if (item.title) { #><span class="veloria-image-gallery__overlay-title">{{{ item.title }}}</span><# } #>
                                    <# if (item.subtitle) { #><span class="veloria-image-gallery__overlay-subtitle">{{{ item.subtitle }}}</span><# } #>
                                </span>
                                <span class="veloria-image-gallery__preview-icon" aria-hidden="true">
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
