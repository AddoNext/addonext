<?php
namespace Addonext\Widgets\TableOfContents;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

/**
 * Table of contents with nested items, ID or text targets, scroll spy, optional sticky.
 */
class TableOfContents extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('addonext-table-of-contents');
        wp_enqueue_script('addonext-table-of-contents');
    }

    public function get_icon(): string
    {
        return 'eicon-table-of-contents addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-table-of-contents';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Table of Contents', 'addonext');
    }

    public function get_description(): string
    {
        return __('Nested page navigation with scroll tracking', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['addonext-table-of-contents'];
    }

    public function get_script_depends(): array
    {
        return ['addonext-table-of-contents'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-table-of-contents',
            ADDONEXT_URL . 'assets/css/table-of-contents.css',
            [],
            ADDONEXT_VERSION
        );
        wp_register_script(
            'addonext-table-of-contents',
            ADDONEXT_URL . 'assets/js/table-of-contents.js',
            [],
            ADDONEXT_VERSION,
            true
        );
    }

    protected function add_render_attributes(): void
    {
        parent::add_render_attributes();
        $settings = $this->get_settings_for_display();
        $sticky    = !isset($settings['sticky_enable']) || $settings['sticky_enable'] === 'yes';
        if ($sticky) {
            $this->add_render_attribute('_wrapper', 'class', 'addonext-toc-widget--sticky');
        }
    }

    public function register_controls(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_title', [
            'label'        => __('Show title', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Show', 'addonext'),
            'label_off'    => __('Hide', 'addonext'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('title', [
            'label'     => __('Title', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Contents', 'addonext'),
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_control('sticky_enable', [
            'label'        => __('Sticky', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'addonext'),
            'label_off'    => __('No', 'addonext'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('scroll_offset', [
            'label'      => __('Scroll offset', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 200]],
            'default'    => ['unit' => 'px', 'size' => 96],
            'description' => __('Distance from top of viewport when scrolling to a section (e.g. fixed header height).', 'addonext'),
        ]);

        $this->add_control('text_match_heading_selector', [
            'label'       => __('Text match selector', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'h1, h2, h3, h4, h5, h6',
            'description' => __('Elements to search when using “Match text” link type.', 'addonext'),
            'ai'          => ['active' => false],
        ]);

        $subRepeater = new Repeater();
        $subRepeater->add_control('item_title', [
            'label'       => __('Label', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Sub section', 'addonext'),
            'label_block' => true,
        ]);
        $subRepeater->add_control('link_type', [
            'label'   => __('Link type', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'element_id',
            'options' => [
                'element_id' => __('HTML ID', 'addonext'),
                'text'       => __('Match text', 'addonext'),
            ],
        ]);
        $subRepeater->add_control('element_id', [
            'label'       => __('Element ID', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'description' => __('Without #. Must match id on the page.', 'addonext'),
            'condition'   => ['link_type' => 'element_id'],
            'ai'          => ['active' => false],
        ]);
        $subRepeater->add_control('match_text', [
            'label'       => __('Text to find', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'description' => __('First heading (or matching element) whose text contains this string.', 'addonext'),
            'condition'   => ['link_type' => 'text'],
            'ai'          => ['active' => false],
        ]);

        $sectionRepeater = new Repeater();
        $sectionRepeater->add_control('section_title', [
            'label'       => __('Label', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Section', 'addonext'),
            'label_block' => true,
        ]);
        $sectionRepeater->add_control('link_type', [
            'label'   => __('Link type', 'addonext'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'element_id',
            'options' => [
                'element_id' => __('HTML ID', 'addonext'),
                'text'       => __('Match text', 'addonext'),
            ],
        ]);
        $sectionRepeater->add_control('element_id', [
            'label'       => __('Element ID', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'condition'   => ['link_type' => 'element_id'],
            'ai'          => ['active' => false],
        ]);
        $sectionRepeater->add_control('match_text', [
            'label'       => __('Text to find', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'condition'   => ['link_type' => 'text'],
            'ai'          => ['active' => false],
        ]);
        $sectionRepeater->add_control('sub_items', [
            'label'       => __('Sub items', 'addonext'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $subRepeater->get_controls(),
            'title_field' => '{{{ item_title }}}',
            'default'     => [
                ['item_title' => __('Overview', 'addonext'), 'link_type' => 'text', 'match_text' => 'Overview'],
                ['item_title' => __('Objectives', 'addonext'), 'link_type' => 'text', 'match_text' => 'Objectives'],
            ],
        ]);

        $this->add_control('toc_sections', [
            'label'       => __('Sections', 'addonext'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $sectionRepeater->get_controls(),
            'title_field' => '{{{ section_title }}}',
            'default'     => [
                [
                    'section_title' => __('Introduction', 'addonext'),
                    'link_type'     => 'element_id',
                    'element_id'    => 'introduction',
                    'sub_items'     => [
                        ['item_title' => __('Overview', 'addonext'), 'link_type' => 'text', 'match_text' => 'Overview'],
                        ['item_title' => __('Objectives', 'addonext'), 'link_type' => 'text', 'match_text' => 'Objectives'],
                    ],
                ],
                [
                    'section_title' => __('Methodology', 'addonext'),
                    'link_type'     => 'element_id',
                    'element_id'    => 'methodology',
                    'sub_items'     => [
                        ['item_title' => __('Research Methods', 'addonext'), 'link_type' => 'text', 'match_text' => 'Research Methods'],
                        ['item_title' => __('Data Analysis', 'addonext'), 'link_type' => 'text', 'match_text' => 'Data Analysis'],
                    ],
                ],
                [
                    'section_title' => __('Results & Discussion', 'addonext'),
                    'link_type'     => 'element_id',
                    'element_id'    => 'results',
                    'sub_items'     => [],
                ],
                [
                    'section_title' => __('Conclusion', 'addonext'),
                    'link_type'     => 'element_id',
                    'element_id'    => 'conclusion',
                    'sub_items'     => [],
                ],
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_box', [
            'label' => __('Box', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('box_background', [
            'label'     => __('Background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#eef6fc',
            'selectors' => [
                '{{WRAPPER}} .addonext-toc' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('accent_color', [
            'label'     => __('Accent (border & badge text)', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1d4ed8',
            'selectors' => [
                '{{WRAPPER}} .addonext-toc' => '--addonext-toc-accent: {{VALUE}};',
            ],
        ]);

        $this->add_control('left_border_width', [
            'label'      => __('Left border width', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 2, 'max' => 12]],
            'default'    => ['unit' => 'px', 'size' => 5],
            'selectors'  => [
                '{{WRAPPER}} .addonext-toc' => 'border-left-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('border_radius', [
            'label'      => __('Border radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 32]],
            'default'    => ['unit' => 'px', 'size' => 12],
            'selectors'  => [
                '{{WRAPPER}} .addonext-toc' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('box_padding', [
            'label'      => __('Padding', 'addonext'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'    => '20',
                'right'  => '20',
                'bottom' => '20',
                'left'   => '24',
                'unit'   => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .addonext-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('sticky_top', [
            'label'      => __('Sticky top offset', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 200]],
            'default'    => ['unit' => 'px', 'size' => 24],
            'condition'  => ['sticky_enable' => 'yes'],
            'selectors'  => [
                '{{WRAPPER}}.addonext-toc-widget--sticky' => 'top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_title', [
            'label'     => __('Title', 'addonext'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .addonext-toc__title',
        ]);

        $this->add_control('title_color', [
            'label'     => __('Color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_l1', [
            'label' => __('Level 1', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('badge_background', [
            'label'     => __('Badge background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#dbeafe',
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__badge' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'l1_typography',
            'selector' => '{{WRAPPER}} .addonext-toc__link--l1 .addonext-toc__label',
        ]);

        $this->add_control('l1_color', [
            'label'     => __('Text color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e293b',
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__link--l1 .addonext-toc__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_l2', [
            'label' => __('Level 2', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'l2_typography',
            'selector' => '{{WRAPPER}} .addonext-toc__link--l2 .addonext-toc__label',
        ]);

        $this->add_control('l2_color', [
            'label'     => __('Text color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#334155',
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__link--l2 .addonext-toc__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('l2_index_color', [
            'label'     => __('Index color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#475569',
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__index' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_active', [
            'label' => __('Active', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('active_badge_bg', [
            'label'     => __('Level 1 badge background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__link.is-active .addonext-toc__badge' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('active_l1_color', [
            'label'     => __('Level 1 text', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__link--l1.is-active .addonext-toc__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('active_l2_color', [
            'label'     => __('Level 2 text', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .addonext-toc__link--l2.is-active .addonext-toc__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        $path = ADDONEXT_DIR . 'pro/templates/table-of-contents/table-of-contents.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    protected function content_template(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        ?>
        <#
        var tocClass = 'addonext-toc';
        if ( settings.sticky_enable === 'yes' ) {
            tocClass += ' addonext-toc--sticky';
        }
        #>
        <nav class="{{ tocClass }}">
            <# if ( settings.show_title === 'yes' && settings.title ) { #>
            <h3 class="addonext-toc__title">{{{ settings.title }}}</h3>
            <# } #>
            <ol class="addonext-toc__list" role="list">
                <# _.each( settings.toc_sections, function( section, i ) { #>
                <li class="addonext-toc__group">
                    <span class="addonext-toc__row">
                        <span class="addonext-toc__badge">{{ i + 1 }}</span>
                        <span class="addonext-toc__link addonext-toc__link--l1">
                            <span class="addonext-toc__label">{{{ section.section_title }}}</span>
                        </span>
                    </span>
                    <# if ( section.sub_items && section.sub_items.length ) { #>
                    <ol class="addonext-toc__sub" role="list">
                        <# _.each( section.sub_items, function( sub, j ) { #>
                        <li>
                            <span class="addonext-toc__link addonext-toc__link--l2">
                                <span class="addonext-toc__index">{{ i + 1 }}.{{ j + 1 }}</span>
                                <span class="addonext-toc__label">{{{ sub.item_title }}}</span>
                            </span>
                        </li>
                        <# }); #>
                    </ol>
                    <# } #>
                </li>
                <# }); #>
            </ol>
        </nav>
        <?php
                }
        }
}
}
