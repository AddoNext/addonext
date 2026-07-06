<?php
defined('ABSPATH') || exit;

$addonext_tweets = isset($addonext_tweets) && is_array($addonext_tweets) ? $addonext_tweets : [];
$addonext_settings = isset($addonext_settings) && is_array($addonext_settings) ? $addonext_settings : [];
$addonext_display_username = isset($addonext_display_username) ? (string) $addonext_display_username : '';

if ($addonext_tweets === [] || $addonext_display_username === '') {
    return;
}

$addonext_open_new_tab = !empty($addonext_settings['open_new_tab']) && $addonext_settings['open_new_tab'] === 'yes';
$addonext_link_target = $addonext_open_new_tab ? '_blank' : '_self';
$addonext_link_rel = $addonext_open_new_tab ? 'noopener noreferrer' : '';
$addonext_show_date = !empty($addonext_settings['show_date']) && $addonext_settings['show_date'] === 'yes';
$addonext_show_metrics = !empty($addonext_settings['show_metrics']) && $addonext_settings['show_metrics'] === 'yes';
$addonext_show_profile = !empty($addonext_settings['show_profile_link']) && $addonext_settings['show_profile_link'] === 'yes';
$addonext_profile_url = 'https://x.com/' . rawurlencode($addonext_display_username);
?>
<div class="addonext-twitter-feed">
    <?php if ($addonext_show_profile) : ?>
        <div class="addonext-twitter-feed__header">
            <a class="addonext-twitter-feed__profile" href="<?php echo esc_url($addonext_profile_url); ?>" target="<?php echo esc_attr($addonext_open_new_tab ? '_blank' : '_self'); ?>"<?php echo $addonext_open_new_tab ? ' rel="noopener noreferrer"' : ''; ?>>
                @<?php echo esc_html($addonext_display_username); ?>
            </a>
        </div>
    <?php endif; ?>

    <ul class="addonext-twitter-feed__list">
        <?php foreach ($addonext_tweets as $addonext_tweet) : ?>
            <?php
            if (!is_array($addonext_tweet) || empty($addonext_tweet['id'])) {
                continue;
            }
            $addonext_status_url = 'https://x.com/' . rawurlencode($addonext_display_username) . '/status/' . rawurlencode((string) $addonext_tweet['id']);
            $addonext_created = isset($addonext_tweet['created_at']) ? (string) $addonext_tweet['created_at'] : '';
            $addonext_ts = $addonext_created !== '' ? strtotime($addonext_created) : false;
            ?>
            <li class="addonext-twitter-feed__item">
                <div class="addonext-twitter-feed__body">
                    <p class="addonext-twitter-feed__text"><?php echo nl2br(esc_html(isset($addonext_tweet['text']) ? (string) $addonext_tweet['text'] : '')); ?></p>
                    <div class="addonext-twitter-feed__footer">
                        <?php if ($addonext_show_date && $addonext_ts) : ?>
                            <time class="addonext-twitter-feed__date" datetime="<?php echo esc_attr($addonext_created !== '' ? $addonext_created : gmdate('c', $addonext_ts)); ?>">
                                <?php echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format'), $addonext_ts)); ?>
                            </time>
                        <?php endif; ?>
                        <?php if ($addonext_show_metrics) : ?>
                            <span class="addonext-twitter-feed__metrics" aria-label="<?php esc_attr_e('Engagement', 'addonext'); ?>">
                                <span class="addonext-twitter-feed__metric"><?php echo esc_html((string) (int) ($addonext_tweet['reply_count'] ?? 0)); ?> <?php esc_html_e('replies', 'addonext'); ?></span>
                                <span class="addonext-twitter-feed__metric"><?php echo esc_html((string) (int) ($addonext_tweet['retweet_count'] ?? 0)); ?> <?php esc_html_e('reposts', 'addonext'); ?></span>
                                <span class="addonext-twitter-feed__metric"><?php echo esc_html((string) (int) ($addonext_tweet['like_count'] ?? 0)); ?> <?php esc_html_e('likes', 'addonext'); ?></span>
                            </span>
                        <?php endif; ?>
                        <a class="addonext-twitter-feed__permalink" href="<?php echo esc_url($addonext_status_url); ?>" target="<?php echo esc_attr($addonext_link_target); ?>"<?php echo $addonext_link_rel !== '' ? ' rel="' . esc_attr($addonext_link_rel) . '"' : ''; ?>>
                            <?php esc_html_e('View on X', 'addonext'); ?>
                        </a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
