<?php
namespace Addonext\Extensions\Duplicator;

use Addonext\Base\ExtensionBase;
use Addonext\Utils\OptionsHelper;
use WP_Post;

defined('ABSPATH') or exit;

/**
 * Duplicate posts and pages from the admin list tables.
 *
 * @package AddoNext
 * @since 1.0.0
 */
class Duplicator extends ExtensionBase
{
    /**
     * @return string
     */
    public function get_id(): string
    {
        return 'duplicator';
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return __('Duplicator', 'addonext');
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return __('Duplicate posts and pages from the list screen.', 'addonext');
    }

    /**
     * @return string
     */
    public function get_icon(): string
    {
        return 'eicon-copy addonext-icon';
    }

    /**
     * @return string
     */
    public function get_category(): string
    {
        return 'content';
    }

    /**
     * @return string
     */
    public function get_date_launched(): string
    {
        return '2026-04-18';
    }

    /**
     * @return string
     */
    public function get_date_updated(): string
    {
        return '2026-04-18';
    }

    /**
     * @return bool
     */
    public function should_enqueue_frontend_assets(): bool
    {
        return false;
    }

    /**
     * @return void
     */
    public function enqueue_assets(): void
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_fields(): array
    {
        $type_options = [];

        foreach ($this->get_selectable_post_types() as $obj) {
            $type_options[] = [
                'value' => $obj->name,
                'label' => $obj->labels->singular_name ?? $obj->label,
            ];
        }

        return [
            [
                'id'          => 'all_post_types',
                'type'        => 'toggle',
                'label'       => __('All post types', 'addonext'),
                'description' => __('Show Duplicate on every public post type that has an admin list screen.', 'addonext'),
                'default'     => true,
            ],
            [
                'id'          => 'post_types',
                'type'        => 'checkbox_group',
                'label'       => __('Post types', 'addonext'),
                'description' => __('Choose where the Duplicate row action appears.', 'addonext'),
                'default'     => ['post', 'page'],
                'options'     => $type_options,
                'show_when'   => [
                    'field' => 'all_post_types',
                    'value' => false,
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function sanitize_settings(array $input): array
    {
        $merged = $this->merge_settings_defaults(is_array($input) ? $input : []);

        $merged['all_post_types'] = !empty($merged['all_post_types']);

        $allowed = array_map(
            static function ($o) {
                return $o->name;
            },
            $this->get_selectable_post_types()
        );

        $types = isset($merged['post_types']) && is_array($merged['post_types']) ? $merged['post_types'] : [];
        $types = array_values(array_unique(array_map('sanitize_key', $types)));
        $types = array_values(array_intersect($types, $allowed));

        if ($types === []) {
            $types = ['post', 'page'];
        }

        $merged['post_types'] = $types;

        return $merged;
    }

    /**
     * @return void
     */
    public function register_hooks(): void
    {
        add_action('admin_init', [$this, 'register_row_action_filters']);
        add_action('admin_action_addonext_duplicate_post', [$this, 'handle_admin_duplicate']);
    }

    /**
     * @return void
     */
    public function register_row_action_filters(): void
    {
        foreach ($this->get_applicable_post_types() as $post_type) {
            add_filter("{$post_type}_row_actions", [$this, 'append_duplicate_link'], 10, 2);
        }
    }

    /**
     * @param array<string, string> $actions
     * @param int|\WP_Post $post
     * @return array<string, string>
     */
    public function append_duplicate_link(array $actions, $post): array
    {
        $post_obj = get_post($post);

        if (!$post_obj instanceof WP_Post) {
            return $actions;
        }

        if (!current_user_can('edit_post', $post_obj->ID)) {
            return $actions;
        }

        if (!in_array($post_obj->post_type, $this->get_applicable_post_types(), true)) {
            return $actions;
        }

        $url = wp_nonce_url(
            admin_url('admin.php?action=addonext_duplicate_post&post=' . $post_obj->ID),
            'addonext_duplicate_' . $post_obj->ID
        );

        $actions['addonext_duplicate'] = sprintf(
            '<a href="%s" rel="noopener noreferrer">%s</a>',
            esc_url($url),
            esc_html__('Duplicate', 'addonext')
        );

        return $actions;
    }

    /**
     * @return void
     */
    public function handle_admin_duplicate(): void
    {
        if (!isset($_GET['post'])) {
            wp_die(esc_html__('Missing post.', 'addonext'));
        }

        $post_id = (int) $_GET['post'];

        if ($post_id <= 0) {
            wp_die(esc_html__('Invalid post.', 'addonext'));
        }

        check_admin_referer('addonext_duplicate_' . $post_id);

        $post = get_post($post_id);

        if (!$post instanceof WP_Post) {
            wp_die(esc_html__('Post not found.', 'addonext'));
        }

        if (!in_array($post->post_type, $this->get_applicable_post_types(), true)) {
            wp_die(esc_html__('This post type cannot be duplicated.', 'addonext'));
        }

        if (!current_user_can('edit_post', $post_id)) {
            wp_die(esc_html__('You are not allowed to duplicate this post.', 'addonext'));
        }

        $new_id = $this->clone_post($post);

        if (is_wp_error($new_id) || $new_id <= 0) {
            wp_die(esc_html__('Duplication failed.', 'addonext'));
        }

        wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_id));
        exit;
    }

    /**
     * @param \WP_Post $post
     * @return int|\WP_Error
     */
    private function clone_post(WP_Post $post)
    {
        $current = get_current_user_id();

        $new_post = [
            'post_author'       => $current > 0 ? $current : (int) $post->post_author,
            'post_content'      => $post->post_content,
            'post_title'        => $post->post_title . ' ' . __('(Copy)', 'addonext'),
            'post_excerpt'      => $post->post_excerpt,
            'post_status'       => 'draft',
            'post_type'         => $post->post_type,
            'post_parent'       => (int) $post->post_parent,
            'menu_order'        => (int) $post->menu_order,
            'comment_status'    => $post->comment_status,
            'ping_status'       => $post->ping_status,
            'post_password'     => $post->post_password,
            'to_ping'           => $post->to_ping,
            'post_content_filtered' => $post->post_content_filtered,
        ];

        $new_id = wp_insert_post($new_post, true);

        if (is_wp_error($new_id)) {
            return $new_id;
        }

        $taxonomies = get_object_taxonomies($post->post_type);

        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_object_terms($post->ID, $taxonomy, ['fields' => 'slugs']);

            if (is_wp_error($terms) || $terms === []) {
                continue;
            }

            wp_set_object_terms($new_id, $terms, $taxonomy);
        }

        $meta = get_post_custom($post->ID);

        foreach ($meta as $meta_key => $values) {
            foreach ($values as $meta_value) {
                add_post_meta($new_id, $meta_key, maybe_unserialize($meta_value));
            }
        }

        return (int) $new_id;
    }

    /**
     * Post types that appear in settings (public, UI).
     *
     * @return array<int, \WP_Post_Type>
     */
    private function get_selectable_post_types(): array
    {
        $types = get_post_types(
            [
                'public'  => true,
                'show_ui' => true,
            ],
            'objects'
        );

        return array_values(
            array_filter(
                $types,
                static function ($t) {
                    return $t->name !== 'attachment';
                }
            )
        );
    }

    /**
     * Post types that currently receive the duplicate row action.
     *
     * @return array<int, string>
     */
    private function get_applicable_post_types(): array
    {
        $settings = $this->merge_settings_defaults(
            $this->read_settings_from_option()
        );

        if (!empty($settings['all_post_types'])) {
            return array_map(
                static function ($o) {
                    return $o->name;
                },
                $this->get_selectable_post_types()
            );
        }

        $chosen = isset($settings['post_types']) && is_array($settings['post_types'])
            ? $settings['post_types']
            : [];

        $allowed = array_map(
            static function ($o) {
                return $o->name;
            },
            $this->get_selectable_post_types()
        );

        $chosen = array_values(array_intersect(array_map('sanitize_key', $chosen), $allowed));

        return $chosen !== [] ? $chosen : ['post', 'page'];
    }

    /**
     * @return array<string, mixed>
     */
    private function read_settings_from_option(): array
    {
        $all = OptionsHelper::get_option('extension_settings', []);

        if (!is_array($all) || !isset($all[$this->get_id()]) || !is_array($all[$this->get_id()])) {
            return [];
        }

        return $all[$this->get_id()];
    }
}
