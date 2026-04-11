<?php
namespace Elementrify\Widgets\TwitterFeed;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * X (Twitter) feed using Twitter API v2 (Bearer token + username).
 */
class TwitterFeed extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-twitter-feed elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-twitter-feed';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Twitter Feed', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Show recent posts from an X (Twitter) account using API v2.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#1da1f2';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-twitter-feed'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-twitter-feed',
            ELEMENTRIFY_URL . 'assets/css/twitter-feed.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    /**
     * Maps cache setting to seconds (same idea as Instagram Feed).
     */
    public static function get_cache_duration(string $select): int
    {
        $map = [
            'minute' => MINUTE_IN_SECONDS,
            'hour'   => HOUR_IN_SECONDS,
            'day'    => DAY_IN_SECONDS,
            'week'   => WEEK_IN_SECONDS,
        ];

        return isset($map[$select]) ? $map[$select] : 0;
    }

    /**
     * Normalizes a handle for Twitter API (no @, lowercase, allowed chars only).
     */
    public static function normalize_username(string $raw): string
    {
        $u = strtolower(trim($raw));
        $u = ltrim($u, '@');
        $u = preg_replace('/[^a-z0-9_]/', '', $u);
        if ($u === '' || strlen($u) > 15) {
            return '';
        }

        return $u;
    }

    /**
     * GET JSON from Twitter API v2.
     *
     * @param string               $path    Path beginning with /2/
     * @param string               $bearer  OAuth 2.0 Bearer token
     * @param array<string, string> $query   Query string pairs
     * @return array{ok:bool, code:int, data:array<string, mixed>|null}
     */
    private function twitter_api_get(string $path, string $bearer, array $query = []): array
    {
        $url = 'https://api.twitter.com' . $path;
        if ($query !== []) {
            $url .= '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }

        $response = wp_remote_get(
            $url,
            [
                'timeout' => 20,
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearer,
                ],
            ]
        );

        if (is_wp_error($response)) {
            return ['ok' => false, 'code' => 0, 'data' => null];
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        $body = json_decode((string) wp_remote_retrieve_body($response), true);

        return [
            'ok'   => $code >= 200 && $code < 300 && is_array($body),
            'code' => $code,
            'data' => is_array($body) ? $body : null,
        ];
    }

    /**
     * Fetches recent tweets for a username (cached).
     *
     * @param array<string, mixed> $settings Widget settings from Elementor.
     * @return array<int, array<string, mixed>>
     */
    public function fetch_tweets(string $username, string $bearer_token, array $settings): array
    {
        $username = self::normalize_username($username);
        $bearer_token = trim(preg_replace('/\s+/', '', $bearer_token));
        if ($username === '' || $bearer_token === '') {
            return [];
        }

        $limit = isset($settings['tweet_limit']) ? (int) $settings['tweet_limit'] : 5;
        $limit = max(1, min(100, $limit));
        $cache_key = 'elementrify_tf_' . $this->get_id() . '_' . md5(
            $username . '|' . substr(hash('sha256', $bearer_token), 0, 16) . '|' . $limit . '|' . (string) ($settings['cache_timeout'] ?? 'hour')
        );

        $cached = get_transient($cache_key);
        if (is_array($cached) && isset($cached['tweets']) && is_array($cached['tweets'])) {
            return array_slice($cached['tweets'], 0, $limit);
        }

        $user_res = $this->twitter_api_get('/2/users/by/username/' . rawurlencode($username), $bearer_token, ['user.fields' => 'name,username,profile_image_url']);
        if (!$user_res['ok'] || !is_array($user_res['data'])) {
            set_transient($cache_key, ['tweets' => [], 'error' => 'lookup'], MINUTE_IN_SECONDS);

            return [];
        }

        $user_payload = $user_res['data'];
        $user_data = isset($user_payload['data']) && is_array($user_payload['data']) ? $user_payload['data'] : null;
        if (!is_array($user_data) || empty($user_data['id'])) {
            set_transient($cache_key, ['tweets' => [], 'error' => 'user'], MINUTE_IN_SECONDS);

            return [];
        }

        $user_id = (string) $user_data['id'];
        $api_max = max(5, min(100, $limit));

        $tweet_query = http_build_query(
            [
                'max_results'  => (string) $api_max,
                'tweet.fields' => 'created_at,public_metrics,conversation_id',
            ],
            '',
            '&',
            PHP_QUERY_RFC3986
        );
        $tweet_query .= '&exclude=retweets&exclude=replies';

        $tweet_res = $this->twitter_api_get(
            '/2/users/' . rawurlencode($user_id) . '/tweets?' . $tweet_query,
            $bearer_token,
            []
        );

        if (!$tweet_res['ok'] || !is_array($tweet_res['data'])) {
            set_transient($cache_key, ['tweets' => [], 'error' => 'tweets'], MINUTE_IN_SECONDS);

            return [];
        }

        $rows = $tweet_res['data']['data'] ?? [];
        if (!is_array($rows)) {
            $rows = [];
        }

        $tweets = [];
        foreach ($rows as $tweet) {
            if (!is_array($tweet) || empty($tweet['id'])) {
                continue;
            }
            $metrics = isset($tweet['public_metrics']) && is_array($tweet['public_metrics']) ? $tweet['public_metrics'] : [];
            $tweets[] = [
                'id'            => (string) $tweet['id'],
                'text'          => isset($tweet['text']) ? (string) $tweet['text'] : '',
                'created_at'    => isset($tweet['created_at']) ? (string) $tweet['created_at'] : '',
                'like_count'    => (int) ($metrics['like_count'] ?? 0),
                'retweet_count' => (int) ($metrics['retweet_count'] ?? 0),
                'reply_count'   => (int) ($metrics['reply_count'] ?? 0),
                'quote_count'   => (int) ($metrics['quote_count'] ?? 0),
            ];
            if (count($tweets) >= $limit) {
                break;
            }
        }

        $duration = self::get_cache_duration((string) ($settings['cache_timeout'] ?? 'hour'));
        if ($duration > 0 && $tweets !== []) {
            set_transient($cache_key, ['tweets' => $tweets], $duration);
        }

        return $tweets;
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_api', [
            'label' => __('API', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('username', [
            'label'       => __('X username', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'placeholder' => 'elonmusk',
            'description' => __('Without @. Requires a Twitter Developer app with read access.', 'elementrify'),
        ]);

        $this->add_control('bearer_token', [
            'label'       => __('Bearer token', 'elementrify'),
            'type'        => Controls_Manager::TEXTAREA,
            'placeholder' => __('Paste OAuth 2.0 Bearer Token (App only)', 'elementrify'),
            'description' => __(
                'Create an app at developer.x.com, generate a Bearer Token, and ensure the project can access user tweet timelines (API v2).',
                'elementrify'
            ),
            'rows'        => 3,
        ]);

        $this->add_control('tweet_limit', [
            'label'   => __('Number of posts', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 100,
            'default' => 5,
        ]);

        $this->add_control('cache_timeout', [
            'label'   => __('Cache duration', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'hour',
            'options' => [
                'none'   => __('None', 'elementrify'),
                'minute' => __('1 minute', 'elementrify'),
                'hour'   => __('1 hour', 'elementrify'),
                'day'    => __('1 day', 'elementrify'),
                'week'   => __('1 week', 'elementrify'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_display', [
            'label' => __('Display', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('open_new_tab', [
            'label'        => __('Open links in new tab', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_date', [
            'label'        => __('Show date', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_metrics', [
            'label'        => __('Show engagement counts', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_profile_link', [
            'label'        => __('Show profile link', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_box', [
            'label' => __('Cards', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label'     => __('Background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-twitter-feed__item' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_border', [
            'label'     => __('Border color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e2e8f0',
            'selectors' => [
                '{{WRAPPER}} .elementrify-twitter-feed__item' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label'      => __('Radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-twitter-feed__item' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'label'    => __('Post text', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-twitter-feed__text',
            ]
        );

        $this->add_control('link_color', [
            'label'     => __('Link color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1d9bf0',
            'selectors' => [
                '{{WRAPPER}} .elementrify-twitter-feed__permalink' => 'color: {{VALUE}};',
                '{{WRAPPER}} .elementrify-twitter-feed__profile' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $username = isset($settings['username']) ? (string) $settings['username'] : '';
        $token = isset($settings['bearer_token']) ? trim((string) $settings['bearer_token']) : '';

        if ($token === '' || self::normalize_username($username) === '') {
            if (current_user_can('manage_options')) {
                echo '<div class="elementrify-twitter-feed elementrify-twitter-feed--empty"><p class="elementrify-twitter-feed__notice">';
                echo esc_html__('Add an X username and Bearer token in the widget settings.', 'elementrify');
                echo '</p></div>';
            }

            return;
        }

        $tweets = $this->fetch_tweets($username, $token, $settings);
        $tweets = apply_filters('elementrify_twitter_feed_tweets', $tweets, $settings, $username);

        if ($tweets === []) {
            if (current_user_can('manage_options')) {
                echo '<div class="elementrify-twitter-feed elementrify-twitter-feed--empty"><p class="elementrify-twitter-feed__notice">';
                echo esc_html__('No posts returned. Check the username, token, and API access (v2 user timeline).', 'elementrify');
                echo '</p></div>';
            }

            return;
        }

        $display_username = self::normalize_username($username);
        $path = ELEMENTRIFY_DIR . 'templates/twitter-feed/twitter-feed.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <# if (!settings.username || !settings.bearer_token) { #>
            <div class="elementrify-twitter-feed elementrify-twitter-feed--empty">
                <p class="elementrify-twitter-feed__notice"><?php echo esc_html__('Add username and Bearer token to load posts in the preview.', 'elementrify'); ?></p>
            </div>
        <# } else { #>
            <div class="elementrify-twitter-feed">
                <p class="elementrify-twitter-feed__notice"><?php echo esc_html__('Editor: posts load on the front end after saving.', 'elementrify'); ?></p>
                <p class="elementrify-twitter-feed__preview-user">@{{ settings.username }}</p>
            </div>
        <# } #>
        <?php
    }
}
