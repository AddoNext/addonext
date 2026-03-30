<?php
namespace Elementrify\Widgets\AuthorBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

defined('ABSPATH') or exit;

/**
 * Author profile card with bio, social links, and statistics row.
 */
class AuthorBox extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-person elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-author-box';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Author Box', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Author bio, social icons, and stats in a card.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#2b66d9';
    }

    public function get_keywords(): array
    {
        return ['elementrify', 'author', 'bio', 'profile', 'team'];
    }

    public function get_style_depends(): array
    {
        return ['elementrify-author-box'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-author-box',
            ELEMENTRIFY_URL . 'assets/css/author-box.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('avatar', [
            'label' => __('Avatar', 'elementrify'),
            'type'  => Controls_Manager::MEDIA,
            'default' => [
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
        ]);

        $this->add_control('name', [
            'label'   => __('Name', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Sarah Johnson', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('profile_link', [
            'label'       => __('Name link', 'elementrify'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://example.com',
            'dynamic'     => ['active' => true],
        ]);

        $this->add_control('job_title', [
            'label'   => __('Job title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Senior UX Designer', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('bio', [
            'label'   => __('Bio', 'elementrify'),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 4,
            'default' => __('Passionate about creating intuitive digital experiences that delight users and drive business results. Over 8 years crafting interfaces for startups and enterprise teams.', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_social', [
            'label'        => __('Show social icons', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $socialRepeater = new Repeater();
        $socialRepeater->add_control('network', [
            'label'   => __('Network', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'twitter',
            'options' => [
                'twitter'  => __('Twitter / X', 'elementrify'),
                'linkedin' => __('LinkedIn', 'elementrify'),
                'github'   => __('GitHub', 'elementrify'),
                'website'  => __('Website', 'elementrify'),
            ],
        ]);
        $socialRepeater->add_control('selected_icon', [
            'label'            => __('Icon', 'elementrify'),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default'          => [
                'value'   => 'fab fa-twitter',
                'library' => 'fa-brands',
            ],
        ]);
        $socialRepeater->add_control('link', [
            'label'       => __('Link', 'elementrify'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://',
            'dynamic'     => ['active' => true],
        ]);

        $this->add_control('social_items', [
            'label'       => __('Social links', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $socialRepeater->get_controls(),
            'default'     => [
                [
                    'network'       => 'twitter',
                    'selected_icon' => ['value' => 'fab fa-twitter', 'library' => 'fa-brands'],
                    'link'          => ['url' => '#'],
                ],
                [
                    'network'       => 'linkedin',
                    'selected_icon' => ['value' => 'fab fa-linkedin-in', 'library' => 'fa-brands'],
                    'link'          => ['url' => '#'],
                ],
                [
                    'network'       => 'github',
                    'selected_icon' => ['value' => 'fab fa-github', 'library' => 'fa-brands'],
                    'link'          => ['url' => '#'],
                ],
                [
                    'network'       => 'website',
                    'selected_icon' => ['value' => 'fas fa-globe', 'library' => 'fa-solid'],
                    'link'          => ['url' => '#'],
                ],
            ],
            'title_field' => '{{{ network }}}',
            'condition'   => ['show_social' => 'yes'],
        ]);

        $this->add_control('show_divider', [
            'label'        => __('Show divider', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_stats', [
            'label'        => __('Show statistics', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $statsRepeater = new Repeater();
        $statsRepeater->add_control('stat_value', [
            'label'   => __('Value', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => '0',
            'dynamic' => ['active' => true],
        ]);
        $statsRepeater->add_control('stat_label', [
            'label'   => __('Label', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Label', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('stat_items', [
            'label'       => __('Statistics', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $statsRepeater->get_controls(),
            'default'     => [
                ['stat_value' => '150+', 'stat_label' => __('Articles', 'elementrify')],
                ['stat_value' => '25K', 'stat_label' => __('Followers', 'elementrify')],
                ['stat_value' => '8', 'stat_label' => __('Years Exp', 'elementrify')],
                ['stat_value' => '95%', 'stat_label' => __('Rating', 'elementrify')],
            ],
            'title_field' => '{{{ stat_value }}} — {{{ stat_label }}}',
            'condition'   => ['show_stats' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_background', [
            'label'     => __('Background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__card' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'label'    => __('Shadow', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-author-box__card',
            ]
        );

        $this->add_responsive_control('card_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'      => '32',
                'right'    => '36',
                'bottom'   => '32',
                'left'     => '36',
                'unit'     => 'px',
                'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-author-box__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('card_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'default'    => ['unit' => 'px', 'size' => 12],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-author-box__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_avatar', [
            'label' => __('Avatar', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('avatar_size', [
            'label'      => __('Size', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 48, 'max' => 200]],
            'default'    => ['unit' => 'px', 'size' => 112],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-author-box__avatar-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('avatar_border_width', [
            'label'      => __('Border width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['unit' => 'px', 'size' => 6],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-author-box__avatar-wrap' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
            ],
        ]);

        $this->add_control('avatar_border_color', [
            'label'     => __('Border color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__avatar-wrap' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'avatar_box_shadow',
                'label'    => __('Shadow', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-author-box__avatar-wrap',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_name', [
            'label' => __('Name & role', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'label'    => __('Name typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-author-box__name',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 20, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('name_color', [
            'label'     => __('Name color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__name' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'label'    => __('Job title typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-author-box__job',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['size' => 15, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('job_color', [
            'label'     => __('Job title color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2b66d9',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__job' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_bio', [
            'label' => __('Bio', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'bio_typography',
                'label'    => __('Typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-author-box__bio',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 15, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('bio_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4a5568',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__bio' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_social', [
            'label'     => __('Social icons', 'elementrify'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_social' => 'yes'],
        ]);

        $this->add_responsive_control('social_icon_size', [
            'label'      => __('Icon box size', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 28, 'max' => 56]],
            'default'    => ['unit' => 'px', 'size' => 40],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-author-box__social-link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('social_gap', [
            'label'      => __('Gap', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['unit' => 'px', 'size' => 10],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-author-box__social' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('social_twitter_bg', [
            'label'     => __('Twitter background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1d9bf0',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__social-link--twitter' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('social_linkedin_bg', [
            'label'     => __('LinkedIn background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0a66c2',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__social-link--linkedin' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('social_github_bg', [
            'label'     => __('GitHub background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#24292f',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__social-link--github' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('social_website_bg', [
            'label'     => __('Website background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#7c3aed',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__social-link--website' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_divider', [
            'label'     => __('Divider', 'elementrify'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_divider' => 'yes'],
        ]);

        $this->add_control('divider_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#edf2f7',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__divider' => 'border-top-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('divider_spacing', [
            'label'              => __('Spacing', 'elementrify'),
            'type'               => Controls_Manager::DIMENSIONS,
            'size_units'         => ['px'],
            'allowed_dimensions' => ['top', 'bottom'],
            'default'            => [
                'top'      => '24',
                'bottom'   => '24',
                'unit'     => 'px',
                'isLinked' => true,
            ],
            'selectors'          => [
                '{{WRAPPER}} .elementrify-author-box__divider' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_stats', [
            'label'     => __('Statistics', 'elementrify'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_stats' => 'yes'],
        ]);

        $this->add_responsive_control('stats_gap', [
            'label'      => __('Gap', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['unit' => 'px', 'size' => 16],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-author-box__stats' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'stat_value_typography',
                'label'    => __('Value typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-author-box__stat-value',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 22, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('stat_value_color', [
            'label'     => __('Value color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__stat-value' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'stat_label_typography',
                'label'    => __('Label typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-author-box__stat-label',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 13, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control('stat_label_color', [
            'label'     => __('Label color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#718096',
            'selectors' => [
                '{{WRAPPER}} .elementrify-author-box__stat-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/author-box/author-box.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var showSocial = settings.show_social === 'yes';
        var showDivider = settings.show_divider === 'yes';
        var showStats = settings.show_stats === 'yes';
        var socialItems = settings.social_items || [];
        var statItems = settings.stat_items || [];
        view.addRenderAttribute('ab_name', 'class', 'elementrify-author-box__name elementor-inline-editing');
        view.addRenderAttribute('ab_name', 'data-elementor-setting-key', 'name');
        view.addRenderAttribute('ab_job', 'class', 'elementrify-author-box__job elementor-inline-editing');
        view.addRenderAttribute('ab_job', 'data-elementor-setting-key', 'job_title');
        view.addRenderAttribute('ab_bio', 'class', 'elementrify-author-box__bio elementor-inline-editing');
        view.addRenderAttribute('ab_bio', 'data-elementor-setting-key', 'bio');
        view.addRenderAttribute('ab_bio', 'data-elementor-inline-editing-toolbar', 'advanced');
        #>
        <div class="elementrify-author-box">
            <div class="elementrify-author-box__card">
                <div class="elementrify-author-box__top">
                    <# if (settings.avatar && settings.avatar.url) { #>
                        <div class="elementrify-author-box__avatar-wrap">
                            <img src="{{ settings.avatar.url }}" alt="" class="elementrify-author-box__avatar" />
                        </div>
                    <# } #>
                    <div class="elementrify-author-box__main">
                        <# var nameLinkUrl = settings.profile_link && settings.profile_link.url; #>
                        <# if (nameLinkUrl) { #>
                            <h3 class="elementrify-author-box__name-heading">
                                <a href="{{ nameLinkUrl }}"
                                    class="elementrify-author-box__name elementor-inline-editing"
                                    data-elementor-setting-key="name"
                                    <# if (settings.profile_link.is_external) { #> target="_blank"<# } #>
                                    <# if (settings.profile_link.nofollow) { #> rel="nofollow"<# } #>
                                >{{{ settings.name }}}</a>
                            </h3>
                        <# } else { #>
                            <h3 {{{ view.getRenderAttributeString('ab_name') }}}>{{{ settings.name }}}</h3>
                        <# } #>
                        <p {{{ view.getRenderAttributeString('ab_job') }}}>{{{ settings.job_title }}}</p>
                        <div {{{ view.getRenderAttributeString('ab_bio') }}}>{{{ settings.bio }}}</div>

                        <# if (showSocial && socialItems.length) { #>
                            <ul class="elementrify-author-box__social">
                                <# _.each(socialItems, function(item, index) {
                                    var net = item.network || 'twitter';
                                    var iconData = item.selected_icon || item.icon;
                                    var iconHtml = iconData && iconData.value
                                        ? elementor.helpers.renderIcon(view, iconData, {}, 'i', 'object').value
                                        : '';
                                    var linkUrl = item.link && item.link.url ? item.link.url : '#';
                                #>
                                    <li class="elementrify-author-box__social-item">
                                        <a class="elementrify-author-box__social-link elementrify-author-box__social-link--{{ net }}" href="{{ linkUrl }}" aria-label="{{ net }}">
                                            <# if (iconHtml) { #>{{{ iconHtml }}}<# } #>
                                        </a>
                                    </li>
                                <# }); #>
                            </ul>
                        <# } #>
                    </div>
                </div>

                <# if (showDivider && showStats && statItems.length) { #>
                    <div class="elementrify-author-box__divider" aria-hidden="true"></div>
                <# } #>

                <# if (showStats && statItems.length) { #>
                    <div class="elementrify-author-box__stats">
                        <# _.each(statItems, function(st, index) {
                            var vk = 'stat_items.' + index + '.stat_value';
                            var lk = 'stat_items.' + index + '.stat_label';
                            var vAttr = 'ab_sv_' + index;
                            var lAttr = 'ab_sl_' + index;
                            view.addRenderAttribute(vAttr, 'class', 'elementrify-author-box__stat-value elementor-inline-editing');
                            view.addRenderAttribute(vAttr, 'data-elementor-setting-key', vk);
                            view.addRenderAttribute(lAttr, 'class', 'elementrify-author-box__stat-label elementor-inline-editing');
                            view.addRenderAttribute(lAttr, 'data-elementor-setting-key', lk);
                        #>
                            <div class="elementrify-author-box__stat">
                                <span {{{ view.getRenderAttributeString(vAttr) }}}>{{{ st.stat_value }}}</span>
                                <span {{{ view.getRenderAttributeString(lAttr) }}}>{{{ st.stat_label }}}</span>
                            </div>
                        <# }); #>
                    </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}
