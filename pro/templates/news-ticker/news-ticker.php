<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_ticker_title = isset($addonext_settings['ticker_title']) ? trim((string) $addonext_settings['ticker_title']) : '';
$addonext_separator_text = isset($addonext_settings['separator_text']) ? (string) $addonext_settings['separator_text'] : '•';
$addonext_entries = \Addonext\Widgets\NewsTicker\NewsTicker::build_ticker_entries($addonext_settings);

/**
 * Renders one ticker sequence group.
 *
 * @param array<int, array{text: string, url: string}> $addonext_ticker_entries
 * @param string $addonext_separator
 */
$addonext_render_news_ticker_group = static function (array $addonext_ticker_entries, string $addonext_separator): void {
    if ($addonext_ticker_entries === []) {
        echo '<span class="addonext-news-ticker__item">' . esc_html__('Add ticker items from the panel.', 'addonext') . '</span>';

        return;
    }

    foreach ($addonext_ticker_entries as $addonext_ticker_entry) {
        $addonext_item_text = isset($addonext_ticker_entry['text']) ? trim((string) $addonext_ticker_entry['text']) : '';
        $addonext_item_url = isset($addonext_ticker_entry['url']) ? (string) $addonext_ticker_entry['url'] : '';
        if ($addonext_item_text === '') {
            continue;
        }
        if ($addonext_item_url !== '') {
            echo '<a class="addonext-news-ticker__item addonext-news-ticker__item-link" href="' . esc_url($addonext_item_url) . '">';
            echo esc_html($addonext_item_text);
            echo '</a>';
        } else {
            echo '<span class="addonext-news-ticker__item">' . esc_html($addonext_item_text) . '</span>';
        }

        if ($addonext_separator !== '') {
            echo '<span class="addonext-news-ticker__separator" aria-hidden="true">' . esc_html($addonext_separator) . '</span>';
        }
    }
};
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($addonext_ticker_title !== '') : ?>
        <span class="addonext-news-ticker__title"><?php echo esc_html($addonext_ticker_title); ?></span>
    <?php endif; ?>
    <div class="addonext-news-ticker__viewport" role="marquee" aria-label="<?php echo esc_attr__('News ticker', 'addonext'); ?>">
        <div class="addonext-news-ticker__track">
            <div class="addonext-news-ticker__group">
                <?php $addonext_render_news_ticker_group($addonext_entries, $addonext_separator_text); ?>
            </div>
            <div class="addonext-news-ticker__group" aria-hidden="true">
                <?php $addonext_render_news_ticker_group($addonext_entries, $addonext_separator_text); ?>
            </div>
        </div>
    </div>
</div>

<?php
    }
}
