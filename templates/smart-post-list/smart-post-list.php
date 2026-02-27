<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$count = isset($s['posts_count']) ? (int) $s['posts_count'] : 5;
$post_type = isset($s['post_type']) ? $s['post_type'] : 'post';
$posts = get_posts(['post_type' => $post_type, 'numberposts' => $count, 'post_status' => 'publish']);
?><ul class="elementrify-smart-post-list"><?php foreach ($posts as $post) : ?><li><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html($post->post_title); ?></a></li><?php endforeach; ?></ul>
