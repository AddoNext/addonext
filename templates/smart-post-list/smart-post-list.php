<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$title    = isset($settings['section_title']) ? $settings['section_title'] : __('Latest Articles', 'elementrify');
$sort_by  = isset($settings['sort_by']) ? $settings['sort_by'] : 'date_desc';
$post_type = isset($settings['post_type']) ? $settings['post_type'] : 'post';
$per_page = isset($settings['posts_per_page']) ? (int) $settings['posts_per_page'] : 9;
$category_ids = isset($settings['category_ids']) && is_array($settings['category_ids']) ? $settings['category_ids'] : [];
$show_pagination = !empty($settings['show_pagination']);
$show_image = !empty($settings['show_image']);
$show_category = !empty($settings['show_category']);
$show_date = !empty($settings['show_date']);
$show_excerpt = !empty($settings['show_excerpt']);
$excerpt_length = isset($settings['excerpt_length']) ? (int) $settings['excerpt_length'] : 80;
$show_author = !empty($settings['show_author']);
$show_read_time = !empty($settings['show_read_time']);

$orderby = 'date';
$order   = 'DESC';
switch ($sort_by) {
    case 'date_asc':
        $order = 'ASC';
        break;
    case 'title_asc':
        $orderby = 'title';
        $order   = 'ASC';
        break;
    case 'title_desc':
        $orderby = 'title';
        $order   = 'DESC';
        break;
    default:
        break;
}

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args  = [
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
    'orderby'        => $orderby,
    'order'          => $order,
];
if ($post_type === 'post' && !empty($category_ids)) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $category_ids,
        ],
    ];
}

$query = new \WP_Query($args);

$sort_labels = [
    'date_desc'  => __('Most Recent', 'elementrify'),
    'date_asc'   => __('Oldest First', 'elementrify'),
    'title_asc'  => __('Title A–Z', 'elementrify'),
    'title_desc' => __('Title Z–A', 'elementrify'),
];
$sort_label = isset($sort_labels[$sort_by]) ? $sort_labels[$sort_by] : $sort_labels['date_desc'];

$this->add_render_attribute('wrapper', 'class', 'elementrify-smart-post-list');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="elementrify-smart-post-list__header">
        <h2 class="elementrify-smart-post-list__title"><?php echo esc_html($title); ?></h2>
        <select class="elementrify-smart-post-list__sort-select" aria-label="<?php esc_attr_e('Sort by', 'elementrify'); ?>">
            <?php foreach ($sort_labels as $key => $label) : ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($sort_by, $key); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="elementrify-smart-post-list__grid">
        <?php
        if ($query->have_posts()) :
            $index = 0;
            while ($query->have_posts()) :
                $query->the_post();
                $post_id   = get_the_ID();
                $categories = get_the_category($post_id);
                $first_cat  = !empty($categories) ? $categories[0] : null;
                $read_mins  = $show_read_time ? max(1, (int) ceil(str_word_count(strip_tags(get_the_content())) / 200)) : 0;
                ?>
                <article class="elementrify-smart-post-list__card">
                    <a href="<?php the_permalink(); ?>" class="elementrify-smart-post-list__card-link" aria-hidden="true" tabindex="-1"></a>
                    <?php if ($show_image) : ?>
                        <div class="elementrify-smart-post-list__image-wrap">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large', ['class' => 'elementrify-smart-post-list__image']); ?>
                            <?php else : ?>
                                <div class="elementrify-smart-post-list__image-placeholder"></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="elementrify-smart-post-list__card-body">
                        <div class="elementrify-smart-post-list__meta-top">
                            <?php if ($show_category && $first_cat) : ?>
                                <span class="elementrify-smart-post-list__category"><?php echo esc_html(strtoupper($first_cat->name)); ?></span>
                            <?php endif; ?>
                            <?php if ($show_date) : ?>
                                <span class="elementrify-smart-post-list__meta-date"><?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'elementrify')); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="elementrify-smart-post-list__card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <?php if ($show_excerpt) : ?>
                            <p class="elementrify-smart-post-list__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), $excerpt_length)); ?></p>
                        <?php endif; ?>
                        <?php if ($show_author || $show_read_time) : ?>
                            <div class="elementrify-smart-post-list__footer">
                                <?php if ($show_author) : ?>
                                    <span class="elementrify-smart-post-list__author">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => 'elementrify-smart-post-list__avatar']); ?>
                                        <span class="elementrify-smart-post-list__author-name"><?php the_author(); ?></span>
                                    </span>
                                <?php endif; ?>
                                <?php if ($show_read_time) : ?>
                                    <span class="elementrify-smart-post-list__read-time"><?php echo (int) $read_mins; ?> <?php echo esc_html(_n('min read', 'min read', $read_mins, 'elementrify')); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
                $index++;
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="elementrify-smart-post-list__empty"><?php esc_html_e('No posts found.', 'elementrify'); ?></p>
        <?php endif; ?>
    </div>

    <?php
    if ($show_pagination && $query->have_posts() && $query->max_num_pages > 1) :
        $total = (int) $query->max_num_pages;
        ?>
        <nav class="elementrify-smart-post-list__pagination" aria-label="<?php esc_attr_e('Posts pagination', 'elementrify'); ?>">
            <ul class="elementrify-smart-post-list__page-list">
                <?php if ($paged > 1) : ?>
                    <li class="elementrify-smart-post-list__page-prev"><a href="<?php echo esc_url($paged === 2 ? get_pagenum_link(1) : get_pagenum_link($paged - 1)); ?>">&laquo;</a></li>
                <?php endif; ?>
                <?php
                $show_ellipsis_before = false;
                $show_ellipsis_after  = false;
                for ($i = 1; $i <= $total; $i++) :
                    if ($total > 7) {
                        if ($i > 3 && $i < $total - 2 && abs($i - $paged) > 1) {
                            if ($i < $paged && !$show_ellipsis_before) {
                                echo '<li><span class="elementrify-smart-post-list__page-ellipsis">…</span></li>';
                                $show_ellipsis_before = true;
                            }
                            if ($i > $paged && !$show_ellipsis_after) {
                                echo '<li><span class="elementrify-smart-post-list__page-ellipsis">…</span></li>';
                                $show_ellipsis_after = true;
                            }
                            continue;
                        }
                    }
                    $active = $i === (int) $paged;
                    $url = $i === 1 ? get_pagenum_link(1) : get_pagenum_link($i);
                    ?>
                    <li>
                        <a class="elementrify-smart-post-list__page-link <?php echo $active ? 'is-active' : ''; ?>" href="<?php echo esc_url($url); ?>"><?php echo (int) $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($paged < $total) : ?>
                    <li class="elementrify-smart-post-list__page-next"><a href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>">&raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
