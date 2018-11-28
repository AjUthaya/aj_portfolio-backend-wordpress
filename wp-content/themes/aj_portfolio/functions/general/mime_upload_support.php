<?php
/**
 * @author Aj
 * @version 1.0
 * @since 2018-11-28
 *
 * FUNCTION: Adds support for different mime types
 */
function mime_upload_support($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'mime_upload_support');