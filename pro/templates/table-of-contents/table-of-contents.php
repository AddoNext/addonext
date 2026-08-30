<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_s = $this->get_settings_for_display();

$addonext_showTitle = isset($addonext_s['show_title']) && $addonext_s['show_title'] === 'yes';
$addonext_title     = isset($addonext_s['title']) ? (string) $addonext_s['title'] : __('Contents', 'addonext');
$addonext_sticky    = !isset($addonext_s['sticky_enable']) || $addonext_s['sticky_enable'] === 'yes';
$addonext_offset    = isset($addonext_s['scroll_offset']['size']) ? (int) $addonext_s['scroll_offset']['size'] : 96;
$addonext_selector  = isset($addonext_s['text_match_heading_selector']) ? trim((string) $addonext_s['text_match_heading_selector']) : 'h1, h2, h3, h4, h5, h6';
if ($addonext_selector === '') {
    $addonext_selector = 'h1, h2, h3, h4, h5, h6';
}

$addonext_sections = isset($addonext_s['toc_sections']) && is_array($addonext_s['toc_sections']) ? $addonext_s['toc_sections'] : [];

$addonext_tocClasses = ['addonext-toc'];
if ($addonext_sticky) {
    $addonext_tocClasses[] = 'addonext-toc--sticky';
}

$addonext_widgetId = 'addonext-toc-' . $this->get_id();
?>
<nav
    id="<?php echo esc_attr($addonext_widgetId); ?>"
    class="<?php echo esc_attr(implode(' ', $addonext_tocClasses)); ?>"
    aria-label="<?php echo esc_attr__('Table of contents', 'addonext'); ?>"
    data-scroll-offset="<?php echo esc_attr((string) max(0, $addonext_offset)); ?>"
    data-text-selector="<?php echo esc_attr($addonext_selector); ?>"
>
<?php
if ($addonext_showTitle && $addonext_title !== '') :
    ?>
    <h3 class="addonext-toc__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php
endif;
?>
    <ol class="addonext-toc__list" role="list">
<?php
$addonext_major = 0;
foreach ($addonext_sections as $addonext_section) {
    if (!is_array($addonext_section)) {
        continue;
    }
    $addonext_majorLabel = isset($addonext_section['section_title']) ? trim((string) $addonext_section['section_title']) : '';
    if ($addonext_majorLabel === '') {
        continue;
    }
    ++$addonext_major;
    $addonext_linkType = isset($addonext_section['link_type']) ? (string) $addonext_section['link_type'] : 'element_id';
    $addonext_elId     = isset($addonext_section['element_id']) ? trim((string) $addonext_section['element_id']) : '';
    $addonext_match    = isset($addonext_section['match_text']) ? trim((string) $addonext_section['match_text']) : '';
    $addonext_key      = 'm' . $addonext_major;

    $addonext_subItems = isset($addonext_section['sub_items']) && is_array($addonext_section['sub_items']) ? $addonext_section['sub_items'] : [];
    ?>
        <li class="addonext-toc__group">
            <div class="addonext-toc__row">
                <span class="addonext-toc__badge" aria-hidden="true"><?php echo esc_html((string) $addonext_major); ?></span>
    <?php
    if ($addonext_linkType === 'text' && $addonext_match !== '') :
        ?>
                <button
                    type="button"
                    class="addonext-toc__link addonext-toc__link--l1"
                    data-toc-key="<?php echo esc_attr($addonext_key); ?>"
                    data-toc-type="text"
                    data-match-text="<?php echo esc_attr($addonext_match); ?>"
                >
                    <span class="addonext-toc__label"><?php echo esc_html($addonext_majorLabel); ?></span>
                </button>
        <?php
    elseif ($addonext_elId !== '') :
        ?>
                <a
                    class="addonext-toc__link addonext-toc__link--l1"
                    href="#<?php echo esc_attr($addonext_elId); ?>"
                    data-toc-key="<?php echo esc_attr($addonext_key); ?>"
                    data-toc-type="id"
                    data-target-id="<?php echo esc_attr($addonext_elId); ?>"
                >
                    <span class="addonext-toc__label"><?php echo esc_html($addonext_majorLabel); ?></span>
                </a>
        <?php
    else :
        ?>
                <span class="addonext-toc__link addonext-toc__link--l1 addonext-toc__link--static" data-toc-key="<?php echo esc_attr($addonext_key); ?>">
                    <span class="addonext-toc__label"><?php echo esc_html($addonext_majorLabel); ?></span>
                </span>
        <?php
    endif;
    ?>
            </div>
    <?php
    if ($addonext_subItems !== []) :
        ?>
            <ol class="addonext-toc__sub" role="list">
        <?php
        $addonext_minor = 0;
        foreach ($addonext_subItems as $addonext_sub) {
            if (!is_array($addonext_sub)) {
                continue;
            }
            $addonext_subLabel = isset($addonext_sub['item_title']) ? trim((string) $addonext_sub['item_title']) : '';
            if ($addonext_subLabel === '') {
                continue;
            }
            ++$addonext_minor;
            $addonext_sType = isset($addonext_sub['link_type']) ? (string) $addonext_sub['link_type'] : 'element_id';
            $addonext_sId   = isset($addonext_sub['element_id']) ? trim((string) $addonext_sub['element_id']) : '';
            $addonext_sMatch = isset($addonext_sub['match_text']) ? trim((string) $addonext_sub['match_text']) : '';
            $addonext_index  = $addonext_major . '.' . $addonext_minor;
            $addonext_sKey   = 'm' . $addonext_major . '-s' . $addonext_minor;
            ?>
                <li>
            <?php
            if ($addonext_sType === 'text' && $addonext_sMatch !== '') :
                ?>
                    <button
                        type="button"
                        class="addonext-toc__link addonext-toc__link--l2"
                        data-toc-key="<?php echo esc_attr($addonext_sKey); ?>"
                        data-toc-type="text"
                        data-match-text="<?php echo esc_attr($addonext_sMatch); ?>"
                    >
                        <span class="addonext-toc__index"><?php echo esc_html($addonext_index); ?></span>
                        <span class="addonext-toc__label"><?php echo esc_html($addonext_subLabel); ?></span>
                    </button>
                <?php
            elseif ($addonext_sId !== '') :
                ?>
                    <a
                        class="addonext-toc__link addonext-toc__link--l2"
                        href="#<?php echo esc_attr($addonext_sId); ?>"
                        data-toc-key="<?php echo esc_attr($addonext_sKey); ?>"
                        data-toc-type="id"
                        data-target-id="<?php echo esc_attr($addonext_sId); ?>"
                    >
                        <span class="addonext-toc__index"><?php echo esc_html($addonext_index); ?></span>
                        <span class="addonext-toc__label"><?php echo esc_html($addonext_subLabel); ?></span>
                    </a>
                <?php
            else :
                ?>
                    <span class="addonext-toc__link addonext-toc__link--l2 addonext-toc__link--static" data-toc-key="<?php echo esc_attr($addonext_sKey); ?>">
                        <span class="addonext-toc__index"><?php echo esc_html($addonext_index); ?></span>
                        <span class="addonext-toc__label"><?php echo esc_html($addonext_subLabel); ?></span>
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
