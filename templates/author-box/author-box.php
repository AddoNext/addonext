<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Plugin;

$veloria_settings = $this->get_settings_for_display();
$veloria_name = isset($veloria_settings['name']) ? (string) $veloria_settings['name'] : '';
$veloria_jobTitle = isset($veloria_settings['job_title']) ? (string) $veloria_settings['job_title'] : '';
$veloria_bio = isset($veloria_settings['bio']) ? (string) $veloria_settings['bio'] : '';
$veloria_avatar = isset($veloria_settings['avatar']) && is_array($veloria_settings['avatar']) ? $veloria_settings['avatar'] : [];
$veloria_profileLink = isset($veloria_settings['profile_link']) && is_array($veloria_settings['profile_link']) ? $veloria_settings['profile_link'] : [];
$veloria_hasNameLink = !empty($veloria_profileLink['url']);
$veloria_showSocial = isset($veloria_settings['show_social']) && $veloria_settings['show_social'] === 'yes';
$veloria_showDivider = isset($veloria_settings['show_divider']) && $veloria_settings['show_divider'] === 'yes';
$veloria_showStats = isset($veloria_settings['show_stats']) && $veloria_settings['show_stats'] === 'yes';
$veloria_socialItems = isset($veloria_settings['social_items']) && is_array($veloria_settings['social_items']) ? $veloria_settings['social_items'] : [];
$veloria_statItems = isset($veloria_settings['stat_items']) && is_array($veloria_settings['stat_items']) ? $veloria_settings['stat_items'] : [];
$veloria_isEditor = Plugin::$instance->editor->is_edit_mode();

$this->add_render_attribute('job_title', 'class', 'veloria-author-box__job');
$this->add_inline_editing_attributes('job_title', 'basic');
$this->add_render_attribute('bio', 'class', 'veloria-author-box__bio');
$this->add_inline_editing_attributes('bio', 'advanced');

if ($veloria_hasNameLink) {
    $this->add_render_attribute('name', 'class', 'veloria-author-box__name');
    $this->add_link_attributes('name', $veloria_profileLink);
    $this->add_inline_editing_attributes('name', 'basic');
} else {
    $this->add_render_attribute('name', 'class', 'veloria-author-box__name');
    $this->add_inline_editing_attributes('name', 'basic');
}
?>
<div class="veloria-author-box">
    <div class="veloria-author-box__card">
        <div class="veloria-author-box__top">
            <?php if (!empty($veloria_avatar['id']) || !empty($veloria_avatar['url'])) : ?>
                <div class="veloria-author-box__avatar-wrap">
                    <?php
                    if (!empty($veloria_avatar['id'])) {
                        echo wp_get_attachment_image(
                            (int) $veloria_avatar['id'],
                            'medium',
                            false,
                            ['class' => 'veloria-author-box__avatar', 'alt' => esc_attr($veloria_name)]
                        );
                    } else {
                        printf(
                            '<img src="%s" alt="%s" class="veloria-author-box__avatar" />',
                            esc_url($veloria_avatar['url']),
                            esc_attr($veloria_name)
                        );
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="veloria-author-box__main">
                <?php if ($veloria_name !== '' || $veloria_isEditor) : ?>
                    <?php if ($veloria_hasNameLink) : ?>
                        <h3 class="veloria-author-box__name-heading">
                            <a <?php $this->print_render_attribute_string('name'); ?>><?php echo esc_html($veloria_name); ?></a>
                        </h3>
                    <?php else : ?>
                        <h3 <?php $this->print_render_attribute_string('name'); ?>><?php echo esc_html($veloria_name); ?></h3>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($veloria_jobTitle !== '' || $veloria_isEditor) : ?>
                    <p <?php $this->print_render_attribute_string('job_title'); ?>><?php echo esc_html($veloria_jobTitle); ?></p>
                <?php endif; ?>

                <?php if ($veloria_bio !== '' || $veloria_isEditor) : ?>
                    <div <?php $this->print_render_attribute_string('bio'); ?>><?php echo wp_kses_post($veloria_bio); ?></div>
                <?php endif; ?>

                <?php if ($veloria_showSocial && !empty($veloria_socialItems)) : ?>
                    <ul class="veloria-author-box__social">
                        <?php foreach ($veloria_socialItems as $veloria_socialIndex => $veloria_item) : ?>
                            <?php
                            $veloria_network = isset($veloria_item['network']) ? preg_replace('/[^a-z]/', '', (string) $veloria_item['network']) : 'twitter';
                            if (!in_array($veloria_network, ['twitter', 'linkedin', 'github', 'website'], true)) {
                                $veloria_network = 'twitter';
                            }
                            $veloria_iconData = $veloria_item['selected_icon'] ?? $veloria_item['icon'] ?? [];
                            $veloria_linkData = isset($veloria_item['link']) && is_array($veloria_item['link']) ? $veloria_item['link'] : [];
                            $veloria_socialKey = 'social-link-' . (int) $veloria_socialIndex;
                            if (!empty($veloria_linkData['url'])) {
                                $this->add_link_attributes($veloria_socialKey, $veloria_linkData);
                            } else {
                                $this->add_render_attribute($veloria_socialKey, 'href', '#');
                            }
                            $this->add_render_attribute($veloria_socialKey, 'class', 'veloria-author-box__social-link veloria-author-box__social-link--' . $veloria_network);
                            $this->add_render_attribute($veloria_socialKey, 'aria-label', ucfirst($veloria_network));
                            ?>
                            <li class="veloria-author-box__social-item">
                                <a <?php $this->print_render_attribute_string($veloria_socialKey); ?>>
                                    <?php
                                    if (!empty($veloria_iconData['value'])) {
                                        Icons_Manager::render_icon($veloria_iconData, ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($veloria_showDivider && $veloria_showStats && !empty($veloria_statItems)) : ?>
            <div class="veloria-author-box__divider" aria-hidden="true"></div>
        <?php endif; ?>

        <?php if ($veloria_showStats && !empty($veloria_statItems)) : ?>
            <div class="veloria-author-box__stats">
                <?php foreach ($veloria_statItems as $veloria_index => $veloria_st) : ?>
                    <?php
                    $veloria_valueKey = 'stat_items.' . $veloria_index . '.stat_value';
                    $veloria_labelKey = 'stat_items.' . $veloria_index . '.stat_label';
                    $this->add_render_attribute($veloria_valueKey, 'class', 'veloria-author-box__stat-value');
                    $this->add_inline_editing_attributes($veloria_valueKey, 'basic');
                    $this->add_render_attribute($veloria_labelKey, 'class', 'veloria-author-box__stat-label');
                    $this->add_inline_editing_attributes($veloria_labelKey, 'basic');
                    ?>
                    <div class="veloria-author-box__stat">
                        <span <?php $this->print_render_attribute_string($veloria_valueKey); ?>><?php echo esc_html((string) ($veloria_st['stat_value'] ?? '')); ?></span>
                        <span <?php $this->print_render_attribute_string($veloria_labelKey); ?>><?php echo esc_html((string) ($veloria_st['stat_label'] ?? '')); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
