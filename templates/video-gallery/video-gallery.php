<?php
/**
 * Video Gallery widget template.
 *
 * @var \Veloria\Widgets\VideoGallery\VideoGallery $this
 */

defined('ABSPATH') || exit;

use Veloria\Widgets\VideoPlayer\VideoPlayer;

$veloria_settings = $this->get_settings_for_display();
$veloria_items    = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];

if ($veloria_items === []) {
    return;
}

$this->add_render_attribute('wrap', 'class', 'veloria-video-gallery');
?>
<div <?php $this->print_render_attribute_string('wrap'); ?>>
    <div class="veloria-video-gallery__grid">
        <?php
        foreach ($veloria_items as $veloria_index => $veloria_item) {
            $veloria_video_type = isset($veloria_item['video_type']) ? (string) $veloria_item['video_type'] : 'youtube';
            $veloria_embed_url  = '';
            $veloria_hosted_url = '';

            if ($veloria_video_type === 'youtube' && !empty($veloria_item['youtube_url']['url'])) {
                $veloria_embed_url = VideoPlayer::get_embed_url('youtube', $veloria_item['youtube_url']['url']);
            } elseif ($veloria_video_type === 'vimeo' && !empty($veloria_item['vimeo_url']['url'])) {
                $veloria_embed_url = VideoPlayer::get_embed_url('vimeo', $veloria_item['vimeo_url']['url']);
            } elseif ($veloria_video_type === 'dailymotion' && !empty($veloria_item['dailymotion_url']['url'])) {
                $veloria_embed_url = VideoPlayer::get_embed_url('dailymotion', $veloria_item['dailymotion_url']['url']);
            } elseif ($veloria_video_type === 'hosted' && !empty($veloria_item['hosted_url']['url'])) {
                $veloria_hosted_url = $veloria_item['hosted_url']['url'];
            }

            $veloria_show_title    = !empty($veloria_item['show_title']);
            $veloria_show_subtitle = !empty($veloria_item['show_subtitle']);
            $veloria_title         = isset($veloria_item['title']) ? (string) $veloria_item['title'] : '';
            $veloria_subtitle      = isset($veloria_item['subtitle']) ? (string) $veloria_item['subtitle'] : '';
            $veloria_thumbnail_url = isset($veloria_item['thumbnail']['url']) ? trim((string) $veloria_item['thumbnail']['url']) : '';

            $veloria_inner_class = 'veloria-video-player__inner';
            if ($veloria_thumbnail_url !== '') {
                $veloria_inner_class .= ' veloria-video-player--has-thumbnail';
            }

            $veloria_item_id = isset($veloria_item['_id']) ? (string) $veloria_item['_id'] : (string) $veloria_index;
            ?>
            <div class="veloria-video-gallery__cell elementor-repeater-item-<?php echo esc_attr($veloria_item_id); ?>">
                <div class="veloria-video-player veloria-video-player--gallery-tile">
                    <div
                        class="<?php echo esc_attr($veloria_inner_class); ?>"
                        data-embed="<?php echo esc_attr($veloria_embed_url); ?>"
                        data-hosted="<?php echo esc_attr($veloria_hosted_url); ?>"
                        data-type="<?php echo esc_attr($veloria_video_type); ?>"
                    >
                        <div class="veloria-video-player__overlay">
                            <?php if ($veloria_thumbnail_url !== '') : ?>
                                <img class="veloria-video-player__thumbnail" src="<?php echo esc_url($veloria_thumbnail_url); ?>" alt="" loading="lazy" decoding="async">
                                <span class="veloria-video-player__overlay-shade" aria-hidden="true"></span>
                            <?php endif; ?>
                            <button type="button" class="veloria-video-gallery__play" aria-label="<?php esc_attr_e('Play video', 'veloria'); ?>">
                                <span class="veloria-video-gallery__play-icon"></span>
                            </button>
                            <?php if (($veloria_show_title && $veloria_title !== '') || ($veloria_show_subtitle && $veloria_subtitle !== '')) : ?>
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
