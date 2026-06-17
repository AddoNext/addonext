<?php
defined('ABSPATH') || exit;

$veloria_tweets = isset($veloria_tweets) && is_array($veloria_tweets) ? $veloria_tweets : [];
$veloria_settings = isset($veloria_settings) && is_array($veloria_settings) ? $veloria_settings : [];
$veloria_display_username = isset($veloria_display_username) ? (string) $veloria_display_username : '';

if ($veloria_tweets === [] || $veloria_display_username === '') {
    return;
}

$veloria_open_new_tab = !empty($veloria_settings['open_new_tab']) && $veloria_settings['open_new_tab'] === 'yes';
$veloria_link_target = $veloria_open_new_tab ? '_blank' : '_self';
$veloria_link_rel = $veloria_open_new_tab ? 'noopener noreferrer' : '';
$veloria_show_date = !empty($veloria_settings['show_date']) && $veloria_settings['show_date'] === 'yes';
$veloria_show_metrics = !empty($veloria_settings['show_metrics']) && $veloria_settings['show_metrics'] === 'yes';
$veloria_show_profile = !empty($veloria_settings['show_profile_link']) && $veloria_settings['show_profile_link'] === 'yes';
$veloria_profile_url = 'https://x.com/' . rawurlencode($veloria_display_username);
?>
<div class="veloria-twitter-feed">
    <?php if ($veloria_show_profile) : ?>
        <div class="veloria-twitter-feed__header">
            <a class="veloria-twitter-feed__profile" href="<?php echo esc_url($veloria_profile_url); ?>" target="<?php echo esc_attr($veloria_open_new_tab ? '_blank' : '_self'); ?>"<?php echo $veloria_open_new_tab ? ' rel="noopener noreferrer"' : ''; ?>>
                @<?php echo esc_html($veloria_display_username); ?>
            </a>
        </div>
    <?php endif; ?>

    <ul class="veloria-twitter-feed__list">
        <?php foreach ($veloria_tweets as $veloria_tweet) : ?>
            <?php
            if (!is_array($veloria_tweet) || empty($veloria_tweet['id'])) {
                continue;
            }
            $veloria_status_url = 'https://x.com/' . rawurlencode($veloria_display_username) . '/status/' . rawurlencode((string) $veloria_tweet['id']);
            $veloria_created = isset($veloria_tweet['created_at']) ? (string) $veloria_tweet['created_at'] : '';
            $veloria_ts = $veloria_created !== '' ? strtotime($veloria_created) : false;
            ?>
            <li class="veloria-twitter-feed__item">
                <div class="veloria-twitter-feed__body">
                    <p class="veloria-twitter-feed__text"><?php echo nl2br(esc_html(isset($veloria_tweet['text']) ? (string) $veloria_tweet['text'] : '')); ?></p>
                    <div class="veloria-twitter-feed__footer">
                        <?php if ($veloria_show_date && $veloria_ts) : ?>
                            <time class="veloria-twitter-feed__date" datetime="<?php echo esc_attr($veloria_created !== '' ? $veloria_created : gmdate('c', $veloria_ts)); ?>">
                                <?php echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format'), $veloria_ts)); ?>
                            </time>
                        <?php endif; ?>
                        <?php if ($veloria_show_metrics) : ?>
                            <span class="veloria-twitter-feed__metrics" aria-label="<?php esc_attr_e('Engagement', 'veloria'); ?>">
                                <span class="veloria-twitter-feed__metric"><?php echo esc_html((string) (int) ($veloria_tweet['reply_count'] ?? 0)); ?> <?php esc_html_e('replies', 'veloria'); ?></span>
                                <span class="veloria-twitter-feed__metric"><?php echo esc_html((string) (int) ($veloria_tweet['retweet_count'] ?? 0)); ?> <?php esc_html_e('reposts', 'veloria'); ?></span>
                                <span class="veloria-twitter-feed__metric"><?php echo esc_html((string) (int) ($veloria_tweet['like_count'] ?? 0)); ?> <?php esc_html_e('likes', 'veloria'); ?></span>
                            </span>
                        <?php endif; ?>
                        <a class="veloria-twitter-feed__permalink" href="<?php echo esc_url($veloria_status_url); ?>" target="<?php echo esc_attr($veloria_link_target); ?>"<?php echo $veloria_link_rel !== '' ? ' rel="' . esc_attr($veloria_link_rel) . '"' : ''; ?>>
                            <?php esc_html_e('View on X', 'veloria'); ?>
                        </a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
