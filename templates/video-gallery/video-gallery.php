<?php
/**
 * Video Gallery widget template.
 *
 * @var \Addonext\Widgets\VideoGallery\VideoGallery $this
 */

defined('ABSPATH') || exit;

use Addonext\Widgets\VideoPlayer\VideoPlayer;

$addonext_settings = $this->get_settings_for_display();
$addonext_items    = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];

if ($addonext_items === []) {
    return;
}

$this->add_render_attribute('wrap', 'class', 'addonext-video-gallery');
?>
<div <?php $this->print_render_attribute_string('wrap'); ?>>
    <div class="addonext-video-gallery__grid">
        <?php
        foreach ($addonext_items as $addonext_index => $addonext_item) {
            $addonext_video_type = isset($addonext_item['video_type']) ? (string) $addonext_item['video_type'] : 'youtube';
            $addonext_embed_url  = '';
            $addonext_hosted_url = '';

            if ($addonext_video_type === 'youtube' && !empty($addonext_item['youtube_url']['url'])) {
                $addonext_embed_url = VideoPlayer::get_embed_url('youtube', $addonext_item['youtube_url']['url']);
            } elseif ($addonext_video_type === 'vimeo' && !empty($addonext_item['vimeo_url']['url'])) {
                $addonext_embed_url = VideoPlayer::get_embed_url('vimeo', $addonext_item['vimeo_url']['url']);
            } elseif ($addonext_video_type === 'dailymotion' && !empty($addonext_item['dailymotion_url']['url'])) {
                $addonext_embed_url = VideoPlayer::get_embed_url('dailymotion', $addonext_item['dailymotion_url']['url']);
            } elseif ($addonext_video_type === 'hosted' && !empty($addonext_item['hosted_url']['url'])) {
                $addonext_hosted_url = $addonext_item['hosted_url']['url'];
            }

            $addonext_show_title    = !empty($addonext_item['show_title']);
            $addonext_show_subtitle = !empty($addonext_item['show_subtitle']);
            $addonext_title         = isset($addonext_item['title']) ? (string) $addonext_item['title'] : '';
            $addonext_subtitle      = isset($addonext_item['subtitle']) ? (string) $addonext_item['subtitle'] : '';
            $addonext_thumbnail_url = isset($addonext_item['thumbnail']['url']) ? trim((string) $addonext_item['thumbnail']['url']) : '';

            $addonext_inner_class = 'addonext-video-player__inner';
            if ($addonext_thumbnail_url !== '') {
                $addonext_inner_class .= ' addonext-video-player--has-thumbnail';
            }

            $addonext_item_id = isset($addonext_item['_id']) ? (string) $addonext_item['_id'] : (string) $addonext_index;
            ?>
            <div class="addonext-video-gallery__cell elementor-repeater-item-<?php echo esc_attr($addonext_item_id); ?>">
                <div class="addonext-video-player addonext-video-player--gallery-tile">
                    <div
                        class="<?php echo esc_attr($addonext_inner_class); ?>"
                        data-embed="<?php echo esc_attr($addonext_embed_url); ?>"
                        data-hosted="<?php echo esc_attr($addonext_hosted_url); ?>"
                        data-type="<?php echo esc_attr($addonext_video_type); ?>"
                    >
                        <div class="addonext-video-player__overlay">
                            <?php if ($addonext_thumbnail_url !== '') : ?>
                                <img class="addonext-video-player__thumbnail" src="<?php echo esc_url($addonext_thumbnail_url); ?>" alt="" loading="lazy" decoding="async">
                                <span class="addonext-video-player__overlay-shade" aria-hidden="true"></span>
                            <?php endif; ?>
                            <button type="button" class="addonext-video-gallery__play" aria-label="<?php esc_attr_e('Play video', 'addonext'); ?>">
                                <span class="addonext-video-gallery__play-icon"></span>
                            </button>
                            <?php if (($addonext_show_title && $addonext_title !== '') || ($addonext_show_subtitle && $addonext_subtitle !== '')) : ?>
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
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="addonext-video-gallery__lightbox" hidden data-open="0">
        <div class="addonext-video-gallery__lightbox-backdrop" tabindex="-1" aria-hidden="true"></div>
        <div class="addonext-video-gallery__lightbox-dialog" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__('Video', 'addonext'); ?>">
            <button type="button" class="addonext-video-gallery__lightbox-close" aria-label="<?php esc_attr_e('Close', 'addonext'); ?>">
                <span class="addonext-video-gallery__lightbox-close-icon" aria-hidden="true">&times;</span>
            </button>
            <div class="addonext-video-gallery__lightbox-stage">
                <div class="addonext-video-gallery__lightbox-embed"></div>
            </div>
        </div>
    </div>
</div>
