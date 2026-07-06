<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_networks = isset($addonext_settings['networks']) && is_array($addonext_settings['networks']) ? $addonext_settings['networks'] : [];
$addonext_title = isset($addonext_settings['title']) ? $addonext_settings['title'] : '';

$addonext_page_url = get_permalink();
if ( ! is_string($addonext_page_url) || $addonext_page_url === '' ) {
    $addonext_page_url = home_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/' );
}

if ( empty( $addonext_networks ) ) {
    return;
}
?>
<div class="addonext-social-share">
    <?php if ( $addonext_title !== '' ) : ?>
        <span class="addonext-social-share__title"><?php echo esc_html( $addonext_title ); ?></span>
    <?php endif; ?>
    <?php foreach ( $addonext_networks as $addonext_n ) :
        $addonext_network = $addonext_n['network'] ?? 'facebook';
        $addonext_custom_url = isset( $addonext_n['custom_url']['url'] ) ? $addonext_n['custom_url']['url'] : '';
        $addonext_href = \Addonext\Widgets\SocialShare\SocialShare::get_share_url( $addonext_network, $addonext_page_url, $addonext_custom_url );
        $addonext_custom_color = isset( $addonext_n['custom_color'] ) ? $addonext_n['custom_color'] : '';
        $addonext_platform_label = \Addonext\Widgets\SocialShare\SocialShare::get_network_label( $addonext_network );
        $addonext_tooltip_text = sprintf( /* translators: %s: platform name */ __( 'Share on %s', 'addonext' ), $addonext_platform_label );
    ?>
        <a href="<?php echo esc_url( $addonext_href ); ?>" target="_blank" rel="noopener noreferrer" class="addonext-social-share__link addonext-social-share__link--<?php echo esc_attr( $addonext_network ); ?>" aria-label="<?php echo esc_attr( $addonext_tooltip_text ); ?>"<?php echo $addonext_custom_color !== '' ? ' style="' . esc_attr( 'background-color:' . $addonext_custom_color ) . '"' : ''; ?>>
            <span class="addonext-social-share__tooltip" role="tooltip"><?php echo esc_html( $addonext_tooltip_text ); ?></span>
            <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from get_icon_svg(); classes use esc_attr.
            echo $this->get_icon_svg( $addonext_network );
            ?>
        </a>
    <?php endforeach; ?>
</div>
