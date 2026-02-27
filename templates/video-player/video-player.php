<?php
defined('ABSPATH') || exit;

$settings  = $this->get_settings_for_display();
$video_type = isset($settings['video_type']) ? $settings['video_type'] : 'youtube';
$embed_url  = '';
$hosted_url = '';

if ($video_type === 'youtube' && !empty($settings['youtube_url']['url'])) {
    $embed_url = \Elementrify\Widgets\VideoPlayer\VideoPlayer::get_embed_url('youtube', $settings['youtube_url']['url']);
} elseif ($video_type === 'vimeo' && !empty($settings['vimeo_url']['url'])) {
    $embed_url = \Elementrify\Widgets\VideoPlayer\VideoPlayer::get_embed_url('vimeo', $settings['vimeo_url']['url']);
} elseif ($video_type === 'dailymotion' && !empty($settings['dailymotion_url']['url'])) {
    $embed_url = \Elementrify\Widgets\VideoPlayer\VideoPlayer::get_embed_url('dailymotion', $settings['dailymotion_url']['url']);
} elseif ($video_type === 'hosted' && !empty($settings['hosted_url']['url'])) {
    $hosted_url = $settings['hosted_url']['url'];
}

$show_title    = !empty($settings['show_title']);
$show_subtitle = !empty($settings['show_subtitle']);
$title         = isset($settings['title']) ? $settings['title'] : '';
$subtitle      = isset($settings['subtitle']) ? $settings['subtitle'] : '';
$thumbnail_url = isset($settings['thumbnail']['url']) ? trim($settings['thumbnail']['url']) : '';

$inner_class = 'elementrify-video-player__inner';
if ($thumbnail_url !== '') {
    $inner_class .= ' elementrify-video-player--has-thumbnail';
}
$this->add_render_attribute('inner', 'class', $inner_class);
$this->add_render_attribute('inner', 'data-embed', esc_attr($embed_url));
$this->add_render_attribute('inner', 'data-hosted', esc_attr($hosted_url));
$this->add_render_attribute('inner', 'data-type', esc_attr($video_type));
?>
<div class="elementrify-video-player">
    <div <?php $this->print_render_attribute_string('inner'); ?>>
        <div class="elementrify-video-player__overlay">
            <?php if ($thumbnail_url !== '') : ?>
                <img class="elementrify-video-player__thumbnail" src="<?php echo esc_url($thumbnail_url); ?>" alt="" loading="lazy" decoding="async">
                <span class="elementrify-video-player__overlay-shade" aria-hidden="true"></span>
            <?php endif; ?>
            <button type="button" class="elementrify-video-player__play" aria-label="<?php esc_attr_e('Play video', 'elementrify'); ?>">
                <span class="elementrify-video-player__play-icon"></span>
            </button>
            <?php if ($show_title && $title !== '' || $show_subtitle && $subtitle !== '') : ?>
                <div class="elementrify-video-player__caption">
                    <?php if ($show_title && $title !== '') : ?>
                        <div class="elementrify-video-player__title"><?php echo esc_html($title); ?></div>
                    <?php endif; ?>
                    <?php if ($show_subtitle && $subtitle !== '') : ?>
                        <div class="elementrify-video-player__subtitle"><?php echo esc_html($subtitle); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="elementrify-video-player__embed"></div>
    </div>
</div>
