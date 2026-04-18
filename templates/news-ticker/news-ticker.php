<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$ticker_title = isset($settings['ticker_title']) ? trim((string) $settings['ticker_title']) : '';
$separator_text = isset($settings['separator_text']) ? (string) $settings['separator_text'] : '•';
$entries = \Elementrify\Widgets\NewsTicker\NewsTicker::build_ticker_entries($settings);

/**
 * Renders one ticker sequence group.
 *
 * @param array<int, array{text: string, url: string}> $ticker_entries
 * @param string $separator
 */
$render_news_ticker_group = static function (array $ticker_entries, string $separator): void {
    if ($ticker_entries === []) {
        echo '<span class="elementrify-news-ticker__item">' . esc_html__('Add ticker items from the panel.', 'elementrify') . '</span>';

        return;
    }

    foreach ($ticker_entries as $ticker_entry) {
        $item_text = isset($ticker_entry['text']) ? trim((string) $ticker_entry['text']) : '';
        $item_url = isset($ticker_entry['url']) ? (string) $ticker_entry['url'] : '';
        if ($item_text === '') {
            continue;
        }
        if ($item_url !== '') {
            echo '<a class="elementrify-news-ticker__item elementrify-news-ticker__item-link" href="' . esc_url($item_url) . '">';
            echo esc_html($item_text);
            echo '</a>';
        } else {
            echo '<span class="elementrify-news-ticker__item">' . esc_html($item_text) . '</span>';
        }

        if ($separator !== '') {
            echo '<span class="elementrify-news-ticker__separator" aria-hidden="true">' . esc_html($separator) . '</span>';
        }
    }
};
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php if ($ticker_title !== '') : ?>
        <span class="elementrify-news-ticker__title"><?php echo esc_html($ticker_title); ?></span>
    <?php endif; ?>
    <div class="elementrify-news-ticker__viewport" role="marquee" aria-label="<?php echo esc_attr__('News ticker', 'elementrify'); ?>">
        <div class="elementrify-news-ticker__track">
            <div class="elementrify-news-ticker__group">
                <?php $render_news_ticker_group($entries, $separator_text); ?>
            </div>
            <div class="elementrify-news-ticker__group" aria-hidden="true">
                <?php $render_news_ticker_group($entries, $separator_text); ?>
            </div>
        </div>
    </div>
</div>

<?php
    }
}
