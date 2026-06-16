<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$s = $this->get_settings_for_display();

$showTitle = isset($s['show_title']) && $s['show_title'] === 'yes';
$title     = isset($s['title']) ? (string) $s['title'] : __('Contents', 'veloria');
$sticky    = !isset($s['sticky_enable']) || $s['sticky_enable'] === 'yes';
$offset    = isset($s['scroll_offset']['size']) ? (int) $s['scroll_offset']['size'] : 96;
$selector  = isset($s['text_match_heading_selector']) ? trim((string) $s['text_match_heading_selector']) : 'h1, h2, h3, h4, h5, h6';
if ($selector === '') {
    $selector = 'h1, h2, h3, h4, h5, h6';
}

$sections = isset($s['toc_sections']) && is_array($s['toc_sections']) ? $s['toc_sections'] : [];

$tocClasses = ['veloria-toc'];
if ($sticky) {
    $tocClasses[] = 'veloria-toc--sticky';
}

$widgetId = 'veloria-toc-' . $this->get_id();
?>
<nav
    id="<?php echo esc_attr($widgetId); ?>"
    class="<?php echo esc_attr(implode(' ', $tocClasses)); ?>"
    aria-label="<?php echo esc_attr__('Table of contents', 'veloria'); ?>"
    data-scroll-offset="<?php echo esc_attr((string) max(0, $offset)); ?>"
    data-text-selector="<?php echo esc_attr($selector); ?>"
>
<?php
if ($showTitle && $title !== '') :
    ?>
    <h3 class="veloria-toc__title"><?php echo esc_html($title); ?></h3>
    <?php
endif;
?>
    <ol class="veloria-toc__list" role="list">
<?php
$major = 0;
foreach ($sections as $section) {
    if (!is_array($section)) {
        continue;
    }
    $majorLabel = isset($section['section_title']) ? trim((string) $section['section_title']) : '';
    if ($majorLabel === '') {
        continue;
    }
    ++$major;
    $linkType = isset($section['link_type']) ? (string) $section['link_type'] : 'element_id';
    $elId     = isset($section['element_id']) ? trim((string) $section['element_id']) : '';
    $match    = isset($section['match_text']) ? trim((string) $section['match_text']) : '';
    $key      = 'm' . $major;

    $subItems = isset($section['sub_items']) && is_array($section['sub_items']) ? $section['sub_items'] : [];
    ?>
        <li class="veloria-toc__group">
            <div class="veloria-toc__row">
                <span class="veloria-toc__badge" aria-hidden="true"><?php echo esc_html((string) $major); ?></span>
    <?php
    if ($linkType === 'text' && $match !== '') :
        ?>
                <button
                    type="button"
                    class="veloria-toc__link veloria-toc__link--l1"
                    data-toc-key="<?php echo esc_attr($key); ?>"
                    data-toc-type="text"
                    data-match-text="<?php echo esc_attr($match); ?>"
                >
                    <span class="veloria-toc__label"><?php echo esc_html($majorLabel); ?></span>
                </button>
        <?php
    elseif ($elId !== '') :
        ?>
                <a
                    class="veloria-toc__link veloria-toc__link--l1"
                    href="#<?php echo esc_attr($elId); ?>"
                    data-toc-key="<?php echo esc_attr($key); ?>"
                    data-toc-type="id"
                    data-target-id="<?php echo esc_attr($elId); ?>"
                >
                    <span class="veloria-toc__label"><?php echo esc_html($majorLabel); ?></span>
                </a>
        <?php
    else :
        ?>
                <span class="veloria-toc__link veloria-toc__link--l1 veloria-toc__link--static" data-toc-key="<?php echo esc_attr($key); ?>">
                    <span class="veloria-toc__label"><?php echo esc_html($majorLabel); ?></span>
                </span>
        <?php
    endif;
    ?>
            </div>
    <?php
    if ($subItems !== []) :
        ?>
            <ol class="veloria-toc__sub" role="list">
        <?php
        $minor = 0;
        foreach ($subItems as $sub) {
            if (!is_array($sub)) {
                continue;
            }
            $subLabel = isset($sub['item_title']) ? trim((string) $sub['item_title']) : '';
            if ($subLabel === '') {
                continue;
            }
            ++$minor;
            $sType = isset($sub['link_type']) ? (string) $sub['link_type'] : 'element_id';
            $sId   = isset($sub['element_id']) ? trim((string) $sub['element_id']) : '';
            $sMatch = isset($sub['match_text']) ? trim((string) $sub['match_text']) : '';
            $index  = $major . '.' . $minor;
            $sKey   = 'm' . $major . '-s' . $minor;
            ?>
                <li>
            <?php
            if ($sType === 'text' && $sMatch !== '') :
                ?>
                    <button
                        type="button"
                        class="veloria-toc__link veloria-toc__link--l2"
                        data-toc-key="<?php echo esc_attr($sKey); ?>"
                        data-toc-type="text"
                        data-match-text="<?php echo esc_attr($sMatch); ?>"
                    >
                        <span class="veloria-toc__index"><?php echo esc_html($index); ?></span>
                        <span class="veloria-toc__label"><?php echo esc_html($subLabel); ?></span>
                    </button>
                <?php
            elseif ($sId !== '') :
                ?>
                    <a
                        class="veloria-toc__link veloria-toc__link--l2"
                        href="#<?php echo esc_attr($sId); ?>"
                        data-toc-key="<?php echo esc_attr($sKey); ?>"
                        data-toc-type="id"
                        data-target-id="<?php echo esc_attr($sId); ?>"
                    >
                        <span class="veloria-toc__index"><?php echo esc_html($index); ?></span>
                        <span class="veloria-toc__label"><?php echo esc_html($subLabel); ?></span>
                    </a>
                <?php
            else :
                ?>
                    <span class="veloria-toc__link veloria-toc__link--l2 veloria-toc__link--static" data-toc-key="<?php echo esc_attr($sKey); ?>">
                        <span class="veloria-toc__index"><?php echo esc_html($index); ?></span>
                        <span class="veloria-toc__label"><?php echo esc_html($subLabel); ?></span>
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
