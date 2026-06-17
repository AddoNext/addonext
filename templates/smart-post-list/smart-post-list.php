<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_title    = isset($veloria_settings['section_title']) ? $veloria_settings['section_title'] : __('Latest Articles', 'veloria');
$veloria_sort_by  = isset($veloria_settings['sort_by']) ? $veloria_settings['sort_by'] : 'date_desc';
$veloria_post_type = isset($veloria_settings['post_type']) ? $veloria_settings['post_type'] : 'post';
$veloria_per_page = isset($veloria_settings['posts_per_page']) ? (int) $veloria_settings['posts_per_page'] : 9;
$veloria_category_ids = isset($veloria_settings['category_ids']) && is_array($veloria_settings['category_ids']) ? $veloria_settings['category_ids'] : [];
$veloria_show_pagination = !empty($veloria_settings['show_pagination']);
$veloria_show_image = !empty($veloria_settings['show_image']);
$veloria_show_category = !empty($veloria_settings['show_category']);
$veloria_show_date = !empty($veloria_settings['show_date']);
$veloria_show_excerpt = !empty($veloria_settings['show_excerpt']);
$veloria_excerpt_length = isset($veloria_settings['excerpt_length']) ? (int) $veloria_settings['excerpt_length'] : 80;
$veloria_show_author = !empty($veloria_settings['show_author']);
$veloria_show_read_time = !empty($veloria_settings['show_read_time']);

$veloria_orderby = 'date';
$veloria_order   = 'DESC';
switch ($veloria_sort_by) {
    case 'date_asc':
        $veloria_order = 'ASC';
        break;
    case 'title_asc':
        $veloria_orderby = 'title';
        $veloria_order   = 'ASC';
        break;
    case 'title_desc':
        $veloria_orderby = 'title';
        $veloria_order   = 'DESC';
        break;
    default:
        break;
}

$veloria_paged = get_query_var('paged') ? get_query_var('paged') : 1;
$veloria_args  = [
    'post_type'      => $veloria_post_type,
    'post_status'    => 'publish',
    'posts_per_page' => $veloria_per_page,
    'paged'          => $veloria_paged,
    'orderby'        => $veloria_orderby,
    'order'          => $veloria_order,
];
if ($veloria_post_type === 'post' && !empty($veloria_category_ids)) {
    $veloria_args['tax_query'] = [
        [
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $veloria_category_ids,
        ],
    ];
}

$veloria_query = new \WP_Query($veloria_args);

$veloria_sort_labels = [
    'date_desc'  => __('Most Recent', 'veloria'),
    'date_asc'   => __('Oldest First', 'veloria'),
    'title_asc'  => __('Title A–Z', 'veloria'),
    'title_desc' => __('Title Z–A', 'veloria'),
];
$veloria_sort_label = isset($veloria_sort_labels[$veloria_sort_by]) ? $veloria_sort_labels[$veloria_sort_by] : $veloria_sort_labels['date_desc'];

$this->add_render_attribute('wrapper', 'class', 'veloria-smart-post-list');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="veloria-smart-post-list__header">
        <h2 class="veloria-smart-post-list__title"><?php echo esc_html($veloria_title); ?></h2>
        <select class="veloria-smart-post-list__sort-select" aria-label="<?php esc_attr_e('Sort by', 'veloria'); ?>">
            <?php foreach ($veloria_sort_labels as $veloria_key => $veloria_label) : ?>
                <option value="<?php echo esc_attr($veloria_key); ?>" <?php selected($veloria_sort_by, $veloria_key); ?>><?php echo esc_html($veloria_label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="veloria-smart-post-list__grid">
        <?php
        if ($veloria_query->have_posts()) :
            $veloria_index = 0;
            while ($veloria_query->have_posts()) :
                $veloria_query->the_post();
                $veloria_post_id   = get_the_ID();
                $veloria_categories = get_the_category($veloria_post_id);
                $veloria_first_cat  = !empty($veloria_categories) ? $veloria_categories[0] : null;
                $veloria_read_mins  = $veloria_show_read_time ? max(1, (int) ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 200)) : 0;
                ?>
                <article class="veloria-smart-post-list__card">
                    <a href="<?php the_permalink(); ?>" class="veloria-smart-post-list__card-link" aria-hidden="true" tabindex="-1"></a>
                    <?php if ($veloria_show_image) : ?>
                        <div class="veloria-smart-post-list__image-wrap">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large', ['class' => 'veloria-smart-post-list__image']); ?>
                            <?php else : ?>
                                <div class="veloria-smart-post-list__image-placeholder"></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="veloria-smart-post-list__card-body">
                        <div class="veloria-smart-post-list__meta-top">
                            <?php if ($veloria_show_category && $veloria_first_cat) : ?>
                                <span class="veloria-smart-post-list__category"><?php echo esc_html(strtoupper($veloria_first_cat->name)); ?></span>
                            <?php endif; ?>
                            <?php if ($veloria_show_date) : ?>
                                <span class="veloria-smart-post-list__meta-date"><?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'veloria')); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="veloria-smart-post-list__card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <?php if ($veloria_show_excerpt) : ?>
                            <p class="veloria-smart-post-list__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), $veloria_excerpt_length)); ?></p>
                        <?php endif; ?>
                        <?php if ($veloria_show_author || $veloria_show_read_time) : ?>
                            <div class="veloria-smart-post-list__footer">
                                <?php if ($veloria_show_author) : ?>
                                    <span class="veloria-smart-post-list__author">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => 'veloria-smart-post-list__avatar']); ?>
                                        <span class="veloria-smart-post-list__author-name"><?php the_author(); ?></span>
                                    </span>
                                <?php endif; ?>
                                <?php if ($veloria_show_read_time) : ?>
                                    <span class="veloria-smart-post-list__read-time"><?php echo (int) $veloria_read_mins; ?> <?php echo esc_html(_n('min read', 'min read', $veloria_read_mins, 'veloria')); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
                $veloria_index++;
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="veloria-smart-post-list__empty"><?php esc_html_e('No posts found.', 'veloria'); ?></p>
        <?php endif; ?>
    </div>

    <?php
    if ($veloria_show_pagination && $veloria_query->have_posts() && $veloria_query->max_num_pages > 1) :
        $veloria_total = (int) $veloria_query->max_num_pages;
        ?>
        <nav class="veloria-smart-post-list__pagination" aria-label="<?php esc_attr_e('Posts pagination', 'veloria'); ?>">
            <ul class="veloria-smart-post-list__page-list">
                <?php if ($veloria_paged > 1) : ?>
                    <li class="veloria-smart-post-list__page-prev"><a href="<?php echo esc_url($veloria_paged === 2 ? get_pagenum_link(1) : get_pagenum_link($veloria_paged - 1)); ?>">&laquo;</a></li>
                <?php endif; ?>
                <?php
                $veloria_show_ellipsis_before = false;
                $veloria_show_ellipsis_after  = false;
                for ($veloria_i = 1; $veloria_i <= $veloria_total; $veloria_i++) :
                    if ($veloria_total > 7) {
                        if ($veloria_i > 3 && $veloria_i < $veloria_total - 2 && abs($veloria_i - $veloria_paged) > 1) {
                            if ($veloria_i < $veloria_paged && !$veloria_show_ellipsis_before) {
                                echo '<li><span class="veloria-smart-post-list__page-ellipsis">…</span></li>';
                                $veloria_show_ellipsis_before = true;
                            }
                            if ($veloria_i > $veloria_paged && !$veloria_show_ellipsis_after) {
                                echo '<li><span class="veloria-smart-post-list__page-ellipsis">…</span></li>';
                                $veloria_show_ellipsis_after = true;
                            }
                            continue;
                        }
                    }
                    $veloria_active = $veloria_i === (int) $veloria_paged;
                    $veloria_url = $veloria_i === 1 ? get_pagenum_link(1) : get_pagenum_link($veloria_i);
                    ?>
                    <li>
                        <a class="veloria-smart-post-list__page-link <?php echo $veloria_active ? 'is-active' : ''; ?>" href="<?php echo esc_url($veloria_url); ?>"><?php echo (int) $veloria_i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($veloria_paged < $veloria_total) : ?>
                    <li class="veloria-smart-post-list__page-next"><a href="<?php echo esc_url(get_pagenum_link($veloria_paged + 1)); ?>">&raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
