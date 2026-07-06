<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_title    = isset($addonext_settings['section_title']) ? $addonext_settings['section_title'] : __('Latest Articles', 'addonext');
$addonext_sort_by  = isset($addonext_settings['sort_by']) ? $addonext_settings['sort_by'] : 'date_desc';
$addonext_post_type = isset($addonext_settings['post_type']) ? $addonext_settings['post_type'] : 'post';
$addonext_per_page = isset($addonext_settings['posts_per_page']) ? (int) $addonext_settings['posts_per_page'] : 9;
$addonext_category_ids = isset($addonext_settings['category_ids']) && is_array($addonext_settings['category_ids']) ? $addonext_settings['category_ids'] : [];
$addonext_show_pagination = !empty($addonext_settings['show_pagination']);
$addonext_show_image = !empty($addonext_settings['show_image']);
$addonext_show_category = !empty($addonext_settings['show_category']);
$addonext_show_date = !empty($addonext_settings['show_date']);
$addonext_show_excerpt = !empty($addonext_settings['show_excerpt']);
$addonext_excerpt_length = isset($addonext_settings['excerpt_length']) ? (int) $addonext_settings['excerpt_length'] : 80;
$addonext_show_author = !empty($addonext_settings['show_author']);
$addonext_show_read_time = !empty($addonext_settings['show_read_time']);

$addonext_orderby = 'date';
$addonext_order   = 'DESC';
switch ($addonext_sort_by) {
    case 'date_asc':
        $addonext_order = 'ASC';
        break;
    case 'title_asc':
        $addonext_orderby = 'title';
        $addonext_order   = 'ASC';
        break;
    case 'title_desc':
        $addonext_orderby = 'title';
        $addonext_order   = 'DESC';
        break;
    default:
        break;
}

$addonext_paged = get_query_var('paged') ? get_query_var('paged') : 1;
$addonext_args  = [
    'post_type'      => $addonext_post_type,
    'post_status'    => 'publish',
    'posts_per_page' => $addonext_per_page,
    'paged'          => $addonext_paged,
    'orderby'        => $addonext_orderby,
    'order'          => $addonext_order,
];
if ($addonext_post_type === 'post' && !empty($addonext_category_ids)) {
    $addonext_args['tax_query'] = [
        [
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $addonext_category_ids,
        ],
    ];
}

$addonext_query = new \WP_Query($addonext_args);

$addonext_sort_labels = [
    'date_desc'  => __('Most Recent', 'addonext'),
    'date_asc'   => __('Oldest First', 'addonext'),
    'title_asc'  => __('Title A–Z', 'addonext'),
    'title_desc' => __('Title Z–A', 'addonext'),
];
$addonext_sort_label = isset($addonext_sort_labels[$addonext_sort_by]) ? $addonext_sort_labels[$addonext_sort_by] : $addonext_sort_labels['date_desc'];

$this->add_render_attribute('wrapper', 'class', 'addonext-smart-post-list');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="addonext-smart-post-list__header">
        <h2 class="addonext-smart-post-list__title"><?php echo esc_html($addonext_title); ?></h2>
        <select class="addonext-smart-post-list__sort-select" aria-label="<?php esc_attr_e('Sort by', 'addonext'); ?>">
            <?php foreach ($addonext_sort_labels as $addonext_key => $addonext_label) : ?>
                <option value="<?php echo esc_attr($addonext_key); ?>" <?php selected($addonext_sort_by, $addonext_key); ?>><?php echo esc_html($addonext_label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="addonext-smart-post-list__grid">
        <?php
        if ($addonext_query->have_posts()) :
            $addonext_index = 0;
            while ($addonext_query->have_posts()) :
                $addonext_query->the_post();
                $addonext_post_id   = get_the_ID();
                $addonext_categories = get_the_category($addonext_post_id);
                $addonext_first_cat  = !empty($addonext_categories) ? $addonext_categories[0] : null;
                $addonext_read_mins  = $addonext_show_read_time ? max(1, (int) ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 200)) : 0;
                ?>
                <article class="addonext-smart-post-list__card">
                    <a href="<?php the_permalink(); ?>" class="addonext-smart-post-list__card-link" aria-hidden="true" tabindex="-1"></a>
                    <?php if ($addonext_show_image) : ?>
                        <div class="addonext-smart-post-list__image-wrap">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large', ['class' => 'addonext-smart-post-list__image']); ?>
                            <?php else : ?>
                                <div class="addonext-smart-post-list__image-placeholder"></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="addonext-smart-post-list__card-body">
                        <div class="addonext-smart-post-list__meta-top">
                            <?php if ($addonext_show_category && $addonext_first_cat) : ?>
                                <span class="addonext-smart-post-list__category"><?php echo esc_html(strtoupper($addonext_first_cat->name)); ?></span>
                            <?php endif; ?>
                            <?php if ($addonext_show_date) : ?>
                                <span class="addonext-smart-post-list__meta-date"><?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'addonext')); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="addonext-smart-post-list__card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <?php if ($addonext_show_excerpt) : ?>
                            <p class="addonext-smart-post-list__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), $addonext_excerpt_length)); ?></p>
                        <?php endif; ?>
                        <?php if ($addonext_show_author || $addonext_show_read_time) : ?>
                            <div class="addonext-smart-post-list__footer">
                                <?php if ($addonext_show_author) : ?>
                                    <span class="addonext-smart-post-list__author">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => 'addonext-smart-post-list__avatar']); ?>
                                        <span class="addonext-smart-post-list__author-name"><?php the_author(); ?></span>
                                    </span>
                                <?php endif; ?>
                                <?php if ($addonext_show_read_time) : ?>
                                    <span class="addonext-smart-post-list__read-time"><?php echo (int) $addonext_read_mins; ?> <?php echo esc_html(_n('min read', 'min read', $addonext_read_mins, 'addonext')); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
                $addonext_index++;
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="addonext-smart-post-list__empty"><?php esc_html_e('No posts found.', 'addonext'); ?></p>
        <?php endif; ?>
    </div>

    <?php
    if ($addonext_show_pagination && $addonext_query->have_posts() && $addonext_query->max_num_pages > 1) :
        $addonext_total = (int) $addonext_query->max_num_pages;
        ?>
        <nav class="addonext-smart-post-list__pagination" aria-label="<?php esc_attr_e('Posts pagination', 'addonext'); ?>">
            <ul class="addonext-smart-post-list__page-list">
                <?php if ($addonext_paged > 1) : ?>
                    <li class="addonext-smart-post-list__page-prev"><a href="<?php echo esc_url($addonext_paged === 2 ? get_pagenum_link(1) : get_pagenum_link($addonext_paged - 1)); ?>">&laquo;</a></li>
                <?php endif; ?>
                <?php
                $addonext_show_ellipsis_before = false;
                $addonext_show_ellipsis_after  = false;
                for ($addonext_i = 1; $addonext_i <= $addonext_total; $addonext_i++) :
                    if ($addonext_total > 7) {
                        if ($addonext_i > 3 && $addonext_i < $addonext_total - 2 && abs($addonext_i - $addonext_paged) > 1) {
                            if ($addonext_i < $addonext_paged && !$addonext_show_ellipsis_before) {
                                echo '<li><span class="addonext-smart-post-list__page-ellipsis">…</span></li>';
                                $addonext_show_ellipsis_before = true;
                            }
                            if ($addonext_i > $addonext_paged && !$addonext_show_ellipsis_after) {
                                echo '<li><span class="addonext-smart-post-list__page-ellipsis">…</span></li>';
                                $addonext_show_ellipsis_after = true;
                            }
                            continue;
                        }
                    }
                    $addonext_active = $addonext_i === (int) $addonext_paged;
                    $addonext_url = $addonext_i === 1 ? get_pagenum_link(1) : get_pagenum_link($addonext_i);
                    ?>
                    <li>
                        <a class="addonext-smart-post-list__page-link <?php echo $addonext_active ? 'is-active' : ''; ?>" href="<?php echo esc_url($addonext_url); ?>"><?php echo (int) $addonext_i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($addonext_paged < $addonext_total) : ?>
                    <li class="addonext-smart-post-list__page-next"><a href="<?php echo esc_url(get_pagenum_link($addonext_paged + 1)); ?>">&raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
