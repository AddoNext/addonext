<?php
defined('ABSPATH') || exit;

$addonext_settings  = $this->get_settings_for_display();
$addonext_video_type = isset($addonext_settings['video_type']) ? $addonext_settings['video_type'] : 'youtube';
$addonext_embed_url  = '';
$addonext_hosted_url = '';

if ($addonext_video_type === 'youtube' && !empty($addonext_settings['youtube_url']['url'])) {
    $addonext_embed_url = \Addonext\Widgets\VideoPlayer\VideoPlayer::get_embed_url('youtube', $addonext_settings['youtube_url']['url']);
} elseif ($addonext_video_type === 'vimeo' && !empty($addonext_settings['vimeo_url']['url'])) {
    $addonext_embed_url = \Addonext\Widgets\VideoPlayer\VideoPlayer::get_embed_url('vimeo', $addonext_settings['vimeo_url']['url']);
} elseif ($addonext_video_type === 'dailymotion' && !empty($addonext_settings['dailymotion_url']['url'])) {
    $addonext_embed_url = \Addonext\Widgets\VideoPlayer\VideoPlayer::get_embed_url('dailymotion', $addonext_settings['dailymotion_url']['url']);
} elseif ($addonext_video_type === 'hosted' && !empty($addonext_settings['hosted_url']['url'])) {
    $addonext_hosted_url = $addonext_settings['hosted_url']['url'];
}

$addonext_show_title    = !empty($addonext_settings['show_title']);
$addonext_show_subtitle = !empty($addonext_settings['show_subtitle']);
$addonext_title         = isset($addonext_settings['title']) ? $addonext_settings['title'] : '';
$addonext_subtitle      = isset($addonext_settings['subtitle']) ? $addonext_settings['subtitle'] : '';
$addonext_thumbnail_url = isset($addonext_settings['thumbnail']['url']) ? trim($addonext_settings['thumbnail']['url']) : '';

$addonext_inner_class = 'addonext-video-player__inner';
if ($addonext_thumbnail_url !== '') {
    $addonext_inner_class .= ' addonext-video-player--has-thumbnail';
}
$this->add_render_attribute('inner', 'class', $addonext_inner_class);
$this->add_render_attribute('inner', 'data-embed', esc_attr($addonext_embed_url));
$this->add_render_attribute('inner', 'data-hosted', esc_attr($addonext_hosted_url));
$this->add_render_attribute('inner', 'data-type', esc_attr($addonext_video_type));
?>
<div class="addonext-video-player">
    <div <?php $this->print_render_attribute_string('inner'); ?>>
        <div class="addonext-video-player__overlay">
            <?php if ($addonext_thumbnail_url !== '') : ?>
                <img class="addonext-video-player__thumbnail" src="<?php echo esc_url($addonext_thumbnail_url); ?>" alt="" loading="lazy" decoding="async">
                <span class="addonext-video-player__overlay-shade" aria-hidden="true"></span>
            <?php endif; ?>
            <button type="button" class="addonext-video-player__play" aria-label="<?php esc_attr_e('Play video', 'addonext'); ?>">
                <span class="addonext-video-player__play-icon"></span>
            </button>
            <?php if ($addonext_show_title && $addonext_title !== '' || $addonext_show_subtitle && $addonext_subtitle !== '') : ?>
                <div class="addonext-video-player__caption">
                    <?php if ($addonext_show_title && $addonext_title !== '') : ?>
                        <div class="addonext-video-player__title"><?php echo esc_html($addonext_title); ?></div>
                    <?php endif; ?>
                    <?php if ($addonext_show_subtitle && $addonext_subtitle !== '') : ?>
                        <div class="addonext-video-player__subtitle"><?php echo esc_html($addonext_subtitle); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="addonext-video-player__embed"></div>
    </div>
</div>
