<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Plugin;

$addonext_settings = $this->get_settings_for_display();
$addonext_name = isset($addonext_settings['name']) ? (string) $addonext_settings['name'] : '';
$addonext_jobTitle = isset($addonext_settings['job_title']) ? (string) $addonext_settings['job_title'] : '';
$addonext_bio = isset($addonext_settings['bio']) ? (string) $addonext_settings['bio'] : '';
$addonext_avatar = isset($addonext_settings['avatar']) && is_array($addonext_settings['avatar']) ? $addonext_settings['avatar'] : [];
$addonext_profileLink = isset($addonext_settings['profile_link']) && is_array($addonext_settings['profile_link']) ? $addonext_settings['profile_link'] : [];
$addonext_hasNameLink = !empty($addonext_profileLink['url']);
$addonext_showSocial = isset($addonext_settings['show_social']) && $addonext_settings['show_social'] === 'yes';
$addonext_showDivider = isset($addonext_settings['show_divider']) && $addonext_settings['show_divider'] === 'yes';
$addonext_showStats = isset($addonext_settings['show_stats']) && $addonext_settings['show_stats'] === 'yes';
$addonext_socialItems = isset($addonext_settings['social_items']) && is_array($addonext_settings['social_items']) ? $addonext_settings['social_items'] : [];
$addonext_statItems = isset($addonext_settings['stat_items']) && is_array($addonext_settings['stat_items']) ? $addonext_settings['stat_items'] : [];
$addonext_isEditor = Plugin::$instance->editor->is_edit_mode();

$this->add_render_attribute('job_title', 'class', 'addonext-author-box__job');
$this->add_inline_editing_attributes('job_title', 'basic');
$this->add_render_attribute('bio', 'class', 'addonext-author-box__bio');
$this->add_inline_editing_attributes('bio', 'advanced');

if ($addonext_hasNameLink) {
    $this->add_render_attribute('name', 'class', 'addonext-author-box__name');
    $this->add_link_attributes('name', $addonext_profileLink);
    $this->add_inline_editing_attributes('name', 'basic');
} else {
    $this->add_render_attribute('name', 'class', 'addonext-author-box__name');
    $this->add_inline_editing_attributes('name', 'basic');
}
?>
<div class="addonext-author-box">
    <div class="addonext-author-box__card">
        <div class="addonext-author-box__top">
            <?php if (!empty($addonext_avatar['id']) || !empty($addonext_avatar['url'])) : ?>
                <div class="addonext-author-box__avatar-wrap">
                    <?php
                    if (!empty($addonext_avatar['id'])) {
                        echo wp_get_attachment_image(
                            (int) $addonext_avatar['id'],
                            'medium',
                            false,
                            ['class' => 'addonext-author-box__avatar', 'alt' => esc_attr($addonext_name)]
                        );
                    } else {
                        printf(
                            '<img src="%s" alt="%s" class="addonext-author-box__avatar" />',
                            esc_url($addonext_avatar['url']),
                            esc_attr($addonext_name)
                        );
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="addonext-author-box__main">
                <?php if ($addonext_name !== '' || $addonext_isEditor) : ?>
                    <?php if ($addonext_hasNameLink) : ?>
                        <h3 class="addonext-author-box__name-heading">
                            <a <?php $this->print_render_attribute_string('name'); ?>><?php echo esc_html($addonext_name); ?></a>
                        </h3>
                    <?php else : ?>
                        <h3 <?php $this->print_render_attribute_string('name'); ?>><?php echo esc_html($addonext_name); ?></h3>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($addonext_jobTitle !== '' || $addonext_isEditor) : ?>
                    <p <?php $this->print_render_attribute_string('job_title'); ?>><?php echo esc_html($addonext_jobTitle); ?></p>
                <?php endif; ?>

                <?php if ($addonext_bio !== '' || $addonext_isEditor) : ?>
                    <div <?php $this->print_render_attribute_string('bio'); ?>><?php echo wp_kses_post($addonext_bio); ?></div>
                <?php endif; ?>

                <?php if ($addonext_showSocial && !empty($addonext_socialItems)) : ?>
                    <ul class="addonext-author-box__social">
                        <?php foreach ($addonext_socialItems as $addonext_socialIndex => $addonext_item) : ?>
                            <?php
                            $addonext_network = isset($addonext_item['network']) ? preg_replace('/[^a-z]/', '', (string) $addonext_item['network']) : 'twitter';
                            if (!in_array($addonext_network, ['twitter', 'linkedin', 'github', 'website'], true)) {
                                $addonext_network = 'twitter';
                            }
                            $addonext_iconData = $addonext_item['selected_icon'] ?? $addonext_item['icon'] ?? [];
                            $addonext_linkData = isset($addonext_item['link']) && is_array($addonext_item['link']) ? $addonext_item['link'] : [];
                            $addonext_socialKey = 'social-link-' . (int) $addonext_socialIndex;
                            if (!empty($addonext_linkData['url'])) {
                                $this->add_link_attributes($addonext_socialKey, $addonext_linkData);
                            } else {
                                $this->add_render_attribute($addonext_socialKey, 'href', '#');
                            }
                            $this->add_render_attribute($addonext_socialKey, 'class', 'addonext-author-box__social-link addonext-author-box__social-link--' . $addonext_network);
                            $this->add_render_attribute($addonext_socialKey, 'aria-label', ucfirst($addonext_network));
                            ?>
                            <li class="addonext-author-box__social-item">
                                <a <?php $this->print_render_attribute_string($addonext_socialKey); ?>>
                                    <?php
                                    if (!empty($addonext_iconData['value'])) {
                                        Icons_Manager::render_icon($addonext_iconData, ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($addonext_showDivider && $addonext_showStats && !empty($addonext_statItems)) : ?>
            <div class="addonext-author-box__divider" aria-hidden="true"></div>
        <?php endif; ?>

        <?php if ($addonext_showStats && !empty($addonext_statItems)) : ?>
            <div class="addonext-author-box__stats">
                <?php foreach ($addonext_statItems as $addonext_index => $addonext_st) : ?>
                    <?php
                    $addonext_valueKey = 'stat_items.' . $addonext_index . '.stat_value';
                    $addonext_labelKey = 'stat_items.' . $addonext_index . '.stat_label';
                    $this->add_render_attribute($addonext_valueKey, 'class', 'addonext-author-box__stat-value');
                    $this->add_inline_editing_attributes($addonext_valueKey, 'basic');
                    $this->add_render_attribute($addonext_labelKey, 'class', 'addonext-author-box__stat-label');
                    $this->add_inline_editing_attributes($addonext_labelKey, 'basic');
                    ?>
                    <div class="addonext-author-box__stat">
                        <span <?php $this->print_render_attribute_string($addonext_valueKey); ?>><?php echo esc_html((string) ($addonext_st['stat_value'] ?? '')); ?></span>
                        <span <?php $this->print_render_attribute_string($addonext_labelKey); ?>><?php echo esc_html((string) ($addonext_st['stat_label'] ?? '')); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
