<?php
namespace Elementrify\Widgets\IconBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') or exit;

class IconBox extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-social-icons elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-icon-box';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Icon Box', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Row of clickable social or custom icons with custom colors and hover effects.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7c3aed';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-icon-box'];
    }

    /**
     * Skip htmlCache so the editor uses the live content_template and reacts to control changes.
     */
    public function get_raw_data($with_html_content = false)
    {
        $data = parent::get_raw_data($with_html_content);
        if ($with_html_content && isset($data['htmlCache'])) {
            unset($data['htmlCache']);
        }
        return $data;
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-icon-box',
            ELEMENTRIFY_URL . 'assets/css/icon-box.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'social_icon',
            [
                'label'   => __('Icon', 'elementrify'),
                'type'    => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value'   => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
                'recommended' => [
                    'fa-brands' => [
                        'facebook-f', 'twitter', 'instagram', 'linkedin-in', 'youtube',
                        'x-twitter', 'pinterest', 'tiktok', 'whatsapp', 'telegram',
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'   => __('Link', 'elementrify'),
                'type'    => Controls_Manager::URL,
                'default' => ['is_external' => 'true'],
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'background_color',
            [
                'label'   => __('Background color', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#3B5998',
            ]
        );

        $this->add_control(
            'icon_list',
            [
                'label'       => __('Icons', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'social_icon'       => ['value' => 'fab fa-facebook-f', 'library' => 'fa-brands'],
                        'background_color'  => '#3B5998',
                    ],
                    [
                        'social_icon'       => ['value' => 'fab fa-twitter', 'library' => 'fa-brands'],
                        'background_color'  => '#1DA1F2',
                    ],
                    [
                        'social_icon'       => ['value' => 'fab fa-instagram', 'library' => 'fa-brands'],
                        'background_color'  => '#C13584',
                    ],
                    [
                        'social_icon'       => ['value' => 'fab fa-linkedin-in', 'library' => 'fa-brands'],
                        'background_color'  => '#0A66C2',
                    ],
                    [
                        'social_icon'       => ['value' => 'fab fa-youtube', 'library' => 'fa-brands'],
                        'background_color'  => '#FF0000',
                    ],
                ],
                'title_field' => '<# var icon = social_icon && social_icon.value ? social_icon.value.split(" ").pop() : ""; #>{{{ icon }}}',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'   => __('Alignment', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start'    => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-text-align-left'],
                    'center'       => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-text-align-center'],
                    'flex-end'    => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-text-align-right'],
                    'space-between' => ['title' => __('Space between', 'elementrify'), 'icon' => 'eicon-text-align-justify'],
                    'space-around'  => ['title' => __('Space around', 'elementrify'), 'icon' => 'eicon-columns'],
                ],
                'default'      => 'center',
                'selectors'    => [
                    '{{WRAPPER}} .elementrify-icon-box__list' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'flex_wrap',
            [
                'label'   => __('Flex wrap', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'wrap',
                'options' => [
                    'wrap'   => __('Wrap', 'elementrify'),
                    'nowrap' => __('No wrap', 'elementrify'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-icon-box__list' => 'flex-wrap: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => __('Spacing between icons', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => ['px' => ['min' => 0, 'max' => 80]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-icon-box__list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label'   => __('Direction', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row'    => __('Horizontal', 'elementrify'),
                    'column' => __('Vertical', 'elementrify'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-icon-box__list' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => __('Icon', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => __('Icon size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => ['px' => ['min' => 8, 'max' => 120]],
                'default'    => ['size' => 15, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-icon-box__link' => '--elementrify-ib-icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_size',
            [
                'label'      => __('Box size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => ['px' => ['min' => 16, 'max' => 200]],
                'default'    => ['size' => 30, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-icon-box__link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-icon-box__link i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-icon-box__link svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label'      => __('Padding inside circle', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => ['px' => ['min' => 0, 'max' => 60]],
                'default'    => ['size' => 7],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-icon-box__link' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 200], '%' => ['min' => 0, 'max' => 100]],
                'default'    => ['unit' => '%', 'size' => 50],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-icon-box__link' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_heading',
            [
                'label'     => __('Border', 'elementrify'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => __('Border width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 10]],
                'default'    => ['size' => 0],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-icon-box__link' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => __('Border color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-icon-box__link' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_hover',
            [
                'label' => __('Hover', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label'   => __('Hover effect', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lift',
                'options' => [
                    'none'    => __('None', 'elementrify'),
                    'scale'   => __('Scale up', 'elementrify'),
                    'lift'    => __('Lift', 'elementrify'),
                    'shrink'  => __('Shrink', 'elementrify'),
                    'opacity' => __('Opacity', 'elementrify'),
                ],
                'prefix_class' => 'elementrify-icon-box-hover-',
            ]
        );

        $this->add_control(
            'hover_primary_color',
            [
                'label'   => __('Background color on hover', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-icon-box__link:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_secondary_color',
            [
                'label'   => __('Icon color on hover', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-icon-box__link:hover i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-icon-box__link:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'hover_box_shadow',
                'label'     => __('Box shadow on hover', 'elementrify'),
                'selector'  => '{{WRAPPER}} .elementrify-icon-box__link:hover',
                'condition' => ['hover_effect!' => 'none'],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $list     = $settings['icon_list'] ?? [];
        if (empty($list)) {
            return;
        }
        $template_path = ELEMENTRIFY_DIR . 'templates/icon-box/icon-box.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var list = settings.icon_list || [];
        if (list.length === 0) return;
        function val(obj, def) {
            if (!obj || typeof obj !== 'object') return def;
            var s = obj.size;
            if (s === undefined || s === '' || s === null) return def;
            return s + (obj.unit || 'px');
        }
        var gapVal = val(settings.gap, '8px');
        var align = (settings.align && settings.align !== '') ? settings.align : 'center';
        var flexWrap = (settings.flex_wrap && settings.flex_wrap !== '') ? settings.flex_wrap : 'wrap';
        var flexDirection = (settings.flex_direction && settings.flex_direction !== '') ? settings.flex_direction : 'row';
        var iconSizeVal = val(settings.icon_size, '');
        var boxSizeVal = val(settings.box_size, '30px');
        var paddingVal = val(settings.icon_padding, '7px');
        var radiusVal = '50%';
        if (settings.border_radius && settings.border_radius.size !== undefined && settings.border_radius.size !== '') {
            radiusVal = settings.border_radius.size + (settings.border_radius.unit || '%');
        }
        var borderW = settings.border_width || {};
        var borderSize = (borderW.size !== undefined && parseFloat(borderW.size) > 0) ? parseFloat(borderW.size) : 0;
        var borderUnit = borderW.unit || 'px';
        var borderColor = (settings.border_color && settings.border_color !== '') ? settings.border_color : 'transparent';
        var iconColor = (settings.icon_color && settings.icon_color !== '') ? settings.icon_color : '#ffffff';
        var wrapperStyle = '--elementrify-ib-gap:' + gapVal + ';--elementrify-ib-align:' + align + ';--elementrify-ib-wrap:' + flexWrap + ';--elementrify-ib-direction:' + flexDirection + ';--elementrify-ib-box-size:' + boxSizeVal + ';--elementrify-ib-padding:' + paddingVal + ';--elementrify-ib-radius:' + radiusVal + ';';
        if (iconSizeVal) wrapperStyle += '--elementrify-ib-icon-size:' + iconSizeVal + ';';
        #>
        <div class="elementrify-icon-box" style="{{{ wrapperStyle }}}">
            <div class="elementrify-icon-box__list">
                <# _.each(list, function(item, index) {
                    var linkUrl = item.link && item.link.url ? item.link.url : '#',
                        bgColor = item.background_color || '#3B5998',
                        linkStyle = 'background-color:' + bgColor,
                        targetAttr = (item.link && item.link.is_external) ? ' target="_blank"' : '',
                        relAttr = (item.link && item.link.nofollow) ? ' rel="nofollow"' : '';
                    if (borderSize > 0) linkStyle += ';border:' + borderSize + borderUnit + ' solid ' + borderColor;
                    var iconHtml = item.social_icon && item.social_icon.value
                        ? elementor.helpers.renderIcon(view, item.social_icon, { 'aria-hidden': 'true' }, 'i', 'object').value
                        : '';
                #>
                <a class="elementrify-icon-box__link elementor-repeater-item-{{ item._id }}" href="{{ linkUrl }}"{{{ targetAttr }}}{{{ relAttr }}} style="{{{ linkStyle }}}">
                    <# if (iconHtml) { #><span class="elementrify-icon-box__icon-inner" style="color:{{{ iconColor }}};">{{{ iconHtml }}}</span><# } #>
                </a>
                <# } ); #>
            </div>
        </div>
        <?php
    }
}
