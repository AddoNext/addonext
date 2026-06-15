<?php
namespace Veloria\Widgets\NoticeBoard;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

/**
 * Notice board: repeater items with expandable descriptions.
 */
class NoticeBoard extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_assets_in_preview']);
    }

    /**
     * Ensures styles and script load in the Elementor editor canvas.
     */
    public function enqueue_assets_in_preview(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('veloria-notice-board');
        wp_enqueue_script('veloria-notice-board');
    }

    public function get_icon(): string
    {
        return 'eicon-bullet-list veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-notice-board';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Notice Board', 'veloria');
    }

    public function get_description(): string
    {
        return __('Date, title, and expandable notices in a board layout.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#b45309';
    }

    
    public function get_required_plan(): string
    {
        return 'pro';
    }

    public function get_style_depends(): array
    {
        return ['veloria-notice-board'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-notice-board'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-notice-board',
            VELORIA_URL . 'assets/css/notice-board.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-notice-board',
            VELORIA_URL . 'assets/js/notice-board.js',
            [],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section('section_content', [
            'label' => __('Notices', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();
        $repeater->add_control('item_date', [
            'label'   => __('Date', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Apr 8, 2026', 'veloria'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('item_title', [
            'label'   => __('Title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Notice title', 'veloria'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('item_description', [
            'label'   => __('Description', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Add the full notice text here. Visitors can tap the row to read more.', 'veloria'),
            'rows'    => 4,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('notice_items', [
            'label'       => __('Items', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'item_date'        => __('Apr 8, 2026', 'veloria'),
                    'item_title'       => __('Office closed', 'veloria'),
                    'item_description' => __('The office will be closed for maintenance. Online orders still ship as usual.', 'veloria'),
                ],
                [
                    'item_date'        => __('Apr 1, 2026', 'veloria'),
                    'item_title'       => __('New parking rules', 'veloria'),
                    'item_description' => __('Visitor parking is limited to two hours. Staff permits must be visible.', 'veloria'),
                ],
            ],
            'title_field' => '{{{ item_title }}}',
        ]);

        $this->add_control('expand_all_initial', [
            'label'       => __('Show all notices expanded', 'veloria'),
            'type'        => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'     => '',
            'description' => __('When enabled, descriptions are always visible and rows are not clickable to collapse.', 'veloria'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_board', [
            'label' => __('Board', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('board_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d4b896',
            'selectors' => [
                '{{WRAPPER}} .veloria-notice-board__surface' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('board_frame', [
            'label'     => __('Frame color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#78350f',
            'selectors' => [
                '{{WRAPPER}} .veloria-notice-board__frame' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('board_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px', 'isLinked' => true,
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-notice-board__surface' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_cards', [
            'label' => __('Cards', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label'     => __('Card background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fffbeb',
            'selectors' => [
                '{{WRAPPER}} .veloria-notice-board__item-inner' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'label'    => __('Shadow', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-notice-board__item-inner',
            ]
        );

        $this->add_control('pin_color', [
            'label'     => __('Pin color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#dc2626',
            'selectors' => [
                '{{WRAPPER}} .veloria-notice-board__pin::before' => 'background: radial-gradient(circle at 30% 25%, #fca5a5, {{VALUE}} 45%, #7f1d1d 100%);',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_typography', [
            'label' => __('Typography', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'date_typography',
                'label'    => __('Date', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-notice-board__date',
            ]
        );

        $this->add_control('date_color', [
            'label'     => __('Date color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#92400e',
            'selectors' => [
                '{{WRAPPER}} .veloria-notice-board__date' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => __('Title', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-notice-board__title',
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Title color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1c1917',
            'selectors' => [
                '{{WRAPPER}} .veloria-notice-board__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'label'    => __('Description', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-notice-board__description',
            ]
        );

        $this->add_control('desc_color', [
            'label'     => __('Description color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#44403c',
            'selectors' => [
                '{{WRAPPER}} .veloria-notice-board__description' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = VELORIA_DIR . 'templates/notice-board/notice-board.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    protected function content_template(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        ?>
        <#
        var items = settings.notice_items || [];
        var expandAll = settings.expand_all_initial === 'yes';
        #>
        <div class="veloria-notice-board<# if (expandAll) { #> veloria-notice-board--expand-all<# } #>">
            <div class="veloria-notice-board__frame">
                <div class="veloria-notice-board__header-bar" aria-hidden="true"></div>
                <div class="veloria-notice-board__surface">
                    <# if (items.length === 0) { #>
                        <p class="veloria-notice-board__empty"><?php echo esc_html__('Add notices in the widget panel.', 'veloria'); ?></p>
                    <# } else { #>
                        <div class="veloria-notice-board__list">
                        <# _.each(items, function(item, index) {
                            var headId = 'nb-h-' + view.getID() + '-' + index;
                            var panelId = 'nb-p-' + view.getID() + '-' + index;
                        #>
                            <div class="veloria-notice-board__item">
                                <div class="veloria-notice-board__item-inner">
                                    <span class="veloria-notice-board__pin" aria-hidden="true"></span>
                                    <# if (expandAll) { #>
                                        <div class="veloria-notice-board__heading" id="{{ headId }}">
                                            <span class="veloria-notice-board__date">{{{ item.item_date }}}</span>
                                            <span class="veloria-notice-board__title">{{{ item.item_title }}}</span>
                                        </div>
                                        <div class="veloria-notice-board__panel" id="{{ panelId }}" role="region" aria-labelledby="{{ headId }}">
                                            <div class="veloria-notice-board__description">{{{ item.item_description }}}</div>
                                        </div>
                                    <# } else { #>
                                        <button type="button" class="veloria-notice-board__trigger" id="{{ headId }}"
                                            aria-expanded="false" aria-controls="{{ panelId }}">
                                            <span class="veloria-notice-board__date">{{{ item.item_date }}}</span>
                                            <span class="veloria-notice-board__title">{{{ item.item_title }}}</span>
                                            <span class="veloria-notice-board__chevron" aria-hidden="true"></span>
                                        </button>
                                        <div class="veloria-notice-board__panel" id="{{ panelId }}" role="region" aria-labelledby="{{ headId }}" hidden>
                                            <div class="veloria-notice-board__description">{{{ item.item_description }}}</div>
                                        </div>
                                    <# } #>
                                </div>
                            </div>
                        <# }); #>
                        </div>
                    <# } #>
                </div>
            </div>
        </div>
        <?php
                }
        }
}
}
