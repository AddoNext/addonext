<?php
namespace Veloria\Widgets\AdvancedGoogleMap;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class AdvancedGoogleMap extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-google-maps veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-advanced-google-map';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Advanced Google Map', 'veloria');
    }

    public function get_description(): string
    {
        return __('Embed map by URL, coordinates or address', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#4285f4';
    }

    public function get_style_depends(): array
    {
        return ['veloria-advanced-google-map'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-advanced-google-map'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_map',
            [
                'label' => __('Map', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'map_source',
            [
                'label'   => __('Map source', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'address',
                'options' => [
                    'url'         => __('Embed URL', 'veloria'),
                    'coordinates' => __('Coordinates', 'veloria'),
                    'address'     => __('Written address', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'map_url',
            [
                'label'       => __('Map embed URL', 'veloria'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://www.google.com/maps/embed?pb=...',
                'description' => __('Paste the embed URL from Google Maps (Share → Embed a map).', 'veloria'),
                'condition'   => ['map_source' => 'url'],
            ]
        );

        $this->add_control(
            'latitude',
            [
                'label'     => __('Latitude', 'veloria'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 51.5074,
                'condition' => ['map_source' => 'coordinates'],
            ]
        );

        $this->add_control(
            'longitude',
            [
                'label'     => __('Longitude', 'veloria'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => -0.1278,
                'condition' => ['map_source' => 'coordinates'],
            ]
        );

        $this->add_control(
            'address',
            [
                'label'     => __('Address', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('London, UK', 'veloria'),
                'condition' => ['map_source' => 'address'],
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label'     => __('Zoom', 'veloria'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => ['px' => ['min' => 1, 'max' => 20]],
                'default'   => ['size' => 14],
                'condition' => [
                    'map_source!' => 'url',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_address_block',
            [
                'label' => __('Address block', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_address_block',
            [
                'label'        => __('Show address block', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'veloria'),
                'label_off'    => __('No', 'veloria'),
                'return_value' => 'yes',
                'default'     => '',
            ]
        );

        $this->add_control(
            'address_title',
            [
                'label'     => __('Title', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Visit us', 'veloria'),
                'condition' => ['show_address_block' => 'yes'],
            ]
        );

        $this->add_control(
            'address_description',
            [
                'label'     => __('Description', 'veloria'),
                'type'      => Controls_Manager::WYSIWYG,
                'default'   => '<p>' . __('Open Monday–Friday, 9am–6pm. Free parking available.', 'veloria') . '</p>',
                'condition' => ['show_address_block' => 'yes'],
            ]
        );

        $this->add_control(
            'address_position',
            [
                'label'     => __('Position', 'veloria'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'bottom',
                'options'   => [
                    'top'    => __('Above map', 'veloria'),
                    'bottom' => __('Below map', 'veloria'),
                ],
                'condition' => ['show_address_block' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_map',
            [
                'label' => __('Map', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'map_height',
            [
                'label'      => __('Height', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => ['min' => 200, 'max' => 800],
                    'vh' => ['min' => 20, 'max' => 100],
                ],
                'default'    => ['unit' => 'px', 'size' => 400],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-advanced-google-map__frame' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'map_border_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-advanced-google-map__frame' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_address_block',
            [
                'label'     => __('Address block', 'veloria'),
                'tab'      => Controls_Manager::TAB_STYLE,
                'condition' => ['show_address_block' => 'yes'],
            ]
        );

        $this->add_control(
            'address_title_color',
            [
                'label'     => __('Title color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .veloria-advanced-google-map__address-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'address_title_typography',
                'selector' => '{{WRAPPER}} .veloria-advanced-google-map__address-title',
            ]
        );

        $this->add_control(
            'address_description_color',
            [
                'label'     => __('Description color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .veloria-advanced-google-map__address-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'address_description_typography',
                'selector' => '{{WRAPPER}} .veloria-advanced-google-map__address-desc',
            ]
        );

        $this->add_responsive_control(
            'address_block_spacing',
            [
                'label'      => __('Spacing', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 60]],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-advanced-google-map__address' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-advanced-google-map__address.is-top' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: 0;',
                    '{{WRAPPER}} .veloria-advanced-google-map__address.is-bottom' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/advanced-google-map/advanced-google-map.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var showBlock = settings.show_address_block === 'yes';
        var posClass = settings.address_position === 'top' ? 'is-top' : 'is-bottom';
        #>
        <div class="veloria-advanced-google-map" data-map-source="{{{ settings.map_source }}}" data-map-url="{{{ settings.map_url && settings.map_url.url ? settings.map_url.url : '' }}}" data-latitude="{{{ settings.latitude }}}" data-longitude="{{{ settings.longitude }}}" data-address="{{{ settings.address }}}" data-zoom="{{{ settings.zoom && settings.zoom.size ? settings.zoom.size : 14 }}}">
            <# if (showBlock && settings.address_position === 'top') { #>
            <div class="veloria-advanced-google-map__address {{ posClass }}">
                <div class="veloria-advanced-google-map__address-title">{{{ settings.address_title }}}</div>
                <# if (settings.address_description) { #><div class="veloria-advanced-google-map__address-desc">{{{ settings.address_description }}}</div><# } #>
            </div>
            <# } #>
            <iframe class="veloria-advanced-google-map__frame" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            <# if (showBlock && settings.address_position === 'bottom') { #>
            <div class="veloria-advanced-google-map__address {{ posClass }}">
                <div class="veloria-advanced-google-map__address-title">{{{ settings.address_title }}}</div>
                <# if (settings.address_description) { #><div class="veloria-advanced-google-map__address-desc">{{{ settings.address_description }}}</div><# } #>
            </div>
            <# } #>
        </div>
        <?php
    }
}
