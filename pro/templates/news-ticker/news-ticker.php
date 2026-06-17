<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_ticker_title = isset($veloria_settings['ticker_title']) ? trim((string) $veloria_settings['ticker_title']) : '';
$veloria_separator_text = isset($veloria_settings['separator_text']) ? (string) $veloria_settings['separator_text'] : '•';
$veloria_entries = \Veloria\Widgets\NewsTicker\NewsTicker::build_ticker_entries($veloria_settings);

/**
 * Renders one ticker sequence group.
 *
 * @param array<int, array{text: string, url: string}> $veloria_ticker_entries
 * @param string $veloria_separator
 */
$veloria_render_news_ticker_group = static function (array $veloria_ticker_entries, string $veloria_separator): void {
    if ($veloria_ticker_entries === []) {
        echo '<span class="veloria-news-ticker__item">' . esc_html__('Add ticker items from the panel.', 'veloria') . '</span>';

        return;
    }

    foreach ($veloria_ticker_entries as $veloria_ticker_entry) {
        $veloria_item_text = isset($veloria_ticker_entry['text']) ? trim((string) $veloria_ticker_entry['text']) : '';
        $veloria_item_url = isset($veloria_ticker_entry['url']) ? (string) $veloria_ticker_entry['url'] : '';
        if ($veloria_item_text === '') {
            continue;
        }
        if ($veloria_item_url !== '') {
            echo '<a class="veloria-news-ticker__item veloria-news-ticker__item-link" href="' . esc_url($veloria_item_url) . '">';
            echo esc_html($veloria_item_text);
            echo '</a>';
        } else {
            echo '<span class="veloria-news-ticker__item">' . esc_html($veloria_item_text) . '</span>';
        }

        if ($veloria_separator !== '') {
            echo '<span class="veloria-news-ticker__separator" aria-hidden="true">' . esc_html($veloria_separator) . '</span>';
        }
    }
};
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($veloria_ticker_title !== '') : ?>
        <span class="veloria-news-ticker__title"><?php echo esc_html($veloria_ticker_title); ?></span>
    <?php endif; ?>
    <div class="veloria-news-ticker__viewport" role="marquee" aria-label="<?php echo esc_attr__('News ticker', 'veloria'); ?>">
        <div class="veloria-news-ticker__track">
            <div class="veloria-news-ticker__group">
                <?php $veloria_render_news_ticker_group($veloria_entries, $veloria_separator_text); ?>
            </div>
            <div class="veloria-news-ticker__group" aria-hidden="true">
                <?php $veloria_render_news_ticker_group($veloria_entries, $veloria_separator_text); ?>
            </div>
        </div>
    </div>
</div>

<?php
    }
}
