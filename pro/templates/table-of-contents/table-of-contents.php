<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_s = $this->get_settings_for_display();

$veloria_showTitle = isset($veloria_s['show_title']) && $veloria_s['show_title'] === 'yes';
$veloria_title     = isset($veloria_s['title']) ? (string) $veloria_s['title'] : __('Contents', 'veloria');
$veloria_sticky    = !isset($veloria_s['sticky_enable']) || $veloria_s['sticky_enable'] === 'yes';
$veloria_offset    = isset($veloria_s['scroll_offset']['size']) ? (int) $veloria_s['scroll_offset']['size'] : 96;
$veloria_selector  = isset($veloria_s['text_match_heading_selector']) ? trim((string) $veloria_s['text_match_heading_selector']) : 'h1, h2, h3, h4, h5, h6';
if ($veloria_selector === '') {
    $veloria_selector = 'h1, h2, h3, h4, h5, h6';
}

$veloria_sections = isset($veloria_s['toc_sections']) && is_array($veloria_s['toc_sections']) ? $veloria_s['toc_sections'] : [];

$veloria_tocClasses = ['veloria-toc'];
if ($veloria_sticky) {
    $veloria_tocClasses[] = 'veloria-toc--sticky';
}

$veloria_widgetId = 'veloria-toc-' . $this->get_id();
?>
<nav
    id="<?php echo esc_attr($veloria_widgetId); ?>"
    class="<?php echo esc_attr(implode(' ', $veloria_tocClasses)); ?>"
    aria-label="<?php echo esc_attr__('Table of contents', 'veloria'); ?>"
    data-scroll-offset="<?php echo esc_attr((string) max(0, $veloria_offset)); ?>"
    data-text-selector="<?php echo esc_attr($veloria_selector); ?>"
>
<?php
if ($veloria_showTitle && $veloria_title !== '') :
    ?>
    <h3 class="veloria-toc__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php
endif;
?>
    <ol class="veloria-toc__list" role="list">
<?php
$veloria_major = 0;
foreach ($veloria_sections as $veloria_section) {
    if (!is_array($veloria_section)) {
        continue;
    }
    $veloria_majorLabel = isset($veloria_section['section_title']) ? trim((string) $veloria_section['section_title']) : '';
    if ($veloria_majorLabel === '') {
        continue;
    }
    ++$veloria_major;
    $veloria_linkType = isset($veloria_section['link_type']) ? (string) $veloria_section['link_type'] : 'element_id';
    $veloria_elId     = isset($veloria_section['element_id']) ? trim((string) $veloria_section['element_id']) : '';
    $veloria_match    = isset($veloria_section['match_text']) ? trim((string) $veloria_section['match_text']) : '';
    $veloria_key      = 'm' . $veloria_major;

    $veloria_subItems = isset($veloria_section['sub_items']) && is_array($veloria_section['sub_items']) ? $veloria_section['sub_items'] : [];
    ?>
        <li class="veloria-toc__group">
            <div class="veloria-toc__row">
                <span class="veloria-toc__badge" aria-hidden="true"><?php echo esc_html((string) $veloria_major); ?></span>
    <?php
    if ($veloria_linkType === 'text' && $veloria_match !== '') :
        ?>
                <button
                    type="button"
                    class="veloria-toc__link veloria-toc__link--l1"
                    data-toc-key="<?php echo esc_attr($veloria_key); ?>"
                    data-toc-type="text"
                    data-match-text="<?php echo esc_attr($veloria_match); ?>"
                >
                    <span class="veloria-toc__label"><?php echo esc_html($veloria_majorLabel); ?></span>
                </button>
        <?php
    elseif ($veloria_elId !== '') :
        ?>
                <a
                    class="veloria-toc__link veloria-toc__link--l1"
                    href="#<?php echo esc_attr($veloria_elId); ?>"
                    data-toc-key="<?php echo esc_attr($veloria_key); ?>"
                    data-toc-type="id"
                    data-target-id="<?php echo esc_attr($veloria_elId); ?>"
                >
                    <span class="veloria-toc__label"><?php echo esc_html($veloria_majorLabel); ?></span>
                </a>
        <?php
    else :
        ?>
                <span class="veloria-toc__link veloria-toc__link--l1 veloria-toc__link--static" data-toc-key="<?php echo esc_attr($veloria_key); ?>">
                    <span class="veloria-toc__label"><?php echo esc_html($veloria_majorLabel); ?></span>
                </span>
        <?php
    endif;
    ?>
            </div>
    <?php
    if ($veloria_subItems !== []) :
        ?>
            <ol class="veloria-toc__sub" role="list">
        <?php
        $veloria_minor = 0;
        foreach ($veloria_subItems as $veloria_sub) {
            if (!is_array($veloria_sub)) {
                continue;
            }
            $veloria_subLabel = isset($veloria_sub['item_title']) ? trim((string) $veloria_sub['item_title']) : '';
            if ($veloria_subLabel === '') {
                continue;
            }
            ++$veloria_minor;
            $veloria_sType = isset($veloria_sub['link_type']) ? (string) $veloria_sub['link_type'] : 'element_id';
            $veloria_sId   = isset($veloria_sub['element_id']) ? trim((string) $veloria_sub['element_id']) : '';
            $veloria_sMatch = isset($veloria_sub['match_text']) ? trim((string) $veloria_sub['match_text']) : '';
            $veloria_index  = $veloria_major . '.' . $veloria_minor;
            $veloria_sKey   = 'm' . $veloria_major . '-s' . $veloria_minor;
            ?>
                <li>
            <?php
            if ($veloria_sType === 'text' && $veloria_sMatch !== '') :
                ?>
                    <button
                        type="button"
                        class="veloria-toc__link veloria-toc__link--l2"
                        data-toc-key="<?php echo esc_attr($veloria_sKey); ?>"
                        data-toc-type="text"
                        data-match-text="<?php echo esc_attr($veloria_sMatch); ?>"
                    >
                        <span class="veloria-toc__index"><?php echo esc_html($veloria_index); ?></span>
                        <span class="veloria-toc__label"><?php echo esc_html($veloria_subLabel); ?></span>
                    </button>
                <?php
            elseif ($veloria_sId !== '') :
                ?>
                    <a
                        class="veloria-toc__link veloria-toc__link--l2"
                        href="#<?php echo esc_attr($veloria_sId); ?>"
                        data-toc-key="<?php echo esc_attr($veloria_sKey); ?>"
                        data-toc-type="id"
                        data-target-id="<?php echo esc_attr($veloria_sId); ?>"
                    >
                        <span class="veloria-toc__index"><?php echo esc_html($veloria_index); ?></span>
                        <span class="veloria-toc__label"><?php echo esc_html($veloria_subLabel); ?></span>
                    </a>
                <?php
            else :
                ?>
                    <span class="veloria-toc__link veloria-toc__link--l2 veloria-toc__link--static" data-toc-key="<?php echo esc_attr($veloria_sKey); ?>">
                        <span class="veloria-toc__index"><?php echo esc_html($veloria_index); ?></span>
                        <span class="veloria-toc__label"><?php echo esc_html($veloria_subLabel); ?></span>
                    </span>
                <?php
            endif;
            ?>
                </li>
            <?php
        }
        ?>
            </ol>
        <?php
    endif;
    ?>
        </li>
    <?php
}
?>
    </ol>
</nav>

<?php
    }
}
