<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Plugin;

$settings = $this->get_settings_for_display();
$name = isset($settings['name']) ? (string) $settings['name'] : '';
$jobTitle = isset($settings['job_title']) ? (string) $settings['job_title'] : '';
$bio = isset($settings['bio']) ? (string) $settings['bio'] : '';
$avatar = isset($settings['avatar']) && is_array($settings['avatar']) ? $settings['avatar'] : [];
$profileLink = isset($settings['profile_link']) && is_array($settings['profile_link']) ? $settings['profile_link'] : [];
$hasNameLink = !empty($profileLink['url']);
$showSocial = isset($settings['show_social']) && $settings['show_social'] === 'yes';
$showDivider = isset($settings['show_divider']) && $settings['show_divider'] === 'yes';
$showStats = isset($settings['show_stats']) && $settings['show_stats'] === 'yes';
$socialItems = isset($settings['social_items']) && is_array($settings['social_items']) ? $settings['social_items'] : [];
$statItems = isset($settings['stat_items']) && is_array($settings['stat_items']) ? $settings['stat_items'] : [];
$isEditor = Plugin::$instance->editor->is_edit_mode();

$this->add_render_attribute('job_title', 'class', 'veloria-author-box__job');
$this->add_inline_editing_attributes('job_title', 'basic');
$this->add_render_attribute('bio', 'class', 'veloria-author-box__bio');
$this->add_inline_editing_attributes('bio', 'advanced');

if ($hasNameLink) {
    $this->add_render_attribute('name', 'class', 'veloria-author-box__name');
    $this->add_link_attributes('name', $profileLink);
    $this->add_inline_editing_attributes('name', 'basic');
} else {
    $this->add_render_attribute('name', 'class', 'veloria-author-box__name');
    $this->add_inline_editing_attributes('name', 'basic');
}
?>
<div class="veloria-author-box">
    <div class="veloria-author-box__card">
        <div class="veloria-author-box__top">
            <?php if (!empty($avatar['id']) || !empty($avatar['url'])) : ?>
                <div class="veloria-author-box__avatar-wrap">
                    <?php
                    if (!empty($avatar['id'])) {
                        echo wp_get_attachment_image(
                            (int) $avatar['id'],
                            'medium',
                            false,
                            ['class' => 'veloria-author-box__avatar', 'alt' => esc_attr($name)]
                        );
                    } else {
                        printf(
                            '<img src="%s" alt="%s" class="veloria-author-box__avatar" />',
                            esc_url($avatar['url']),
                            esc_attr($name)
                        );
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="veloria-author-box__main">
                <?php if ($name !== '' || $isEditor) : ?>
                    <?php if ($hasNameLink) : ?>
                        <h3 class="veloria-author-box__name-heading">
                            <a <?php $this->print_render_attribute_string('name'); ?>><?php echo esc_html($name); ?></a>
                        </h3>
                    <?php else : ?>
                        <h3 <?php $this->print_render_attribute_string('name'); ?>><?php echo esc_html($name); ?></h3>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($jobTitle !== '' || $isEditor) : ?>
                    <p <?php $this->print_render_attribute_string('job_title'); ?>><?php echo esc_html($jobTitle); ?></p>
                <?php endif; ?>

                <?php if ($bio !== '' || $isEditor) : ?>
                    <div <?php $this->print_render_attribute_string('bio'); ?>><?php echo wp_kses_post($bio); ?></div>
                <?php endif; ?>

                <?php if ($showSocial && !empty($socialItems)) : ?>
                    <ul class="veloria-author-box__social">
                        <?php foreach ($socialItems as $socialIndex => $item) : ?>
                            <?php
                            $network = isset($item['network']) ? preg_replace('/[^a-z]/', '', (string) $item['network']) : 'twitter';
                            if (!in_array($network, ['twitter', 'linkedin', 'github', 'website'], true)) {
                                $network = 'twitter';
                            }
                            $iconData = $item['selected_icon'] ?? $item['icon'] ?? [];
                            $linkData = isset($item['link']) && is_array($item['link']) ? $item['link'] : [];
                            $socialKey = 'social-link-' . (int) $socialIndex;
                            if (!empty($linkData['url'])) {
                                $this->add_link_attributes($socialKey, $linkData);
                            } else {
                                $this->add_render_attribute($socialKey, 'href', '#');
                            }
                            $this->add_render_attribute($socialKey, 'class', 'veloria-author-box__social-link veloria-author-box__social-link--' . $network);
                            $this->add_render_attribute($socialKey, 'aria-label', ucfirst($network));
                            ?>
                            <li class="veloria-author-box__social-item">
                                <a <?php $this->print_render_attribute_string($socialKey); ?>>
                                    <?php
                                    if (!empty($iconData['value'])) {
                                        Icons_Manager::render_icon($iconData, ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($showDivider && $showStats && !empty($statItems)) : ?>
            <div class="veloria-author-box__divider" aria-hidden="true"></div>
        <?php endif; ?>

        <?php if ($showStats && !empty($statItems)) : ?>
            <div class="veloria-author-box__stats">
                <?php foreach ($statItems as $index => $st) : ?>
                    <?php
                    $valueKey = 'stat_items.' . $index . '.stat_value';
                    $labelKey = 'stat_items.' . $index . '.stat_label';
                    $this->add_render_attribute($valueKey, 'class', 'veloria-author-box__stat-value');
                    $this->add_inline_editing_attributes($valueKey, 'basic');
                    $this->add_render_attribute($labelKey, 'class', 'veloria-author-box__stat-label');
                    $this->add_inline_editing_attributes($labelKey, 'basic');
                    ?>
                    <div class="veloria-author-box__stat">
                        <span <?php $this->print_render_attribute_string($valueKey); ?>><?php echo esc_html((string) ($st['stat_value'] ?? '')); ?></span>
                        <span <?php $this->print_render_attribute_string($labelKey); ?>><?php echo esc_html((string) ($st['stat_label'] ?? '')); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
