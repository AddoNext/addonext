<?php
namespace Veloria\Widgets\SocialShare;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') || exit;

class SocialShare extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-share veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-social-share';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Social Share', 'veloria');
    }

    public function get_description(): string
    {
        return __('Boost sharing buttons', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#0ea5e9';
    }

    public function get_style_depends(): array
    {
        return ['veloria-social-share'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-social-share',
            VELORIA_URL . 'assets/css/social-share.css',
            [],
            VELORIA_VERSION
        );
    }

    public static function get_share_url(string $network, string $page_url, string $custom_url = ''): string
    {
        if ($custom_url !== '') {
            return $custom_url;
        }
        $encoded = rawurlencode($page_url);
        switch ($network) {
            case 'facebook':
                return 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded;
            case 'twitter':
                return 'https://twitter.com/intent/tweet?url=' . $encoded;
            case 'linkedin':
                return 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded;
            case 'instagram':
                return 'https://www.instagram.com/';
            case 'youtube':
                return 'https://www.youtube.com/';
            default:
                return $page_url;
        }
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __('Title', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Share this on', 'veloria'),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'network',
            [
                'label'   => __('Network', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'facebook',
                'options' => [
                    'facebook'  => __('Facebook', 'veloria'),
                    'twitter'   => __('Twitter', 'veloria'),
                    'instagram' => __('Instagram', 'veloria'),
                    'linkedin'  => __('LinkedIn', 'veloria'),
                    'youtube'   => __('YouTube', 'veloria'),
                ],
            ]
        );
        $repeater->add_control(
            'custom_url',
            [
                'label'       => __('Custom URL', 'veloria'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('Leave empty to share current page', 'veloria'),
                'description' => __('Used for Instagram/YouTube or to override share link.', 'veloria'),
            ]
        );
        $repeater->add_control(
            'custom_color',
            [
                'label' => __('Icon background color', 'veloria'),
                'type'  => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'networks',
            [
                'label'       => __('Networks', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['network' => 'facebook'],
                    ['network' => 'twitter'],
                    ['network' => 'instagram'],
                    ['network' => 'linkedin'],
                    ['network' => 'youtube'],
                ],
                'title_field' => '{{{ network }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .veloria-social-share__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label'      => __('Spacing after title', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 40]],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-social-share__title' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icons',
            [
                'label' => __('Icons', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => __('Size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 24, 'max' => 80]],
                'default'    => ['size' => 32],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-social-share__link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => __('Spacing between icons', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 40]],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-social-share' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public static function get_network_label(string $network): string
    {
        $labels = [
            'facebook'  => __('Facebook', 'veloria'),
            'twitter'   => __('Twitter', 'veloria'),
            'instagram' => __('Instagram', 'veloria'),
            'linkedin'  => __('LinkedIn', 'veloria'),
            'youtube'   => __('YouTube', 'veloria'),
        ];
        return $labels[ $network ] ?? $network;
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/social-share/social-share.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        $network_labels = [
            'facebook' => __('Facebook', 'veloria'),
            'twitter' => __('Twitter', 'veloria'),
            'instagram' => __('Instagram', 'veloria'),
            'linkedin' => __('LinkedIn', 'veloria'),
            'youtube' => __('YouTube', 'veloria'),
        ];
        ?>
        <div class="veloria-social-share">
            <# if (settings.title) { #>
                <span class="veloria-social-share__title">{{{ settings.title }}}</span>
            <# } #>
            <# _.each(settings.networks, function(n) {
                var shareUrl = '#';
                if (n.custom_url && n.custom_url.url) {
                    shareUrl = n.custom_url.url;
                }
                var colorStyle = n.custom_color ? ' style="background-color: ' + n.custom_color + '"' : '';
                var platformLabel = n.network === 'facebook' ? '<?php echo esc_js( $network_labels['facebook'] ); ?>' : (n.network === 'twitter' ? '<?php echo esc_js( $network_labels['twitter'] ); ?>' : (n.network === 'instagram' ? '<?php echo esc_js( $network_labels['instagram'] ); ?>' : (n.network === 'linkedin' ? '<?php echo esc_js( $network_labels['linkedin'] ); ?>' : '<?php echo esc_js( $network_labels['youtube'] ); ?>')));
                var tooltipText = '<?php echo esc_attr__( 'Share on', 'veloria' ); ?> ' + platformLabel;
            #>
                <a href="<# print(shareUrl); #>" target="_blank" rel="noopener noreferrer" class="veloria-social-share__link veloria-social-share__link--{{{ n.network }}}" aria-label="<# print(tooltipText); #>"{{{ colorStyle }}}>
                    <span class="veloria-social-share__tooltip" role="tooltip"><# print(tooltipText); #></span>
                    <?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Editor template; SVG markup from get_icon_svg() uses esc_attr on classes.
                    echo $this->get_icons_svg_markup();
                    ?>
                </a>
            <# }); #>
        </div>
        <?php
    }

    public function get_icon_svg(string $network): string
    {
        $class = 'veloria-social-share__icon veloria-social-share__icon--' . $network;
        $icons = [
            'facebook'  => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            'twitter'   => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            'instagram' => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
            'linkedin'  => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            'youtube'   => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        ];
        return $icons[ $network ] ?? '';
    }

    public function get_icons_svg_markup(): string
    {
        $fb = $this->get_icon_svg('facebook');
        $tw = $this->get_icon_svg('twitter');
        $ig = $this->get_icon_svg('instagram');
        $li = $this->get_icon_svg('linkedin');
        $yt = $this->get_icon_svg('youtube');
        return "<# if (n.network === 'facebook') { #>{$fb}<# } else if (n.network === 'twitter') { #>{$tw}<# } else if (n.network === 'instagram') { #>{$ig}<# } else if (n.network === 'linkedin') { #>{$li}<# } else if (n.network === 'youtube') { #>{$yt}<# } #>";
    }
}
