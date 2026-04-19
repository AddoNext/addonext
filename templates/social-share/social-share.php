<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$networks = isset($settings['networks']) && is_array($settings['networks']) ? $settings['networks'] : [];
$title = isset($settings['title']) ? $settings['title'] : '';

$page_url = get_permalink();
if ( ! is_string($page_url) || $page_url === '' ) {
    $page_url = home_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/' );
}

if ( empty( $networks ) ) {
    return;
}
?>
<div class="elementrify-social-share">
    <?php if ( $title !== '' ) : ?>
        <span class="elementrify-social-share__title"><?php echo esc_html( $title ); ?></span>
    <?php endif; ?>
    <?php foreach ( $networks as $n ) :
        $network = $n['network'] ?? 'facebook';
        $custom_url = isset( $n['custom_url']['url'] ) ? $n['custom_url']['url'] : '';
        $href = \Elementrify\Widgets\SocialShare\SocialShare::get_share_url( $network, $page_url, $custom_url );
        $custom_color = isset( $n['custom_color'] ) ? $n['custom_color'] : '';
        $platform_label = \Elementrify\Widgets\SocialShare\SocialShare::get_network_label( $network );
        $tooltip_text = sprintf( /* translators: %s: platform name */ __( 'Share on %s', 'elementrify' ), $platform_label );
    ?>
        <a href="<?php echo esc_url( $href ); ?>" target="_blank" rel="noopener noreferrer" class="elementrify-social-share__link elementrify-social-share__link--<?php echo esc_attr( $network ); ?>" aria-label="<?php echo esc_attr( $tooltip_text ); ?>"<?php echo $custom_color !== '' ? ' style="' . esc_attr( 'background-color:' . $custom_color ) . '"' : ''; ?>>
            <span class="elementrify-social-share__tooltip" role="tooltip"><?php echo esc_html( $tooltip_text ); ?></span>
            <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from get_icon_svg(); classes use esc_attr.
            echo $this->get_icon_svg( $network );
            ?>
        </a>
    <?php endforeach; ?>
</div>
