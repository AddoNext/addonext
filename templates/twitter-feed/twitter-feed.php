<?php
defined('ABSPATH') || exit;

$tweets = isset($tweets) && is_array($tweets) ? $tweets : [];
$settings = isset($settings) && is_array($settings) ? $settings : [];
$display_username = isset($display_username) ? (string) $display_username : '';

if ($tweets === [] || $display_username === '') {
    return;
}

$open_new_tab = !empty($settings['open_new_tab']) && $settings['open_new_tab'] === 'yes';
$link_target = $open_new_tab ? '_blank' : '_self';
$link_rel = $open_new_tab ? 'noopener noreferrer' : '';
$show_date = !empty($settings['show_date']) && $settings['show_date'] === 'yes';
$show_metrics = !empty($settings['show_metrics']) && $settings['show_metrics'] === 'yes';
$show_profile = !empty($settings['show_profile_link']) && $settings['show_profile_link'] === 'yes';
$profile_url = 'https://x.com/' . rawurlencode($display_username);
?>
<div class="veloria-twitter-feed">
    <?php if ($show_profile) : ?>
        <div class="veloria-twitter-feed__header">
            <a class="veloria-twitter-feed__profile" href="<?php echo esc_url($profile_url); ?>" target="<?php echo esc_attr($open_new_tab ? '_blank' : '_self'); ?>"<?php echo $open_new_tab ? ' rel="noopener noreferrer"' : ''; ?>>
                @<?php echo esc_html($display_username); ?>
            </a>
        </div>
    <?php endif; ?>

    <ul class="veloria-twitter-feed__list">
        <?php foreach ($tweets as $tweet) : ?>
            <?php
            if (!is_array($tweet) || empty($tweet['id'])) {
                continue;
            }
            $status_url = 'https://x.com/' . rawurlencode($display_username) . '/status/' . rawurlencode((string) $tweet['id']);
            $created = isset($tweet['created_at']) ? (string) $tweet['created_at'] : '';
            $ts = $created !== '' ? strtotime($created) : false;
            ?>
            <li class="veloria-twitter-feed__item">
                <div class="veloria-twitter-feed__body">
                    <p class="veloria-twitter-feed__text"><?php echo nl2br(esc_html(isset($tweet['text']) ? (string) $tweet['text'] : '')); ?></p>
                    <div class="veloria-twitter-feed__footer">
                        <?php if ($show_date && $ts) : ?>
                            <time class="veloria-twitter-feed__date" datetime="<?php echo esc_attr($created !== '' ? $created : gmdate('c', $ts)); ?>">
                                <?php echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format'), $ts)); ?>
                            </time>
                        <?php endif; ?>
                        <?php if ($show_metrics) : ?>
                            <span class="veloria-twitter-feed__metrics" aria-label="<?php esc_attr_e('Engagement', 'veloria'); ?>">
                                <span class="veloria-twitter-feed__metric"><?php echo esc_html((string) (int) ($tweet['reply_count'] ?? 0)); ?> <?php esc_html_e('replies', 'veloria'); ?></span>
                                <span class="veloria-twitter-feed__metric"><?php echo esc_html((string) (int) ($tweet['retweet_count'] ?? 0)); ?> <?php esc_html_e('reposts', 'veloria'); ?></span>
                                <span class="veloria-twitter-feed__metric"><?php echo esc_html((string) (int) ($tweet['like_count'] ?? 0)); ?> <?php esc_html_e('likes', 'veloria'); ?></span>
                            </span>
                        <?php endif; ?>
                        <a class="veloria-twitter-feed__permalink" href="<?php echo esc_url($status_url); ?>" target="<?php echo esc_attr($link_target); ?>"<?php echo $link_rel !== '' ? ' rel="' . esc_attr($link_rel) . '"' : ''; ?>>
                            <?php esc_html_e('View on X', 'veloria'); ?>
                        </a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
