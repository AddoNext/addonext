<?php
defined('ABSPATH') || exit;

$veloria_settings  = $this->get_settings_for_display();
$veloria_video_type = isset($veloria_settings['video_type']) ? $veloria_settings['video_type'] : 'youtube';
$veloria_embed_url  = '';
$veloria_hosted_url = '';

if ($veloria_video_type === 'youtube' && !empty($veloria_settings['youtube_url']['url'])) {
    $veloria_embed_url = \Veloria\Widgets\VideoPlayer\VideoPlayer::get_embed_url('youtube', $veloria_settings['youtube_url']['url']);
} elseif ($veloria_video_type === 'vimeo' && !empty($veloria_settings['vimeo_url']['url'])) {
    $veloria_embed_url = \Veloria\Widgets\VideoPlayer\VideoPlayer::get_embed_url('vimeo', $veloria_settings['vimeo_url']['url']);
} elseif ($veloria_video_type === 'dailymotion' && !empty($veloria_settings['dailymotion_url']['url'])) {
    $veloria_embed_url = \Veloria\Widgets\VideoPlayer\VideoPlayer::get_embed_url('dailymotion', $veloria_settings['dailymotion_url']['url']);
} elseif ($veloria_video_type === 'hosted' && !empty($veloria_settings['hosted_url']['url'])) {
    $veloria_hosted_url = $veloria_settings['hosted_url']['url'];
}

$veloria_show_title    = !empty($veloria_settings['show_title']);
$veloria_show_subtitle = !empty($veloria_settings['show_subtitle']);
$veloria_title         = isset($veloria_settings['title']) ? $veloria_settings['title'] : '';
$veloria_subtitle      = isset($veloria_settings['subtitle']) ? $veloria_settings['subtitle'] : '';
$veloria_thumbnail_url = isset($veloria_settings['thumbnail']['url']) ? trim($veloria_settings['thumbnail']['url']) : '';

$veloria_inner_class = 'veloria-video-player__inner';
if ($veloria_thumbnail_url !== '') {
    $veloria_inner_class .= ' veloria-video-player--has-thumbnail';
}
$this->add_render_attribute('inner', 'class', $veloria_inner_class);
$this->add_render_attribute('inner', 'data-embed', esc_attr($veloria_embed_url));
$this->add_render_attribute('inner', 'data-hosted', esc_attr($veloria_hosted_url));
$this->add_render_attribute('inner', 'data-type', esc_attr($veloria_video_type));
?>
<div class="veloria-video-player">
    <div <?php $this->print_render_attribute_string('inner'); ?>>
        <div class="veloria-video-player__overlay">
            <?php if ($veloria_thumbnail_url !== '') : ?>
                <img class="veloria-video-player__thumbnail" src="<?php echo esc_url($veloria_thumbnail_url); ?>" alt="" loading="lazy" decoding="async">
                <span class="veloria-video-player__overlay-shade" aria-hidden="true"></span>
            <?php endif; ?>
            <button type="button" class="veloria-video-player__play" aria-label="<?php esc_attr_e('Play video', 'veloria'); ?>">
                <span class="veloria-video-player__play-icon"></span>
            </button>
            <?php if ($veloria_show_title && $veloria_title !== '' || $veloria_show_subtitle && $veloria_subtitle !== '') : ?>
                <div class="veloria-video-player__caption">
                    <?php if ($veloria_show_title && $veloria_title !== '') : ?>
                        <div class="veloria-video-player__title"><?php echo esc_html($veloria_title); ?></div>
                    <?php endif; ?>
                    <?php if ($veloria_show_subtitle && $veloria_subtitle !== '') : ?>
                        <div class="veloria-video-player__subtitle"><?php echo esc_html($veloria_subtitle); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="veloria-video-player__embed"></div>
    </div>
</div>
