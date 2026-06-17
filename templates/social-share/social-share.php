<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_networks = isset($veloria_settings['networks']) && is_array($veloria_settings['networks']) ? $veloria_settings['networks'] : [];
$veloria_title = isset($veloria_settings['title']) ? $veloria_settings['title'] : '';

$veloria_page_url = get_permalink();
if ( ! is_string($veloria_page_url) || $veloria_page_url === '' ) {
    $veloria_page_url = home_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/' );
}

if ( empty( $veloria_networks ) ) {
    return;
}
?>
<div class="veloria-social-share">
    <?php if ( $veloria_title !== '' ) : ?>
        <span class="veloria-social-share__title"><?php echo esc_html( $veloria_title ); ?></span>
    <?php endif; ?>
    <?php foreach ( $veloria_networks as $veloria_n ) :
        $veloria_network = $veloria_n['network'] ?? 'facebook';
        $veloria_custom_url = isset( $veloria_n['custom_url']['url'] ) ? $veloria_n['custom_url']['url'] : '';
        $veloria_href = \Veloria\Widgets\SocialShare\SocialShare::get_share_url( $veloria_network, $veloria_page_url, $veloria_custom_url );
        $veloria_custom_color = isset( $veloria_n['custom_color'] ) ? $veloria_n['custom_color'] : '';
        $veloria_platform_label = \Veloria\Widgets\SocialShare\SocialShare::get_network_label( $veloria_network );
        $veloria_tooltip_text = sprintf( /* translators: %s: platform name */ __( 'Share on %s', 'veloria' ), $veloria_platform_label );
    ?>
        <a href="<?php echo esc_url( $veloria_href ); ?>" target="_blank" rel="noopener noreferrer" class="veloria-social-share__link veloria-social-share__link--<?php echo esc_attr( $veloria_network ); ?>" aria-label="<?php echo esc_attr( $veloria_tooltip_text ); ?>"<?php echo $veloria_custom_color !== '' ? ' style="' . esc_attr( 'background-color:' . $veloria_custom_color ) . '"' : ''; ?>>
            <span class="veloria-social-share__tooltip" role="tooltip"><?php echo esc_html( $veloria_tooltip_text ); ?></span>
            <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from get_icon_svg(); classes use esc_attr.
            echo $this->get_icon_svg( $veloria_network );
            ?>
        </a>
    <?php endforeach; ?>
</div>
