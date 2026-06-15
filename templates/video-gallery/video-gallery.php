<?php
/**
 * Video Gallery widget template.
 *
 * @var \Veloria\Widgets\VideoGallery\VideoGallery $this
 */

defined('ABSPATH') || exit;

use Veloria\Widgets\VideoPlayer\VideoPlayer;

$settings = $this->get_settings_for_display();
$items    = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];

if ($items === []) {
    return;
}

$this->add_render_attribute('wrap', 'class', 'veloria-video-gallery');
?>
<div <?php $this->print_render_attribute_string('wrap'); ?>>
    <div class="veloria-video-gallery__grid">
        <?php
        foreach ($items as $index => $item) {
            $video_type = isset($item['video_type']) ? (string) $item['video_type'] : 'youtube';
            $embed_url  = '';
            $hosted_url = '';

            if ($video_type === 'youtube' && !empty($item['youtube_url']['url'])) {
                $embed_url = VideoPlayer::get_embed_url('youtube', $item['youtube_url']['url']);
            } elseif ($video_type === 'vimeo' && !empty($item['vimeo_url']['url'])) {
                $embed_url = VideoPlayer::get_embed_url('vimeo', $item['vimeo_url']['url']);
            } elseif ($video_type === 'dailymotion' && !empty($item['dailymotion_url']['url'])) {
                $embed_url = VideoPlayer::get_embed_url('dailymotion', $item['dailymotion_url']['url']);
            } elseif ($video_type === 'hosted' && !empty($item['hosted_url']['url'])) {
                $hosted_url = $item['hosted_url']['url'];
            }

            $show_title    = !empty($item['show_title']);
            $show_subtitle = !empty($item['show_subtitle']);
            $title         = isset($item['title']) ? (string) $item['title'] : '';
            $subtitle      = isset($item['subtitle']) ? (string) $item['subtitle'] : '';
            $thumbnail_url = isset($item['thumbnail']['url']) ? trim((string) $item['thumbnail']['url']) : '';

            $inner_class = 'veloria-video-player__inner';
            if ($thumbnail_url !== '') {
                $inner_class .= ' veloria-video-player--has-thumbnail';
            }

            $item_id = isset($item['_id']) ? (string) $item['_id'] : (string) $index;
            ?>
            <div class="veloria-video-gallery__cell elementor-repeater-item-<?php echo esc_attr($item_id); ?>">
                <div class="veloria-video-player veloria-video-player--gallery-tile">
                    <div
                        class="<?php echo esc_attr($inner_class); ?>"
                        data-embed="<?php echo esc_attr($embed_url); ?>"
                        data-hosted="<?php echo esc_attr($hosted_url); ?>"
                        data-type="<?php echo esc_attr($video_type); ?>"
                    >
                        <div class="veloria-video-player__overlay">
                            <?php if ($thumbnail_url !== '') : ?>
                                <img class="veloria-video-player__thumbnail" src="<?php echo esc_url($thumbnail_url); ?>" alt="" loading="lazy" decoding="async">
                                <span class="veloria-video-player__overlay-shade" aria-hidden="true"></span>
                            <?php endif; ?>
                            <button type="button" class="veloria-video-gallery__play" aria-label="<?php esc_attr_e('Play video', 'veloria'); ?>">
                                <span class="veloria-video-gallery__play-icon"></span>
                            </button>
                            <?php if (($show_title && $title !== '') || ($show_subtitle && $subtitle !== '')) : ?>
                                <div class="veloria-video-player__caption">
                                    <?php if ($show_title && $title !== '') : ?>
                                        <div class="veloria-video-player__title"><?php echo esc_html($title); ?></div>
                                    <?php endif; ?>
                                    <?php if ($show_subtitle && $subtitle !== '') : ?>
                                        <div class="veloria-video-player__subtitle"><?php echo esc_html($subtitle); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="veloria-video-gallery__lightbox" hidden data-open="0">
        <div class="veloria-video-gallery__lightbox-backdrop" tabindex="-1" aria-hidden="true"></div>
        <div class="veloria-video-gallery__lightbox-dialog" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__('Video', 'veloria'); ?>">
            <button type="button" class="veloria-video-gallery__lightbox-close" aria-label="<?php esc_attr_e('Close', 'veloria'); ?>">
                <span class="veloria-video-gallery__lightbox-close-icon" aria-hidden="true">&times;</span>
            </button>
            <div class="veloria-video-gallery__lightbox-stage">
                <div class="veloria-video-gallery__lightbox-embed"></div>
            </div>
        </div>
    </div>
</div>
