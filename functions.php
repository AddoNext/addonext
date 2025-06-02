<?php

defined('ABSPATH') or exit;

/**
 * Get image url
 *
 * @param string $filename
 * @return string
 */
function dgn_get_img_url(string $filename = ''): string
{
    return DRAGNEXT_URL . 'src/img/' . $filename;
}


/**
 * Get image path
 *
 * @param string $filename
 * @return string
 */
function dgn_get_img_path(string $filename = ''): string
{
    return DRAGNEXT_DIR . 'src/img/' . $filename;
}
